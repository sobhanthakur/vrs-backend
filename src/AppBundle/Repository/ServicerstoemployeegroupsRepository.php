<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 24/10/19
 * Time: 3:52 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ServicerstoemployeegroupsRepository extends EntityRepository
{
    public function ServicerstoEmployeeGroupsJoinMatched($staffTag)
    {
        return $this
            ->createQueryBuilder('pp')
            ->select('IDENTITY(pp.servicerid)')
            ->where('pp.employeegroupid IN (:StaffTag)')
            ->setParameter('StaffTag', $staffTag)
            ->getQuery()
            ->execute();
    }
}