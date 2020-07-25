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
use AppBundle\DatabaseViews\TimeClockDays;
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
    public function StartTask($servicerID, $content)
    {
        try {
            $startPause = $content['StartPause'];
            $taskID = $content['TaskID'];
            $dateTime = $content['DateTime'];
            $response = [];
            $timeClockResponse = null;

            $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->find($servicerID);

            // Start/Pause a Task
            if($startPause) {

                $timeClockTasks = $this->getEntityManager()->getConnection()->prepare("UPDATE TimeClockTasks SET ClockOut = '".(new \DateTime($dateTime))->format('Y-m-d H:i:s')."' WHERE ClockOut IS NULL AND ServicerID=".$servicerID)->execute();

                // Get time clock days
                $timeZone = new \DateTimeZone($servicer->getTimezoneid()->getRegion());

                // Check If Any TimeClockDay is present for current day
                $timeClockDays = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CheckTimeClockForCurrentDay($servicerID,$timeZone,$dateTime);

                // Insert new Time Clock Days if empty
                if (empty($timeClockDays)) {
                    $timeClockDays = new TimeClock();
                    $timeClockDays->setServicerid($servicer);
                    $this->entityManager->persist($timeClockDays);
                    $this->entityManager->flush($timeClockDays);
                    $response['TimeClockDaysID'] = $timeClockDays->getTimeclockdayid();
                }

                $task = $this->entityManager->getRepository('AppBundle:Tasks')->GetCompleteConfirmedDateForStartTask($taskID);
                if(!empty($task)) {
                    $timeClockTasks = new Timeclocktasks();
                    $timeClockTasks->setServicerid($servicer);
                    $timeClockTasks->setClockin((new \DateTime($dateTime)));
                    $timeClockTasks->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($taskID));
                    $this->entityManager->persist($timeClockTasks);
                    $this->entityManager->flush();
                    $response['TimeClockTasksID'] = $timeClockTasks->getTimeclocktaskid();
                }

                $dateTime = (new \DateTime($dateTime));
                $dateTime->setTimezone($timeZone);
                $response['Started'] = $dateTime->format('h:i A');
            } else {
                // Clock Out/Pause Task
                $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID,$servicer->getTimezoneid()->getRegion());
                if(!empty($timeClockTasks)) {
                    $timeClockTasks = $this->getEntityManager()->getConnection()->prepare("UPDATE TimeClockTasks SET ClockOut = '".(new \DateTime($dateTime))->format('Y-m-d H:i:s')."' WHERE ClockOut IS NULL AND ServicerID=".$servicerID." AND TaskID=".$taskID)->execute();
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