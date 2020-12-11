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
use AppBundle\Entity\ChecklistItemImages;
use AppBundle\Entity\Tasks;
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
    public function SaveManageDetails($servicerID, $content, $complete=null)
    {
        try {
            $taskID = $content['TaskID'];
            $checkListItems = $content['CheckListDetails'];
            $rsThisTask = $this->entityManager->getRepository('AppBundle:Tasks')->TaskToSaveManage($taskID, $servicerID);

            if (!empty($rsThisTask)) {
                // STORE THE ISSUE TO THE  TASK TO MAKE SURE THE NOTIFICATION CAN GRAB THE INFO
                /*
                 * TaskNote = servicernotes
                 * Note To Owner: = To Owner Note
                 */
                $task = $this->entityManager->getRepository('AppBundle:Tasks')->find($taskID);
                if (!$task) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
                }
                $task->setServicernotes(trim(substr($content['TaskNote'], 0, 5000)));
                if (array_key_exists('NoteToOwner', $content) && $content['NoteToOwner'] !== '') {
                    $task->setToownernote(trim(substr($content['NoteToOwner'], 0, 5000)));
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
                                $this->ProcessMultipleImageUpload($inputs,(int)$checkListItem['ChecklistItemID']);
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
            $taskToCheckListItem = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->find((int)$input['TaskToChecklistItemID']);
            if ($option === 7 || $option === 10) {
                $taskToCheckListItem->setOptionselected((int)$input['OptionSelected']);
            } else {
                $taskToCheckListItem->setEnteredvalueamount($input['EnteredValueAmount']);
            }

            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
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
            $taskToCheckListItem = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->find((int)$input['TaskToChecklistItemID']);
            $taskToCheckListItem->setChecked((int)$input['Checked'] === 1 ? true : false);
            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
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
            $taskToCheckListItem = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->find((int)$input['TaskToChecklistItemID']);
            if (array_key_exists('OptionSelected',$input) && $input['OptionSelected'] === 'Other') {
                $taskToCheckListItem->setEnteredvalue($input['EnteredValue']);
            }
            $taskToCheckListItem->setOptionselected($input['OptionSelected']);
            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
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
            $taskToCheckListItem = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->find((int)$input['TaskToChecklistItemID']);
            $taskToCheckListItem->setEnteredvalue($input['EnteredValue']);
            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
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
            $taskToCheckListItem = $this->entityManager->getRepository('AppBundle:Taskstochecklistitems')->find((int)$input['TaskToChecklistItemID']);
            $taskToCheckListItem->setImageuploaded($input['ImageUploaded']);
            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
    }

    /**
     * @param $inputs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ProcessMultipleImageUpload($inputs,$checklistItemID)
    {
        foreach ($inputs as $input) {
            // Update the record
            $taskToCheckListItem = new ChecklistItemImages();
            $taskToCheckListItem->setUploadedimage($input['ImageUploaded']);
            $taskToCheckListItem->setChecklistitemid($checklistItemID);
            $this->entityManager->persist($taskToCheckListItem);
        }
        $this->entityManager->flush();
    }
}