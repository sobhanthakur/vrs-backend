<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 26/5/20
 * Time: 1:14 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class ChecklistitemsRepository
 * @package AppBundle\Repository
 */
class ChecklistitemsRepository extends EntityRepository
{
    /**
     * @param $ChecklistItemID
     * @return mixed
     */
    public function GetOptions($ChecklistItemID)
    {
        return $this->createQueryBuilder('c')
            ->select('c.checklistitem AS CheckListItem,c.sortorder AS SortOrder,c.showonownerreport AS ShowOnOwnerReport,c.description AS Description,c.image AS Image,c.options AS Options')
            ->where('c.checklistitemid='.$ChecklistItemID)
            ->getQuery()
            ->execute();
    }
}