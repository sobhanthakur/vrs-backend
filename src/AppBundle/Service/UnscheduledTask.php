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
            $servicers = 'Select ShowIssuesLog,TaskName,IncludeServicerNote,IncludeToOwnerNote,DefaultToOwnerNote FROM ('.Servicers::vServicers.') AS S WHERE  S.ServicerID = '.$servicerID.'
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
    public function CompleteUnscheduledTask($servicerID, $content)
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

            $propertyObj = $this->entityManager->getRepository('AppBundle:Properties')->find($propertyID);
            if (!$propertyObj) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            // MAKE SURE THIS TASK HAS NOT BEEN SUBMITTED IN THE LAST xx seconds to AVOID ACCIDENTAL DUPLICATES
            $lastTask = $this->entityManager->getRepository('AppBundle:Tasks')->TaskSubmittedInLast5Seconds($servicerID, $propertyID);
            if (!empty($lastTask)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::TRYAFTERSOMETIME);
            }

            // INSERTING A COMPLETED TASKS FOR THEMSELVES

            $today = new \DateTime($dateTime);

            $task = new Tasks();
            $task->setPropertybookingid(null);
            $task->setPropertyid($propertyObj);
            $task->setTaskname(trim($details['TaskName']));
            $task->setTasktype(6);
            $task->setTaskdate($today);
            $task->setTasktime((int)ltrim($today->format('H'), '0'));
            $task->setTaskdatetime($today);
            $task->setTaskcompletebydate($today);
            $task->setTaskcompletebytime(99);
            $task->setServiceid(null);
            $task->setServicerid($servicerID);
            $task->setServicernotes(trim($details['UnscheduledTaskNote']));
            $task->setToownernote(trim($details['NoteToOwner']));
            $task->setMarked(true);
            $task->setEdited(true);
            $task->setCompleteconfirmeddate($completeStatus ? $today : null);
            $task->setCloseddate($completeStatus ? $today : null);
            $task->setCompletedbyservicerid($completeStatus ? $servicerID : null);
            $task->setCompleted($completeStatus ? true : false);
            $task->setTaskstartdate($today);
            $task->setTaskstarttime(99);
            $task->setIncludedamage(true);
            $task->setIncludemaintenance(true);
            $task->setIncludelostandfound(true);
            $task->setIncludesupplyflag(true);
            $task->setIncludeurgentflag(true);
            $task->setIncludeservicernote(true);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $response['TaskID'] = $task->getTaskid();


            $tasksToServicers = new Taskstoservicers();
            $tasksToServicers->setTaskid($task);
            $tasksToServicers->setServicerid($servicerObj);
            $tasksToServicers->setIslead(true);
            $tasksToServicers->setPayrate($servicerObj->getPayrate());
            $tasksToServicers->setAccepteddate($today);

            $this->entityManager->persist($tasksToServicers);
            $this->entityManager->flush();

            $response['TasksToServicers'] = $tasksToServicers->getTasktoservicerid();

            // Manage Notification
            if ($completeStatus) {
                $thisOwnerID = 0;
                if ($propertyObj->getOwnerid() && (int)$details['SendToOwnerNote'] !== 1 && (int)$servicerObj->getNotifyowneroncompletion() > 0) {
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

                $taskNotification = $this->serviceContainer->get('vrscheduler.notification_service')->CreateManageCompleteNotification($result);
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