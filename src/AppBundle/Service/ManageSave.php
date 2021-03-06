<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 25/5/20
 * Time: 11:42 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\DatabaseViews\CheckLists;
use AppBundle\Entity\Tasks;
use AppBundle\Entity\Taskstochecklistitems;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    public function SaveManageDetails($servicerID, $content, $complete=null)
    {
        try {
            $taskID = $content[GeneralConstants::TASK_ID];
            $checkListItems = $content['CheckListDetails'];
            $rsThisTask = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->TaskToSaveManage($taskID, $servicerID);
            if (empty($rsThisTask)) {
                // Throw Error if the Task Does not belong to the Servicer.
                throw new BadRequestHttpException(ErrorConstants::WRONG_LOGIN);
            }

            // STORE THE ISSUE TO THE  TASK TO MAKE SURE THE NOTIFICATION CAN GRAB THE INFO
            /*
             * TaskNote = servicernotes
             * Note To Owner: = To Owner Note
             */
            $task = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($taskID);
            if (!$task) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            // Insert servicer note if exists
            if (array_key_exists('TaskNote', $content) && $content['TaskNote'] !== '') {
                $task->setServicernotes(trim(substr($content['TaskNote'], 0, 5000)));
            }

            if (array_key_exists('NoteToOwner', $content) && $content['NoteToOwner'] !== '') {
                $task->setToownernote(trim(substr($content['NoteToOwner'], 0, 5000)));
            } else {
                $task->setToownernote($task->getDefaulttoownernote());
            }

            // Update Completed Time if the task is completed.
            if ($complete && $complete !== '') {
                $now = new \DateTime($complete);
                $task->setCloseddate($now);
                $task->setCompleteconfirmeddate($now);
                $task->setCompletedbyservicerid($servicerID);
                $task->setCompleted(true);
            }

            // Save and commit Task
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            // Process CheckList items
            foreach ($checkListItems as $checkListItem) {
                $inputs = $checkListItem['Input'];
                if ((int)$checkListItem['ChecklistItemID'] !== 0) {
                    switch ((int)$checkListItem['ChecklistTypeID']) {
                        case 0:
                            // CheckBox
                            $this->ProcessChecked($inputs);
                            break;
                        case 1:
                        case 9:
                            // Radio With Other
                            $this->ProcessEnteredValue($inputs);
                            break;
                        case 2:
                            // Radio buttons
                        case 3:
                            // Dropdown
                        case 8:
                            // Radio With Other
                            $this->ProcessOptionSelected($inputs);
                            break;
                        case 4:
                            // Image Upload
                        case 5:
                            // Image Verification
                        case 13:
                            // Video Upload
                            $this->ProcessImageUpload($inputs);
                            break;
                        case 7:
                            $this->ProcessOption7and10and11($inputs);
                            break;
                        case 10:
                            // ColumnCount
                            $this->ProcessOption7and10and11($inputs, 10);
                            break;
                        case 11:
                            // ColumnCount
                            $this->ProcessOption7and10and11($inputs, 11);
                            break;
                        case 12:
                            // Multiple Image Upload
                        case 14:
                            // Multiple Video Upload
                            if (!$complete) {
                                $this->ProcessMultipleImageUpload($task, $checkListItem);
                            }
                            break;
                        default: break;
                    }
                }
            }

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS
            );
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Saving Manage form ' .
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
    public function ProcessOption7and10and11($inputs, $option = 7)
    {
        // Checked/See Notes/NA grid
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->find((int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]);
            if ($taskToCheckListItem) {
                if ($option === 7 || $option === 10) {
                    $taskToCheckListItem->setOptionselected((int)$input[GeneralConstants::OPTION_SELECTED]);
                } else {
                    $input['EnteredValueAmount'] = ltrim($input['EnteredValueAmount'], '0');
                    if(!preg_match("/[a-z]/i", $input['EnteredValueAmount'])) {
                        $taskToCheckListItem->setEnteredvalueamount(eval('return '.$input['EnteredValueAmount'].';'));
                    } else {
                        $taskToCheckListItem->setEnteredvalueamount(0);
                    }
                }

                $this->entityManager->persist($taskToCheckListItem);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param $inputs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessChecked($inputs)
    {
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->find((int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]);
            if ($taskToCheckListItem) {
                $taskToCheckListItem->setChecked($input[GeneralConstants::CHECKED] && $input[GeneralConstants::CHECKED] !== "" ? (int)$input[GeneralConstants::CHECKED] : 0);
                $this->entityManager->persist($taskToCheckListItem);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param $inputs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessOptionSelected($inputs)
    {
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->find((int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]);
            if ($taskToCheckListItem) {
                if (array_key_exists(GeneralConstants::OPTION_SELECTED,$input) && $input[GeneralConstants::OPTION_SELECTED] === 'Other') {
                    $taskToCheckListItem->setEnteredvalue($input['EnteredValue']);
                }
                $taskToCheckListItem->setOptionselected($input[GeneralConstants::OPTION_SELECTED]);
                $this->entityManager->persist($taskToCheckListItem);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param $inputs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessEnteredValue($inputs)
    {
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->find((int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]);
            if ($taskToCheckListItem) {
                $taskToCheckListItem->setEnteredvalue($input['EnteredValue']);
                $this->entityManager->persist($taskToCheckListItem);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param $inputs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessImageUpload($inputs)
    {
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->find((int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]);
            if ($taskToCheckListItem) {
                $taskToCheckListItem->setImageuploaded($input[GeneralConstants::IMAGE_UPLOADED]);
                $this->entityManager->persist($taskToCheckListItem);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param $inputs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessMultipleImageUpload($task, $checklistDetails)
    {
        foreach ($checklistDetails['Input'] as $input) {
            if ($input[GeneralConstants::TASKTOCHECKLISTITEMID] && (int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]) {
                // Update an entry
                $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->find((int)$input[GeneralConstants::TASKTOCHECKLISTITEMID]);
                $taskToCheckListItem->setImageuploaded($input[GeneralConstants::IMAGE_UPLOADED]);
                $this->entityManager->persist($taskToCheckListItem);
            } else {
                // Create New Entry
                $taskToCheckListItem = new Taskstochecklistitems();
                $taskToCheckListItem->setTaskid($task);
                $taskToCheckListItem->setChecklistitemid($this->entityManager->getRepository('AppBundle:Checklistitems')->find((int)$checklistDetails['ChecklistItemID']));
                $taskToCheckListItem->setOptionid(0);
                $taskToCheckListItem->setOptionselected('');
                $taskToCheckListItem->setChecklisttypeid(array_key_exists('ChecklistTypeID',$checklistDetails) ? (int)$checklistDetails['ChecklistTypeID'] : 12);
                $taskToCheckListItem->setChecklistitem(array_key_exists('ChecklistItem',$checklistDetails) ? $checklistDetails['ChecklistItem'] : '');
                $taskToCheckListItem->setDescription(array_key_exists('Description',$checklistDetails) ? $checklistDetails['Description'] : '');
                $taskToCheckListItem->setImage(array_key_exists('Image',$checklistDetails) ? $checklistDetails['Image'] : '');
                $taskToCheckListItem->setEnteredvalue('');
                $taskToCheckListItem->setShowonownerreport(array_key_exists('ShowOnOwnerReport',$checklistDetails) ? (int)$checklistDetails['ShowOnOwnerReport'] : 0);
                $taskToCheckListItem->setChecked(false);
                $taskToCheckListItem->setSortorder(array_key_exists('SortOrder',$checklistDetails) ? (int)$checklistDetails['SortOrder'] : 0);
                $taskToCheckListItem->setImageuploaded($input[GeneralConstants::IMAGE_UPLOADED]);
                $this->entityManager->persist($taskToCheckListItem);
                $this->entityManager->flush();
            }
        }
        $this->entityManager->flush();
    }

    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function RemoveChecklist($servicerID, $content)
    {
        try {
            $taskID = $content[GeneralConstants::TASK_ID];
            $checkListItem = $content['ChecklistItemID'];
            $imageUploaded = $content[GeneralConstants::IMAGE_UPLOADED];

            // Check if Task ID is valid
            $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->DoesTaskBelongToServicer($servicerID, $taskID);

            $task = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($taskID);
            if (!$task) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
            }

            // Find the TaskToCheckListItemID
            $taskToCheckListItem = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKSTOCHECKLISTITEMID)->findOneBy(array(
                'taskid' => $task,
                'checklistitemid' => $this->entityManager->getRepository('AppBundle:Checklistitems')->find((int)$checkListItem),
                'imageuploaded' => $imageUploaded
            ));

            if ($taskToCheckListItem) {
                $this->entityManager->remove($taskToCheckListItem);
                $this->entityManager->flush();
            }

            return array(
                GeneralConstants::STATUS_CAP => GeneralConstants::SUCCESS
            );
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to Remove Checklist Item Due to: ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}