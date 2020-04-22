<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 20/4/20
 * Time: 3:57 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\ErrorConstants;
use AppBundle\DatabaseViews\CheckLists;
use AppBundle\DatabaseViews\Issues;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TabsService
 * @package AppBundle\Service
 */
class TabsService extends BaseService
{
    /**
     * @param $content
     * @return array
     */
    public function GetLog($content)
    {
        try {
            $propertyID = $content['PropertyID'];

            $property = $this->entityManager->getRepository('AppBundle:Properties')->GetPropertyNameByID($propertyID);
            $staffTasks = $this->entityManager->getConnection()->prepare('SELECT CreateDate,Issue,FromTaskID,SubmittedByServicerID,CustomerName,SubmittedByName,TimeZoneRegion,Urgent,IssueType,PropertyID,Notes FROM ('.Issues::vIssues.') AS SubQuery WHERE SubQuery.ClosedDate IS NULL AND SubQuery.PropertyID='.$propertyID);
            $staffTasks->execute();
            $staffTasks = $staffTasks->fetchAll();
            $response = array(
                'PropertyName' => $property[0]['PropertyName'] . ' Open Issues',
                'Details' => $staffTasks
            );
            return $response;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Log Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
//    public function GetInfo($servicerID, $content)
//    {
//        try {
//            $taskID = $content['TaskID'];
//            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForInfoTab($taskID);
////            $query = 'SELECT ChecklistName,ChecklistItemID,ChecklistItem,Description,Options,Required,Image,ChecklistTypeID,ColumnCount,ShowDescription FROM vServicesChecklistItems WHERE ServiceID = '.$tasks[0]['ServiceID'];
////            $query .= ' UNION ';
////            $query .= 'SELECT ChecklistName,ChecklistItemID,ChecklistItem,Description,Options,Required,Image,ChecklistTypeID,ColumnCount ,ShowDescription FROM vServicesToPropertiesChecklistItems WHERE ServiceID = '.$tasks[0]['ServiceID'].' AND PropertyID=171';
//
//
////            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID);
//            $checkListItems = $this->entityManager->getRepository('AppBundle:Checklistitems')->CheckListItemsForInfo($tasks[0]['PropertyID'],$tasks[0]['ServiceID']);
////            $checkListItems = $this->entityManager->getConnection()->prepare(CheckLists::AllChecklistItems);
////            $checkListItems->execute();
////            $checkListItems = $checkListItems->fetchAll();
//            print_r($checkListItems);die();
//        } catch (HttpException $exception) {
//            throw $exception;
//        } catch (\Exception $exception) {
//            $this->logger->error('Failed to fetch Info Details' .
//                $exception->getMessage());
//            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
//        }
//    }

    public function GetBooking($servicerID,$content)
    {
        try {
            $response = [];
            $previous = [];
            $next = [];
            $common = [];
            $prevGuest = null;
            $nextGuest = null;
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForBookingTab($content['TaskID'],$servicers);
            if ($tasks) {
                foreach ($tasks as $task) {
                    foreach ($task as $key => $value) {
                        if (strpos($key, 'Prev') !== false) {
                            $previous = array_merge($previous, array(
                                $key => $value
                            ));
                            if ($servicers[0]['IncludeGuestNumbers'] || $servicers[0]['IncludeGuestEmailPhone'] || $servicers[0]['IncludeGuestName']) {
                                if (strpos($key, 'PrevGuest') !== false) {
                                    $prevGuest = array_merge($prevGuest ? $prevGuest : [],array(
                                        $key => $value
                                    ));
                                }

                            }
                        } elseif (strpos($key, 'Next') !== false) {
                            $next = array_merge($next, array(
                                $key => $value
                            ));
                            if ($servicers[0]['IncludeGuestNumbers'] || $servicers[0]['IncludeGuestEmailPhone'] || $servicers[0]['IncludeGuestName']) {
                                if (strpos($key, 'PrevGuest') !== false) {
                                    $nextGuest = array_merge($nextGuest ? $nextGuest : [],array(
                                        $key => $value
                                    ));
                                }

                            }
                        } else {
                            $common = array_merge($common, array(
                                $key => $value
                            ));
                        }
                    }
                }
            }
            $response['Previous'] = $previous;
            $response['Previous']['GuestDetails'] = $prevGuest;
            $response['Next'] = $next;
            $response['Next']['GuestDetails'] = $nextGuest;
            $response['Common'] = $common;
            return $response;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Info Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}