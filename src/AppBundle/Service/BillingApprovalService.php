<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 11:45 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbdbillingrecords;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class BillingApprovalService
 * @package AppBundle\Service
 */
class BillingApprovalService extends BaseService
{
    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function MapTasks($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $properties = null;
            $createDate = null;
            $completedDate = null;
            $limit = 10;
            $offset = 1;
            $response = null;
            $integrationID = null;
            $count = null;
            $filters = [];
            $flag = null;
            $response = [];
            $status = [];

            if (!array_key_exists('IntegrationID', $data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID, $customerID);
            if (empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if (!empty($data)) {
                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];

                if (array_key_exists('Property', $filters)) {
                    $properties = $this->entityManager->getRepository('AppBundle:Properties')->SearchPropertiesByID($customerID, $filters['Property']);
                }
                if (array_key_exists('CompletedDate', $filters)) {
                    $completedDate = $filters['CompletedDate'];
                }
                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }

            if (array_key_exists('Status', $filters)) {
                $status = $filters['Status'];

                // IntegrationQBDBilling Repository
                $billingRecordsRepo = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords');

                // If status is either Approved Or Excluded or both
                if (
                    ((count($status) === 1) || (count($status) === 2)) &&
                    ((in_array(GeneralConstants::APPROVED, $status)) ||
                        in_array(GeneralConstants::EXCLUDED, $status)
                    ) &&
                    (!in_array(GeneralConstants::NEW, $status))
                ) {
                    if ($offset === 1) {
                        $count = $billingRecordsRepo->CountMapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate);
                        if (!empty($count)) {
                            $count = (int)$count[0][1];
                        }
                    }
                    $response = $billingRecordsRepo->MapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate, $limit, $offset);
                    $flag = 1;
                } elseif (
                    (count($status) === 1) &&
                    in_array(GeneralConstants::NEW, $status)
                ) {
                    if ($offset === 1) {
                        $count = $this->entityManager->getRepository('AppBundle:Tasks')->CountMapTasks($customerID, $properties, $createDate, $completedDate);
                        if (!empty($count)) {
                            $count = (int)$count[0][1];
                        }
                    }

                    $response = $this->entityManager->getRepository('AppBundle:Tasks')->MapTasks($customerID, $properties, $createDate, $completedDate, $limit, $offset);
                    for ($i = 0; $i < count($response); $i++) {
                        $response[$i]["Status"] = 2;
                    }
                    $flag = 1;
                }
            }

            /*
             * Default Condition
             * If status is not set
             * Or else status is set to all
             * or else status if New AND (Approved OR Excluded)
             */
            if (!$flag) {
                if ($offset === 1) {
                    $count1 = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->CountMapTasksQBDFilters($status, $properties,$customerID, $createDate, $completedDate);
                    if ($count1) {
                        $count1 = (int)$count1[0][1];
                    }
                    $count2 = $this->entityManager->getRepository('AppBundle:Tasks')->CountMapTasks($customerID, $properties, $createDate, $completedDate);

                    if ($count2) {
                        $count2 = (int)$count2[0][1];
                    }
                    $count = $count1 + $count2;
                }
                $response2 = null;
                $response = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->MapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate, $limit, $offset);
                $countResponse = count($response);
                if ($countResponse < $limit) {
                    $limit = $limit - $countResponse;
                    $response2 = $this->entityManager->getRepository('AppBundle:Tasks')->MapTasks($customerID, $properties, $createDate, $completedDate, $limit, $offset);
                    for ($i = 0; $i < count($response2); $i++) {
                        $response2[$i]["Status"] = 2;
                    }
                }
                $response = array_merge($response, $response2);
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
            $this->logger->error('Failed fetching tasks due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function ApproveBilling($customerID, $content)
    {
        try {
            if(!array_key_exists('IntegrationID',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID,$customerID);
            if(empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if(!array_key_exists('Data',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_DATA);
            }

            $data = $content['Data'];

            // Traverse data to create/update mappings
            for ($i = 0; $i < count($data); $i++) {

                // Check If status is correct or not
                if(
                ($data[$i]['Status'] !== 0) &&
                ($data[$i]['Status'] !== 1)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STATUS);
                }

                $billingRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findOneBy(
                    array(
                        'taskid' => $data[$i][GeneralConstants::TASK_ID]
                    )
                );

                // Throw Exception if the the record's txnID is not null
                if(
                    ($billingRecords !== null?($billingRecords->getTxnid() !== null):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
                }

                $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->findOneBy(array(
                        'taskid' => $data[$i][GeneralConstants::TASK_ID]
                    )
                );

                if(!$tasks ||
                    ($tasks !== null?($tasks->getPropertyid()->getCustomerid()->getCustomerid() !== $customerID):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
                }

                // If Integration QBD Billing Record exist, then simply update the record with the new Status
                if (!$billingRecords) {
                    // Create New Record

                    $billingRecords = new Integrationqbdbillingrecords();

                    $billingRecords->setTaskid($tasks);
                    $billingRecords->setStatus($data[$i]['Status']);

                    $this->entityManager->persist($billingRecords);
                } else {
                    // Update the record
                    $billingRecords->setStatus($data[$i]['Status']);
                    $this->entityManager->persist($billingRecords);
                }
            }
            $this->entityManager->flush();

            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message')
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Saving Billing Approval due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}