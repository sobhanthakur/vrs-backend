<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 6/5/20
 * Time: 11:57 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class TasksToChecklistItemsRepository
 * @package AppBundle\Repository
 */
class TasksToChecklistItemsRepository extends EntityRepository
{
    /**
     * @param $taskID
     * @param $checkListItemID
     * @return mixed
     */
    public function GetCheckListItemsForManageTab($taskID, $checkListItemID,$limit=null)
    {
        $result = $this->createQueryBuilder('c')
            ->select('c.enteredvalueamount AS EnteredValueAmount,c.columnvalue AS ColumnValue,c.tasktochecklistitemid AS TaskToChecklistItemID,c.optionselected AS OptionSelected,c.imageuploaded AS ImageUploaded,c.enteredvalue AS EnteredValue,(CASE WHEN c.checked=1 THEN 1 ELSE 0 END) AS Checked,cli.checklistitemid AS ChecklistItemID')
            ->leftJoin('c.checklistitemid','cli')
            ->where('c.taskid='.$taskID);
        if ($checkListItemID) {
            $result->andWhere('c.checklistitemid='.$checkListItemID);
        } else {
            $result->andWhere('c.checklistitemid=0');
        }


        if ($limit) {
            $result->setMaxResults($limit);
        }
        return $result->getQuery()
            ->execute();
    }
}