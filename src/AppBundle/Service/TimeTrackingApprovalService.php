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
            $limit = 20;
            $offset = 1;
            $response = null;
            $integrationID = null;
            $count = null;
            $filters = [];
            $status = [];
            $completedDate = [];
            $timezones = [];
            $new = null;
            $qbo = null;

            if (!array_key_exists('IntegrationID', $data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncTimeTracking is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array(
               'integrationid' => $integrationID,
                'customerid' => $customerID,
                'active' => true,
                'qbdsyncpayroll' => true
            ));
            if (!$integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            // Set if version is QBO
            if($integrationToCustomers->getVersion() === 2) {
                $qbo = true;
            }

            if (!empty($data)) {
                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];


                if (array_key_exists('Staff', $filters)) {
                    $staff = $filters['Staff'];
                }

                $regions = $this->entityManager->getRepository('AppBundle:Timeclockdays')->GetAllTimeZones($customerID, $staff);
                $timezones = $this->StartDateCalculation($regions,$integrationToCustomers->getStartdate());

                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('CompletedDate', $filters) && !empty($filters['CompletedDate']['From']) && !empty($filters['CompletedDate']['To'])) {
                    $completedDate = $filters['CompletedDate'];
                    $completedDate = $this->CompletedDateRequestCalculation($regions,$completedDate);
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }


            if (array_key_exists('Status', $filters)) {
                $status = $filters['Status'];
            }

            // Process Time tracking records
            if ($integrationToCustomers->getTimetrackingtype()) {
                // Fetch Time Clock Tasks
                if ($offset === 1) {
                    $count = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CountMapTimeClockTasks($customerID, $staff, $completedDate, $timezones, $status);
                    if ($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->MapTimeClockTasks($customerID, $staff,$completedDate, $timezones, $limit, $offset, $status);
                $response = $this->processResponse($response);
            } else {
                // Fetch Time Clock Days
                if ($offset === 1) {
                    $count = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CountMapTimeClockDaysWithFilters($customerID, $staff,$completedDate, $timezones, $status,$qbo);
                    if ($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response = $this->entityManager->getRepository('AppBundle:Timeclockdays')->MapTimeClockDaysWithFilters($customerID, $staff, $completedDate, $timezones, $limit, $offset, $status,$qbo);
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
            $this->logger->error('Failed fetching Time Tracking due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }


    /**
     * @param $region
     * @param \DateTime $clockIn
     * @return mixed
     */
    public function TimeZoneCalculation($region, $clockIn)
    {
        $newTimeZone = new \DateTimeZone($region);
        $clockIn->setTimezone($newTimeZone);
        return $clockIn;

    }

    /**
     * @param \DateTime $diff
     * @return float|int
     */
    public function DateDiffCalculation($diff)
    {
        $days = (float)$diff->format('%a');
        $hours = (float)$diff->format('%h');
        $minutes = (float)$diff->format('%i');
        $seconds = (float)$diff->format('%s');

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
            if ($response[$i]['Status'] === null) {
                $response[$i]["Status"] = 2;
            }

            if($response[$i]['TimeTracked']) {
                $response[$i]["TimeTracked"] = gmdate('H:i:s',$response[$i]["TimeTracked"]);
                $response[$i]["Date"] = $response[$i]["Date"]->format('Y-m-d');
            } else {
                $response[$i]["TimeTracked"] = gmdate('H:i:s', $this->DateDiffCalculation($response[$i]['ClockIn']->diff($response[$i]['ClockOut'])));
                $time = $this->TimeZoneCalculation($response[$i]['TimeZoneRegion'], $response[$i]['ClockIn']);
                $response[$i]["Date"] = $time->format('Y-m-d');
            }
            if(array_key_exists('ClockIn',$response[$i])) {
                unset($response[$i]['ClockIn']);
            }
            if(array_key_exists('ClockOut',$response[$i])) {
                unset($response[$i]['ClockOut']);
            }
            if(array_key_exists('TimeZoneRegion',$response[$i])) {
                unset($response[$i]['TimeZoneRegion']);
            }
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
                $timetrackingRecords = null;
                $timeclock = null;

                // Check If status is correct or not
                if(
                    ($data[$i]['Status'] !== 0) &&
                    ($data[$i]['Status'] !== 1) &&
                    ($data[$i]['Status'] !== 2)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STATUS);
                }

                if(array_key_exists('TimeClockTasksID',$data[$i])) {
                    $timetrackingRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(
                        array(
                            'timeclocktasksid' => $data[$i][GeneralConstants::TIME_CLOCK_TASKS_ID]
                        )
                    );

                    $timeclock = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->findOneBy(array(
                            'timeclocktaskid' => $data[$i][GeneralConstants::TIME_CLOCK_TASKS_ID]
                        )
                    );
                } else {
                    $timetrackingRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(
                        array(
                            'timeclockdaysid' => $data[$i][GeneralConstants::TIME_CLOCK_DAYS_ID]
                        )
                    );

                    $timeclock = $this->entityManager->getRepository('AppBundle:Timeclockdays')->findOneBy(array(
                            'timeclockdayid' => $data[$i][GeneralConstants::TIME_CLOCK_DAYS_ID]
                        )
                    );
                }

                // If Integration QBD Time Tracking Record exist, then simply update the record with the new Status
                if (!$timetrackingRecords) {
                    // Create New Record
                    $timetrackingRecords = new Integrationqbdtimetrackingrecords();

                    if(array_key_exists('TimeClockTasksID',$data[$i])) {
                        $timetrackingRecords->setTimeclocktasksid($timeclock);
                    } else {
                        $timetrackingRecords->setTimeclockdaysid($timeclock);
                    }

                    $timetrackingRecords->setStatus($data[$i]['Status']);
                    $timetrackingRecords->setDay(new \DateTime($data[$i]['Date']));
                    $timetrackingRecords->setTimetrackedseconds($this->DateDiffCalculation($timeclock->getClockout()->diff($timeclock->getClockin())));

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

    /**
     * Converts the Completed Date filter to UTC and query in the DB
     * @param $regions
     * @param $completedDateRequest
     * @return array|null
     */
    public function CompletedDateRequestCalculation($regions, $completedDateRequest)
    {
        $response = [];
        for($i=0;$i<count($regions);$i++) {
            $timeZoneLocal = new \DateTimeZone($regions[$i]['Region']);
            $fromLocal = new \DateTime($completedDateRequest['From'],$timeZoneLocal);
            $toLocal = new \DateTime($completedDateRequest['To'],$timeZoneLocal);

            $timeZoneUTC = new \DateTimeZone('UTC');
            $fromUTC = $fromLocal->setTimezone($timeZoneUTC);
            $toUTC = $toLocal->setTimezone($timeZoneUTC);
            $response[$i]['From'] = $fromUTC;
            $response[$i]['To'] = $toUTC;

        }
        return $response;
    }

    /**
     * Fetches Records Based on clockout>=StartDate
     * @param $regions
     * @param \DateTime $startDate
     * @return array|null
     */
    public function StartDateCalculation($regions, $startDate)
    {
        $response = [];
        for($i=0;$i<count($regions);$i++) {
            $region = $regions[$i]['Region'];
            $timeZoneLocal = new \DateTimeZone($region);
            $startDateLocal = new \DateTime($startDate->format('Y-m-d'),$timeZoneLocal);
            $timeZoneUTC = new \DateTimeZone('UTC');
            $response[] = $startDateLocal->setTimezone($timeZoneUTC);

        }
        return $response;
    }

    /**
     * @param $dateFilter
     * @return array
     */
    public function TrimDateFilter($dateFilter)
    {
        $response = [];
        for($i=0;$i<count($dateFilter);$i++) {
            $response[] = $dateFilter[$i]['TimeClockDaysID'];
        }
        return $response;
    }

    public function FetchDriveTimeForStaffs($customerID,$data)
    {
        try {
            $integrationID = $data['IntegrationID'];
            $startDate = null;

            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array(
               'customerid' => $customerID,
                'integrationid' => $integrationID
            ));
            if (!$integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INTEGRATION_NOT_PRESENT);
            }

            $startDate = $integrationToCustomers->getStartdate();
            $timeTrackingType = $integrationToCustomers->getTimetrackingtype();

            if ($timeTrackingType) {
                $clockSortByID = [];
                $taskSortByID = [];
                $clockResponse = [];
                $taskResponse = [];


                // Get Time Clock Days For Drive Time
                $timeClockDays = $this->entityManager->getRepository('AppBundle:Timeclockdays')->TimeClockDaysForDriveTime($customerID,$startDate->format('Y-m-d'));
                if(!empty($timeClockDays)) {
                    foreach ($timeClockDays as $timeClockDay) {
                        $clockSortByID[$timeClockDay['ServicerID']][$timeClockDay['ClockIn']->format('Y-m-d')][] =
                            $this->DateDiffCalculation($timeClockDay['ClockIn']->diff($timeClockDay['ClockOut']));
                    }
                    foreach ($clockSortByID as $outerKey => $outerValue) {
                        foreach ($outerValue as $innerKey => $innerValue) {
                            $sum = 0;
                            foreach ($innerValue as $time) {
                                $sum += (float)$time;
                            }
                            $clockResponse[$outerKey][$innerKey] = $sum;
                        }
                    }
                }

                // Get TimeClock Tasks For Drive Time
                $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->TimeClockTasksForDriveTime($customerID,$startDate->format('Y-m-d'));
                if (!empty($timeClockDays) && !empty($timeClockTasks)) {
                    foreach ($timeClockTasks as $timeClockTask) {
                        $taskSortByID[$timeClockTask['ServicerID']][$timeClockTask['ClockIn']->format('Y-m-d')][] =
                            $this->DateDiffCalculation($timeClockTask['ClockIn']->diff($timeClockTask['ClockOut']));
                    }
                    foreach ($taskSortByID as $outerKey => $outerValue) {
                        foreach ($outerValue as $innerKey => $innerValue) {
                            $sum = 0;
                            foreach ($innerValue as $time) {
                                $sum += (float)$time;
                            }
                            $taskResponse[$outerKey][$innerKey] = $sum;
                        }
                    }
                }

                // Calculate The Difference
                foreach ($clockResponse as $outerKey => $outerValue) {
                    $staff = $this->entityManager->getRepository('AppBundle:Servicers')->findOneBy(array('servicerid'=>$outerKey));
                    foreach ($outerValue as $innerKey => $innerValue) {
//                            print_r($innerValue);
                        if(array_key_exists($innerKey,$taskResponse[$outerKey])) {
                            $diff = $innerValue - $taskResponse[$outerKey][$innerKey];
                            $response[$outerKey][$innerKey] = $diff;
                            $integrationQBDTimeTracking = new Integrationqbdtimetrackingrecords();
                            $integrationQBDTimeTracking->setStatus(2);
                            $integrationQBDTimeTracking->setDay(new \DateTime($innerKey));
                            $integrationQBDTimeTracking->setTimetrackedseconds($diff);
                            $integrationQBDTimeTracking->setDrivetimestaffid($staff);
                            $this->entityManager->persist($integrationQBDTimeTracking);
                        }
                    }
                }
                $this->entityManager->flush();
                return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
            }
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching DriveTime due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}