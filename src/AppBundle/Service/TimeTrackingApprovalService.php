<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 22/11/19
 * Time: 11:45 AM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbdtimetrackingrecords;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class TimeTrackingApprovalService
 * @package AppBundle\Service
 */
class TimeTrackingApprovalService extends BaseService
{
    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function FetchTimeClock($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $staff = null;
            $createDate = null;
            $limit = 10;
            $offset = 1;
            $response = null;
            $integrationID = null;
            $count = null;
            $filters = [];
            $flag = null;
            $response = [];
            $status = [];
            $completedDate = null;

            if (!array_key_exists('IntegrationID', $data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncTimeTracking is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncTimeTrackingEnabled($integrationID, $customerID);
            if (empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if (!empty($data)) {
                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];

                if (array_key_exists('Staff', $filters)) {
                    $staff = $this->entityManager->getRepository('AppBundle:Servicers')->SearchStaffByID($customerID, $filters['Staff']);
                }
                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('CompletedDate', $filters)) {
                    $completedDate = $filters['CompletedDate'];
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }

            if (array_key_exists('Status', $filters)) {
                $status = $filters['Status'];

                // IntegrationQBDTime Tracking Repository
                $timetrackingRecordsRepo = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords');

                // If status is either Approved Or Excluded or both
                if (
                    ((count($status) === 1) || (count($status) === 2)) &&
                    ((in_array(GeneralConstants::APPROVED, $status)) ||
                        in_array(GeneralConstants::EXCLUDED, $status)
                    ) &&
                    (!in_array(GeneralConstants::NEW, $status))
                ) {
                    if ($offset === 1) {
                        $count = $timetrackingRecordsRepo->CountMapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate);
                        if (!empty($count)) {
                            $count = (int)$count[0][1];
                        }
                    }
                    $response = $timetrackingRecordsRepo->MapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate,$limit, $offset);
                    $response = $this->processResponse($response);
                    $flag = 1;
                } elseif (
                    (count($status) === 1) &&
                    in_array(GeneralConstants::NEW, $status)
                ) {
                    if ($offset === 1) {
                        $count = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CountMapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate);
                        if (!empty($count)) {
                            $count = (int)$count[0][1];
                        }
                    }

                    $response = $this->entityManager->getRepository('AppBundle:Timeclockdays')->MapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate,$limit, $offset);
                    $response = $this->processResponse($response);
                    $flag = 1;
                }
            }

            //Default Condition
            if (!$flag) {
                if ($offset === 1) {
                    $count1 = $timetrackingRecordsRepo->CountMapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate);
                    if ($count1) {
                        $count1 = (int)$count1[0][1];
                    }
                    $count2 = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CountMapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate);

                    if ($count2) {
                        $count2 = (int)$count2[0][1];
                    }
                    $count = $count1 + $count2;
                }
                $response2 = null;
                $response = $timetrackingRecordsRepo->MapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate,$limit, $offset);
                $response = $this->processResponse($response);
                $countResponse = count($response);
                if ($countResponse < $limit) {
                    $limit = $limit - $countResponse;
                    $response2 = $this->entityManager->getRepository('AppBundle:Timeclockdays')->MapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate,$limit, $offset);
                    $response2 = $this->processResponse($response2);
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
            $this->logger->error('Failed fetching Time Tracking due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }


    /**
     * @param $timeZoneID
     * @param $clockIn
     * @return mixed
     */
    public function TimeZoneCalculation($timeZoneID, $clockIn)
    {
        try {
            $timeZoneRepo = $this->entityManager->getRepository('AppBundle:Timezones')->findOneBy(array('timezoneid'=>$timeZoneID));
            if(!$timeZoneRepo) {
                throw new UnprocessableEntityHttpException(null,ErrorConstants::INVALID_STATUS);
            }
            $timeZoneRegion = $timeZoneRepo->getRegion();
            $newTimeZone = new \DateTimeZone($timeZoneRegion);
            $clockIn->setTimezone($newTimeZone);
            return $clockIn;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching Time Tracking due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $diff
     * @return float|int
     */
    public function DateDiffCalculation($diff)
    {
        $days = $diff->format('%a');
        $hours = $diff->format('%h');
        $minutes = $diff->format('%i');
        $seconds = $diff->format('%s');

        $totalTime = ($days*24*60*60)+($hours*60*60)+($minutes*60)+$seconds;
        return $totalTime;
    }

    /**
     * @param $response
     * @return mixed
     */
    public function processResponse($response)
    {
        for ($i = 0; $i < count($response); $i++) {
            if (!array_key_exists('Status', $response[$i])) {
                $response[$i]["Status"] = 2;
            }
            $response[$i]["TimeTracked"] = gmdate('H:i:s', $this->DateDiffCalculation($response[$i]['ClockIn']->diff($response[$i]['ClockOut'])));
            $time = $this->TimeZoneCalculation($response[$i]['TimeZoneID'], $response[$i]['ClockIn']);
            $response[$i]["Date"] = $time->format('Y-m-d');
            unset($response[$i]['ClockIn']);
            unset($response[$i]['ClockOut']);
            unset($response[$i]['TimeZoneID']);
        }
        return $response;
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function ApproveTimeTracking($customerID, $content)
    {
        try {
            if(!array_key_exists('IntegrationID',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncTimeTracking is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncTimeTrackingEnabled($integrationID,$customerID);
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

                $timetrackingRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(
                    array(
                        'timeclockdaysid' => $data[$i][GeneralConstants::TIME_CLOCK_DAYS_ID]
                    )
                );

                // Throw Exception if the the record's txnID is not null
                if(
                ($timetrackingRecords !== null?($timetrackingRecords->getTxnid() !== null):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TIMECLOCKDAYSID);
                }

                $timeclockdays = $this->entityManager->getRepository('AppBundle:Timeclockdays')->findOneBy(array(
                        'timeclockdayid' => $data[$i][GeneralConstants::TIME_CLOCK_DAYS_ID]
                    )
                );

                if(!$timeclockdays ||
                    ($timeclockdays !== null?($timeclockdays->getServicerid()->getCustomerid()->getCustomerid() !== $customerID):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TIMECLOCKDAYSID);
                }

                // If Integration QBD Time Tracking Record exist, then simply update the record with the new Status
                if (!$timetrackingRecords) {
                    // Create New Record
                    $timetrackingRecords = new Integrationqbdtimetrackingrecords();

                    $timetrackingRecords->setTimeclockdaysid($timeclockdays);
                    $timetrackingRecords->setStatus($data[$i]['Status']);

                    $this->entityManager->persist($timetrackingRecords);
                } else {
                    // Update the record
                    $timetrackingRecords->setStatus($data[$i]['Status']);
                    $this->entityManager->persist($timetrackingRecords);
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
            $this->logger->error('Failed Saving Time Tracking Approval due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}