<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 18/2/20
 * Time: 10:59 AM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Entity\Integrationqbbatches;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\Facades\Estimate;
use QuickBooksOnline\API\Facades\Invoice;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class QuickbooksOnlineSyncBilling
 * @package AppBundle\Service
 */
class QuickbooksOnlineSyncBilling extends BaseService
{
    /**
     * @param $customerID
     * @param $quickbooksConfig
     * @return array
     * @throws ServiceException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \Exception
     */
    public function SyncBilling($customerID, $quickbooksConfig, $integrationID)
    {
        $authService = $this->serviceContainer->get('vrscheduler.quickbooksonline_authentication');
        $integrationQBOTokens = $this->entityManager->getRepository('AppBundle:Integrationqbotokens')->findOneBy(array('customerid'=>$customerID));
        $dataService = null;
        $status = null;
        try {
            if(!$integrationQBOTokens) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            // If Access and refresh tokens are not present in the table
            if(!$integrationQBOTokens->getRefreshToken() || !$integrationQBOTokens->getAccessToken()) {
                throw new UnprocessableEntityHttpException(ErrorConstants::OAUTH_FAILED);
            }

            // Authenticate
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);
            if(!$dataService) {
                throw new ServiceException('Unauthorized');
            }

            // Create Billing
            $status = $this->CreateBilling($dataService,$customerID,$integrationID);
        } catch (ServiceException $exception) {

            /*
             * This occurs when authentication fails using the existing access token.
             * Re-connect to QBO using the refresh token to get a new pair of tokens
             */
            $authService->RefreshAccessToken($dataService,$integrationQBOTokens);

            // Re-Login with new Updated Tokens
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);

            // Create Billing
            $status = $this->CreateBilling($dataService,$customerID,$integrationID);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Creating Billing in QBO due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        if($status) {
            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
        }
        return null;
    }

    /**
     * @param DataService $dataService
     * @param $customerID
     * @param $integrationID
     * @return null
     * @throws \Exception
     */
    public function CreateBilling($dataService, $customerID, $integrationID)
    {
        try {
            // Initialize variables
            $response = [];
            $billingRecordID = [];
            $description = [];
            $amount = [];
            $result = null;

            // Get Tasks that are ready for Billing
            $tasks = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->GetTasksForSalesOrder($customerID,true);
            if (empty($tasks)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::NOTHING_TO_MAP);
            }

            $integrationsToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid'=>$customerID,'integrationid'=>$integrationID,'active'=>true,'qbdsyncbilling'=>true));
            if(!$integrationsToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            foreach ($tasks as $task) {
                $response[$task['QBDCustomerListID']][] = $task['QBDListID'];
                $billingRecordID[$task['QBDCustomerListID']][] = $task['IntegrationQBDBillingRecordID'];
                $description[$task['QBDCustomerListID']][] = $task['PropertyName'] . ' - ' . $task['TaskName'] . ' - ' . $task['ServiceName'] . ' - ' . $this->TimeZoneConversion($task['CompleteConfirmedDate']->format('Y-m-d'), $task['Region']) . ' - ' . ($task['LaborOrMaterial'] === true ? "Materials" : "Labor");
                $amount[$task['QBDCustomerListID']][] = $task['Amount'];
            }

            // Create a Batch
            $batch = new Integrationqbbatches();
            $batch->setBatchtype(false);
            $batch->setIntegrationtocustomer($integrationsToCustomers);
            $this->entityManager->persist($batch);

            // Create the billing array
            foreach ($response as $key => $value) {
                $line = [];
                foreach ($value as $key1=>$value1) {
                    $line[] = array(
                        "DetailType" => "SalesItemLineDetail",
                        "Amount" => number_format((float)$amount[$key][$key1],2,'.',''),
                        "Description" => $description[$key][$key1],
                        "SalesItemLineDetail" => array(
                            "ItemRef" => array(
                                "value" => $value1
                            ),
                            "Qty" => 1
                        )
                    );
                }
                $billing = array(
                    "CustomerRef" => array(
                        "value" => $key
                    ),
                    "Line" => $line
                );

                // Get Version and Qb type
                $version = $integrationsToCustomers->getVersion();
                $type = $integrationsToCustomers->getType();

                if($version === 2 && $type === 1) {
                    // Create Estimates
                    $resource = Estimate::create($billing);
                    $result = $dataService->Add($resource);
                } elseif ($version === 2 && $type === 2) {
                    // Create Invoice
                    $resource = Invoice::create($billing);
                    $result = $dataService->Add($resource);
                } else {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
                }

                $billingIDs = array_unique($billingRecordID[$key]);
                foreach ($billingIDs as $item) {
                    $billingID = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findOneBy(array('integrationqbdbillingrecordid'=>$item));
                    if($billingID) {
                        if($result) {
                            $billingID->setTxnid($result->Id);
                        }
                        $billingID->setSentstatus(true);
                        $billingID->setIntegrationqbbatchid($batch);
                        $this->entityManager->persist($billingID);
                    }
                }
            }
            $this->entityManager->flush();
            return true;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $date
     * @param $region
     * @return string
     */
    public function TimeZoneConversion($date, $region)
    {
        $localTimeZone = new \DateTimeZone($region);
        $date = new \DateTime($date,$localTimeZone);

        return $date->format('Y-m-d');
    }
}