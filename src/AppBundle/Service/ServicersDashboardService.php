<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 10/2/20
 * Time: 12:10 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\DatabaseViews\Issues;
use AppBundle\DatabaseViews\TaskWithServicers;
use AppBundle\DatabaseViews\TimeClockDays;
use AppBundle\Entity\Taskacceptdeclines;
use AppBundle\Entity\Taskchanges;
use AppBundle\Entity\Timeclockdays as TimeClock;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    public function GetTasks($servicerID,$content)
    {
        try {
            $response = [];
            $currentTaskDate = null;
            $notes = [];
            $servicers = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->ServicerDashboardRestrictions((int)$servicerID);
            $tasks = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->FetchTasksForDashboard((int)$servicerID, $servicers,null,$content);
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks((int)$servicerID,$servicers[0][GeneralConstants::REGION]);

            // Scheduling Notes
            !empty($tasks) ? $currentTaskDate = $tasks[0][GeneralConstants::ASSIGNEDDATE] : $currentTaskDate = null;

            $schedulingCalenderNotes = $this->entityManager->getRepository('AppBundle:Schedulingcalendarnotes')->SchedulingNotesForDashboard2($servicerID, $servicers, $currentTaskDate);
            foreach ($schedulingCalenderNotes as $calenderNote) {
                $notes[$calenderNote['StartDate']->format('Y-m-d')] = $calenderNote;
            }


            // Local Time
            $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($servicers[0][GeneralConstants::REGION]);
            $localHour = (int)ltrim($localTime->format('H'), '0');;
            $localTime->setTime(0,0,0);

            /*
             * Make Sure Local time is changed here
             */
            for ($i=0; $i<count($tasks); $i++) {
                // Initialize local variables
                $taskEstimates = null;
                $guestDetails = null;
                $acceptDecline = 0;
                $expand = 0;
                $startTask = 0;
                $pauseTask = 0;
                $tabs = null;
                $description = null;
                $manage = 0;
                $started = null;
                $doneCondition = 0;

                // Scheduling Notes
                $response[$i]['Notes'] = null;
                $assignedDate = $tasks[$i][GeneralConstants::ASSIGNEDDATE]->format('Y-m-d');
                if (!empty($notes) && array_key_exists($assignedDate,$notes)) {
                    $response[$i]['Notes'] = $notes[$assignedDate];
                    unset($notes[$assignedDate]);
                }
                $response[$i]['IsTask'] = 1;

                // Show AcceptDecline
                if($servicers[0][GeneralConstants::REQUESTACCEPTTASK] && !$tasks[$i]['AcceptedDate']) {
                    $acceptDecline = 1;
                }
                $response[$i]['AcceptDecline'] = $acceptDecline;

                // Show or hide expand
                if(!$acceptDecline && (!$servicers[0][GeneralConstants::TIMETRACKING] || $servicers[0][GeneralConstants::TIMETRACKING] === 0)) {
                    $expand = 1;
                }
                $response[$i]['Expand'] = $expand;

                // Condition for Show Checklist Preview in Info Tab
                $checkListPreview = 0;
                if ($tasks[$i][GeneralConstants::TASKSTARTDATE] > $localTime ||
                    (
                    (int)$servicers[0][GeneralConstants::TIMETRACKING] === 1 &&
                    (
                    empty($timeClockTasks) || (string)$timeClockTasks[0][GeneralConstants::TASK_ID] !== (string)$tasks[$i][GeneralConstants::TASK_ID]
                    )
                    )
                ) {
                    $checkListPreview = 1;
                }
                $response[$i]['InfoChecklistPreview'] = $checkListPreview;

                // Show or hide Start Task
                if ( ((int)$servicers[0][GeneralConstants::TIMETRACKING] === 1 && $tasks[$i][GeneralConstants::TASKSTARTDATE] <= $localTime) &&
                     ((int)$servicers[0]['AllowStartEarly'] === 1 || $tasks[$i][GeneralConstants::ASSIGNEDDATE] <= $localTime) &&
                     (((int)$servicers[0][GeneralConstants::REQUESTACCEPTTASK]) !== 1 || ((int)$servicers[0][GeneralConstants::REQUESTACCEPTTASK] === 1 && ($tasks[$i]['AcceptedDate'] !== '')))
                ) {
                    if (empty($timeClockTasks) || (string)$timeClockTasks[0][GeneralConstants::TASK_ID] !== (string)$tasks[$i][GeneralConstants::TASK_ID]) {
                        $startTask = 1;
                    } else {
                        $pauseTask = 1;
                        $started = new \DateTime($timeClockTasks[0]['ClockIn']);
                        $started->setTimezone(new \DateTimeZone($servicers[0][GeneralConstants::REGION]));
                        $started = $started->format('h:i A');
                    }
                }

                if (!((int)$servicers[0][GeneralConstants::TIMETRACKING] === 1
                    && (empty($timeClockTasks) || (string)$timeClockTasks[0][GeneralConstants::TASK_ID] !== (string)$tasks[$i][GeneralConstants::TASK_ID]))
                    && $tasks[$i][GeneralConstants::TASKSTARTDATE] <= $localTime
                    && ((int)$servicers[0]['AllowStartEarly'] === 1 || $tasks[$i][GeneralConstants::ASSIGNEDDATE] <= $localTime)
                ) {
                    $manage = 1;
                }

                if ((int)$servicers[0][GeneralConstants::TIMETRACKING] === 1 && !empty($timeClockTasks) && (string)$timeClockTasks[0][GeneralConstants::TASK_ID] === (string)$tasks[$i][GeneralConstants::TASK_ID]) {
                    $doneCondition = 1;
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
                $response[$i][GeneralConstants::ASSIGNEDDATE] = $tasks[$i][GeneralConstants::ASSIGNEDDATE];

                // Window Calculation
                $response[$i]['Window'] = array(
                    'FromDate' => $tasks[$i][GeneralConstants::TASKSTARTDATE],
                    'ToDate' => $tasks[$i][GeneralConstants::TASKCOMPLETEBYDATE],
                    'FromTime' => $tasks[$i][GeneralConstants::TASKSTARTTIME],
                    'ToTime' => $tasks[$i][GeneralConstants::TASKCOMPLETEBYTIME],
                    'FromMinutes' => $tasks[$i]['TaskStartTimeMinutes'],
                    'ToMinutes' => $tasks[$i]['TaskCompleteByTimeMinutes'],
                    'TaskTime' => $tasks[$i]['TaskTime'],
                    'TaskTimeMinutes' => $tasks[$i]['TaskTimeMinutes'],
                    'TaskDateTime' => $tasks[$i]['TaskDateTime']
                );

                $globalNote = null;
                if ((int)$tasks[$i]['ShowNextBookingNotes'] === 1) {
                    if ((string)$tasks[$i]['NextGlobalNote'] !== '' &&
                        $tasks[$i]['NextCheckIn'] &&
                        ($localTime->diff($tasks[$i]['NextCheckIn'])->format('%a')) <= 14
                    ) {
                        $globalNote = 'Next Booking Note ('.$tasks[$i]['NextCheckIn']->format('m-d-y').') '.$tasks[$i]['NextGlobalNote'];
                    }
                } else {
                    if ((string)$tasks[$i]['GlobalNote'] !== '') {
                        $globalNote = 'Booking Note: '.$tasks[$i]['GlobalNote'];
                    }
                }

                if (
                    ($tasks[$i][GeneralConstants::TASKDESCRIPTION] !== null ? $tasks[$i][GeneralConstants::TASKDESCRIPTION] !== '' : null) ||
                    ($globalNote) ||
                    ($tasks[$i][GeneralConstants::INSTRUCTIONS] !== null ? $tasks[$i][GeneralConstants::INSTRUCTIONS] !== '' : null) ||
                    ($tasks[$i][GeneralConstants::INGLOBALNOTE] !== null ? $tasks[$i][GeneralConstants::INGLOBALNOTE] !== '' : null) ||
                    ($tasks[$i][GeneralConstants::OUTGLOBALNOTE] !== null ? $tasks[$i][GeneralConstants::OUTGLOBALNOTE] !== '' : null) ||
                    ($tasks[$i][GeneralConstants::BOOKINGTAGS] !== null ? $tasks[$i][GeneralConstants::BOOKINGTAGS] !== '' : null) ||
                    ($tasks[$i][GeneralConstants::MANUALBOOKINGTAGS] !== null ? $tasks[$i][GeneralConstants::MANUALBOOKINGTAGS] !== '' : null) ||
                    ($tasks[$i]['NextBookingTags'] !== null ? $tasks[$i]['NextBookingTags'] !== '' : null) ||
                    ($tasks[$i][GeneralConstants::NEXTMANUALBOOKINGTAGS] !== null ? $tasks[$i][GeneralConstants::NEXTMANUALBOOKINGTAGS] !== '' : null) ||
                    ($tasks[$i][GeneralConstants::PMSHOUSEKEEPINGNOTE] !== null ? $tasks[$i][GeneralConstants::PMSHOUSEKEEPINGNOTE] !== '' : null)
                ) {
                    $description = array(
                        GeneralConstants::TASKDESCRIPTION => $tasks[$i][GeneralConstants::TASKDESCRIPTION],
                        GeneralConstants::GLOBALNOTE => $globalNote,
                        GeneralConstants::TASKTYPE => $tasks[$i][GeneralConstants::TASKTYPE],
                        GeneralConstants::OUTGLOBALNOTE => $tasks[$i][GeneralConstants::OUTGLOBALNOTE],
                        'ShowAllTagsOnDashboards' => (int)$tasks[$i]['ShowAllTagsOnDashboards'],
                        GeneralConstants::BOOKINGTAGS => $tasks[$i][GeneralConstants::BOOKINGTAGS],
                        GeneralConstants::MANUALBOOKINGTAGS => $tasks[$i][GeneralConstants::MANUALBOOKINGTAGS],
                        'NextBookingTags' => $tasks[$i]['NextBookingTags'],
                        GeneralConstants::NEXTMANUALBOOKINGTAGS => $tasks[$i][GeneralConstants::NEXTMANUALBOOKINGTAGS],
                        'ShowPMSHousekeepingNoteOnDashboards' => (int)$tasks[$i]['ShowPMSHousekeepingNoteOnDashboards'],
                        GeneralConstants::PMSHOUSEKEEPINGNOTE => $tasks[$i][GeneralConstants::PMSHOUSEKEEPINGNOTE],
                        GeneralConstants::INGLOBALNOTE => $tasks[$i][GeneralConstants::INGLOBALNOTE],
                        GeneralConstants::INSTRUCTIONS => $tasks[$i][GeneralConstants::INSTRUCTIONS],
                        GeneralConstants::SHORTDESCRIPTION => $tasks[$i][GeneralConstants::SHORTDESCRIPTION]
                    );
                }
                $response[$i]['Description'] = $description;

                // Task Details
                $quickChangeAbbreviation = null;
                if ((int)$tasks[$i]['BackToBackStart'] === 1 && $tasks[$i][GeneralConstants::TASKTYPE] !== null && (int)$tasks[$i][GeneralConstants::TASKTYPE] === 1) {
                    $quickChangeAbbreviation = trim($servicers[0][GeneralConstants::QUICKCHANGEABBREVIATION]);
                }

                if ((int)$tasks[$i]['BackToBackEnd'] === 1 && $tasks[$i][GeneralConstants::TASKTYPE] !== null && ((int)$tasks[$i][GeneralConstants::TASKTYPE] === 0 ||
                        (int)$tasks[$i][GeneralConstants::TASKTYPE] === 4 ||
                        (int)$tasks[$i][GeneralConstants::TASKTYPE] === 8)
                ) {
                    $quickChangeAbbreviation = trim($servicers[0][GeneralConstants::QUICKCHANGEABBREVIATION]);
                }

                $piecePay = null;

                if ((int)$servicers[0]['ShowPiecePayAmountsOnEmployeeDashboards'] === 1) {
                    if ((int)$tasks[$i]['PayType'] === 1) {
                        $piecePay = $tasks[$i][GeneralConstants::PIECEPAY];
                    }

                    if ((int)$tasks[$i]['PayType'] === 2) {
                        $piecePay = $tasks[$i][GeneralConstants::PIECEPAY] * $servicers[0]['PayRate'];
                    }
                }

                // Set Currency
                if ($piecePay) {
                    $fmt = new \NumberFormatter( $servicers[0]['CustomersLocale'], \NumberFormatter::CURRENCY);
                    $piecePay = $fmt->format($piecePay);
                }


                // Check Scheduling Notes
                $schedulingNote = null;
                $thisDayOfWeek =  GeneralConstants::DAYOFWEEK[$tasks[$i][GeneralConstants::ASSIGNEDDATE]->format('N')];
                if ((int)$servicers[0]['Schedulenote' . $thisDayOfWeek . 'Show']) {
                    $schedulingNote = trim($servicers[0]['ScheduleNote' . $thisDayOfWeek]);
                }

                // Set Status
                if ($tasks[$i][GeneralConstants::TASKCOMPLETEBYDATE] < $localTime || ($tasks[$i][GeneralConstants::TASKCOMPLETEBYDATE] === $localTime && $tasks[GeneralConstants::TASKCOMPLETEBYTIME] <=  $localHour)) {
                    $status = 0;
                } elseif ($tasks[$i][GeneralConstants::ASSIGNEDDATE] <= $localTime) {
                    $status = 1;
                } else {
                    $status = 2;
                }

                // Compute Region Colour
                if ((string)$tasks[$i]['BookingColor'] !== '' && $tasks[$i][GeneralConstants::PROPERTY_ID] === $tasks[$i]['PropertyBookingPropertyID']) {
                    $regionColour = trim($tasks[$i]['BookingColor']);
                } elseif ($tasks[$i][GeneralConstants::REGIONCOLOR] !== '') {
                    $regionColour = trim($tasks[$i][GeneralConstants::REGIONCOLOR]);
                } else {
                    $regionColour = '#0275d8';
                }

                $response[$i]['Details'] = array(
                    GeneralConstants::SHORTDESCRIPTION => $tasks[$i][GeneralConstants::SHORTDESCRIPTION],
                    'DoneCondition' => $doneCondition,
                    'PropertyStatus' => (int)$tasks[$i]['ShowPropertyStatusOnDashboards'] ? $tasks[$i]['PropertyStatus'] : null,
                    GeneralConstants::STATUS_CAP => $status,
                    'AllowChangeTaskDate' => (int)$servicers[0]['AllowChangeTaskDate'],
                    'ParentTaskDate' => $tasks[$i]['ParentTaskDate'],
                    'ParentTaskID' => $tasks[$i]['ParenTaskID'],
                    'ParentServiceAbbreviation' => $tasks[$i]['ParentServiceAbbreviation'],
                    'ParentCompleteConfirmedDate' => $tasks[$i]['ParentCompleteConfirmedDate'],
                    'SchedulingNote' => $schedulingNote,
                    'ShowStartTimeOnDashboard' => (int)$servicers[0]['ShowStartTimeOnDashboard'] === 1 ? 1 : 0,
                    GeneralConstants::PIECEPAY => $piecePay,
                    GeneralConstants::QUICKCHANGEABBREVIATION => $quickChangeAbbreviation,
                    'StaffDashboardNote' => $tasks[$i]['StaffDashboardNote'],
                    GeneralConstants::TASK_ID => $tasks[$i][GeneralConstants::TASK_ID],
                    'TaskName' => $tasks[$i]['TaskName'],
                    GeneralConstants::REGION => $tasks[$i][GeneralConstants::REGION],
                    GeneralConstants::REGIONCOLOR => $regionColour,
                    GeneralConstants::PROPERTYBOOKINGID => $tasks[$i][GeneralConstants::PROPERTYBOOKINGID],
                    'Map' => array(
                        'Lat' => $tasks[$i]['Lon'],
                        'Lon' => $tasks[$i]['Lat']
                    ),
                    'ServiceName' => $tasks[$i]['ServiceName'],
                    GeneralConstants::PROPERTY_ID => $tasks[$i][GeneralConstants::PROPERTY_ID],
                    'ServiceID' => $tasks[$i]['ServiceID'],
                    'PropertyName' => $tasks[$i]['PropertyName'],
                    'Started' => $started,
                    'SlackLink' => (trim($tasks[$i][GeneralConstants::SLACKCHANNELID]) !== '' && trim($tasks[$i][GeneralConstants::SLACKTEAMID] !== '') && (int)$tasks[$i]['UseSlack'] === 1) ? 1 : 0,
                    GeneralConstants::SLACKTEAMID => trim($tasks[$i][GeneralConstants::SLACKTEAMID]),
                    GeneralConstants::SLACKCHANNELID => trim($tasks[$i][GeneralConstants::SLACKCHANNELID]),
                    GeneralConstants::ADDRESS => $tasks[$i][GeneralConstants::ADDRESS]
                );

                // Fetch Parent Task Details
                $parentTaskDetails = null;
                if ($tasks[$i]['ParenTaskID'] !== '' && (int)$tasks[$i]['ParenTaskID'] !== 0) {
                    $parentTaskDetails = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->GetParentTaskServicerDetails($tasks[$i]['ParenTaskID']);
                }
                if ($parentTaskDetails && !empty($parentTaskDetails)) {
                    $parentTaskDetails = $parentTaskDetails[0];
                }
                $response[$i]['ParentTaskDetails'] = $parentTaskDetails;

                // Guest Details
                if ((int)$servicers[0]['IncludeGuestNumbers'] && ((int)$tasks[$i][GeneralConstants::TASKTYPE] !== 0 ||
                        (int)$tasks[$i][GeneralConstants::TASKTYPE] !== 2 ||
                        (int)$tasks[$i][GeneralConstants::TASKTYPE] !== 3 ||
                        (int)$tasks[$i][GeneralConstants::TASKTYPE] !== 4 ||
                        (int)$tasks[$i][GeneralConstants::TASKTYPE] !== 8
                    )
                ) {
                    $guestDetails[GeneralConstants::PREVIOUS]['NumberOfGuests'] = $tasks[$i]['PrevNumberOfGuests'];
                    $guestDetails[GeneralConstants::PREVIOUS]['NumberOfChildren'] = $tasks[$i]['PrevNumberOfChildren'];
                    $guestDetails[GeneralConstants::PREVIOUS]['NumberOfPets'] = $tasks[$i]['PrevNumberOfPets'];

                    $guestDetails['Next']['NumberOfGuests'] = $tasks[$i]['NextNumberOfGuests'];
                    $guestDetails['Next']['NumberOfChildren'] = $tasks[$i]['NextNumberOfChildren'];
                    $guestDetails['Next']['NumberOfPets'] = $tasks[$i]['NextNumberOfPets'];
                }

                if((int)$servicers[0]['IncludeGuestEmailPhone'] && (int)$tasks[$i][GeneralConstants::TASKTYPE] !== 3) {
                    $guestDetails[GeneralConstants::PREVIOUS][GeneralConstants::EMAIL] = $tasks[$i]['PrevEmail'];
                    $guestDetails[GeneralConstants::PREVIOUS]['Phone'] = $tasks[$i]['PrevPhone'];

                    $guestDetails['Next'][GeneralConstants::EMAIL] = $tasks[$i]['NextEmail'];
                    $guestDetails['Next']['Phone'] = $tasks[$i]['NextPhone'];
                }

                if((int)$servicers[0]['IncludeGuestName'] && (int)$tasks[$i][GeneralConstants::TASKTYPE] !== 3) {
                    $guestDetails[GeneralConstants::PREVIOUS]['Name'] = $tasks[$i]['PrevName'];

                    $guestDetails['Next']['Name'] = $tasks[$i]['NextName'];
                }

                $response[$i]['GuestDetails'] = $guestDetails;

                // Check if log tab has to be rendered
                $log = 0;
                $issues = $this->entityManager->getRepository('AppBundle:Issues')->LogTabForTasksAPI($tasks[$i][GeneralConstants::PROPERTY_ID],$servicers,$tasks[$i][GeneralConstants::TASK_ID]);

                /*$allIssues = 'SELECT TOP 1 CreateDate,FromTaskID FROM  ('.Issues::vIssues.') AS vIssues  WHERE vIssues.PropertyID='.$tasks[$i][GeneralConstants::PROPERTY_ID].' AND vIssues.PropertyID <> 0';
                $allIssues .= ' AND vIssues.ClosedDate IS NULL';
                if ((int)$servicers[0]['ShowIssueLog'] !== 1) {
                    $allIssues .= ' AND vIssues.FromTaskID='.$tasks[$i][GeneralConstants::TASK_ID];
                }
                $issues = $this->entityManager->getConnection()->prepare($allIssues);
                $issues->execute();
                $issues = $issues->fetchAll();*/

                if (!empty($issues)) {
                    $log = 1;
                }

                // Check if image tab has to be rendered
                $image = 0;
                $img = $this->entityManager->getRepository('AppBundle:Images')->GetImageCountForDashboard($tasks[$i][GeneralConstants::PROPERTY_ID]);
                if(!empty($img)) {
                    $image = 1;
                }

                // Check if info tab has to be rendered
                $info =0;
                if (trim($tasks[$i]['PropertyFile'] !== '')
                    || trim($tasks[$i][GeneralConstants::TASKDESCRIPTION])
                    || trim($tasks[$i]['DoorCode'])
                    || trim($tasks[$i][GeneralConstants::ADDRESS])
                ) {
                    $info = 1;
                }

                // Check if Assignments tab has to be rendered or not
                $assignments = 0;
                $propertyBookings = '';
                $propertiesCondition = '';
                $taskIDs = '';

                $pb = $tasks;
                if (!empty($pb)) {
                    foreach ($pb as $value) {
                        if ($value[GeneralConstants::PROPERTYBOOKINGID]) {
                            $propertyBookings .= $value[GeneralConstants::PROPERTYBOOKINGID] . ',';
                        }
                        if ($value[GeneralConstants::TASK_ID]) {
                            $taskIDs .= $value[GeneralConstants::TASK_ID] . ',';
                        }
                        if ($value[GeneralConstants::PROPERTY_ID]) {
                            $properties[] = $value[GeneralConstants::PROPERTY_ID] . ',';
                        }
                    }
                    $propertyBookings = preg_replace("/,$/", '', $propertyBookings);
                    $taskIDs = preg_replace("/,$/", '', $taskIDs);
                }
                // Get All Properties
                $rsCurrentTaskServicers = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->getTaskServicers(!empty($taskIDs) ? $taskIDs : 0, $servicers[0][GeneralConstants::CUSTOMER_ID], $servicers);
                if (!empty($rsCurrentTaskServicers)) {
                    foreach ($rsCurrentTaskServicers as $currentTaskServicer) {
                        if ($currentTaskServicer[GeneralConstants::PROPERTY_ID]) {
                            $propertiesCondition .= $currentTaskServicer[GeneralConstants::PROPERTY_ID] . ',';
                        }
                    }
                    $propertiesCondition = preg_replace("/,$/", '', $propertiesCondition);
                }

                empty($propertiesCondition) ? $propertiesCondition = 0 : false;
                empty($propertyBookings) ? $propertyBookings = 0 : false;

                $query1 = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->AssignmentsTask($servicers[0][GeneralConstants::CUSTOMER_ID], 50, $propertyBookings);
                $query2 = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->AssignmentsTask($servicers[0][GeneralConstants::CUSTOMER_ID], 50, null, $propertiesCondition);

                $rsAllEmployeesAndTasks = $query1 . ' UNION ' . $query2;
                $temp = 'SELECT TOP 1 ServicerID,CompleteConfirmedDate,ServiceName,Abbreviation,TaskID,TaskDate  FROM (' . $rsAllEmployeesAndTasks . ') AS R  WHERE R.PropertyID = ' . $tasks[$i][GeneralConstants::PROPERTY_ID] . '  ORDER BY R.TaskDate desc';
                $temp = $this->entityManager->getConnection()->prepare($temp);
                $temp->execute();
                $temp = $temp->fetchAll();

                if (
                    ((int)$servicers[0]['AllowAdminAccess'] === 1 ||
                        $servicers[0][GeneralConstants::EMAIL] === $servicers[0]['CustomersEmail']) &&
                    (!empty($temp))
                ) {
                    $assignments = 1;
                }

                // Check if Bookings tab has to be rendered or not
                $bookings = 0;
                $propertyAccessList = $this->entityManager->getRepository('AppBundle:Servicerstoproperties')->findOneBy(array(
                    'propertyid' => $tasks[$i][GeneralConstants::PROPERTY_ID],
                    'servicerid' => $servicerID
                ));
                if ($propertyAccessList &&
                    ((int)$tasks[$i][GeneralConstants::PROPERTYBOOKINGID] !== 0 || (int)$tasks[$i]['NextPropertyBookingID'] !== 0)
                ) {
                    $bookings = 1;
                }

                if ( $manage || $image || $log || $info) {
                    $tabs = array(
                        'Manage' => $manage,
                        'Assgmnts' => $assignments,
                        'Log' => $log,
                        'Imgs' => $image,
                        'Info' => $info,
                        'Bkngs' => $bookings
                    );
                }
                $response[$i]['Tabs'] = $tabs;

                // Team for Each Task
                $team = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->GetTeamByTask($tasks[$i][GeneralConstants::TASK_ID],$servicers);
                $response[$i]['Team'] = !empty($team) ? $team : [];
            }

            $schedulingNoteForNonTasks = [];
            foreach ($notes as $note) {
                $schedulingNoteForNonTasks[] = array(
                    'IsTask' => 0,
                    GeneralConstants::ASSIGNEDDATE => $note['StartDate'],
                    'Notes' => $note
                );
            }

            return array('Tasks' => array_merge($response,$schedulingNoteForNonTasks),'Notes' => []);
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
            $dateTime = $content['DateTime'];
            $clockInOut = $content['ClockInOut'];
            $mileage = $content['Mileage'] ? (int)$content['Mileage'] : null;

            // ServicerObject
            $servicer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($servicerID);

            $timeZone = new \DateTimeZone($servicer->getTimezoneid()->getRegion());

            // Query TimeClockDays
            $timeClockDays = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CheckTimeClockForCurrentDay($servicerID,$timeZone,$dateTime);

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
                    $dateTime = new \DateTime($dateTime);

                    if (!$mileage) {
                        $mileage = 'NULL';
                    }

                    // Set TimeClock Tasks to Current UTC DateTime
                    $this->getEntityManager()->getConnection()->prepare("UPDATE TimeClockTasks SET ClockOut = '".$dateTime->format('Y-m-d H:i:s')."' WHERE ClockOut IS NULL AND ServicerID=".$servicerID)->execute();

                    // Set TimeClock Days to Current UTC DateTime
                    $timeClock = $this->getEntityManager()->getConnection()->prepare("UPDATE TimeClockDays SET ClockOut = '".$dateTime->format('Y-m-d H:i:s')."', MileageOut=".$mileage." WHERE ClockOut IS NULL AND ServicerID=".$servicerID)->execute();
                    $this->entityManager->flush();
                }
            }

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS
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
    public function AcceptDeclineTask($servicerID, $content)
    {
        try {
            $taskID = $content[GeneralConstants::TASK_ID];
            $acceptDecline = $content['AcceptDecline'];
            $dateTime = $content['DateTime'];

            $rsThisTask = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->AcceptDeclineTask($servicerID,$taskID);

            if (empty($rsThisTask)) {
                throw new BadRequestHttpException(ErrorConstants::WRONG_LOGIN);
            }

            switch ($acceptDecline) {
                case 0:
                    $response = $this->DeclineTask($servicerID,$taskID,$dateTime,$rsThisTask);
                    break;
                case 1:
                    $response = $this->AcceptTask($servicerID,$taskID,$dateTime,$rsThisTask);
                    break;
                default: break;
            }


            return $response;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to accept/decline task due to: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @return array
     * @param $servicerID
     * @param $taskID
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function AcceptTask($servicerID, $taskID,$dateTime, $rsThisTask)
    {
        $notification = [];
        $tasksToServicers = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->findOneBy(array(
            'taskid' => $taskID,
            'servicerid' => $servicerID
        ));

        if (!$tasksToServicers) {
            throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKSTOSERVICERS);
        }

        if ($tasksToServicers->getAccepteddate() === null) {
            $tasksToServicers->setAccepteddate(new \DateTime($dateTime));
            $this->entityManager->persist($tasksToServicers);

            $taskAcceptDeclines = new Taskacceptdeclines();
            $taskAcceptDeclines->setTaskid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($taskID));
            $taskAcceptDeclines->setServicerid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($servicerID));
            $taskAcceptDeclines->setAcceptordecline(true);
            $taskAcceptDeclines->setCreatedate(new \DateTime($dateTime));
            $this->entityManager->persist($taskAcceptDeclines);

            $this->entityManager->flush();

            $result = array(
                GeneralConstants::MESSAGE_ID => 26,
                GeneralConstants::CUSTOMER_ID => $rsThisTask[0][GeneralConstants::CUSTOMER_ID],
                GeneralConstants::TASK_ID => $taskID,
                GeneralConstants::SENDTOMANAGERS => 1,
                GeneralConstants::SUBMITTEDBYSERVICERID => $servicerID
            );
            $taskNotification = $this->serviceContainer->get(GeneralConstants::NOTIFICATION_SERVICE)->CreateTaskAcceptDeclineNotification($result);
            $notification['TaskNotification'] = $taskNotification;

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS,
                'TasksToServicerID' => $tasksToServicers->getTasktoservicerid(),
                'TaskAcceptDeclineID' => $taskAcceptDeclines->getTaskacceptdeclineid(),
                'Notification' => $notification
            );
        }
        return [];
    }

    /**
     * @param $servicerID
     * @param $taskID
     * @param $dateTime
     * @param $customerID
     * @param $rsThisTask
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function DeclineTask($servicerID, $taskID, $dateTime,$rsThisTask)
    {
        $notification = [];
        $backupServicer = 0;
        $rsBackup = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->DeclineBackup($servicerID);
        $thisAdditionalMessage = '';
        $thisAdditionalTextMessage = '';
        $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($rsThisTask[0][GeneralConstants::REGION], $dateTime);
        $currentTime = new \DateTime($dateTime);

        // Manage Backup Servicer.
        // If Backup Servicer ID is present then assign the servicer ID to that task
        if (!empty($rsBackup)) {
            $backupServicer = $rsBackup[0]['DeclineBackupServicerID'];
        }

        $backupServicerObj = $backupServicer !==0 ? $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($backupServicer) : null;
        $backupWorkDays = null;
        if ($backupServicerObj) {
            $backupWorkDays = $backupServicerObj->getWorkdays();
        }

        $thisDayOfWeek =  GeneralConstants::DAYOFWEEK[$rsThisTask[0]['TaskDate']->format('N')];

        // Find backup servicer if the current backup servicer is not working on that day
        if ($backupWorkDays && strpos((string)$thisDayOfWeek, $backupWorkDays) === false) {
            $funcName = 'getBackupservicerid'.$thisDayOfWeek;
            $backupServicer2 = $backupServicerObj->$funcName();
            if ((int)$backupServicer2) {
                $backupServicerObj = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($backupServicer2);
            }
        }

        $tasksToServicers = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->findOneBy(array(
            'taskid' => $taskID,
            'servicerid' => $servicerID
        ));

        if (!$tasksToServicers) {
            throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKSTOSERVICERS);
        }

        if ($tasksToServicers->getDeclineddate() === null) {
            $tasksToServicers->setDeclineddate($currentTime);
            $tasksToServicers->setServicerid($backupServicerObj);
            $this->entityManager->persist($tasksToServicers);

            $taskAcceptDeclines = new Taskacceptdeclines();
            $taskAcceptDeclines->setTaskid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($taskID));
            $taskAcceptDeclines->setServicerid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($servicerID));
            $taskAcceptDeclines->setAcceptordecline(false);
            $taskAcceptDeclines->setCreatedate($currentTime);
            $this->entityManager->persist($taskAcceptDeclines);

            $this->entityManager->flush();

            // Manage Notifications for the task ID in last one minute
            $result = array(
                GeneralConstants::MESSAGE_ID => 27,
                GeneralConstants::CUSTOMER_ID => $rsThisTask[0][GeneralConstants::CUSTOMER_ID],
                GeneralConstants::TASK_ID => $taskID,
                GeneralConstants::SENDTOMANAGERS => 1,
                GeneralConstants::SUBMITTEDBYSERVICERID => $servicerID
            );
            $taskNotification = $this->serviceContainer->get(GeneralConstants::NOTIFICATION_SERVICE)->CreateTaskAcceptDeclineNotification($result, $currentTime);
            $notification['TaskNotification'] = $taskNotification;

            // Deal with backup servicer notification
            if (!empty($rsBackup)) {
                $viewTaskDate = $localTime->modify('+' . $rsBackup[0]['ViewTasksWithinDays'] . ' day');
                if ($viewTaskDate <= $rsThisTask[0][GeneralConstants::TASKDATE]) {
                    if ($rsBackup[0][GeneralConstants::REQUESTACCEPTTASK]) {
                        $thisAdditionalTextMessage = 'Please Accept or Decline';
                        $thisAdditionalMessage = 'Please Accept or Decline';
                    }

                    $result = array(
                        GeneralConstants::MESSAGE_ID => 34,
                        GeneralConstants::CUSTOMER_ID => $rsThisTask[0][GeneralConstants::CUSTOMER_ID],
                        GeneralConstants::TASK_ID => $taskID,
                        GeneralConstants::SENDTOMANAGERS => 1,
                        'BackupServicerID' => $backupServicerObj ? $backupServicerObj->getServicerid() : null,
                        GeneralConstants::SUBMITTEDBYSERVICERID => $servicerID,
                        'AdditionalTextMessage' => $thisAdditionalTextMessage,
                        'AdditionalMessage' => $thisAdditionalMessage
                    );
                    $taskNotification = $this->serviceContainer->get(GeneralConstants::NOTIFICATION_SERVICE)->CreateTaskAcceptDeclineNotification($result, $currentTime, true, $thisAdditionalTextMessage, $thisAdditionalMessage);
                    $notification['BackupServicerNotification'] = $taskNotification;
                }
            }

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS,
                'TasksToServicerID' => $tasksToServicers->getTasktoservicerid(),
                'BackupServicerID' => $backupServicerObj ? $backupServicerObj->getServicerid() : null,
                'TaskAcceptDeclineID' => $taskAcceptDeclines->getTaskacceptdeclineid(),
                'Notification' => $notification
            );
        }
        return [];
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function ChangeTaskDate($servicerID, $content)
    {
        try {
            $taskID = $content[GeneralConstants::TASK_ID];
            $taskDate = new \DateTime($content[GeneralConstants::TASKDATE]);
            $rsThisTask = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->ChangeTaskDate($taskID,$servicerID);
            $thisTime = 8;
            $response = [];
            $schedulingNote = "";

            if(empty($rsThisTask)) {
                // Throw Error if the Task Does not belong to the Servicer.
                throw new BadRequestHttpException(ErrorConstants::WRONG_LOGIN);
            }

            $taskDateTime = (new \DateTime($content[GeneralConstants::TASKDATE]))->setTime($thisTime,0);

            // make sure entered date falls within the window
            if ($taskDate >= $rsThisTask[0][GeneralConstants::TASKSTARTDATE] && $taskDate <= $rsThisTask[0][GeneralConstants::TASKCOMPLETEBYDATE]) {
                if ($taskDate === $rsThisTask[0][GeneralConstants::TASKSTARTDATE] && (int)$rsThisTask[0][GeneralConstants::TASKSTARTTIME] !== 99) {
                    $thisTime = max((int)$rsThisTask[0][GeneralConstants::TASKSTARTTIME],$thisTime);
                    $taskDateTime = $taskDateTime->setTime($thisTime,0);
                }

                if ($taskDate === $rsThisTask[0][GeneralConstants::TASKCOMPLETEBYDATE] && (int)$rsThisTask[0][GeneralConstants::TASKSTARTTIME] !== 99) {
                    if ($thisTime > ($rsThisTask[0][GeneralConstants::TASKCOMPLETEBYTIME] - $rsThisTask[0]['MaxTimeToComplete'])) {
                        $thisTime = $rsThisTask[0][GeneralConstants::TASKCOMPLETEBYTIME] - $rsThisTask[0]['MaxTimeToComplete'];
                        $taskDateTime = $taskDateTime->setTime($thisTime,0);
                    }
                }

                $thisDayOfWeek =  GeneralConstants::DAYOFWEEK[$taskDateTime->format('N')];
                if ((int)$rsThisTask[0]['Schedulenote' . $thisDayOfWeek . 'Show']) {
                    $schedulingNote = trim($rsThisTask[0]['ScheduleNote' . $thisDayOfWeek]);
                }

                // Update Datetime
                $task = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($taskID);
                $task->setTaskdatetime($taskDateTime);
                $task->setTaskdate($taskDate);
                $task->setTasktime($thisTime);

                $this->entityManager->persist($task);

                // Track the change
                $taskChanges = new Taskchanges();
                $taskChanges->setTaskid($task);
                $taskChanges->setTodate($taskDateTime->setTime(0,0));
                $taskChanges->setByservicer($servicerID);
                $taskChanges->setBycustomer(0);
                $taskChanges->setDescription('Emp Dashboard');
                $this->entityManager->persist($taskChanges);

                $this->entityManager->flush();

                $response[GeneralConstants::TASK_ID] = $task->getTaskid();
                $response['TaskChangesID'] = $taskChanges->getTaskchangeid();
                $response['SchedulingNote'] = $schedulingNote;
            }

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS,
                'Details' => $response
            );

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to change task date due to: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}