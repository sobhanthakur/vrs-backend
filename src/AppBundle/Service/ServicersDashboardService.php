<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 10/2/20
 * Time: 12:10 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\DatabaseViews\Issues;
use AppBundle\DatabaseViews\TimeClockDays;
use AppBundle\Entity\Taskacceptdeclines;
use AppBundle\Entity\Timeclockdays as TimeClock;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


/**
 * Class ServicersDashboardService
 * @package AppBundle\Service
 */
class ServicersDashboardService extends BaseService
{
    /**
     * @param $servicerID
     * @return array
     */
    public function GetTasks($servicerID)
    {
        try {
            $response = [];
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->FetchTasksForDashboard($servicerID, $servicers);
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID,$servicers[0]['Region']);

            for ($i=0; $i<count($tasks); $i++) {
//            for ($i=0; $i<2; $i++) {

                    // Initialize local variables
                $taskEstimates = null;
                $guestDetails = null;
                $acceptDecline = 0;
                $expand = 0;
                $startTask = 0;
                $pauseTask = 0;
                $window = null;
                $tabs = null;
                $description = null;
                $manage = 0;
                $started = null;

                // Show AcceptDecline
                if($servicers[0]['RequestAcceptTasks'] && !$tasks[$i]['AcceptedDate']) {
                    $acceptDecline = 1;
                }
                $response[$i]['AcceptDecline'] = $acceptDecline;

                // Show or hide expand
                if(!$acceptDecline && (!$servicers[0]['TimeTracking'] || $servicers[0]['TimeTracking'] === 0)) {
                    $expand = 1;
                }
                $response[$i]['Expand'] = $expand;

                // Show or hide Start Task
                if ( ((int)$servicers[0]['TimeTracking'] === 1) &&
                     ((int)$servicers[0]['AllowStartEarly']) &&
                     (((int)$servicers[0]['RequestAcceptTasks']) !== 1 || ((int)$servicers[0]['RequestAcceptTasks'] === 1 && ($tasks[$i]['AcceptedDate'] !== '')))
                ) {
                    if (empty($timeClockTasks) || (string)$timeClockTasks[0]['TaskID'] !== (string)$tasks[$i]['TaskID']) {
                        $startTask = 1;
                    } else {
                        $pauseTask = 1;
                        $manage = 1;
                        $started = new \DateTime($timeClockTasks[0]['ClockIn']);
                        $started->setTimezone(new \DateTimeZone($servicers[0]['Region']));
                        $started = $started->format('h:i A');
                    }
                }

                $response[$i]['StartTask'] = $startTask;
                $response[$i]['PauseTask'] = $pauseTask;

                // Task Estimates Response
                if($servicers[0]['ShowTaskEstimates']) {
                    $taskEstimates = array(
                        'Min' => $tasks[$i]['Min'],
                        'Max' => $tasks[$i]['Max']
                    );
                }
                $response[$i]['TaskEstimates'] = $taskEstimates;

                // Assigned Date
                $response[$i]['AssignedDate'] = $tasks[$i]['AssignedDate'];

                // Window Calculation
                $response[$i]['Window'] = array(
                    'FromDate' => $tasks[$i]['TaskStartDate'],
                    'ToDate' => $tasks[$i]['TaskCompleteByDate'],
                    'FromTime' => $tasks[$i]['TaskStartTime'],
                    'ToTime' => $tasks[$i]['TaskCompleteByTime'],
                    'FromMinutes' => $tasks[$i]['TaskStartTimeMinutes'],
                    'ToMinutes' => $tasks[$i]['TaskCompleteByTimeMinutes']
                );

                if (
                    ($tasks[$i]['TaskDescription'] !== null ? $tasks[$i]['TaskDescription'] !== '' : null) ||
                    ($tasks[$i]['GlobalNote'] !== null ? $tasks[$i]['GlobalNote'] !== '' : null) ||
                    ($tasks[$i]['Instructions'] !== null ? $tasks[$i]['Instructions'] !== '' : null) ||
                    ($tasks[$i]['InGlobalNote'] !== null ? $tasks[$i]['InGlobalNote'] !== '' : null) ||
                    ($tasks[$i]['OutGlobalNote'] !== null ? $tasks[$i]['OutGlobalNote'] !== '' : null) ||
                    ($tasks[$i]['BookingTags'] !== null ? $tasks[$i]['BookingTags'] !== '' : null) ||
                    ($tasks[$i]['ManualBookingTags'] !== null ? $tasks[$i]['ManualBookingTags'] !== '' : null) ||
                    ($tasks[$i]['NextBookingTags'] !== null ? $tasks[$i]['NextBookingTags'] !== '' : null) ||
                    ($tasks[$i]['NextManualBookingTags'] !== null ? $tasks[$i]['NextManualBookingTags'] !== '' : null) ||
                    ($tasks[$i]['PMSHousekeepingNote'] !== null ? $tasks[$i]['PMSHousekeepingNote'] !== '' : null)
                ) {
                    $description = array(
                        'TaskDescription' => $tasks[$i]['TaskDescription'],
                        'GlobalNote' => $tasks[$i]['GlobalNote'],
                        'TaskType' => $tasks[$i]['TaskType'],
                        'OutGlobalNote' => $tasks[$i]['OutGlobalNote'],
                        'ShowAllTagsOnDashboards' => $tasks[$i]['ShowAllTagsOnDashboards'],
                        'BookingTags' => $tasks[$i]['BookingTags'],
                        'ManualBookingTags' => $tasks[$i]['ManualBookingTags'],
                        'NextBookingTags' => $tasks[$i]['NextBookingTags'],
                        'NextManualBookingTags' => $tasks[$i]['NextManualBookingTags'],
                        'ShowPMSHousekeepingNoteOnDashboards' => $tasks[$i]['ShowPMSHousekeepingNoteOnDashboards'],
                        'PMSHousekeepingNote' => $tasks[$i]['PMSHousekeepingNote'],
                        'InGlobalNote' => $tasks[$i]['InGlobalNote'],
                        'Instructions' => $tasks[$i]['Instructions']
                    );
                }
                $response[$i]['Description'] = $description;

                // Task Details
                $response[$i]['Details'] = array(
                    'StaffDashboardNote' => $tasks[$i]['StaffDashboardNote'],
                    'TaskID' => $tasks[$i]['TaskID'],
                    'TaskName' => $tasks[$i]['TaskName'],
                    'Region' => $tasks[$i]['Region'],
                    'RegionColor' => $tasks[$i]['RegionColor'],
                    'PropertyBookingID' => $tasks[$i]['PropertyBookingID'],
                    'Map' => array(
                        'Lat' => $tasks[$i]['Lon'],
                        'Lon' => $tasks[$i]['Lat']
                    ),
                    'ServiceName' => $tasks[$i]['ServiceName'],
                    'PropertyID' => $tasks[$i]['PropertyID'],
                    'ServiceID' => $tasks[$i]['ServiceID'],
                    'PropertyName' => $tasks[$i]['PropertyName'],
                    'Started' => $started
                );

                // Guest Details
                if ($servicers[0]['IncludeGuestNumbers'] && $servicers[0]['IncludeGuestEmailPhone'] && $servicers[0]['IncludeGuestName']) {
                    $guestDetails = array(
                        'Previous' => array(
                            'Name' => $tasks[$i]['PrevName'],
                            'Email' => $tasks[$i]['PrevEmail'],
                            'Phone' => $tasks[$i]['PrevPhone'],
                            'NumberOfGuests' => $tasks[$i]['PrevNumberOfGuests'],
                            'NumberOfChildren' => $tasks[$i]['PrevNumberOfChildren'],
                            'NumberOfPets' => $tasks[$i]['PrevNumberOfPets']
                        ),
                        'Next' => array(
                            'Name' => $tasks[$i]['NextName'],
                            'Email' => $tasks[$i]['NextEmail'],
                            'Phone' => $tasks[$i]['NextPhone'],
                            'NumberOfGuests' => $tasks[$i]['NextNumberOfGuests'],
                            'NumberOfChildren' => $tasks[$i]['NextNumberOfChildren'],
                            'NumberOfPets' => $tasks[$i]['NextNumberOfPets']
                        )

                    );
                }
                $response[$i]['GuestDetails'] = $guestDetails;

                // Check if log tab has to be rendered
                $temp = false;
                $allIssues = 'SELECT FromTaskID FROM  ('.Issues::vIssues.') AS vIssues  WHERE vIssues.ClosedDate IS NULL AND vIssues.PropertyID='.$tasks[$i]['PropertyID'].' AND vIssues.PropertyID <> 0';
                $issues = $this->entityManager->getConnection()->prepare($allIssues);
                $issues->execute();
                $issues = $issues->fetchAll();

                foreach ($issues as $issue) {
                    if ($issue['FromTaskID'] === (string)$tasks[$i]['TaskID']) {
                        $temp = true;
                        break;
                    }
                }

                $log = 0;
                if ( count($issues) > 0 &&
                    ((int)$servicers[0]['ShowIssueLog'] === 1  || $temp)
                ) {
                    $log = 1;
                }

                // Check if image tab has to be rendered
                $image = 0;
                $img = $this->entityManager->getRepository('AppBundle:Images')->GetImageCountForDashboard($tasks[$i]['PropertyID']);
                if(!empty($img)) {
                    $image = 1;
                }

                // Check if info tab has to be rendered
                $info =0;
                if (trim($tasks[$i]['PropertyFile'] !== '')
                    || trim($tasks[$i]['TaskDescription'])
                    || trim($tasks[$i]['DoorCode'])
                    || trim($tasks[$i]['Address'])
                ) {
                    $info = 1;
                }

                // Check if Assignments tab has to be rendered or not
                $assignments = 0;
                $temp = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForAssignmentsTab($tasks[$i]['PropertyBookingID'],1);

                if (
                    ((int)$servicers[0]['AllowAdminAccess'] === 1 ||
                        $servicers[0]['Email'] === $tasks[$i]['Email']) &&
                    (!empty($temp))
                ) {
                    $assignments = 1;
                }

                // Check if Bookings tab has to be rendered or not
                $bookings = 0;
                $propertyAccessList = $this->entityManager->getRepository('AppBundle:Servicerstoproperties')->findOneBy(array(
                    'propertyid' => $tasks[$i]['PropertyID'],
                    'servicerid' => $servicerID
                ));
                if ($propertyAccessList &&
                    ((int)$tasks[$i]['PropertyBookingID'] !== 0 || (int)$tasks[$i]['NextPropertyBookingID'] !== 0)
                ) {
                    $bookings = 1;
                }

                if ( $manage || $image || $log || $info) {
                    $tabs = array(
                        'Manage' => $manage,
                        'Info' => $info,
                        'Log' => $log,
                        'Imgs' => $image,
                        'Assgmnts' => $assignments,
                        'Bkngs' => $bookings
                    );
                }
                $response[$i]['Tabs'] = $tabs;

                // Scheduling Notes
                $schedulingCalenderNotes = $this->entityManager->getRepository('AppBundle:Schedulingcalendarnotes')->SchedulingNotesForDashboard($servicerID,$tasks[$i]['AssignedDate']);
                $response[$i]['Notes'] = !empty($schedulingCalenderNotes) ? $schedulingCalenderNotes[0] : null;

                // Team for Each Task
                $team = $this->entityManager->getRepository('AppBundle:Tasks')->GetTeamByTask($tasks[$i]['TaskID']);
                $response[$i]['Team'] = !empty($team) ? $team : null;
            }
            return array('Tasks' => $response);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching tasks for servicers dashboard: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function ClockInOut($servicerID, $content)
    {
        try {
            $dateTime = $content['DateTime'] ? (new \DateTime($content['DateTime'])) : null;
            $clockInOut = $content['ClockInOut'];
            $mileage = $content['Mileage'] ? $content['Mileage'] : null;

            // ServicerObject
            $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID);

            $today = $dateTime->setTimezone((new \DateTimeZone($servicer->getTimezoneid()->getRegion())));

            // Query TimeClockDays
            $timeClockDays = "SELECT TOP 1 ClockIn,ClockOut,TimeZoneRegion FROM (".TimeClockDays::vTimeClockDays.") AS T WHERE T.ClockIn >= '".$today->format('Y-m-d')."' AND T.ClockIn <= '".$today->modify('+1 day')->format('Y-m-d')."' AND T.ClockOut IS NULL And T.ServicerID=".$servicerID;
            $timeClockDays = $this->entityManager->getConnection()->prepare($timeClockDays);
            $timeClockDays->execute();
            $timeClockDays = $timeClockDays->fetchAll();

            if($clockInOut) {
                // Clock In
                if(empty($timeClockDays)) {
                    $timeClock = new Timeclock();
                    $timeClock->setServicerid($servicer);
                    $timeClock->setMileagein($mileage);
                    $this->entityManager->persist($timeClock);
                    $this->entityManager->flush();
                }
            } else {
                // Clock Out
                if(!empty($timeClockDays)) {
                    $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->findOneBy(array(
                       'servicerid' => $servicerID,
                       'clockout' => null
                    ));

                    // Set TimeClock Tasks to Current UTC DateTime
                    if ($timeClockTasks) {
                        $timeClockTasks->setClockout($dateTime);
                        $this->entityManager->persist($timeClockTasks);
                    }

                    $timeClock = $this->entityManager->getRepository('AppBundle:Timeclockdays')->findOneBy(array(
                        'clockout' => null,
                        'servicerid' => $servicerID
                    ));
                    if($timeClock) {
                        $timeClock->setClockout($dateTime);
                        $this->entityManager->persist($timeClock);
                    }
                    $this->entityManager->flush();
                }
            }

            return array(
                'Status' => 'Success'
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to Clock In/Out due to: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function AcceptTask($servicerID, $content)
    {
        try {
            $taskID = $content['TaskID'];
            $rsThisTask = $this->entityManager->getRepository('AppBundle:Tasks')->AcceptTask($servicerID,$taskID);

            if (empty($rsThisTask)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            $tasksToServicers = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->findOneBy(array(
               'taskid' => $taskID,
                'servicerid' => $servicerID
            ));

            if (!$tasksToServicers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKSTOSERVICERS);
            }

            $tasksToServicers->setAccepteddate(new \DateTime('now', new \DateTimeZone('UTC')));
            $this->entityManager->persist($tasksToServicers);

            $taskAcceptDeclines = new Taskacceptdeclines();
            $taskAcceptDeclines->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($taskID));
            $taskAcceptDeclines->setServicerid($this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID));
            $taskAcceptDeclines->setAcceptordecline(true);
            $this->entityManager->persist($taskAcceptDeclines);

            $this->entityManager->flush();

            return array(
                'Status' => 'Success',
                'TaskAcceptDeclineID' => $taskAcceptDeclines->getTaskacceptdeclineid()
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to accept task due to: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}