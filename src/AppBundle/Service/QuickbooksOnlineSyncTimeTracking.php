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


/**
 * Class QuickbooksOnlineSyncTimeTracking
 * @package AppBundle\Service
 */
class QuickbooksOnlineSyncTimeTracking extends BaseService
{
    private $persistBatch = null;
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
                $driveTimes = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->DriveTimeClockTasksForQuickbooksOnline($customerID);
                $timeclocks = array_merge($timeclocks,$driveTimes);
                if (empty($timeclocks)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::NOTHING_TO_MAP);
                }

                $batch = $this->CreateBatch($integrationsToCustomers);

                // Create time activity object for Quickbooks Online
                foreach ($timeclocks as $timeclock) {
                    $description = '';
                    if ($timeclock['DriveTimeClockTaskID']) {
                        $description = 'Drive / Load Time';
                    } else {
                        if(!empty($timeclock['PropertyName'])) {
                            $description .= $timeclock['PropertyName']." ";
                        }
                        if(!empty($timeclock['TaskName'])) {
                            $description .= $timeclock['TaskName']." ";
                        }
                        if(!empty($timeclock['ServiceName'])) {
                            $description .= $timeclock['ServiceName']." ";
                        }
                    }

                    $timeTracked = explode(":",$this->GMDateCalculation($timeclock['TimeTrackedSeconds']));

                    if ((int)$timeclock['IsContractor'] === 1) {
                        $timeActivity = array(
                            "NameOf" => "Vendor",
                            "VendorRef" => [
                                "Value" => $timeclock['EmployeeValue']
                            ]
                        );
                    } else {
                        $timeActivity = array(
                            "NameOf" => "Employee",
                            "EmployeeRef" => [
                                "Value" => $timeclock['EmployeeValue']
                            ]
                        );
                    }
                    $timeActivity = array_merge($timeActivity,array(
                        "TxnDate" => $timeclock['Date']->format('Y-m-d'),
                        "Minutes" => $timeTracked[1],
                        "Hours" => $timeTracked[0],
                        "HourlyRate" =>$timeclock['PayRate'],
                        "Taxable" => "false",
                        "Description" => $description
                    ));

                    if(array_key_exists('CustomerValue',$timeclock) && $timeclock['CustomerValue']) {
                        $timeActivity = array_merge($timeActivity,array(
                            "CustomerRef" => [
                                "Value" => $timeclock['CustomerValue']
                            ],
                            "BillableStatus" => "Billable"
                        ));
                    }

                    if (!$timeclock['PayRate'] && array_key_exists('UnitPrice',$timeclock) && $timeclock['UnitPrice']) {
                        $timeActivity['HourlyRate'] = $timeclock['UnitPrice'];
                    }

                    if(array_key_exists('ItemListID',$timeclock) && $timeclock['ItemListID']) {
                        $timeActivity = array_merge($timeActivity,array(
                           "ItemRef" =>  [
                               "value" => $timeclock['ItemListID']
                           ]
                        ));
                    }

                    $timeActivity = TimeActivity::create($timeActivity);
                    $result = $dataService->Add($timeActivity);

                    if (!$this->persistBatch) {
                        $this->persistBatch = true;
                        $this->entityManager->persist($batch);
                    }

                    // Update TxnID
                    $integrationQBDTimeTracking = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(array('integrationqbdtimetrackingrecords'=>$timeclock['IntegrationQBDTimeTrackingRecordID']));
                    if($integrationQBDTimeTracking) {
                        if($result) {
                            $integrationQBDTimeTracking->setTxnid($result->Id);
                        }
                        $integrationQBDTimeTracking->setSentstatus(true);
                        $integrationQBDTimeTracking->setIntegrationqbbatchid($batch);
                        $this->entityManager->persist($integrationQBDTimeTracking);
                        $this->entityManager->flush();
                    }
                }
            } else {
                // Time Clock Days
                $timeclocks = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetTimeTrackingRecordsToSync($customerID);
                if (empty($timeclocks)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::NOTHING_TO_MAP);
                }

                foreach ($timeclocks as $timeclock) {
                    $timeTracked = explode(":",$this->GMDateCalculation($timeclock['TimeTrackedSeconds']));
                    if ((int)$timeclock['IsContractor'] === 1) {
                        $timeActivity = array(
                            "NameOf" => "Vendor",
                            "VendorRef" => [
                                "Value" => $timeclock['QBDEmployeeListID']
                            ]
                        );
                    } else {
                        $timeActivity = array(
                            "NameOf" => "Employee",
                            "EmployeeRef" => [
                                "Value" => $timeclock['QBDEmployeeListID']
                            ]
                        );
                    }
                    $timeActivity = array_merge($timeActivity,array(
                        "TxnDate" => $timeclock['Date']->format('Y-m-d'),
                        "Minutes" => $timeTracked[1],
                        "Hours" => $timeTracked[0],
                        "HourlyRate" => $timeclock['PayRate']
                    ));

                    $timeActivity = TimeActivity::create($timeActivity);
                    $result = $dataService->Add($timeActivity);

                    $timetrackingIDs = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->UpdateSuccessTxnIDOnline($timeclock['Date'],$timeclock['QBDEmployeeListID'],$customerID);
                    foreach ($timetrackingIDs as $id) {
                        $ttid = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(array('integrationqbdtimetrackingrecords'=>$id['integrationqbdtimetrackingrecords']));
                        $ttid->setTxnid($result->Id);
                        $this->entityManager->persist($ttid);
                    }
                }
                $this->entityManager->flush();

                // Set Sent Status to 1 And BatchID
                $batch = $this->CreateBatch($integrationsToCustomers);
                $this->entityManager->persist($batch);
                $integrationQBDTimetrackingID = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetUnsycedTimeTrackingBatchOnline($customerID);
                foreach ($integrationQBDTimetrackingID as $id) {
                    $result = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(array('integrationqbdtimetrackingrecords'=>$id['integrationqbdtimetrackingrecords']));
                    $result->setIntegrationqbbatchid($batch);
                    $result->setSentstatus(true);
                    $this->entityManager->persist($result);
                }
                $this->entityManager->flush();
            }

            return true;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $integrationsToCustomers
     * @return Integrationqbbatches
     * @throws \Doctrine\ORM\ORMException
     */
    public function CreateBatch($integrationsToCustomers)
    {
        $batch = new Integrationqbbatches();
        $batch->setBatchtype(true);
        $batch->setIntegrationtocustomer($integrationsToCustomers);
        return $batch;
    }

    /**
     * @param $date
     * @return string
     */
    public function GMDateCalculation($date)
    {
        $hours = gmdate('H',$date);
        $days = gmdate('d',$date);
        $minsec = gmdate("i:s", $date);
        $hours = ($days-1)*24 + $hours;
        $result = $hours.':'.$minsec;
        return $result;
    }
}