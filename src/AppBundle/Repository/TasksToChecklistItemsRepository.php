<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 6/5/20
 * Time: 11:57 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TasksToChecklistItemsRepository extends EntityRepository
{
    public function GetCheckListItemsForManageTab($taskID,$checkListItemID)
    {
        return $this->createQueryBuilder('c')
            ->select('c.enteredvalueamount AS EnteredValueAmount,c.columnvalue AS ColumnValue,c.tasktochecklistitemid AS TaskToChecklistItemID,c.optionselected AS OptionSelected,c.imageuploaded AS ImageUploaded,c.enteredvalue AS EnteredValue,c.checked AS Checked,cli.checklistitemid AS ChecklistItemID')
            ->leftJoin('c.checklistitemid','cli')
            ->where('c.taskid='.$taskID)
            ->andWhere('c.checklistitemid='.$checkListItemID)
            ->getQuery()
            ->execute();
    }
}