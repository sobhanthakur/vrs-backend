<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 18/6/20
 * Time: 11:41 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Gpstracking;
use AppBundle\Entity\Issues;
use AppBundle\Entity\Scheduledwebhooks;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class ManageSubmit
 * Complete Manage Tab
 * @package AppBundle\Service
 */
class ManageSubmit extends BaseService
{
    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function SubmitManageForm($servicerID, $content,$mobileHeaders)
    {
        try {
            $taskID = $content[GeneralConstants::TASK_ID];
            $dateTime = $content['DateTime'];
            $taskObj = null;
            $propertyObj = null;
            $notification = [];
            $details = [];
            $isMobile = $mobileHeaders['IsMobile'];
            $now = new \DateTime($dateTime);


            $rsThisTask = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->SubmitManage($servicerID,$taskID);

            if (empty($rsThisTask)) {
                // Throw Error if the Task Does not belong to the Servicer.
                throw new BadRequestHttpException(ErrorConstants::WRONG_LOGIN);
            }

            $rsServicers = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->SubmitManageTab($servicerID);

            if (empty($rsServicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            (int)$rsServicers[0]['TimeTrackingGPS'] ? $timeTrackingGps = true : $timeTrackingGps = false;
            array_key_exists('lat',$content) ? $lat = $content['lat'] : $lat = null;
            array_key_exists('long',$content) ? $long = $content['long'] : $long = null;
            array_key_exists('accuracy',$content) ? $accuracy = $content['accuracy'] : $accuracy = null;

            if ($timeTrackingGps && $lat && $long && $accuracy) {
                $gpsTracking = new Gpstracking();
                $gpsTracking->setServicerid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($servicerID));
                $gpsTracking->setAccuracy($accuracy);
                $gpsTracking->setIsmobile($isMobile);
                $gpsTracking->setLatitude($lat);
                $gpsTracking->setLongitude($long);
                $gpsTracking->setUseragent($mobileHeaders['UserAgent']);
                $gpsTracking->setCreatedate($now);
                $this->entityManager->persist($gpsTracking);
                $this->entityManager->flush();

            }

            // Task Object
            $taskObj = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($rsThisTask[0][GeneralConstants::TASK_ID]);

            $propertyObj = $this->entityManager->getRepository('AppBundle:Properties')->find($rsThisTask[0][GeneralConstants::PROPERTY_ID]);

            // there is a SERVICER NOTE, Make it an Issue
            if (((string)$taskObj->getServicernotes() !== "") || (trim($content[GeneralConstants::TASKNOTE]) !== "")) {
                trim($content[GeneralConstants::TASKNOTE]) !== "" ? $taskNote = $content[GeneralConstants::TASKNOTE] : $taskNote = (string)$taskObj->getServicernotes();
                $issues = new Issues();
                $issues->setIssuetype(-1);
                $issues->setUrgent(false);
                $issues->setIssue(substr(str_replace("'","",$taskNote), 0, 150));
                $issues->setPropertyid($propertyObj);

                if (strlen($taskNote) > 150) {
                    $issues->setNotes($taskNote);
                } else {
                    $issues->setNotes('');
                }

                $issues->setFromtaskid($taskObj);
                $issues->setSubmittedbyservicerid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($rsServicers[0][GeneralConstants::SERVICERID]));
                $this->entityManager->persist($issues);
                $this->entityManager->flush();
            }

            // Save the manage form first
            $save = $this->serviceContainer->get('vrscheduler.manage_save')->SaveManageDetails($servicerID, $content,$dateTime);

            if ($rsThisTask[0][GeneralConstants::PROPERTYSTATUSID] && (int)$rsThisTask[0][GeneralConstants::PROPERTYSTATUSID] !== 0 && $propertyObj) {
                $propertyObj->setPropertystatusid($rsThisTask[0][GeneralConstants::PROPERTYSTATUSID]);
                $this->entityManager->persist($propertyObj);
                $this->entityManager->flush();
            }

            // Submit To CloudBeds
            if ((string)trim($rsThisTask[0]['CloudbedsHousekeepingStatus']) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(3);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['CloudbedsHousekeepingStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit To WebRezProStatus
            if ((string)trim($rsThisTask[0]['WebRezProStatus']) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(11);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['WebRezProStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to Mews
            if ((string)trim($rsThisTask[0]['MewsStatus']) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(7);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['MewsStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to LMPM
            if ((int)$rsThisTask[0]['LMPMStatus'] !== 0) {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(9);
                $schedulingWebHooks->setEventid(0);
                $schedulingWebHooks->setValue($rsThisTask[0]['LMPMStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to GUESTY
            if ((int)$rsThisTask[0][GeneralConstants::GUESTYSTATUS] !== 0 && (string)trim($rsThisTask[0][GeneralConstants::GUESTYSTATUS]) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(10);
                $schedulingWebHooks->setEventid(0);
                $schedulingWebHooks->setValue($rsThisTask[0][GeneralConstants::GUESTYSTATUS]);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to Operto
            if ((string)trim($rsThisTask[0]['OpertoStatus']) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(4);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['OpertoStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to Trackhs
            if ((int)$rsThisTask[0]['TrackHSCleanTypeID'] !== 0) {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(6);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['TrackHSCleanTypeID']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to Trackhs
            if ((int)$rsThisTask[0]['Beds24UnitStatusIndex'] !== 0) {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(8);
                $schedulingWebHooks->setEventid($rsThisTask[0]['Beds24UnitStatusIndex']);
                $schedulingWebHooks->setValue($rsThisTask[0]['Beds24UnitStatusText']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to ESCAPIA
            if ((string)trim($rsThisTask[0]['EscapiaHousekeepingStatus']) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(2);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['EscapiaHousekeepingStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to STREAMLINE
            if ((int)$rsThisTask[0]['StreamlineHousekeepingStatus'] !== 0) {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(5);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['StreamlineHousekeepingStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to New Book
            if ((string)trim($rsThisTask[0]['NewbookStatus']) !== '') {
                $schedulingWebHooks = new Scheduledwebhooks();
                $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                $schedulingWebHooks->setPartnerid(12);
                $schedulingWebHooks->setEventid(1);
                $schedulingWebHooks->setValue($rsThisTask[0]['NewbookStatus']);
                $this->entityManager->persist($schedulingWebHooks);
            }

            // Submit to BH247
            if ((int)$rsServicers[0]['UseBeHome247'] !== 0 &&
                (int)$rsThisTask[0]['BeHome247ID'] !== 0 &&
                ($rsThisTask[0][GeneralConstants::BH247CLEANINGSTATE] !== '' ||
                    $rsThisTask[0][GeneralConstants::BH247QASTATE] !== '' ||
                    $rsThisTask[0][GeneralConstants::BH247MAINTANENCESTATE] !== '' ||
                    $rsThisTask[0][GeneralConstants::BH247CUSTOM_1STATE] !== '' ||
                    $rsThisTask[0][GeneralConstants::BH247CUSTOM_2STATE] !== ''
                )
            ) {
                if (trim($rsThisTask[0][GeneralConstants::BH247CLEANINGSTATE]) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                    $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                    $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(1);
                    $schedulingWebHooks->setValue($rsThisTask[0][GeneralConstants::BH247CLEANINGSTATE]);
                    $this->entityManager->persist($schedulingWebHooks);
                }

                if (trim($rsThisTask[0][GeneralConstants::BH247QASTATE]) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                    $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                    $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(2);
                    $schedulingWebHooks->setValue($rsThisTask[0][GeneralConstants::BH247QASTATE]);
                    $this->entityManager->persist($schedulingWebHooks);
                }

                if (trim($rsThisTask[0][GeneralConstants::BH247MAINTANENCESTATE]) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                    $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                    $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(3);
                    $schedulingWebHooks->setValue($rsThisTask[0][GeneralConstants::BH247MAINTANENCESTATE]);
                    $this->entityManager->persist($schedulingWebHooks);
                }

                if (trim($rsThisTask[0][GeneralConstants::BH247CUSTOM_1STATE]) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                    $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                    $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(4);
                    $schedulingWebHooks->setValue($rsThisTask[0][GeneralConstants::BH247CUSTOM_1STATE]);
                    $this->entityManager->persist($schedulingWebHooks);
                }

                if (trim($rsThisTask[0][GeneralConstants::BH247CUSTOM_2STATE]) !== '') {
                    $schedulingWebHooks = new Scheduledwebhooks();
                    $schedulingWebHooks->setCustomerid($rsThisTask[0][GeneralConstants::CUSTOMER_ID]);
                    $schedulingWebHooks->setTaskid($rsThisTask[0][GeneralConstants::TASK_ID]);
                    $schedulingWebHooks->setServicerid($rsServicers[0][GeneralConstants::SERVICERID]);
                    $schedulingWebHooks->setPropertyid($rsThisTask[0][GeneralConstants::PROPERTY_ID]);
                    $schedulingWebHooks->setPropertybookingid($rsThisTask[0][GeneralConstants::PROPERTYBOOKINGID]);
                    $schedulingWebHooks->setPartnerid(1);
                    $schedulingWebHooks->setEventid(5);
                    $schedulingWebHooks->setValue($rsThisTask[0][GeneralConstants::BH247CUSTOM_2STATE]);
                    $this->entityManager->persist($schedulingWebHooks);
                }
            }

            // Update the time Tracking
            $query = "UPDATE TimeClockTasks SET ClockOut = '".$now->format('Y-m-d H:i:s')."'";

            if ($timeTrackingGps && $lat && $long && $accuracy) {
                // Update lat and lon
                $query .= ", OutLat=".$lat.", OutLon=".$long.", OutAccuracy=".$accuracy.", OutIsMobile=".$isMobile.", UpdateDate='".$dateTime."'";
            }

            $query .= " WHERE ClockOut IS NULL AND TaskID=".$taskID;

            $this->getEntityManager()->getConnection()->prepare($query)->execute();

            // Get Owner ID
            $thisOwnerID = 0;
            if ((int)$rsThisTask[0][GeneralConstants::OWNERID] !== 0 &&
                ((array_key_exists('SendToOwnerNote', $content) && (int)$content['SendToOwnerNote'] !== 0) || (int)$rsThisTask[0]['IncludeToOwnerNote'] === 0)
            ) {
                $thisOwnerID = $rsThisTask[0][GeneralConstants::OWNERID];
            }
            $details[GeneralConstants::OWNERID] = $thisOwnerID;

            // manage Notification
            $result = array(
                GeneralConstants::MESSAGE_ID => 5,
                GeneralConstants::CUSTOMER_ID => $rsServicers[0][GeneralConstants::CUSTOMER_ID],
                GeneralConstants::TASK_ID => $taskID,
                'ToCustomerID' => $rsThisTask[0][GeneralConstants::CUSTOMER_ID],
                GeneralConstants::SERVICERID => $rsThisTask[0]['ManagerServicerID'],
                GeneralConstants::OWNERID => $thisOwnerID,
                'SendToMaintenanceStaff' => 1,
                GeneralConstants::SENDTOMANAGERS => 1,
                GeneralConstants::SUBMITTEDBYSERVICERID => $servicerID
            );
            $taskNotification = $this->serviceContainer->get(GeneralConstants::NOTIFICATION_SERVICE)->CreateManageCompleteNotification($result, $now);
            $notification['TaskNotification'] = $taskNotification;

            $this->entityManager->flush();

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS,
                'Notification' => $notification,
                'Details' => $details,
                'SaveDetails' => $save
            );

        } catch (BadRequestHttpException $exception) {
            throw $exception;
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