<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 25/5/20
 * Time: 11:42 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\DatabaseViews\CheckLists;
use AppBundle\Entity\Taskstochecklistitems;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class ManageSave
 * @package AppBundle\Service
 */
class ManageSave extends BaseService
{
    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function SaveManageDetails($servicerID, $content)
    {
        try {
            $taskID = $content['TaskID'];
            $checkListItems = $content['CheckListDetails'];
            $rsThisTask = $this->entityManager->getRepository('AppBundle:Tasks')->TaskToSaveManage($taskID,$servicerID);

            if (!empty($rsThisTask)) {
                // STORE THE ISSUE TO THE  TASK TO MAKE SURE THE NOTIFICATION CAN GRAB THE INFO
                /*
                 * TaskNote = servicernotes
                 * Note To Owner: = To Owner Note
                 */
                $task = $this->entityManager->getRepository('AppBundle:Tasks')->find($taskID);
                $task->setServicernotes(substr($content['TaskNote'],0,5000));
                if (array_key_exists('NoteToOwner',$content) && $content['NoteToOwner'] !== '') {
                    $task->setToownernote(substr($content['NoteToOwner'],0,5000));
                }

                // Save and commit Task
//                $this->entityManager->persist($task);
//                $this->entityManager->flush();

                // Process CheckList items
                foreach ($checkListItems as $checkListItem) {
                    // Initialise Checklist variables
                    $updateIt = false;
                    $checked = 0;
                    $optionSelected = '';
                    $imageUploaded = '';
                    $enteredValue = '';

                    $inputs = $checkListItem['Input'];
                    if ((int)$checkListItem['ChecklistItemID'] !== 0) {
                        $rsThisResponse = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->GetCheckListItemsForManageTab($taskID,$checkListItem['ChecklistItemID']);

                        if(!empty($rsThisResponse)) {
                            $updateIt = true;
                        }

                        switch ((int)$checkListItem['ChecklistTypeID']) {
                            case 0:
                                // CheckBox
                                foreach ($inputs as $input) {
                                    $checked = $input['Checked'];
                                }
                                break;
                            case 1:
                                // Radio With Other
                                foreach ($inputs as $input) {
                                    $enteredValue = $input['EnteredValue'];
                                }
                                break;
                            case 2:
                                // Radio buttons
                            case 3:
                                // Dropdown
                                foreach ($inputs as $input) {
                                    $optionSelected = $input['OptionSelected'];
                                }
                                break;
                            case 4:
                                // Image Upload
                            case 5:
                                // Image Verification
                                foreach ($inputs as $input) {
                                    $imageUploaded = $input['ImageUploaded'];
                                }
                                break;
                            case 7:
                                $this->ProcessOption7and10($task, $rsThisTask, $checkListItem, $rsThisResponse, $inputs);
                                break;
                            case 8:
                                // Radio With Other
                                foreach ($inputs as $input) {
                                    $optionSelected = $input['OptionSelected'];
                                }
                                break;
                            case 9:
                                // Radio With Other
                                foreach ($inputs as $input) {
                                    $enteredValue = $input['EnteredValue'];
                                }
                                break;
                            case 10:
                                // ColumnCount
                                $this->ProcessOption7and10($task, $rsThisTask, $checkListItem, $rsThisResponse, $inputs,10);
                                break;
                            case 11:
                                // ColumnCount
                                $this->ProcessOption7and10($task, $rsThisTask, $checkListItem, $rsThisResponse, $inputs,11);
                                break;
                        }
                    }
                }
            }

            return array(
                'Status' => 'Success'
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Saving Manage form '.
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $task
     * @param $rsThisTask
     * @param $checkListItem
     * @param $rsThisResponse
     * @param $inputs
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessOption7and10($task, $rsThisTask, $checkListItem, $rsThisResponse, $inputs,$option=7)
    {
        $subCheckListItems = 'SELECT DISTINCT ColumnCount,Options,ShowOnOwnerReport,SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesToPropertiesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$rsThisTask[0]['ServiceID'].' AND SubQuery.PropertyID='.$rsThisTask[0]['PropertyID'].' AND SubQUery.ChecklistItemID='.$checkListItem['ChecklistItemID'].' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
        $subCheckListItems = $this->entityManager->getConnection()->prepare($subCheckListItems);
        $subCheckListItems->execute();
        $subCheckListItems = $subCheckListItems->fetchAll();
        if(!empty($subCheckListItems)) {
            $checkListResponse = $subCheckListItems;
        } else {
            // Master CheckLists
            $masterCheckListItems = 'SELECT DISTINCT ColumnCount,Options,ShowOnOwnerReport,SortOrder,Description,Image,required,ChecklistItem FROM ('.CheckLists::vServicesChecklistItems.') AS SubQuery WHERE SubQuery.ServiceID='.$rsThisTask[0]['ServiceID'].' AND SubQUery.ChecklistItemID='.$checkListItem['ChecklistItemID'].' AND SubQuery.ChecklistID IS NOT NULL ORDER BY SubQuery.SortOrder';
            $masterCheckListItems = $this->entityManager->getConnection()->prepare($masterCheckListItems);
            $masterCheckListItems->execute();
            $checkListResponse = $masterCheckListItems->fetchAll();
        }

        $res1 = explode("\n", $checkListResponse[0]['Options']);
        foreach ($rsThisResponse as $value) {
            if ($value['EnteredValue'] !== '') {
                $res2[] = $value['EnteredValue'];
            }
        }

        if ($option === 7) {
            $diff = array_diff($res1,$res2);
        } elseif ($option === 10) {
            if (count($res2) !== count($res1)*(int)$checkListResponse[0]['ColumnCount']) {
                $res = [];
                for ($i=0;$i<count($res1);$i++) {
                    for ($j=0;$j<(int)$checkListResponse[0]['ColumnCount'];$j++) {
                        $res[] = $res1[$i];
                    }
                }
                $diff = array_diff_key($res,$res2);
            } else {
                $diff = [];
            }
        } else {
            $diff = array_diff_key($res1,$res2);
        }

        if(!empty($diff)) {
            foreach ($diff as $key => $value) {
                $taskToCheckListItems = new Taskstochecklistitems();
                $taskToCheckListItems->setTaskid($task);
                $taskToCheckListItems->setChecklistitemid($this->entityManager->getRepository('AppBundle:Checklistitems')->find($checkListItem['ChecklistItemID']));
                if($option === 7) {
                    $taskToCheckListItems->setOptionid((int)($key+1));
                    $taskToCheckListItems->setOptionselected(1);
                } elseif ($option === 10) {
                    $optionID = ceil((int)($key+1) / (int)$checkListResponse[0]['ColumnCount']);
                    $columnValue = (int)($key+1) % (int)$checkListResponse[0]['ColumnCount'];
                    $taskToCheckListItems->setOptionid($optionID);
                    $taskToCheckListItems->setColumnvalue($columnValue === 0 ? (int)$checkListResponse[0]['ColumnCount'] : $columnValue);
                    $taskToCheckListItems->setOptionselected(0);
                } else {
                    $taskToCheckListItems->setOptionid((int)($key+1));
                }

                $taskToCheckListItems->setChecklisttypeid((int)$checkListItem['ChecklistTypeID']);
                $taskToCheckListItems->setChecklistitem($checkListResponse[0]['ChecklistItem']);
                $taskToCheckListItems->setDescription($checkListResponse[0]['Description']);
                $taskToCheckListItems->setImage($checkListResponse[0]['Image']);
                $taskToCheckListItems->setEnteredvalue($value);
                $taskToCheckListItems->setImageuploaded('');
                $taskToCheckListItems->setShowonownerreport($checkListResponse[0]['ShowOnOwnerReport']);
                $taskToCheckListItems->setChecked(0);
                $taskToCheckListItems->setSortorder($checkListResponse[0]['SortOrder']);
                $this->entityManager->persist($taskToCheckListItems);
            }
            $this->entityManager->flush();
        }
        // Checked/See Notes/NA grid
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->find($input['TaskToChecklistItemID']);
            if ($option === 7 || $option === 10) {
                $taskToCheckListItem->setOptionselected($input['OptionSelected']);
            } else {
                $taskToCheckListItem->setEnteredvalueamount($input['EnteredValueAmount']);
            }

            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
    }
}