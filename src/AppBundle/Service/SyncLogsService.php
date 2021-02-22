<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 28/11/19
 * Time: 3:16 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbdbillingrecords;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


/**
 * Class SyncLogsService
 * @package AppBundle\Service
 */
class SyncLogsService extends BaseService
{
    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function FetchAllSyncLogs($customerID, $content)
    {
        try {
            $limit = 10;
            $offset = 1;
            $response = [];
            $count = null;
            $billingBatch = [];
            $batchType = [];
            $timeTrackingBatch = [];
            $completedDate = null;
            $region = "UTC";

            // Get Time zone of that customer
            $timeZone = $this->entityManager->getRepository('AppBundle:Customers')->GetTimeZone($customerID);
            if($timeZone) {
                $region = $timeZone[0]['Region'];
            }

            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid'=>$customerID,'integrationid'=>$integrationID));
            if(!$integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }

            if (!empty($content)) {
                $filters = array_key_exists('Filters', $content) ? $content['Filters'] : [];
                if (array_key_exists('CompletedDate', $filters) && !empty($filters['CompletedDate']['From']) && !empty($filters['CompletedDate']['To'])) {
                    $completedDate = $filters['CompletedDate'];
                    $completedDate = $this->TimeZoneConversion($completedDate,$region);
                }

                if (array_key_exists('BatchType', $filters)) {
                    $batchType = $filters['BatchType'];
                }

                if (array_key_exists(GeneralConstants::PAGINATION, $content)) {
                    $limit = $content[GeneralConstants::PAGINATION]['Limit'];
                    $offset = $content[GeneralConstants::PAGINATION]['Offset'];
                }
            }

            // Count Batch Records
            if($offset === 1) {
                $count = $this->entityManager->getRepository('AppBundle:Integrationqbbatches')->CountBatches($integrationToCustomers->getIntegrationtocustomerid(),$completedDate,$batchType);
                if($count) {
                    $count = (int)$count[0][1];
                }
            }

            // Find BatchType in IntegrationQBBatches Table
            $integrationQBBatches = $this->entityManager->getRepository('AppBundle:Integrationqbbatches')->FetchBatches($integrationToCustomers->getIntegrationtocustomerid(), $completedDate, $batchType,$limit,$offset);
            $integrationQBBatches = $this->entityManager->getRepository('AppBundle:Integrationqbbatches')->FetchBatches($integrationToCustomers->getIntegrationtocustomerid(), $completedDate, $batchType,$limit,$offset);
            if($integrationQBBatches) {
                $batchSize = count($integrationQBBatches);
                for($i=0;$i<$batchSize;$i++) {
                    if(!$integrationQBBatches[$i]['BatchType']) {
                        $billingBatch[] = array(
                            'BatchID' => $integrationQBBatches[$i]['IntegrationQBBatchID'],
                            GeneralConstants::CREATEDATE => $integrationQBBatches[$i][GeneralConstants::CREATEDATE]
                        );
                    } else {
                        $timeTrackingBatch[] = array(
                            'BatchID' => $integrationQBBatches[$i]['IntegrationQBBatchID'],
                            GeneralConstants::CREATEDATE => $integrationQBBatches[$i][GeneralConstants::CREATEDATE]
                        );
                    }
                }
            }

            // Search Logs in BillingRecords Table
            for($i=0;$i<count($billingBatch);$i++) {
                // Count Succeeded
                $success = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->DistinctBatchCountSuccess($billingBatch[$i]['BatchID']);
                if($success) {
                    $success = (int)$success[0][1];
                } else {
                    $success = 0;
                }

                // Count Failed
                $failed = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->DistinctBatchCountFailed($billingBatch[$i]['BatchID']);
                if($failed) {
                    $failed = (int)$failed[0][1];
                } else {
                    $failed = 0;
                }
                $timeZoneRegion = new \DateTimeZone($region);
                $billingBatch[$i][GeneralConstants::CREATEDATE]->setTimeZone($timeZoneRegion);
                $response[] = array(
                    'BatchType' => 0,
                    'Sent' => $billingBatch[$i][GeneralConstants::CREATEDATE],
                    'Records' => array(
                        "Success" => $success,
                        "Failed" => $failed
                    ),
                    'BatchID' => $billingBatch[$i]['BatchID']
                );
            }

            // Search Logs in TimeTracking Table
            for($i=0;$i<count($timeTrackingBatch);$i++) {
                // Count Records that succeeded
                $success = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->DistinctBatchCountSuccess($timeTrackingBatch[$i]['BatchID']);
                if($success) {
                    $success = (int)$success[0][1];
                } else {
                    $success = 0;
                }

                // Count Records that Failed
                $failed = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->DistinctBatchCountFailed($timeTrackingBatch[$i]['BatchID']);
                if($failed) {
                    $failed = (int)$failed[0][1];
                } else {
                    $failed = 0;
                }

                $timeZoneRegion = new \DateTimeZone($region);
                $timeTrackingBatch[$i][GeneralConstants::CREATEDATE]->setTimeZone($timeZoneRegion);
                $response[] = array(
                    'BatchType' => 1,
                    'Sent' => $timeTrackingBatch[$i][GeneralConstants::CREATEDATE],
                    'Records' => array(
                        "Success" => $success,
                        "Failed" => $failed
                    ),
                    'BatchID' => $timeTrackingBatch[$i]['BatchID']
                );
            }

            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => array(
                    'Count' => $count,
                    'Details' => $response
                )
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching logs due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function BatchWiseLogs($customerID, $content)
    {
        try {
            $response = [];
            $count = null;
            $completedDate = null;

            if (!array_key_exists(GeneralConstants::INTEGRATION_ID, $content) ||
                !array_key_exists('BatchID', $content) ||
                !array_key_exists('BatchType', $content) ||
                !array_key_exists(GeneralConstants::PAGINATION, $content)
            ) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PAYLOAD);
            }
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid' => $customerID, 'integrationid' => $integrationID, 'active' => 1));
            if (!$integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }

            $batchType = $content['BatchType'];
            $limit = $content[GeneralConstants::PAGINATION]['Limit'];
            $offset = $content[GeneralConstants::PAGINATION]['Offset'];
            $batchID = $content['BatchID'];

            // Batch Type = 0 Means Billing And Batch Type = 1 Means Time Tracking
            if ($batchType === 0) {
                if($offset === 1) {
                    $count = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->CountGetBatchDetails($batchID);
                    if($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->GetBatchDetails($batchID,$limit,$offset);


            } elseif ($batchType === 1) {
                if($offset === 1) {
                    $count = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->CountBatches($batchID);
                    if($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response1 = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->BatchWiseLog($batchID,$limit,$offset,2);
                $response2 = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->BatchWiseLog($batchID,$limit,$offset,1);
                $response = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->BatchWiseLog($batchID,$limit,$offset);
                $response = array_merge($response,$response1);
                $response = array_merge($response,$response2);
            }


            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => array(
                    'Count' => $count,
                    'Details' => $response
                )
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching logs due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $date
     * @param $region
     * @return array
     */
    public function TimeZoneConversion($date, $region)
    {
        $localTimeZone = new \DateTimeZone($region);
        $fromLocal = new \DateTime($date['From'],$localTimeZone);
        $toLocal = new \DateTime($date['To'],$localTimeZone);

        $utcTimeZone = new \DateTimeZone("UTC");
        $fromLocal->setTimezone($utcTimeZone);
        $toLocal->setTimezone($utcTimeZone);

        return array(
            'From' => $fromLocal,
            'To' => $toLocal->modify('+1 day')
        );
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function ResetSyncLogs($customerID, $content)
    {
        try {
            $batchID = $content['BatchID'];
            $batchType = $content['BatchType'];
            $update = null;
            if($batchType === 1) {
                $update = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->ResetTimeTrackingBatch($batchID);
            } else {
                $update = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->ResetBillingBatch($batchID);
            }
            if(!$update) {
                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_RESET_BATCH);
            }
            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed resetting logs due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}