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
                        $count = $billingRecordsRepo->CountMapTasksQBDFilters($status, $customerID, $createDate, $completedDate);
                        if (!empty($count)) {
                            $count = (int)$count[0][1];
                        }
                    }
                    $response = $billingRecordsRepo->MapTasksQBDFilters($status, $customerID, $createDate, $completedDate, $limit, $offset);
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
                    $count1 = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->CountMapTasksQBDFilters($status, $customerID, $createDate, $completedDate);
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
                $response = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->MapTasksQBDFilters($status, $customerID, $createDate, $completedDate, $limit, $offset);
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
}