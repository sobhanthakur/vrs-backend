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
            $completedDate = [];
            $timezones = [];
            $new = null;

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
                    $staff = $filters['Staff'];
                }

                $regions = $this->entityManager->getRepository('AppBundle:Timeclockdays')->GetAllTimeZones($customerID, $staff);
                $timezones = $this->StartDateCalculation($regions,$integrationToCustomers[0]['startdate']);

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

            //Default Condition
            if (!$flag) {
                if ($offset === 1) {
                    $count = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CountMapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate,$timezones,$status);
                    if ($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response = $this->entityManager->getRepository('AppBundle:Timeclockdays')->MapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate,$timezones, $limit, $offset,$status);
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

                // Check If status is correct or not
                if(
                    ($data[$i]['Status'] !== 0) &&
                    ($data[$i]['Status'] !== 1) &&
                    ($data[$i]['Status'] !== 2)
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
                    $timetrackingRecords->setDay(new \DateTime($data[$i]['Date']));
                    $timetrackingRecords->setTimetrackedseconds($this->DateDiffCalculation($timeclockdays->getClockout()->diff($timeclockdays->getClockin())));

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
}