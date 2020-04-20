<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 10/2/20
 * Time: 12:10 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
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
                $isLead = 0;
                $tabs = null;

                // Check is servicer Is Lead
                if($tasks[$i]['IsLead']) {
                    $isLead = 1;
                }
                $response[$i]['IsLead'] = $isLead;

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

                // Task Details
                $response[$i]['Details'] = array(
                    'TaskID' => $tasks[$i]['TaskID'],
                    'TaskName' => $tasks[$i]['TaskName'],
                    'Region' => $tasks[$i]['Region'],
                    'RegionColor' => $tasks[$i]['RegionColor'],
                    'TaskDescription' => $tasks[$i]['TaskDescription'],
                    'Map' => array(
                        'Lat' => $tasks[$i]['Lon'],
                        'Lon' => $tasks[$i]['Lat']
                    ),
                    'ServiceName' => $tasks[$i]['ServiceName'],
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
                if ($servicers[0]['ShowIssueLog']) {
                    $log = 1;
                } else {
                    $log = 0;
                }

                // Check if image tab has to be rendered
                $image = $this->entityManager->getRepository('AppBundle:Images')->GetImageCountForDashboard($tasks[$i]['PropertyID']);
                if(!empty($image)) {
                    $image = 1;
                } else {
                    $image = 0;
                }

                // Check if manage tab has to be rendered
                $today = new \DateTime('now',new \DateTimeZone('UTC'));
                if( !(
                    (int)$servicers[0]['TimeTracking'] === 1 &&
                    (empty($timeClockTasks) || $timeClockTasks[0]['TaskID'] !== $tasks[$i]['TaskID'])) &&
                    ($tasks[$i]['TaskStartDate'] <= $today) &&
                    ((int)$servicers[0]['AllowStartEarly'] === 1 || $tasks[$i]['StartDate'] <= $today)
                ) {
                    $manage = 1;
                } else {
                    $manage = 0;
                }

                // Check if info tab has to be rendered
                if (trim($tasks[$i]['PropertyFile'] !== '')
                    || trim($tasks[$i]['TaskDescription'])
                    || trim($tasks[$i]['DoorCode'])
                    || trim($tasks[$i]['Address'])
                ) {
                    $info = 1;
                } else {
                    $info = 0;
                }

                // Check if Assignments tab has to be rendered or not
                if (
                    (int)$servicers[0]['AllowStartEarly'] === 1 ||
                    $servicers[0]['Email'] === $tasks[$i]['Email']
                ) {
                    $assignments = 1;
                } else {
                    $assignments = 0;
                }

                if ( $manage || $image || $log || $info) {
                    $tabs = array(
                        'Manage' => $manage,
                        'Info' => $info,
                        'Log' => $log,
                        'Imgs' => $image,
                        'Assgmnts' => $assignments
                    );
                }
                $response[$i]['Tabs'] = $tabs;
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