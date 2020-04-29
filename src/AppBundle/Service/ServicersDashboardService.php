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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class ServicersDashboardService extends BaseService
{
    public function GetTasks($servicerID)
    {
        try {
            $response = [];
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->FetchTasksForDashboard($servicerID, $servicers);

            for ($i=0; $i<count($tasks); $i++) {
                // Initialize local variables
                $taskEstimates = null;
                $guestDetails = null;
                $acceptDecline = 0;
                $expand = 0;
                $startTask = 0;
                $pauseTask = 0;
                $window = null;
                $tabs = null;

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
                $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID);
                if(!$acceptDecline
                    && (int)$servicers[0]['TimeTracking'] === 1
                    && (empty($timeClockTasks) || ($timeClockTasks[0]['TaskID'] !== $tasks[$i]['TaskID']))
                ) {
                    $startTask = 1;
                }
                $response[$i]['StartTask'] = $startTask;

                // Show or hide Pause Task
                if(!$acceptDecline
                    && (int)$servicers[0]['TimeTracking'] === 1
                    && (!empty($timeClockTasks) ? ($timeClockTasks[0]['TaskID'] !== $tasks[$i]['TaskID']) : null)
                ) {
                    $pauseTask = 1;
                }
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

                $response[$i]['Description'] = array(
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
                    'PMSHousekeepingNote' => $tasks[$i]['PMSHousekeepingNote']
                );

                // Task Details
                $response[$i]['Details'] = array(
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
                    'PropertyName' => $tasks[$i]['PropertyName']
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

                // Check if manage tab has to be rendered
                $manage = 0;
                $today = new \DateTime('now',new \DateTimeZone('UTC'));
                if( !(
                    (int)$servicers[0]['TimeTracking'] === 1 &&
                    (empty($timeClockTasks) || $timeClockTasks[0]['TaskID'] !== $tasks[$i]['TaskID'])) &&
                    ($tasks[$i]['TaskStartDate'] <= $today) &&
                    ((int)$servicers[0]['AllowStartEarly'] === 1 || $tasks[$i]['StartDate'] <= $today)
                ) {
                    $manage = 1;
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

    public function StartTask()
    {
        return array(
            "ReasonCode" => 0,
            "ReasonText" => "Success"
        );
    }
}