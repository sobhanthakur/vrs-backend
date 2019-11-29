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


class SyncLogsService extends BaseService
{
    public function FetchAllSyncLogs($customerID, $content)
    {
        try {
            $limit = 10;
            $offset = 1;
            $response = [];
            $count = 0;
            $billingBatch = [];
            $timeTrackingBatch = [];
            if(!array_key_exists('IntegrationID',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $content['IntegrationID'];
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid'=>$customerID,'integrationid'=>$integrationID));
            if(!$integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }

            if (!empty($content)) {
//                $filters = array_key_exists('Filters', $content) ? $content['Filters'] : [];

                if (array_key_exists('Pagination', $content)) {
                    $limit = $content['Pagination']['Limit'];
                    $offset = $content['Pagination']['Offset'];
                }
            }

            // Count Batch Records
            $count = $this->entityManager->getRepository('AppBundle:Integrationqbbatches')->findBy(array('integrationtocustomer'=>$integrationToCustomers));
            if($count) {
                $count = count($count);
            }

            // Find BatchType in IntegrationQBBatches Table
            $integrationQBBatches = $this->entityManager->getRepository('AppBundle:Integrationqbbatches')->findBy(array('integrationtocustomer'=>$integrationToCustomers),array('createdate'=>'DESC'),$limit,(($offset - 1) * $limit));
            if($integrationQBBatches) {
                $batchSize = count($integrationQBBatches);
                for($i=0;$i<$batchSize;$i++) {
                    if($integrationQBBatches[$i]->getBatchtype() === false) {
                        $billingBatch[] = array(
                            'BatchID' => $integrationQBBatches[$i]->getIntegrationqbbatchid(),
                            'CreateDate' => $integrationQBBatches[$i]->getCreatedate()
                        );
                    } else {
                        $timeTrackingBatch[] = array(
                            'BatchID' => $integrationQBBatches[$i]->getIntegrationqbbatchid(),
                            'CreateDate' => $integrationQBBatches[$i]->getCreatedate()
                        );
                    }
                }
            }

            // Search Logs in BillingRecords Table
            for($i=0;$i<count($billingBatch);$i++) {
                $record = 0;
                $record = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->DistinctBatchCount($billingBatch[$i]['BatchID']);
                if($record) {
                    $record = (int)$record[0][1];
                }
                $response[] = array(
                    'BatchType' => 0,
                    'Sent' => $billingBatch[$i]['CreateDate'],
                    'Records' => $record
                );
            }

            // Search Logs in TimeTracking Table
            for($i=0;$i<count($timeTrackingBatch);$i++) {
                $record = 0;
                $record = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->DistinctBatchCount($timeTrackingBatch[$i]['BatchID']);
                if($record) {
                    $record = (int)$record[0][1];
                }
                $response[] = array(
                    'BatchType' => 1,
                    'Sent' => $timeTrackingBatch[$i]['CreateDate'],
                    'Records' => $record
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
}