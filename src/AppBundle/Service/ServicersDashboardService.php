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
use AppBundle\DatabaseViews\TimeClockDays;
use AppBundle\Entity\Taskacceptdeclines;
use AppBundle\Entity\Taskchanges;
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

            // Local Time
            $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($servicers[0]['Region']);
            $localHour = (int)ltrim($localTime->format('H'), '0');;
            $localTime->setTime(0,0,0);
            /*
             * Make Sure Local time is changed here
             */

            for ($i=0; $i<count($tasks); $i++) {
//            for ($i=18; $i<19; $i++) {

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
                if ( ((int)$servicers[0]['TimeTracking'] === 1 && $tasks[$i]['TaskStartDate'] <= $localTime) &&
                     ((int)$servicers[0]['AllowStartEarly'] === 1 || $tasks[$i]['AssignedDate'] <= $localTime) &&
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
                    'ToMinutes' => $tasks[$i]['TaskCompleteByTimeMinutes'],
                    'TaskTime' => $tasks[$i]['TaskTime'],
                    'TaskTimeMinutes' => $tasks[$i]['TaskTimeMinutes'],
                    'TaskDateTime' => $tasks[$i]['TaskDateTime']
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
                $quickChangeAbbreviation = null;
                if ((int)$tasks[$i]['BackToBackStart'] && (int)$tasks[$i]['TaskType'] === 1) {
                    $quickChangeAbbreviation = trim($servicers[0]['QuickChangeAbbreviation']);
                }

                if ((int)$tasks[$i]['BackToBackEnd'] && ((int)$tasks[$i]['TaskType'] === 0 ||
                        (int)$tasks[$i]['TaskType'] === 4 ||
                        (int)$tasks[$i]['TaskType'] === 8)
                ) {
                    $quickChangeAbbreviation = trim($servicers[0]['QuickChangeAbbreviation']);
                }
                $servicers[0]['ShowPiecePayAmountsOnEmployeeDashboards'] ? $piecePay = $tasks[$i]['PiecePay'] : $piecePay = null;

                // Check Scheduling Notes
                $schedulingNote = null;
                $thisDayOfWeek =  GeneralConstants::DAYOFWEEK[$tasks[$i]['AssignedDate']->format('N')];
                if ((int)$servicers[0]['Schedulenote' . $thisDayOfWeek . 'Show']) {
                    $schedulingNote = trim($servicers[0]['ScheduleNote' . $thisDayOfWeek]);
                }

                // Set Status
                if ($tasks[$i]['TaskCompleteByDate'] < $localTime || ($tasks[$i]['TaskCompleteByDate'] === $localTime && $tasks['TaskCompleteByTime'] <=  $localHour)) {
                    $status = 0;
                } elseif ($tasks[$i]['AssignedDate'] <= $localTime) {
                    $status = 1;
                } else {
                    $status = 2;
                }

                $response[$i]['Details'] = array(
                    'Status' => $status,
                    'AllowChangeTaskDate' => (int)$servicers[0]['AllowChangeTaskDate'],
                    'ParentTaskDate' => $tasks[$i]['ParentTaskDate'],
                    'ParentTaskID' => $tasks[$i]['ParenTaskID'],
                    'ParentServiceAbbreviation' => $tasks[$i]['ParentServiceAbbreviation'],
                    'ParentCompleteConfirmedDate' => $tasks[$i]['ParentCompleteConfirmedDate'],
                    'SchedulingNote' => $schedulingNote,
                    'ShowStartTimeOnDashboard' => (int)$servicers[0]['ShowStartTimeOnDashboard'] === 1 ? 1 : 0,
                    'PiecePay' => $piecePay,
                    'QuickChangeAbbreviation' => $quickChangeAbbreviation,
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
                if ((int)$servicers[0]['IncludeGuestNumbers'] && ((int)$tasks[$i]['TaskType'] !== 0 ||
                        (int)$tasks[$i]['TaskType'] !== 2 ||
                        (int)$tasks[$i]['TaskType'] !== 3 ||
                        (int)$tasks[$i]['TaskType'] !== 4 ||
                        (int)$tasks[$i]['TaskType'] !== 8
                    )
                ) {
                    $guestDetails['Previous']['NumberOfGuests'] = $tasks[$i]['PrevNumberOfGuests'];
                    $guestDetails['Previous']['NumberOfChildren'] = $tasks[$i]['PrevNumberOfChildren'];
                    $guestDetails['Previous']['NumberOfPets'] = $tasks[$i]['PrevNumberOfPets'];

                    $guestDetails['Next']['NumberOfGuests'] = $tasks[$i]['NextNumberOfGuests'];
                    $guestDetails['Next']['NumberOfChildren'] = $tasks[$i]['NextNumberOfChildren'];
                    $guestDetails['Next']['NumberOfPets'] = $tasks[$i]['NextNumberOfPets'];
                }

                if((int)$servicers[0]['IncludeGuestEmailPhone'] && (int)$tasks[$i]['TaskType'] !== 3) {
                    $guestDetails['Previous']['Email'] = $tasks[$i]['PrevEmail'];
                    $guestDetails['Previous']['Phone'] = $tasks[$i]['PrevPhone'];

                    $guestDetails['Next']['Email'] = $tasks[$i]['NextEmail'];
                    $guestDetails['Next']['Phone'] = $tasks[$i]['NextPhone'];
                }

                if((int)$servicers[0]['IncludeGuestName'] && (int)$tasks[$i]['TaskType'] !== 3) {
                    $guestDetails['Previous']['Name'] = $tasks[$i]['PrevName'];

                    $guestDetails['Next']['Name'] = $tasks[$i]['NextName'];
                }

                $response[$i]['GuestDetails'] = $guestDetails;

                // Check if log tab has to be rendered
                $log = 0;
                $allIssues = 'SELECT TOP 1 FromTaskID FROM  ('.Issues::vIssues.') AS vIssues  WHERE vIssues.PropertyID='.$tasks[$i]['PropertyID'].' AND vIssues.PropertyID <> 0 AND vIssues.FromTaskID='.$tasks[$i]['TaskID'];
                $issues = $this->entityManager->getConnection()->prepare($allIssues);
                $issues->execute();
                $issues = $issues->fetchAll();

                if (!empty($issues) || (int)$servicers[0]['ShowIssueLog']) {
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
                        'Assgmnts' => $assignments,
                        'Log' => $log,
                        'Imgs' => $image,
                        'Info' => $info,
                        'Bkngs' => $bookings
                    );
                }
                $response[$i]['Tabs'] = $tabs;

                // Scheduling Notes
                $schedulingCalenderNotes = $this->entityManager->getRepository('AppBundle:Schedulingcalendarnotes')->SchedulingNotesForDashboard($servicerID,$tasks[$i]['AssignedDate']);
                $response[$i]['Notes'] = !empty($schedulingCalenderNotes) ? $schedulingCalenderNotes[0] : null;

                // Team for Each Task
                $team = $this->entityManager->getRepository('AppBundle:Tasks')->GetTeamByTask($tasks[$i]['TaskID'],$servicers);
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
            $dateTime = $content['DateTime'];
            $clockInOut = $content['ClockInOut'];
            $mileage = $content['Mileage'] ? $content['Mileage'] : null;

            // ServicerObject
            $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID);

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
                    $timeClockTasks = $this->getEntityManager()->getConnection()->prepare("UPDATE TimeClockTasks SET ClockOut = '".$dateTime->format('Y-m-d H:i:s')."' WHERE ClockOut IS NULL AND ServicerID=".$servicerID)->execute();

                    // Set TimeClock Tasks to Current UTC DateTime
                    $timeClock = $this->getEntityManager()->getConnection()->prepare("UPDATE TimeClockDays SET ClockOut = '".$dateTime->format('Y-m-d H:i:s')."', MileageOut=".$mileage." WHERE ClockOut IS NULL AND ServicerID=".$servicerID)->execute();
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
    public function AcceptDeclineTask($servicerID, $content)
    {
        try {
            $taskID = $content['TaskID'];
            $acceptDecline = $content['AcceptDecline'];
            $dateTime = $content['DateTime'];
            $rsThisTask = $this->entityManager->getRepository('AppBundle:Tasks')->AcceptDeclineTask($servicerID,$taskID);

            if (empty($rsThisTask)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            switch ($acceptDecline) {
                case 0:
                    $response = $this->DeclineTask($servicerID,$taskID,$dateTime,$rsThisTask);
                    break;
                case 1:
                    $response = $this->AcceptTask($servicerID,$taskID,$dateTime,$rsThisTask);
                    break;
            }


            return $response;

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

    /**
     * @return array
     * @param $servicerID
     * @param $taskID
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function AcceptTask($servicerID, $taskID,$dateTime, $rsThisTask)
    {
        try {
            $notification = [];
            $tasksToServicers = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->findOneBy(array(
                'taskid' => $taskID,
                'servicerid' => $servicerID
            ));

            if (!$tasksToServicers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKSTOSERVICERS);
            }

            $tasksToServicers->setAccepteddate(new \DateTime($dateTime));
            $this->entityManager->persist($tasksToServicers);

            $taskAcceptDeclines = new Taskacceptdeclines();
            $taskAcceptDeclines->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($taskID));
            $taskAcceptDeclines->setServicerid($this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID));
            $taskAcceptDeclines->setAcceptordecline(true);
            $taskAcceptDeclines->setCreatedate(new \DateTime($dateTime));
            $this->entityManager->persist($taskAcceptDeclines);

            $this->entityManager->flush();

//            $taskNotification = $this->entityManager->getRepository('AppBundle:Notifications')->TaskNotificationInLastOneMinute($taskID,$rsThisTask[0]['CustomerID'],26);
//            if ((int)$taskNotification[0]['Count'] === 0) {
                $result = array(
                    'MessageID' => 26,
                    'CustomerID' => $rsThisTask[0]['CustomerID'],
                    'TaskID' => $taskID,
                    'SendToManagers' => 1,
                    'SubmittedByServicerID' => $servicerID,
                    'TypeID' => 0
                );
                $taskNotification = $this->serviceContainer->get('vrscheduler.notification_service')->CreateTaskAcceptDeclineNotification($result);
                $notification['TaskNotification'] = $taskNotification;

//            }

            return array(
                'Status' => 'Success',
                'TasksToServicerID' => $tasksToServicers->getTasktoservicerid(),
                'TaskAcceptDeclineID' => $taskAcceptDeclines->getTaskacceptdeclineid(),
                'Notification' => $notification
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        }
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
        try {
            $notification = [];
            $backupServicer = 0;
            $rsBackup = $this->entityManager->getRepository('AppBundle:Servicers')->DeclineBackup($servicerID);
            $thisAdditionalMessage = '';
            $thisAdditionalTextMessage = '';
            $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($rsThisTask[0]['Region'],$dateTime);
            $currentTime = new \DateTime($dateTime);

            // Manage Backup Servicer.
            // If Backup Servicer ID is present then assign the servicer ID to that task
            if (!empty($rsBackup)) {
                $backupServicer = $rsBackup[0]['DeclineBackupServicerID'];
            }

            $tasksToServicers = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->findOneBy(array(
                'taskid' => $taskID,
                'servicerid' => $servicerID
            ));

            if (!$tasksToServicers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKSTOSERVICERS);
            }

            $tasksToServicers->setDeclineddate($currentTime);
            $tasksToServicers->setServicerid($backupServicer && $backupServicer !== 0 ? $this->entityManager->getRepository('AppBundle:Servicers')->find($backupServicer) : null);
            $this->entityManager->persist($tasksToServicers);

            $taskAcceptDeclines = new Taskacceptdeclines();
            $taskAcceptDeclines->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($taskID));
            $taskAcceptDeclines->setServicerid($this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID));
            $taskAcceptDeclines->setAcceptordecline(false);
            $taskAcceptDeclines->setCreatedate($currentTime);
            $this->entityManager->persist($taskAcceptDeclines);

            $this->entityManager->flush();

            // Manage Notifications for the task ID in last one minute
//            $taskNotification = $this->entityManager->getRepository('AppBundle:Notifications')->TaskNotificationInLastOneMinute($taskID,$rsThisTask[0]['CustomerID'],27);
//            if ((int)$taskNotification[0]['Count'] === 0) {
                $result = array(
                    'MessageID' => 27,
                    'CustomerID' => $rsThisTask[0]['CustomerID'],
                    'TaskID' => $taskID,
                    'SendToManagers' => 1,
                    'SubmittedByServicerID' => $servicerID,
                    'TypeID' => 0
                );
                $taskNotification = $this->serviceContainer->get('vrscheduler.notification_service')->CreateTaskAcceptDeclineNotification($result,$currentTime);
                $notification['TaskNotification'] = $taskNotification;

//            }

            // Deal with backup servicer notification
            if (!empty($rsBackup)) {
                $viewTaskDate = $localTime->modify('+'.$rsBackup[0]['ViewTasksWithinDays'].' day');
                if ($viewTaskDate <= $rsThisTask[0]['TaskDate']) {
                    if ($rsBackup[0]['RequestAcceptTasks']) {
                        $thisAdditionalTextMessage = 'Please Accept or Decline';
                        $thisAdditionalMessage = 'Please Accept or Decline';
                    }

                    $result = array(
                        'MessageID' => 34,
                        'CustomerID' => $rsThisTask[0]['CustomerID'],
                        'TaskID' => $taskID,
                        'SendToManagers' => 1,
                        'BackupServicerID' => $backupServicer,
                        'SubmittedByServicerID' => $servicerID,
                        'TypeID' => 0,
                        'AdditionalTextMessage' => $thisAdditionalTextMessage,
                        'AdditionalMessage' => $thisAdditionalMessage
                    );
                    $taskNotification = $this->serviceContainer->get('vrscheduler.notification_service')->CreateTaskAcceptDeclineNotification($result,$currentTime,true,$thisAdditionalTextMessage,$thisAdditionalMessage);
                    $notification['BackupServicerNotification'] = $taskNotification;
                }
            }

            return array(
                'Status' => 'Success',
                'TasksToServicerID' => $tasksToServicers->getTasktoservicerid(),
                'BackupServicerID' => $backupServicer,
                'TaskAcceptDeclineID' => $taskAcceptDeclines->getTaskacceptdeclineid(),
                'Notification' => $notification
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function ChangeTaskDate($servicerID, $content)
    {
        try {
            $taskID = $content['TaskID'];
            $taskDate = new \DateTime($content['TaskDate']);
            $rsThisTask = $this->entityManager->getRepository('AppBundle:Tasks')->ChangeTaskDate($taskID,$servicerID);
            $thisTime = 8;
            $response = [];

            if(empty($rsThisTask)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            $taskDateTime = (new \DateTime($content['TaskDate']))->setTime($thisTime,0);

            // make sure entered date falls within the window
            if ($taskDate >= $rsThisTask[0]['TaskStartDate'] && $taskDate <= $rsThisTask[0]['TaskCompleteByDate']) {
                if ($taskDate === $rsThisTask[0]['TaskStartDate'] && (int)$rsThisTask[0]['TaskStartTime'] !== 99) {
                    $thisTime = max((int)$rsThisTask[0]['TaskStartTime'],$thisTime);
                    $taskDateTime = $taskDateTime->setTime($thisTime,0);
                }

                if ($taskDate === $rsThisTask[0]['TaskCompleteByDate'] && (int)$rsThisTask[0]['TaskStartTime'] !== 99) {
                    if ($thisTime > ($rsThisTask[0]['TaskCompleteByTime'] - $rsThisTask[0]['MaxTimeToComplete'])) {
                        $thisTime = $rsThisTask[0]['TaskCompleteByTime'] - $rsThisTask[0]['MaxTimeToComplete'];
                        $taskDateTime = $taskDateTime->setTime($thisTime,0);
                    }
                }

                // Update Datetime
                $task = $this->entityManager->getRepository('AppBundle:Tasks')->find($taskID);
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

                $response['TaskID'] = $task->getTaskid();
                $response['TaskChangesID'] = $taskChanges->getTaskchangeid();
            }

            return array(
                'Status' => 'Success',
                'Details' => $response
            );

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