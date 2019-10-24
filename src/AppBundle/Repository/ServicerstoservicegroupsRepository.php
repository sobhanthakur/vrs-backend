<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 24/10/19
 * Time: 4:07 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ServicerstoservicegroupsRepository extends EntityRepository
{
    public function ServicerstoServiceGroupsJoinMatched($department)
    {
        return $this
            ->createQueryBuilder('pp')
            ->select('IDENTITY(pp.servicerid)')
            ->where('pp.servicegroupid IN (:ServiceGroups)')
            ->setParameter('ServiceGroups', $department)
            ->getQuery()
            ->execute();
    }
}