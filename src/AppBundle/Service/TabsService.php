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
            $staffTasks = $this->entityManager->getConnection()->prepare('SELECT CreateDate,Issue,FromTaskID,SubmittedByServicerID,CustomerName,SubmittedByName,TimeZoneRegion,Urgent,IssueType,PropertyID,Notes FROM ('.Issues::vIssues.') AS SubQuery WHERE SubQuery.ClosedDate IS NULL AND SubQuery.PropertyID='.$propertyID.' ORDER BY SubQuery.CreateDate DESC');
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
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForInfoTab($taskID);
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID);
            $today = new \DateTime('now',new \DateTimeZone('UTC'));
            if( $tasks[0]['TaskStartDate'] >= $today ||
                ((int)$servicers[0]['TimeTracking'] === 1 &&
                    (empty($timeClockTasks) || $timeClockTasks[0]['TaskID'] !== (string)$tasks[0]['TaskID'])

                )
            ) {
                // Sub CheckLists
                $subCheckListItems = 'SELECT DISTINCT SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesToPropertiesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$tasks[0]['ServiceID'].' AND SubQuery.PropertyID='.$tasks[0]['PropertyID'].' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
                $subCheckListItems = $this->entityManager->getConnection()->prepare($subCheckListItems);
                $subCheckListItems->execute();
                $subCheckListItems = $subCheckListItems->fetchAll();
                if(!empty($subCheckListItems)) {
                    $checkListItems = $subCheckListItems;
                } else {
                    // Master CheckLists
                    $masterCheckListItems = 'SELECT DISTINCT SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$tasks[0]['ServiceID'].' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
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
            'CheckListDetails' => !empty($checkListItems) ? $checkListItems : null
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
            $serviceID = $content['ServiceID'];

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
    public function GetAssignments($content)
    {
        try {
            $propertyBookingID = $content['PropertyBookingID'];

            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForAssignmentsTab($propertyBookingID);
            return array(
                'Assignments' => $tasks
            );
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
            $today = (new \DateTime('now'));
            $response = [];
            $team = 0;
            $taskID = $content['TaskID'];
            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->ServicerDashboardRestrictions($servicerID);
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->FetchTasksForDashboard2($servicerID,$taskID);
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID);

            // START: TIME TRACKING
            if ( ((int)$servicers[0]['TimeTracking'] === 1) &&
                ((int)$servicers[0]['AllowStartEarly']) &&
                (((int)$servicers[0]['RequestAcceptTasks']) !== 1 || ((int)$servicers[0]['RequestAcceptTasks'] === 1 && ($tasks[0]['AcceptedDate'] !== ''))) &&
                (!(empty($timeClockTasks) || $timeClockTasks[0]['TaskID'] !== (string)$tasks[0]['TaskID']))
            ) {
                // Initialize Standard Services
                $standardServices = null;
                if ((int)$servicers[0]['AllowAddStandardTask'] === 1) {
                    $standardServices = $this->entityManager->getConnection()->prepare('Select ServiceID,ServiceName,Name FROM ('.ServicesToProperties::vServicesToProperties.') AS stp WHERE stp.TaskType=9 AND stp.CustomerID='.$servicers[0]['CustomerID'].' AND stp.PropertyID='.$tasks[0]['PropertyID'].' AND stp.Active = 1 AND stp.ServiceActive = 1 And stp.IncludeOnIssueForm = 1');
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

                // Check If checklist is present
//                $subCheckListItems = 'SELECT DISTINCT SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesToPropertiesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$tasks[0]['ServiceID'].' AND SubQuery.PropertyID='.$tasks[0]['PropertyID'].' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
//                $subCheckListItems = $this->entityManager->getConnection()->prepare($subCheckListItems);
//                $subCheckListItems->execute();
//                $subCheckListItems = $subCheckListItems->fetchAll();
//                if(!empty($subCheckListItems)) {
//                    $checkListItems = $subCheckListItems;
//                } else {
//                    // Master CheckLists
//                    $masterCheckListItems = 'SELECT DISTINCT SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$tasks[0]['ServiceID'].' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
//                    $masterCheckListItems = $this->entityManager->getConnection()->prepare($masterCheckListItems);
//                    $masterCheckListItems->execute();
//                    $checkListItems = $masterCheckListItems->fetchAll();
//                }

                // SHOW "END TASK" IF THIS IS THE STARTED TASK
                if ((int)$servicers[0]['TimeTracking'] === 1 && ($timeClockTasks[0]['TaskID'] === (string)$tasks[0]['TaskID']))
                {
                    // Team Details
                    $team = $this->entityManager->getRepository('AppBundle:Tasks')->GetTeamByTask($tasks[0]['TaskID'],2);
                    if (count($team) > 1) {
                        $team = 1;
                    }
                }

                //TaskInfo
                $response['TaskInfo'] = array(
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

            return $response;

        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch Assignment Details' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}