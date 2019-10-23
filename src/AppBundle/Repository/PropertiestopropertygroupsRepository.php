<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 23/10/19
 * Time: 12:47 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PropertiestopropertygroupsRepository extends EntityRepository
{
    public function PropertiestoPropertyGroupsJoinMatched($propertyGroups)
    {
        return $this
            ->createQueryBuilder('pp')
            ->select('IDENTITY(pp.propertyid)')
            ->where('pp.propertygroupid IN (:PropertyGroups)')
            ->setParameter('PropertyGroups', $propertyGroups)
            ->getQuery()
            ->execute();
    }
}