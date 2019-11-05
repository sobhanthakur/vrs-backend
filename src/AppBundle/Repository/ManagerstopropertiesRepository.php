<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 5/11/19
 * Time: 11:18 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ManagerstopropertiesRepository extends EntityRepository
{
    /**
     * @param $servicerID
     * @return mixed
     */
    public function GetPropertyID($servicerID)
    {
        return $this
            ->createQueryBuilder('m')
            ->select('IDENTITY(m.propertyid)')
            ->where('m.managerservicerid= :ServicerID')
            ->setParameter('ServicerID',$servicerID)
            ->getQuery()
            ->execute();
    }
}