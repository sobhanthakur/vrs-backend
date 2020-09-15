<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/6/20
 * Time: 2:03 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\DatabaseViews\Issues;
use AppBundle\DatabaseViews\Servicers;
use AppBundle\DatabaseViews\ServicesToProperties;
use AppBundle\Entity\Gpstracking;
use AppBundle\Entity\Tasks;
use AppBundle\Entity\Taskstoservicers;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UnscheduledTask
 * @package AppBundle\Service
 */
class UnscheduledTask extends BaseService
{
    /**
     * @param $servicerID
     * @return array
     */
    public function GetProperties($servicerID)
    {
        try {
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->GetPropertiesForUnscheduledTask($servicerID);

            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }
            
            return array('Properties' => $properties);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch properties ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function PropertyTab($servicerID, $content)
    {
        try {
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->PropertyTabUnscheduledTasks($servicerID);

            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            $servicers[0]['AllowAdminAccess'] = $servicers[0]['AllowAdminAccess'] ? 1 : 0;

            $propertyID = $content['PropertyID'];

            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyTabUnscheduledTasks($propertyID);
            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            return array(
                'PropertyDetails' => $properties[0],
                'ServicerDetails' => $servicers[0]
                );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch properties ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function ImageTab($servicerID, $content)
    {
        try {
            $propertyID = $content['PropertyID'];
            $images = $this->entityManager->getRepository('AppBundle:Images')->GetImagesForImageTab($propertyID);
            return array(
                'Images' => $images
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch properties ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function UnscheduledTaskDetails($servicerID, $content)
    {
        try {
            $response = [];
            $propertyID = $content['PropertyID'];
            $log = 0;
            $property = 0;
            $image = 0;
            $manage = 1;

            // Get Servicers Details
            $servicers = 'Select IncludeMaintenance,IncludeDamage,IncludeLostAndFound,IncludeSupplyFlag,IncludeUrgentFlag,AllowShareImagesWithOwners,CustomerID,AllowAddStandardTask,ShowIssuesLog,TaskName,IncludeServicerNote,IncludeToOwnerNote,DefaultToOwnerNote FROM ('.Servicers::vServicers.') AS S WHERE  S.ServicerID = '.$servicerID.'
             AND S.CustomerActive = 1 and S.Active = 1';
            $servicers = $this->entityManager->getConnection()->prepare($servicers);
            $servicers->execute();
            $servicers = $servicers->fetchAll();

            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            // Get PropertyDetails
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyDetailsForUnscheduledTasks($propertyID);
            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            $response['Details'] = array_merge($servicers[0],$properties[0]);

            // Get Tab Details

            // Log Tab
            $staffTasks = $this->entityManager->getConnection()->prepare('SELECT TOP 1 CreateDate,Issue,FromTaskID,SubmittedByServicerID,CustomerName,SubmittedByName,TimeZoneRegion,Urgent,IssueType,PropertyID,Notes FROM ('.Issues::vIssues.') AS SubQuery WHERE SubQuery.ClosedDate IS NULL AND SubQuery.PropertyID='.$propertyID.' ORDER BY SubQuery.CreateDate DESC');
            $staffTasks->execute();
            $staffTasks = $staffTasks->fetchAll();

            if (!empty($staffTasks)) {
                $log = 1;
            }

            // Property Tab
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyTabUnscheduledTasks($propertyID);
            if (!empty($properties)) {
                $property = 1;
            }

            // Images Tab
            $images = $this->entityManager->getRepository('AppBundle:Images')->GetImagesForImageTab($propertyID,null,1);
            if (!empty($images)) {
                $image = 1;
            }

            // Standard Tasks
            $standardServices = null;
            if ((int)$servicers[0]['AllowAddStandardTask'] === 1) {
                $standardServices = $this->entityManager->getConnection()->prepare('Select ServiceID,PropertyID,ServiceName,Name FROM ('.ServicesToProperties::vServicesToProperties.') AS stp WHERE stp.TaskType=9 AND stp.CustomerID='.$servicers[0]['CustomerID'].' AND stp.PropertyID='.$propertyID.' AND stp.Active = 1 AND stp.ServiceActive = 1 And stp.IncludeOnIssueForm = 1');
                $standardServices->execute();
                $standardServices = $standardServices->fetchAll();
            }

            // Issue Form Details
            $response['IssueForm'] = array(
                'IncludeMaintenance' => (int)$servicers[0]['IncludeMaintenance'],
                'IncludeDamage' => (int)$servicers[0]['IncludeDamage'],
                'IncludeLostAndFound' => (int)$servicers[0]['IncludeLostAndFound'],
                'IncludeSupplyFlag' => (int)$servicers[0]['IncludeSupplyFlag'],
                'IncludeUrgentFlag' => (int)$servicers[0]['IncludeUrgentFlag'],
                'AllowShareImagesWithOwners' => (int)$servicers[0]['AllowShareImagesWithOwners'],
                'StandardServices' => $standardServices
            );

            // Create Tab Response
            $response['Tabs'] = array(
                'Manage' => $manage,
                'Log' => $log,
                'Image' => $image,
                'Property' => $property
            );

            return $response;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to fetch Unscheduled task details ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function CompleteUnscheduledTask($servicerID, $content,$mobileHeaders)
    {
        try {
            $propertyID = $content['PropertyID'];
            $completeStatus = $content['CompleteStatus'];
            $details = $content['Details'];
            $dateTime = $content['DateTime'];
            $response = [];

            $servicerObj = $this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID);

            if (!$servicerObj) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            $region = $servicerObj->getTimezoneid();
            if ($region) {
                $region = $region->getRegion();
            }

            $propertyObj = $this->entityManager->getRepository('AppBundle:Properties')->find($propertyID);
            if (!$propertyObj) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            // MAKE SURE THIS TASK HAS NOT BEEN SUBMITTED IN THE LAST xx seconds to AVOID ACCIDENTAL DUPLICATES
//            $lastTask = $this->entityManager->getRepository('AppBundle:Tasks')->TaskSubmittedInLast5Seconds($servicerID, $propertyID);
//            if (!empty($lastTask)) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::TRYAFTERSOMETIME);
//            }

            // INSERTING A COMPLETED TASKS FOR THEMSELVES
            $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($region,$dateTime);
            $today = new \DateTime($dateTime);


            $task = new Tasks();
            $task->setPropertybookingid(null);
            $task->setPropertyid($propertyObj);
            $task->setTaskname(array_key_exists('TaskName',$details) && trim($details['TaskName']) !== '' ?  $details['TaskName'] : $servicerObj->getTaskname());
            $task->setTasktype(6);
            $task->setTaskdate($localTime);
            $task->setTasktime((int)ltrim($localTime->format('H'), '0'));
            $task->setTaskdatetime($localTime);
            $task->setTaskcompletebydate($localTime);
            $task->setTaskcompletebytime(99);
            $task->setServiceid(null);
            $task->setServicerid($servicerID);
            $task->setServicernotes(array_key_exists('UnscheduledTaskNote',$details) ? trim($details['UnscheduledTaskNote']) : null);
            $task->setToownernote(array_key_exists('NoteToOwner',$details) ? trim($details['NoteToOwner']) : null);
            $task->setMarked(true);
            $task->setEdited(true);
            $task->setCompleteconfirmeddate($completeStatus ? $today : null);
            $task->setCloseddate($completeStatus ? $today : null);
            $task->setCompletedbyservicerid($completeStatus ? $servicerID : null);
            $task->setCompleted($completeStatus ? true : false);
            $task->setTaskstartdate($localTime);
            $task->setTaskstarttime(99);
            $task->setIncludedamage(true);
            $task->setIncludemaintenance(true);
            $task->setIncludelostandfound(true);
            $task->setIncludesupplyflag(true);
            $task->setIncludeurgentflag(true);
            $task->setIncludeservicernote(true);
            $task->setCreatedate($today);
            $task->setSchedulechangedate($today);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $response['TaskID'] = $task->getTaskid();


            $tasksToServicers = new Taskstoservicers();
            $tasksToServicers->setTaskid($task);
            $tasksToServicers->setServicerid($servicerObj);
            $tasksToServicers->setIslead(true);
            $tasksToServicers->setPayrate($servicerObj->getPayrate());
            $tasksToServicers->setAccepteddate($today);
            $tasksToServicers->setCreatedate($today);

            $this->entityManager->persist($tasksToServicers);
            $this->entityManager->flush();

            $response['TasksToServicers'] = $tasksToServicers->getTasktoservicerid();

            // Start Task If Mark Complete is false
            if (!$completeStatus) {
                $content['TaskID'] = $task->getTaskid();
                $content['StartPause'] = 1;
                $this->serviceContainer->get('vrscheduler.starttask_service')->StartTask($servicerID, $content, $mobileHeaders);
            } else {

                $isMobile = $mobileHeaders['IsMobile'];
                (int)$servicerObj->getTimetrackinggps() ? $timeTrackingGps = true : $timeTrackingGps = false;
                array_key_exists('lat', $content) ? $lat = $content['lat'] : $lat = null;
                array_key_exists('long', $content) ? $long = $content['long'] : $long = null;
                array_key_exists('accuracy', $content) ? $accuracy = $content['accuracy'] : $accuracy = null;

                if ($timeTrackingGps && $lat && $long && $accuracy) {
                    $gpsTracking = new Gpstracking();
                    $gpsTracking->setServicerid($servicerObj);
                    $gpsTracking->setAccuracy($accuracy);
                    $gpsTracking->setIsmobile($isMobile);
                    $gpsTracking->setLatitude($lat);
                    $gpsTracking->setLongitude($long);
                    $gpsTracking->setUseragent($mobileHeaders['UserAgent']);
                    $gpsTracking->setCreatedate($today);
                    $this->entityManager->persist($gpsTracking);
                    $this->entityManager->flush();

                }
                // Update the time Tracking
//                $query = "UPDATE TimeClockTasks SET ClockOut = '" . $today->format('Y-m-d H:i:s') . "'";
//
//                if ($timeTrackingGps && $lat && $long && $accuracy) {
//                    // Update lat and lon
//                    $query .= ", OutLat=" . $lat . ", OutLon=" . $long . ", OutAccuracy=" . $accuracy . ", OutIsMobile=" . $isMobile . ", UpdateDate='" . $dateTime . "'";
//                }
//
//                $query .= " WHERE ClockOut IS NULL AND TaskID=" . $task->getTaskid();
//
//                $timeClockTasks = $this->getEntityManager()->getConnection()->prepare($query)->execute();
            }

            // Manage Notification
            array_key_exists('SendToOwnerNote',$details) ? $sendToOwnerNote = (int)$details['SendToOwnerNote'] : $sendToOwnerNote = 1;
            if ($completeStatus) {
                $thisOwnerID = 0;
                if ($propertyObj->getOwnerid() && $sendToOwnerNote !== 1 && (int)$servicerObj->getNotifyowneroncompletion() > 0) {
                    $thisOwnerID = $propertyObj->getOwnerid()->getOwnerid();
                }
                $result = array(
                    'MessageID' => 5,
                    'CustomerID' => $servicerObj->getCustomerid()->getCustomerid(),
                    'TaskID' => $task->getTaskid(),
                    'ToCustomerID' => $servicerObj->getCustomerid()->getCustomerid(),
                    'OwnerID' => $thisOwnerID,
                    'SendToMaintenanceStaff' => 1,
                    'SendToManagers' => 1,
                    'SubmittedByServicerID' => $servicerID,
                    'TypeID' => 0
                );

                $taskNotification = $this->serviceContainer->get('vrscheduler.notification_service')->CreateManageCompleteNotification($result,$today);
                $response['TaskNotification'] = $taskNotification;
            }

            return $response;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to mark Unscheduled task as complete/incomplete ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}