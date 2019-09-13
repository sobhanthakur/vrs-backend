<?php
/**
 * Created by Sobhan Thakur.
 * Date: 13/9/19
 * Time: 6:18 PM
 */

namespace AppBundle\Repository;


class RegionGroupsRepository extends \Doctrine\ORM\EntityRepository
{
    public function GetRegionGroupsRestrictions($customerID)
    {
        $regionGroupRestrictions = $this
            ->createQueryBuilder('r')
            ->select('r.regiongroupid')
            ->where('r.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
        return $regionGroupRestrictions;
    }
}