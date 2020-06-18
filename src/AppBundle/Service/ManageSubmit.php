<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 18/6/20
 * Time: 11:41 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Entity\Issues;
use AppBundle\Entity\Scheduledwebhooks;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ManageSubmit extends BaseService
{
    public function SubmitManageForm($servicerID, $content)
    {
        try {
            $taskID = $content['TaskID'];
            $taskObj = null;
            $propertyObj = null;
            $notification = [];
            $details = [];

            $rsThisTask = $this->entityManager->getRepository('AppBundle:Tasks')->SubmitManage($servicerID,$taskID);

            if (empty($rsThisTask)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            $rsServicers = $this->entityManager->getRepository('AppBundle:Servicers')->SubmitManageTab($servicerID);

            if (empty($rsServicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            // Task Object
            $taskObj = $this->entityManager->getRepository('AppBundle:Tasks')->find($rsThisTask[0]['TaskID']);

            // Save the manage form first
            $this->serviceContainer->get('vrscheduler.manage_save')->SaveManageDetails($servicerID, $content,true);

            $propertyObj = $this->entityManager->getRepository('AppBundle:Properties')->find($rsThisTask[0]['PropertyID']);

            if ($rsThisTask[0]['PropertyStatusID'] && (int)$rsThisTask[0]['PropertyStatusID'] !== 0) {
                if ($propertyObj) {
                    $propertyObj->setPropertystatusid($rsThisTask[0]['PropertyStatusID']);
                    $this->entityManager->persist($propertyObj);
                    $this->entityManager->flush();
                }
            }

            // Submit To CloudBeds
            $schedulingWebHooks = new Scheduledwebhooks();
            $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
            $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
            $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
            $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
            $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
            $schedulingWebHooks->setPartnerid(3);
            $schedulingWebHooks->setEventid(1);
            $schedulingWebHooks->setValue($rsThisTask[0]['CloudbedsHousekeepingStatus']);
            $this->entityManager->persist($schedulingWebHooks);
            $details['CloudBeds'] = $schedulingWebHooks->getScheduledwebhookid();

            // Submit to Mews
            $schedulingWebHooks = new Scheduledwebhooks();
            $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
            $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
            $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
            $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
            $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
            $schedulingWebHooks->setPartnerid(7);
            $schedulingWebHooks->setEventid(1);
            $schedulingWebHooks->setValue($rsThisTask[0]['MewsStatus']);
            $this->entityManager->persist($schedulingWebHooks);
            $details['Mews'] = $schedulingWebHooks->getScheduledwebhookid();

            // Submit to Operto
            $schedulingWebHooks = new Scheduledwebhooks();
            $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
            $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
            $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
            $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
            $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
            $schedulingWebHooks->setPartnerid(4);
            $schedulingWebHooks->setEventid(1);
            $schedulingWebHooks->setValue($rsThisTask[0]['OpertoStatus']);
            $this->entityManager->persist($schedulingWebHooks);
            $details['Operto'] = $schedulingWebHooks->getScheduledwebhookid();

            // Submit to Trackhs
            $schedulingWebHooks = new Scheduledwebhooks();
            $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
            $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
            $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
            $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
            $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
            $schedulingWebHooks->setPartnerid(6);
            $schedulingWebHooks->setEventid(1);
            $schedulingWebHooks->setValue($rsThisTask[0]['TrackHSCleanTypeID']);
            $this->entityManager->persist($schedulingWebHooks);
            $details['Trackhs'] = $schedulingWebHooks->getScheduledwebhookid();

            // Submit to ESCAPIA
            $schedulingWebHooks = new Scheduledwebhooks();
            $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
            $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
            $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
            $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
            $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
            $schedulingWebHooks->setPartnerid(2);
            $schedulingWebHooks->setEventid(1);
            $schedulingWebHooks->setValue($rsThisTask[0]['EscapiaHousekeepingStatus']);
            $this->entityManager->persist($schedulingWebHooks);
            $details['Escapia'] = $schedulingWebHooks->getScheduledwebhookid();

            // Submit to STREAMLINE
            $schedulingWebHooks = new Scheduledwebhooks();
            $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
            $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
            $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
            $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
            $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
            $schedulingWebHooks->setPartnerid(5);
            $schedulingWebHooks->setEventid(1);
            $schedulingWebHooks->setValue($rsThisTask[0]['StreamlineHousekeepingStatus']);
            $this->entityManager->persist($schedulingWebHooks);
            $details['Streamline'] = $schedulingWebHooks->getScheduledwebhookid();

            // Submit to BH247
            if ((int)$rsServicers[0]['UseBeHome247'] !== 0 &&
                (int)$rsThisTask[0]['BeHome247ID'] !== 0 &&
                ($rsThisTask[0]['BH247CleaningState'] !== '' ||
                    $rsThisTask[0]['BH247Custom_1State'] !== '' ||
                    $rsThisTask[0]['BH247Custom_2State'] !== ''
                )
            ) {
                if (trim($rsThisTask[0]['BH247CleaningState']) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
                    $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
                    $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(1);
                    $schedulingWebHooks->setValue($rsThisTask[0]['BH247CleaningState']);
                    $this->entityManager->persist($schedulingWebHooks);
                    $details['BH247CleaningState'] = $schedulingWebHooks->getScheduledwebhookid();
                }

                if (trim($rsThisTask[0]['BH247QAState']) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
                    $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
                    $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(2);
                    $schedulingWebHooks->setValue($rsThisTask[0]['BH247QAState']);
                    $this->entityManager->persist($schedulingWebHooks);
                    $details['BH247QAState'] = $schedulingWebHooks->getScheduledwebhookid();
                }

                if (trim($rsThisTask[0]['BH247MaintenanceState']) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
                    $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
                    $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(3);
                    $schedulingWebHooks->setValue($rsThisTask[0]['BH247MaintenanceState']);
                    $this->entityManager->persist($schedulingWebHooks);
                    $details['BH247MaintenanceState'] = $schedulingWebHooks->getScheduledwebhookid();
                }

                if (trim($rsThisTask[0]['BH247Custom_1State']) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
                    $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
                    $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(4);
                    $schedulingWebHooks->setValue($rsThisTask[0]['BH247Custom_1State']);
                    $this->entityManager->persist($schedulingWebHooks);
                    $details['BH247Custom_1State'] = $schedulingWebHooks->getScheduledwebhookid();
                }

                if (trim($rsThisTask[0]['BH247Custom_2State']) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0]['CustomerID']);
                    $schedulingWebHooks->setTaskid($rsThisTask[0]['TaskID']);
                    $schedulingWebHooks->setServicerid($rsServicers[0]['ServicerID']);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0]['PropertyID']);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0]['PropertyBookingID']);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(5);
                    $schedulingWebHooks->setValue($rsThisTask[0]['BH247Custom_2State']);
                    $this->entityManager->persist($schedulingWebHooks);
                    $details['BH247Custom_2State'] = $schedulingWebHooks->getScheduledwebhookid();
                }
            }

            // there is a SERVICER NOTE, Make it an Issue
            if (trim($content['TaskNote']) !== '') {
                $rsAllTaskIssues = $this->entityManager->getRepository('AppBundle:Issues')->GetIssuesFromLastOneMinuteManageSubmit($taskID,substr($content['TaskNote'], 0, 150));
                if (empty($rsAllTaskIssues)) {
                    $issues = new Issues();
                    $issues->setIssuetype(-1);
                    $issues->setUrgent(false);
                    $issues->setIssue(substr($content['TaskNote'], 0, 150));
                    $issues->setPropertyid($propertyObj);

                    if (strlen($content['TaskNote']) > 150) {
                        $issues->setNotes($content['TaskNote']);
                    }

                    $issues->setFromtaskid($taskObj);
                    $issues->setSubmittedbyservicerid($this->entityManager->getRepository('AppBundle:Servicers')->find($rsServicers[0]['ServicerID']));
                    $this->entityManager->persist($issues);
                }
            }

            // Update the time Tracking
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->findOneBy(array(
               'clockout' => null,
               'taskid' => $rsThisTask[0]['TaskID']
            ));

            $now = new \DateTime('now',new \DateTimeZone('UTC'));
            $timeClockTasks->setClockout($now);

            $this->entityManager->persist($timeClockTasks);
            $details['TimeClockTasks'] = $timeClockTasks->getTimeclocktaskid();

            // Get Owner ID
            $thisOwnerID = 0;
            if ((int)$rsThisTask[0]['OwnerID'] !== 0 &&
                ($content['NoteToOwner'] !== '' || (int)$rsThisTask[0]['IncludeToOwnerNote'] === 0)
            ) {
                $thisOwnerID = $rsThisTask[0]['OwnerID'];
            }
            $details['OwnerID'] = $thisOwnerID;

            // manage Notification
            $taskNotification = $this->entityManager->getRepository('AppBundle:Notifications')->TaskNotificationInLastOneMinute($taskID,$rsServicers[0]['CustomerID'],5);
            if ((int)$taskNotification[0]['Count'] === 0) {
                $result = array(
                    'MessageID' => 5,
                    'CustomerID' => $rsServicers[0]['CustomerID'],
                    'TaskID' => $taskID,
                    'ToCustomerID' => $rsThisTask[0]['CustomerID'],
                    'ServicerID' => $rsThisTask[0]['ManagerServicerID'],
                    'OwnerID' => $thisOwnerID,
                    'SendToMaintenanceStaff' => 1,
                    'SendToManagers' => 1,
                    'SubmittedByServicerID' => $servicerID,
                    'TypeID' => 0
                );
                $taskNotification = $this->serviceContainer->get('vrscheduler.notification_service')->CreateManageCompleteNotification($result);
                $notification['TaskNotification'] = $taskNotification;
            }

            $this->entityManager->flush();

            return array(
                'Status' => 'Success',
                'Notification' => $notification
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Submitting Manage form ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}