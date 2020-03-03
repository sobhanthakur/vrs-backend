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
                $acceptDecline = null;
                $expand = null;
                $startTask = null;

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
                if(!$acceptDecline && (int)$servicers[0]['TimeTracking'] === 1) {
                    $startTask = 1;
                }
                $response[$i]['StartTask'] = $startTask;

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

                // Task Details
                $response[$i]['Details'] = array(
                    'TaskID' => $tasks[$i]['TaskID'],
                    'TaskName' => $tasks[$i]['TaskName'],
                    'Region' => $tasks[$i]['Region'],
                    'RegionColor' => $tasks[$i]['RegionColor'],
                    'Map' => array(
                        'Lat' => $tasks[$i]['Lon'],
                        'Lon' => $tasks[$i]['Lat']
                    )
                );

                // Guest Details
                if ($servicers[0]['IncludeGuestNumbers'] && $servicers[0]['IncludeGuestEmailPhone'] && $servicers[0]['IncludeGuestName']) {
                    $guestDetails = array(
                        'Name' => $tasks[$i]['Name'],
                        'Email' => $tasks[$i]['Email'],
                        'Phone' => $tasks[$i]['Phone'],
                        'Number' => $tasks[$i]['Number']
                    );
                }
                $response[$i]['GuestDetails'] = $guestDetails;
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
}