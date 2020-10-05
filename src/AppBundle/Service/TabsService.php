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
use AppBundle\DatabaseViews\ServicesToProperties;
use AppBundle\DatabaseViews\TaskWithServicers;
use AppBundle\Entity\Taskstochecklistitems;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
    public function GetLog($content,$servicerID)
    {
        try {
            $propertyID = $content['PropertyID'];

            array_key_exists('TaskID',$content) ? $taskID = $content['TaskID'] : $taskID = null;

            $property = $this->entityManager->getRepository('AppBundle:Properties')->GetPropertyNameByID($propertyID);
            $query = 'SELECT CreateDate,Issue,FromTaskID,SubmittedByServicerID,CustomerName,SubmittedByName,TimeZoneRegion,Urgent,IssueType,PropertyID,Notes FROM ('.Issues::vIssues.') AS SubQuery WHERE SubQuery.PropertyID <> 0 AND SubQuery.PropertyID='.$propertyID;
            $query .= ' AND SubQuery.ClosedDate IS NULL';

            if ($taskID) {
                $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
                if ((int)$servicers[0]['ShowIssueLog'] !== 1) {
                    $query .= ' AND SubQuery.FromTaskID='.$taskID;
                }
                $query .= ' ORDER BY SubQuery.CreateDate DESC';
            }

            $staffTasks = $this->entityManager->getConnection()->prepare($query);
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
    public function GetInfo($servicerID, $content)
    {
        try {
            $checkListItems = [];
            $taskID = $content['TaskID'];
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForInfoTab($taskID,$servicerID);
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID,$servicers[0]['Region']);
            $today = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($servicers[0]['Region']);
            $today->setTime(0,0,0);
            if( $tasks[0]['TaskStartDate'] >= $today ||
                ((int)$servicers[0]['TimeTracking'] === 1 &&
                    (empty($timeClockTasks) || $timeClockTasks[0]['TaskID'] !== (string)$tasks[0]['TaskID'])

                )
            ) {
                // Sub CheckLists
                $tasks[0]['ServiceID'] ? $serviceID = $tasks[0]['ServiceID'] : $serviceID = 0;
                $tasks[0]['PropertyID'] ? $propertyID = $tasks[0]['PropertyID'] : $propertyID = 0;
                $subCheckListItems = 'SELECT DISTINCT SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesToPropertiesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$serviceID.' AND SubQuery.PropertyID='.$propertyID.' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
                $subCheckListItems = $this->entityManager->getConnection()->prepare($subCheckListItems);
                $subCheckListItems->execute();
                $subCheckListItems = $subCheckListItems->fetchAll();
                if(!empty($subCheckListItems)) {
                    $checkListItems = $subCheckListItems;
                } else {
                    // Master CheckLists
                    $masterCheckListItems = 'SELECT DISTINCT SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$serviceID.' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
                    $masterCheckListItems = $this->entityManager->getConnection()->prepare($masterCheckListItems);
                    $masterCheckListItems->execute();
                    $checkListItems = $masterCheckListItems->fetchAll();
                }

            }

            // unset tasks fields that are not required
            if(!empty($tasks)) {
                unset($tasks[0]['TaskStartDate']);
                unset($tasks[0]['ServiceID']);
                unset($tasks[0]['PropertyID']);
                unset($tasks[0]['Servicers_CustomerID']);
                unset($tasks[0]['TimeTracking']);
                $tasks[0]['AllowAdminAccess'] = $servicers[0]['AllowAdminAccess'] ? 1 : 0;
            }
        return array(
            'TaskDetails' => $tasks[0],
            'CheckListDetails' => !empty($checkListItems) ? $checkListItems : []
        );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Info Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function GetBooking($servicerID, $content)
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
                                if (strpos($key, 'NextGuest') !== false) {
                                    $nextGuest = array_merge($nextGuest ? $nextGuest : [],array(
                                        $key => $value
                                    ));
                                }

                            }
                        } else {
                            $common = array_merge($common, array(
                                $key => trim($value)
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
            $this->logger->error('Failed to fetch Booking Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @return array
     */
    public function GetImages($content)
    {
        try {
            $propertyID = $content['PropertyID'];
            array_key_exists('ServiceID',$content) ? $serviceID = $content['ServiceID'] : $serviceID = null;

            $images = $this->entityManager->getRepository('AppBundle:Images')->GetImagesForImageTab($propertyID,$serviceID);
            return array(
                'Images' => $images
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Images Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @return array
     */
    public function GetAssignments($servicerID,$content)
    {
        try {
            $propertyID = (int)$content['PropertyID'];
            $propertyBookings = '';
            $propertiesCondition = '';
            $region = null;
            $taskIDs = '';
            $response = [];

            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            if (empty($servicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::SERVICER_NOT_FOUND);
            }

            $region = $servicers[0]['Region'];
            $timeZoneRegion = new \DateTimeZone($region);

//            $now = (new \DateTime('now',$timeZoneRegion));
//            $today = new \DateTime($now->format('Y-m-d'));

//            $todaysBooking = $this->entityManager->getRepository('AppBundle:Tasks')->FetchTasksForDashboard($servicerID,$servicers,$taskID);

                $region = $servicers[0]['Region'];
                $timeZoneRegion = new \DateTimeZone($region);

                // Get all PropertyBookings
                $pb = $this->entityManager->getRepository('AppBundle:Tasks')->FetchTasksForDashboard($servicerID,$servicers);

                if (!empty($pb)) {
                    foreach ($pb as $value) {
                        $value['PropertyBookingID'] ? $propertyBookings .= $value['PropertyBookingID'].',' : false;
                        $value['TaskID'] ? $taskIDs .= $value['TaskID'].',' : false;
                        $value['PropertyID'] ? $properties[] = $value['PropertyID'].',' : false;
                    }
                    $propertyBookings = preg_replace("/,$/", '', $propertyBookings);
                    $taskIDs = preg_replace("/,$/", '', $taskIDs);
                }

                // Get All Properties
                $rsCurrentTaskServicers = $this->entityManager->getRepository('AppBundle:Tasks')->getTaskServicers(!empty($taskIDs)?$taskIDs:0,$servicers[0]['CustomerID'],$servicers);
                if (!empty($rsCurrentTaskServicers)) {
                    foreach ($rsCurrentTaskServicers as $currentTaskServicer) {
                        $currentTaskServicer['PropertyID'] ? $propertiesCondition .= $currentTaskServicer['PropertyID'].',' : false;
                    }
                    $propertiesCondition = preg_replace("/,$/", '', $propertiesCondition);
                }

                empty($propertiesCondition) ? $propertiesCondition='0':false;
                empty($propertyBookings) ? $propertyBookings='0':false;

                $query1 = $this->entityManager->getRepository('AppBundle:Tasks')->AssignmentsTask($servicers[0]['CustomerID'],50,$propertyBookings);

                $query2 = $this->entityManager->getRepository('AppBundle:Tasks')->AssignmentsTask($servicers[0]['CustomerID'],50,null,$propertiesCondition);

                $rsAllEmployeesAndTasks = $query1.' UNION '.$query2;
                $response = 'SELECT TOP 5 *  FROM ('.$rsAllEmployeesAndTasks.') AS R  WHERE R.PropertyID = '.$propertyID.'  ORDER BY R.TaskDate desc';
                $response = $this->entityManager->getConnection()->prepare($response);
                $response->execute();
                $response = $response->fetchAll();

            for ($i = 0; $i < count($response); $i++) {
                if ($response[$i]['CompleteConfirmedDate']) {
                    $dateTime = new \DateTime($response[$i]['CompleteConfirmedDate']);
                    $dateTime->setTimezone($timeZoneRegion);
                    $response[$i]['CompleteConfirmedDate'] = $dateTime->format('Y-m-d h:i A');
                }
                $response[$i]['ServicersPhone'] = trim($response[$i]['ServicersPhone']);
                $response[$i]['Abbreviation'] = trim($response[$i]['Abbreviation']);
                $response[$i]['ServicersDetails'] = array(
                    'ServicersPhone' => $response[$i]['ServicersPhone'],
                    'ServicersEmail' => $response[$i]['ServicersEmail'],
                    'ServicersName' => $response[$i]['ServicersName']
                );

                unset($response[$i]['ServicersPhone']);
                unset($response[$i]['ServicersEmail']);
                unset($response[$i]['ServicersName']);
            }

        return array(
            'Assignments' => $response
        );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Assignment Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function ManageTabDetails($servicerID, $content)
    {
        try {
            $response = [];
            $team = 0;
            $taskID = $content['TaskID'];
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $today = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($servicers[0]['Region']);
            $today->setTime(0,0,0);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->FetchTasksForDashboard($servicerID,$servicers,$taskID);

            if (empty($tasks)) {
//                return $response;
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            $taskObj = $this->entityManager->getRepository('AppBundle:Tasks')->find($tasks[0]['TaskID']);
//            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID);

            // START: TIME TRACKING
            if ($tasks[0]['TaskStartDate']<= $today) {
                if (((int)$servicers[0]['AllowStartEarly'] === 1 || $tasks[0]['TaskStartDate'] <= $today)) {
                    // Initialize Standard Services
                    $standardServices = [];
                    if ((int)$servicers[0]['AllowAddStandardTask'] === 1) {
                        $standardServices = $this->entityManager->getConnection()->prepare('Select ServiceID,PropertyID,ServiceName,Name FROM ('.ServicesToProperties::vServicesToProperties.') AS stp WHERE stp.TaskType=9 AND stp.CustomerID='.$servicers[0]['CustomerID'].' AND stp.PropertyID='.$tasks[0]['PropertyID'].' AND stp.Active = 1 AND stp.ServiceActive = 1 And stp.IncludeOnIssueForm = 1');
                        $standardServices->execute();
                        $standardServices = $standardServices->fetchAll();
                    }

                    // Check Conditions for Issue Form
                    $response['IssueForm'] = array(
                        'IncludeMaintenance' => (int)$tasks[0]['IncludeMaintenance'],
                        'IncludeDamage' => (int)$tasks[0]['IncludeDamage'],
                        'IncludeLostAndFound' => (int)$tasks[0]['IncludeLostAndFound'],
                        'IncludeSupplyFlag' => (int)$tasks[0]['IncludeSupplyFlag'],
                        'IncludeUrgentFlag' => (int)$tasks[0]['IncludeUrgentFlag'],
                        'AllowShareImagesWithOwners' => (int)$tasks[0]['AllowShareImagesWithOwners'],
                        'StandardServices' => $standardServices
                    );

                    // Get CheckList Items
                    $tasks[0]['ServiceID'] ? $serviceID = $tasks[0]['ServiceID'] : $serviceID = 0;
                    $tasks[0]['PropertyID'] ? $propertyID = $tasks[0]['PropertyID'] : $propertyID = 0;
                    $subCheckListItems = 'SELECT DISTINCT * FROM ('.CheckLists::vServicesToPropertiesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$serviceID.' AND SubQuery.PropertyID='.$propertyID.' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
                    $subCheckListItems = $this->entityManager->getConnection()->prepare($subCheckListItems);
                    $subCheckListItems->execute();
                    $subCheckListItems = $subCheckListItems->fetchAll();
                    if(!empty($subCheckListItems)) {
                        $rsChecklistItems = $subCheckListItems;
                    } else {
                        // Master CheckLists
                        $masterCheckListItems = 'SELECT DISTINCT * FROM ('.CheckLists::vServicesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$serviceID.' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
                        $masterCheckListItems = $this->entityManager->getConnection()->prepare($masterCheckListItems);
                        $masterCheckListItems->execute();
                        $rsChecklistItems = $masterCheckListItems->fetchAll();
                    }
                    $checkListCount = count($rsChecklistItems);
                    $response['CheckListInfo'] = array(
                        'CheckListCount' => $checkListCount
                    );

                    // CheckList Response
                    $checkListResponse = [];
                    foreach ($rsChecklistItems as $rsChecklistItem) {
                        $rsThisResponse = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->GetCheckListItemsForManageTab($tasks[0]['TaskID'],$rsChecklistItem['ChecklistItemID']);
                        $result = [];

                        // Create check Lists if empty
                        switch ((int)$rsChecklistItem['ChecklistTypeID']) {
                            case 0:
                                // CheckBox
                            case 1:
                            case 9:
                                // Radio With Other
                            case 2:
                                // Radio buttons
                            case 3:
                                // Dropdown
                            case 8:
                                // Radio With Other
                            case 4:
                                // Image Upload
                            case 5:
                                // Image Verification
                                if (empty($rsThisResponse)) {
                                    $rsThisResponse = $this->InsertNewTaskToCheckList($taskObj, $rsChecklistItem);
                                }
                                break;
                            case 7:
                                $result = $this->ProcessOption7and10and11($taskObj,$rsChecklistItem,$rsThisResponse,7);
                                break;
                            case 10:
                                // ColumnCount
                                $result = $this->ProcessOption7and10and11($taskObj,$rsChecklistItem,$rsThisResponse,10);
                                break;
                            case 11:
                                // ColumnCount
                                $result = $this->ProcessOption7and10and11($taskObj,$rsChecklistItem,$rsThisResponse,11);
                                break;
                        }

                        $checkLists = array('CheckListItem' => $rsChecklistItem);
                        $checkLists['CheckListItem']['ResponseInfo'] = !empty($result) ? array_merge($rsThisResponse,$result) : $rsThisResponse;
                        $checkListResponse[] = $checkLists;
                    }

                    $response['CheckListInfo']['Details'] = $checkListResponse;

                    // SHOW "END TASK" IF THIS IS THE STARTED TASK
//                if (!empty($timeClockTasks) && ($timeClockTasks[0]['TaskID'] === (string)$tasks[0]['TaskID'])) {
                    // Team Details
                    $team = $this->entityManager->getRepository('AppBundle:Tasks')->GetTeamByTask($tasks[0]['TaskID'],$servicers,2);
                    $team = count($team);
//                }

                    // TaskImages
                    $images = $this->entityManager->getRepository('AppBundle:Issueandtaskimagestotasks')->GetImagesByTaskID($taskID);

                    //TaskInfo
                    $response['TaskInfo'] = array(
                        'TaskImages' => $images,
                        'TaskDescriptionImage1' => $tasks[0]['TaskDescriptionImage1'],
                        'TaskDescriptionImage2' => $tasks[0]['TaskDescriptionImage2'],
                        'TaskDescriptionImage3' => $tasks[0]['TaskDescriptionImage3'],
                        'IncludeToOwnerNote' => (int)$tasks[0]['IncludeToOwnerNote'],
                        'ToOwnerNote' => $tasks[0]['ToOwnerNote'],
                        'DefaultToOwnerNote' => $tasks[0]['DefaultToOwnerNote'],
                        'IncludeServicerNote' => $tasks[0]['IncludeServicerNote'],
                        'ServicerNotes' => $tasks[0]['ServicerNotes'],
                        'PropertyName' => $tasks[0]['PropertyName'],
                        'ServiceName' => $tasks[0]['ServiceName'],
                        'TaskName' => $tasks[0]['TaskName'],
                        'Team' => $team,
                        'TimeTracking' => (int)$servicers[0]['TimeTracking']
                    );
                }
            }

            return $response;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Manage Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $task
     * @param $checkListItem
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @return array
     */
    public function InsertNewTaskToCheckList($task, $checkListItem)
    {
        // Create new TasksToCheckList
        $taskToCheckListItems = new Taskstochecklistitems();
        $taskToCheckListItems->setTaskid($task);
        $taskToCheckListItems->setChecklistitemid($this->entityManager->getRepository('AppBundle:Checklistitems')->find($checkListItem['ChecklistItemID']));
        $taskToCheckListItems->setOptionid(0);
        $taskToCheckListItems->setOptionselected('');
        $taskToCheckListItems->setChecklisttypeid((int)$checkListItem['ChecklistTypeID']);
        $taskToCheckListItems->setChecklistitem($checkListItem['ChecklistItem']);
        $taskToCheckListItems->setDescription($checkListItem['Description']);
        $taskToCheckListItems->setImage($checkListItem['Image']);
        $taskToCheckListItems->setEnteredvalue('');
        $taskToCheckListItems->setImageuploaded('');
        $taskToCheckListItems->setShowonownerreport((int)$checkListItem['ShowOnOwnerReport'] ? true : false);
        $taskToCheckListItems->setChecked(false);
        $taskToCheckListItems->setSortorder((int)$checkListItem['SortOrder']);
        $this->entityManager->persist($taskToCheckListItems);
        $this->entityManager->flush();

        return array(
            array(
                'EnteredValueAmount' => null,
                'ColumnValue' => 0,
                'TaskToChecklistItemID' => $taskToCheckListItems->getTasktochecklistitemid(),
                'OptionSelected' => "0",
                'ImageUploaded' => "",
                'EnteredValue' => "",
                'Checked' => "0",
                'ChecklistItemID' => $checkListItem['ChecklistItemID']
            )
        );
    }

    /**
     * @param $task
     * @param $checkListItem
     * @param $rsThisResponse
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return array
     */
    public function ProcessOption7and10and11($task,$checkListItem, $rsThisResponse, $option = 7)
    {
        // Initialise Results
        $res2 = [];
        $res = [];
        $diff = [];

        // Get All options
        $res1 = explode("\n", $checkListItem['Options']);
        foreach ($rsThisResponse as $value) {
            if ($value['EnteredValue'] !== '') {
                $res2[] = $value['EnteredValue'];
            }
        }

        // Create entries if the option selected are not present in the DB
        if ($option === 7) {
            $diff = array_diff($res1, $res2);
        } elseif ($option === 10) {
            if (count($res2) !== count($res1) * (int)$checkListItem['ColumnCount']) {
                $res = [];
                for ($i = 0; $i < count($res1); $i++) {
                    for ($j = 0; $j < (int)$checkListItem['ColumnCount']; $j++) {
                        $res[] = $res1[$i];
                    }
                }
                $diff = array_diff_key($res, $res2);
            }
        } else {
            $diff = array_diff_key($res1, $res2);
        }

        //
        if (!empty($diff)) {
            foreach ($diff as $key => $value) {
                // Initialize variables
                $columnValue = 0;
                $optionSelected = 0;
                $taskToCheckListItems = new Taskstochecklistitems();
                $taskToCheckListItems->setTaskid($task);
                $taskToCheckListItems->setChecklistitemid($this->entityManager->getRepository('AppBundle:Checklistitems')->find($checkListItem['ChecklistItemID']));
                if ($option === 7) {
                    $taskToCheckListItems->setOptionid((int)($key + 1));
                    $taskToCheckListItems->setOptionselected($optionSelected);
                } elseif ($option === 10) {
                    $optionID = ceil((int)($key + 1) / (int)$checkListItem['ColumnCount']);
                    $columnValue = (int)($key + 1) % (int)$checkListItem['ColumnCount'];
                    $taskToCheckListItems->setOptionid($optionID);
                    $taskToCheckListItems->setColumnvalue($columnValue === 0 ? (int)$checkListItem['ColumnCount'] : $columnValue);
                    $taskToCheckListItems->setOptionselected($optionSelected);
                } else {
                    $columnValue = (int)$key+1;
                    $optionSelected = '';
                    $taskToCheckListItems->setOptionselected($optionSelected);
                    $taskToCheckListItems->setColumnvalue($columnValue);
                    $taskToCheckListItems->setOptionid((int)($key + 1));
                }

                $taskToCheckListItems->setEnteredvalueamount(0);
                $taskToCheckListItems->setChecklisttypeid((int)$checkListItem['ChecklistTypeID']);
                $taskToCheckListItems->setChecklistitem($checkListItem['ChecklistItem']);
                $taskToCheckListItems->setDescription($checkListItem['Description']);
                $taskToCheckListItems->setImage($checkListItem['Image']);
                $taskToCheckListItems->setEnteredvalue($value);
                $taskToCheckListItems->setImageuploaded('');
                $taskToCheckListItems->setShowonownerreport((int)$checkListItem['ShowOnOwnerReport'] ? true : false);
                $taskToCheckListItems->setChecked(0);
                $taskToCheckListItems->setSortorder($checkListItem['SortOrder']);
                $this->entityManager->persist($taskToCheckListItems);
                $this->entityManager->flush();

                $res[] = array(
                    'EnteredValueAmount' => 0,
                    'ColumnValue' => $columnValue,
                    'TaskToChecklistItemID' => $taskToCheckListItems->getTasktochecklistitemid(),
                    'OptionSelected' => $optionSelected,
                    'ImageUploaded' => "",
                    'EnteredValue' => $value,
                    'Checked' => "0",
                    'ChecklistItemID' => $checkListItem['ChecklistItemID']
                );
            }
        }

        return $res;
    }
}