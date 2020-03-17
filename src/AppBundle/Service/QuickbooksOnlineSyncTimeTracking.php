<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 17/3/20
 * Time: 2:10 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Entity\Integrationqbbatches;
use AppBundle\Repository\IntegrationqbdtimetrackingrecordsRepository;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\Facades\TimeActivity;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class QuickbooksOnlineSyncTimeTracking extends BaseService
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
    public function SyncTimeTracking($customerID, $quickbooksConfig, $integrationID)
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
            $status = $this->CreateTimetracking($dataService,$customerID,$integrationID);
        } catch (ServiceException $exception) {

            /*
             * This occurs when authentication fails using the existing access token.
             * Re-connect to QBO using the refresh token to get a new pair of tokens
             */
            $authService->RefreshAccessToken($dataService,$integrationQBOTokens);

            // Re-Login with new Updated Tokens
            $dataService = $authService->Authenticate($integrationQBOTokens, $quickbooksConfig);

            // Create TimeTracking
            $status = $this->CreateTimeTracking($dataService,$customerID,$integrationID);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Creating Timetracking in QBO due to : ' .
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
    public function CreateTimeTracking($dataService, $customerID, $integrationID)
    {
        try {
            // Initialize variables
            $timeActivity = null;
            $result = null;

            $integrationsToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid'=>$customerID,'integrationid'=>$integrationID,'active'=>true,'qbdsyncpayroll'=>true));
            if(!$integrationsToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            $timetrackingType = $integrationsToCustomers->getTimetrackingtype();

            // Get records that are ready for TimeTracking
            if($timetrackingType) {
                // Time Clock Tasks
                $timeclocks = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->TimeClockTasksForQuickbooksOnline($customerID);
                if (empty($timeclocks)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::NOTHING_TO_MAP);
                }

                $batch = $this->CreateBatch($integrationsToCustomers);

                // Create time activity object for Quickbooks Online
                foreach ($timeclocks as $timeclock) {
                    $timeTracked = explode(":",gmdate('H:i',$timeclock['TimeTrackedSeconds']));
                    $timeActivity = array(
                        "NameOf" => "Employee",
                        "TxnDate" => $timeclock['Date']->format('Y-m-d'),
                        "EmployeeRef" => [
                            "Value" => $timeclock['EmployeeValue']
                        ],
                        "Minutes" => $timeTracked[1],
                        "Hours" => $timeTracked[0],
                        "CustomerRef" => [
                            "Value" => $timeclock['CustomerValue']
                        ]
                    );

                    if(!$timeclock['CustomerValue']) {
                        unset($timeActivity['CustomerRef']);
                    }
                    $timeActivity = TimeActivity::create($timeActivity);
                    $result = $dataService->Add($timeActivity);

                    // Update TxnID
                    $integrationQBDTimeTracking = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(array('integrationqbdtimetrackingrecords'=>$timeclock['IntegrationQBDTimeTrackingRecordID']));
                    if($integrationQBDTimeTracking) {
                        if($result) {
                            $integrationQBDTimeTracking->setTxnid($result->Id);
                        }
                        $integrationQBDTimeTracking->setSentstatus(true);
                        $integrationQBDTimeTracking->setIntegrationqbbatchid($batch);
                        $this->entityManager->persist($integrationQBDTimeTracking);
                    }
                }
                $this->entityManager->flush();

            } else {
                // Time Clock Days
                $timeclocks = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetTimeTrackingRecordsToSync($customerID);
                if (empty($timeclocks)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::NOTHING_TO_MAP);
                }

                // Set Sent Status to 1
//                $batch = $this->CreateBatch($integrationsToCustomers);
//                $integrationQBDTimetrackingID = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetUnsycedTimeTrackingBatch($customerID);
//                $updateTimeTrackingBatch = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->UpdateTimeTrackingBatches($batch->getIntegrationqbbatchid(),$integrationQBDTimetrackingID);
//
//                foreach ($timeclocks as $timeclock) {
//                    $timeTracked = explode(":",gmdate('H:i',$timeclock['TimeTrackedSeconds']));
//                    $timeActivity = array(
//                        "NameOf" => "Employee",
//                        "TxnDate" => $timeclock['Date']->format('Y-m-d'),
//                        "EmployeeRef" => [
//                            "Value" => $timeclock['QBDEmployeeListID']
//                        ],
//                        "Minutes" => $timeTracked[1],
//                        "Hours" => $timeTracked[0]
//                    );
//
//                    $timeActivity = TimeActivity::create($timeActivity);
//                    $result = $dataService->Add($timeActivity);
//                }

                // Create time activity object for Quickbooks Online
            }

//            $this->entityManager->flush();
            return true;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function CreateBatch($integrationsToCustomers)
    {
        $batch = new Integrationqbbatches();
        $batch->setBatchtype(true);
        $batch->setIntegrationtocustomer($integrationsToCustomers);
        $this->entityManager->persist($batch);
        return $batch;
    }
}