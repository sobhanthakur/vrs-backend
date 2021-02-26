<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 15/5/20
 * Time: 3:21 PM
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\CustomClasses\TimeZoneConverter;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Gpstracking;
use AppBundle\Entity\Timeclocktasks;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use AppBundle\Entity\Timeclockdays as TimeClock;

/**
 * Class StartTaskService
 * @package AppBundle\Service
 */
class StartTaskService extends BaseService
{
    /**
     * @param $servicerID
     * @param $content
     * @return mixed
     */
    public function StartTask($servicerID, $content,$mobileHeaders)
    {
        try {
            $startPause = $content['StartPause'];
            $taskID = $content[GeneralConstants::TASK_ID];
            $dateTime = $content['DateTime'];
            $response = [];
            $timeClockResponse = null;
            $isMobile = $mobileHeaders['IsMobile'];
            $now = new \DateTime($dateTime);

            $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->DoesTaskBelongToServicer($servicerID,$taskID);

            $servicer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($servicerID);
            (int)$servicer->getTimetrackinggps() ? $timeTrackingGps = true : $timeTrackingGps = false;
            array_key_exists('lat',$content) ? $lat = $content['lat'] : $lat = null;
            array_key_exists('long',$content) ? $long = $content['long'] : $long = null;
            array_key_exists('accuracy',$content) ? $accuracy = $content['accuracy'] : $accuracy = null;

            if ($timeTrackingGps && $lat && $long && $accuracy) {
                $gpsTracking = new Gpstracking();
                $gpsTracking->setServicerid($servicer);
                $gpsTracking->setAccuracy($accuracy);
                $gpsTracking->setIsmobile($isMobile);
                $gpsTracking->setLatitude($lat);
                $gpsTracking->setLongitude($long);
                $gpsTracking->setUseragent($mobileHeaders['UserAgent']);
                $gpsTracking->setCreatedate($now);
                $this->entityManager->persist($gpsTracking);
                $this->entityManager->flush();

            }

            // Start/Pause a Task
            if($startPause) {

                $query = "UPDATE TimeClockTasks SET ClockOut = '".$now->format('Y-m-d H:i:s')."'";

                if ($timeTrackingGps && $lat && $long && $accuracy) {
                    // Update lat and lon
                    $query .= ", OutLat=".$lat.", OutLon=".$long.", OutAccuracy=".$accuracy.", OutIsMobile=".$isMobile.", UpdateDate='".$dateTime."'";
                }

                $query .= " WHERE ClockOut IS NULL AND ServicerID=".$servicerID;

                $timeClockTasks = $this->getEntityManager()->getConnection()->prepare($query)->execute();

                // Get time clock days
                $timeZone = new \DateTimeZone($servicer->getTimezoneid()->getRegion());

                // Check If Any TimeClockDay is present for current day
                $timeClockDays = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CheckTimeClockForCurrentDay($servicerID,$timeZone,$dateTime);

                // Insert new Time Clock Days if empty
                if (empty($timeClockDays)) {
                    $timeClockDays = new TimeClock();
                    $timeClockDays->setServicerid($servicer);
                    if ($timeTrackingGps && $lat && $long && $accuracy) {
                        $timeClockDays->setInlat($lat);
                        $timeClockDays->setInlon($long);
                        $timeClockDays->setInaccuracy($accuracy);
                        $timeClockDays->setInismobile($isMobile);
                        $timeClockDays->setUpdatedate($now);
                    }
                    $this->entityManager->persist($timeClockDays);
                    $this->entityManager->flush($timeClockDays);
                    $response['TimeClockDaysID'] = $timeClockDays->getTimeclockdayid();
                }

                $task = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->GetCompleteConfirmedDateForStartTask($taskID);
                if(!empty($task)) {
                    $timeClockTasks = new Timeclocktasks();
                    $timeClockTasks->setServicerid($servicer);
                    $timeClockTasks->setClockin($now);
                    $timeClockTasks->setTaskid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($taskID));
                    if ($timeTrackingGps && $lat && $long && $accuracy) {
                        $timeClockTasks->setInlat($lat);
                        $timeClockTasks->setInlon($long);
                        $timeClockTasks->setInaccuracy($accuracy);
                        $timeClockTasks->setInismobile($isMobile);
                        $timeClockTasks->setUpdatedate($now);
                    }
                    $this->entityManager->persist($timeClockTasks);
                    $this->entityManager->flush();
                    $response['TimeClockTasksID'] = $timeClockTasks->getTimeclocktaskid();
                }

                $now->setTimezone($timeZone);
                $response['Started'] = $now->format('h:i A');
            } else {
                // Clock Out/Pause Task
                $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID,$servicer->getTimezoneid()->getRegion(),$dateTime);
                if(!empty($timeClockTasks)) {
                    $query = "UPDATE TimeClockTasks SET ClockOut = '".$now->format('Y-m-d H:i:s')."'";

                    if ($timeTrackingGps && $lat && $long && $accuracy) {
                        // Update lat and lon
                        $query .= ", OutLat=".$lat.", OutLon=".$long.", OutAccuracy=".$accuracy.", OutIsMobile=".$isMobile.", UpdateDate='".$dateTime."'";
                    }

                    $query .= " WHERE ClockOut IS NULL AND ServicerID=".$servicerID." AND TaskID=".$taskID;

                    $timeClockTasks = $this->getEntityManager()->getConnection()->prepare($query)->execute();
                }
                $response['Started'] = null;

            }
            return $response;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to Start Task Due to: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}