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
use AppBundle\Constants\GeneralConstants;

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

//            if (empty($properties)) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
//            }
            
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
            $servicers = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->PropertyTabUnscheduledTasks($servicerID);

            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            $servicers[0]['AllowAdminAccess'] = $servicers[0]['AllowAdminAccess'] ? 1 : 0;

            $propertyID = $content[GeneralConstants::PROPERTY_ID];

            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertyTabUnscheduledTasks($propertyID);
            if (empty($properties)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            $properties[0]['SlackLink'] = ($properties[0][GeneralConstants::SLACKCHANNELID] !== '' && trim($properties[0][GeneralConstants::SLACKTEAMID] !== '') && (int)$servicers[0]['UseSlack'] === 1) ? 1 : 0;

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
            $propertyID = $content[GeneralConstants::PROPERTY_ID];
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
            $propertyID = $content[GeneralConstants::PROPERTY_ID];
            $log = 0;
            $property = 0;
            $image = 0;
            $manage = 1;

            // Get Servicers Details
            $servicers = 'Select AllowImageUpload,IncludeHouseKeeping,IncludeMaintenance,IncludeDamage,IncludeLostAndFound,IncludeSupplyFlag,IncludeUrgentFlag,AllowShareImagesWithOwners,CustomerID,AllowAddStandardTask,ShowIssuesLog,TaskName,IncludeServicerNote,IncludeToOwnerNote,DefaultToOwnerNote FROM ('.Servicers::vServicers.') AS S WHERE  S.ServicerID = '.$servicerID.'
             AND S.CustomerActive = 1 and S.Active = 1';
            $servicers = $this->entityManager->getConnection()->prepare($servicers);
            $servicers->execute();
            $servicers = $servicers->fetchAll();

            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            // Add new Query
            $query = 'SELECT IssueAllowVideoUpload,AllowSendIssuesAsWorkOrders,AllowSendTasksAsWorkOrders,WorkOrderIntegrationCompanyStaffID,WorkOrderIntegrationCompanyID
                    ,IssueDamageAlt,IssueDamageAbbrAlt,IssueMaintenanceAlt,IssueMaintenanceAbbrAlt,IssueLostAndFoundAlt,IssueLostAndFoundAbbrAlt,IssueSupplyAlt,IssueSupplyAbbrAlt,IssueHousekeepingAlt,IssueHousekeepingAbbrAlt
                    FROM Customers
                    WHERE CustomerID = '.$servicers[0][GeneralConstants::CUSTOMER_ID];
            $servicers1 = $this->entityManager->getConnection()->prepare($query);
            $servicers1->execute();
            $servicers1 = $servicers1->fetchAll();

            // Create Issue Form Array
            $issueForm = [
                [
                    'IssueType' => "IncludeDamage",
                    'IssueFlag' => (int)$servicers[0]['IncludeDamage'],
                    'IssueValue' => 0,
                    'DefaultText' => 'Property Has Damage',
                    'AlternativeText' => $servicers1[0]['IssueDamageAlt'],
                    'AlternativeAbbreviationText' => $servicers1[0]['IssueDamageAbbrAlt']
                ],
                [
                    'IssueType' => "IncludeMaintenance",
                    'IssueFlag' => (int)$servicers[0]['IncludeMaintenance'],
                    'IssueValue' => 1,
                    'DefaultText' => 'Property Needs Maintenance',
                    'AlternativeText' => $servicers1[0]['IssueMaintenanceAlt'],
                    'AlternativeAbbreviationText' => $servicers1[0]['IssueMaintenanceAbbrAlt']
                ],
                [
                    'IssueType' => "IncludeHouseKeeping",
                    'IssueFlag' => (int)$servicers[0]['IncludeHouseKeeping'],
                    'IssueValue' => 4,
                    'DefaultText' => 'Housekeeping',
                    'AlternativeText' => $servicers1[0]['IssueHousekeepingAlt'],
                    'AlternativeAbbreviationText' => $servicers1[0]['IssueHousekeepingAbbrAlt']
                ],
                [
                    'IssueType' => "IncludeLostAndFound",
                    'IssueFlag' => (int)$servicers[0]['IncludeLostAndFound'],
                    'IssueValue' => 2,
                    'DefaultText' => 'Lost and Found Item',
                    'AlternativeText' => $servicers1[0]['IssueLostAndFoundAlt'],
                    'AlternativeAbbreviationText' => $servicers1[0]['IssueLostAndFoundAbbrAlt']
                ],
                [
                    'IssueType' => "IncludeSupplyFlag",
                    'IssueFlag' => (int)$servicers[0]['IncludeSupplyFlag'],
                    'IssueValue' => 3,
                    'DefaultText' => 'Set Supply Flag',
                    'AlternativeText' => $servicers1[0]['IssueSupplyAlt'],
                    'AlternativeAbbreviationText' => $servicers1[0]['IssueSupplyAbbrAlt']
                ],
                [
                    'IssueType' => "None or Other",
                    'IssueFlag' => 1,
                    'IssueValue' => -1,
                    'DefaultText' => 'None or Other',
                    'AlternativeText' => '',
                    'AlternativeAbbreviationText' => ''
                ]
            ];

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
                $standardServices = $this->entityManager->getConnection()->prepare('Select ServiceID,PropertyID,ServiceName,Name FROM ('.ServicesToProperties::vServicesToProperties.') AS stp WHERE stp.TaskType=9 AND stp.CustomerID='.$servicers[0][GeneralConstants::CUSTOMER_ID].' AND stp.PropertyID='.$propertyID.' AND stp.Active = 1 AND stp.ServiceActive = 1 And stp.IncludeOnIssueForm = 1');
                $standardServices->execute();
                $standardServices = $standardServices->fetchAll();
            }

            // Issue Form Details
            $response['IssueForm'] = array(
                'IncludeMaintenance' => (int)$servicers[0]['IncludeMaintenance'],
                'IncludeDamage' => (int)$servicers[0]['IncludeDamage'],
                'AllowImageUpload' => (int)$servicers[0]['AllowImageUpload'],
                'IncludeLostAndFound' => (int)$servicers[0]['IncludeLostAndFound'],
                'IncludeSupplyFlag' => (int)$servicers[0]['IncludeSupplyFlag'],
                'IncludeUrgentFlag' => (int)$servicers[0]['IncludeUrgentFlag'],
                'AllowShareImagesWithOwners' => (int)$servicers[0]['AllowShareImagesWithOwners'],
                'StandardServices' => $standardServices,
                'IssueAllowVideoUpload' => (int)$servicers1[0]['IssueAllowVideoUpload'],
                'IssueTypeForm' => $issueForm
            );

            // Create Tab Response
            $response['Tabs'] = array(
                'Manage' => $manage,
                'Log' => $log,
                'Image' => $image,
                'Info' => $property
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
            $propertyID = $content[GeneralConstants::PROPERTY_ID];
            $completeStatus = $content['CompleteStatus'];
            $details = $content['Details'];
            $dateTime = $content['DateTime'];
            $response = [];

            array_key_exists('UnscheduledTaskNote',$details) ? $details['UnscheduledTaskNote'] = trim($details['UnscheduledTaskNote']) : $details['UnscheduledTaskNote'] = '';

            $servicerObj = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($servicerID);

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
//            $lastTask = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->TaskSubmittedInLast5Seconds($servicerID, $propertyID);
//            if (!empty($lastTask)) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::TRYAFTERSOMETIME);
//            }

            // INSERTING A COMPLETED TASKS FOR THEMSELVES
            $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($region,$dateTime);
            $today = new \DateTime($dateTime);


            $task = new Tasks();
            $task->setPropertybookingid(null);
            $task->setPropertyid($propertyObj);
            // $task->setTaskname(array_key_exists('TaskName',$details) && trim($details['TaskName']) !== '' ?  $details['TaskName'] : $servicerObj->getTaskname());
            $task->setTaskname(array_key_exists('TaskName',$details) ?  $details['TaskName'] : '');
            $task->setTasktype(6);
            $task->setTaskdate($localTime);
            $task->setTasktime((int)ltrim($localTime->format('H'), '0'));
            $task->setTaskdatetime($localTime);
            $task->setTaskcompletebydate($localTime);
            $task->setTaskcompletebytime(99);
            // remove this when schema changes
//             $task->setServiceid(null);
            $task->setServiceid(0);
            $task->setServicerid($servicerID);
            $task->setServicernotes($details['UnscheduledTaskNote']);
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

            $response[GeneralConstants::TASK_ID] = $task->getTaskid();


            $tasksToServicers = new Taskstoservicers();
            $tasksToServicers->setTaskid($task);
            $tasksToServicers->setServicerid($servicerObj);
            $tasksToServicers->setIslead(true);
            $tasksToServicers->setPayrate($servicerObj->getPayrate() ? $servicerObj->getPayrate() : 0);
            $tasksToServicers->setAccepteddate($today);
            $tasksToServicers->setCreatedate($today);

            $this->entityManager->persist($tasksToServicers);
            $this->entityManager->flush();

            $response['TasksToServicers'] = $tasksToServicers->getTasktoservicerid();

            // Start Task If Mark Complete is false
            if (!$completeStatus) {
//                $content[GeneralConstants::TASK_ID] = $task->getTaskid();
//                $content['StartPause'] = 1;
//                $this->serviceContainer->get('vrscheduler.starttask_service')->StartTask($servicerID, $content, $mobileHeaders);
            } else {
                // Create an Issue if task note is present
                if (trim($details['UnscheduledTaskNote']) !== '') {
                    $issues = new \AppBundle\Entity\Issues();
                    $issues->setIssuetype(-1);
                    $issues->setUrgent(false);
                    $issues->setIssue(substr($details['UnscheduledTaskNote'], 0, 150));
                    $issues->setPropertyid($propertyObj);

                    if (strlen($details['UnscheduledTaskNote']) > 150) {
                        $issues->setNotes($details['UnscheduledTaskNote']);
                    } else {
                        $issues->setNotes('');
                    }

                    $issues->setFromtaskid($task);
                    $issues->setSubmittedbyservicerid($servicerObj);
                    $this->entityManager->persist($issues);
                    $this->entityManager->flush();
                    $response['IssueID'] = $issues->getIssueid();
                }

                // Manage Locations
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
                    GeneralConstants::MESSAGE_ID => 5,
                    GeneralConstants::CUSTOMER_ID => $servicerObj->getCustomerid()->getCustomerid(),
                    GeneralConstants::TASK_ID => $task->getTaskid(),
                    'ToCustomerID' => $servicerObj->getCustomerid()->getCustomerid(),
                    GeneralConstants::OWNERID => $thisOwnerID,
                    'SendToMaintenanceStaff' => 1,
                    GeneralConstants::SENDTOMANAGERS => 1,
                    GeneralConstants::SUBMITTEDBYSERVICERID => $servicerID
                );

                $taskNotification = $this->serviceContainer->get(GeneralConstants::NOTIFICATION_SERVICE)->CreateManageCompleteNotification($result,$today);
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