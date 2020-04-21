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
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForBookingTab($content['TaskID'],$servicers);
            return $tasks;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Info Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}