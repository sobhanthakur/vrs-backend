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
            $new = null;
            $dateFilter = [];

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
                $dateFilter = $this->entityManager->getRepository('AppBundle:Tasks')->GetAllTimeZones($customerID, $properties);
                $dateFilter = $this->StartDateCalculation($dateFilter,$integrationToCustomers[0]['startdate']);

                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];

                if (array_key_exists('Property', $filters)) {
                    $properties = $filters['Property'];
                }
                if (array_key_exists('CompletedDate', $filters) && !empty($filters['CompletedDate']['From']) && !empty($filters['CompletedDate']['To'])) {
                    $completedDateRequest = $filters['CompletedDate'];
                    $dateFilter = $this->CompletedDateRequestCalculation($dateFilter,$completedDateRequest);
                }
                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }
            $dateFilter = $this->TrimDateFilter($dateFilter);

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
                        $count = $billingRecordsRepo->CountMapTasksQBDFilters($status, $properties, $customerID, $createDate, $dateFilter);
                        if (!empty($count)) {
                            $count = (int)$count[0][1];
                        }
                    }
                    $response = $billingRecordsRepo->MapTasksQBDFilters($status, $properties, $customerID, $createDate, $dateFilter, $limit, $offset);
                    $response = $this->processResponse($response);
                    $flag = 1;
                } elseif (
                    (count($status) === 1) &&
                    in_array(GeneralConstants::NEW, $status)
                ) {
                    $new = true;
                    $flag = 0;
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
                    $count = $this->entityManager->getRepository('AppBundle:Tasks')->CountMapTasks($customerID, $properties, $createDate, $dateFilter, $new);
                    if ($count) {
                        $count = (int)$count[0][1];
                    }
                }

                $response = $this->entityManager->getRepository('AppBundle:Tasks')->MapTasks($customerID, $properties, $createDate, $dateFilter, $limit, $offset,$new);
                $response = $this->processResponse($response);
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

    /**
     * Converts the Completed Date filter to UTC and query in the DB
     * @param $completedDateFromQuery
     * @param $completedDateRequest
     * @return array|null
     */
    public function CompletedDateRequestCalculation($completedDateFromQuery, $completedDateRequest)
    {
        $response = [];
        for($i=0;$i<count($completedDateFromQuery);$i++) {
            $region = $completedDateFromQuery[$i]['Region'];
            $completeConfirmedDate = $completedDateFromQuery[$i]['CompleteConfirmedDate'];
            $timeZoneLocal = new \DateTimeZone($region);
            $fromLocal = new \DateTime($completedDateRequest['From'],$timeZoneLocal);
            $toLocal = new \DateTime($completedDateRequest['To'],$timeZoneLocal);

            $timeZoneUTC = new \DateTimeZone('UTC');
            $fromUTC = $fromLocal->setTimezone($timeZoneUTC);
            $toUTC = $toLocal->setTimezone($timeZoneUTC);


            if(($completeConfirmedDate>=$fromUTC) &&($completeConfirmedDate<=$toUTC)) {
                $response[] = $completedDateFromQuery[$i];
            }
        }
        return $response;
    }

    /**
     * Fetches Records Based on completedDate>=StartDate
     * @param $filterQuery
     * @param \DateTime $startDate
     * @return array|null
     */
    public function StartDateCalculation($filterQuery, $startDate)
    {
        $response = [];
        for($i=0;$i<count($filterQuery);$i++) {
            $region = $filterQuery[$i]['Region'];
            $completeConfirmedDate = $filterQuery[$i]['CompleteConfirmedDate'];
            $timeZoneLocal = new \DateTimeZone($region);
            $startDateLocal = new \DateTime($startDate->format('Y-m-d'),$timeZoneLocal);
            $timeZoneUTC = new \DateTimeZone('UTC');
            $startDate = $startDateLocal->setTimezone($timeZoneUTC);


            if($completeConfirmedDate>=$startDate) {
                $response[] = $filterQuery[$i];
            }
        }
        return $response;
    }

    /**
     * @param $response
     * @return mixed
     */
    public function processResponse($response)
    {
        for ($i = 0; $i < count($response); $i++) {
            if ($response[$i]['Status'] === null) {
                $response[$i]["Status"] = 2;
            }
            $time = $this->TimeZoneCalculation($response[$i]['TimeZoneRegion'], $response[$i]['CompleteConfirmedDate']);
            $response[$i]["CompleteConfirmedDate"] = $time;
            unset($response[$i]['TimeZoneRegion']);
        }
        return $response;
    }


    /**
     * @param $region
     * @param \DateTime $completeConfirmedDate
     * @return mixed
     */
    public function TimeZoneCalculation($region, $completeConfirmedDate)
    {
        $newTimeZone = new \DateTimeZone($region);
        $completeConfirmedDate->setTimezone($newTimeZone);
        return $completeConfirmedDate;

    }

    /**
     * @param $dateFilter
     * @return array
     */
    public function TrimDateFilter($dateFilter)
    {
        $response = [];
        for($i=0;$i<count($dateFilter);$i++) {
            $response[] = $dateFilter[$i]['TaskID'];
        }
        return $response;
    }
}