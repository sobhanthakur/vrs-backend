<?php
/**
 * Created by Sobhan Thakur.
 * Date: 13/9/19
 * Time: 6:18 PM
 */

namespace AppBundle\Repository;


class RegionGroupsRepository extends \Doctrine\ORM\EntityRepository
{
    public function GetRegionGroupsRestrictions($customerID,$region)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('r.regiongroupid as RegionGroupID, r.regiongroup as RegionGroup')
            ->where('r.customerid = (:CustomerID)')
            ->andWhere('r.regiongroupid IN (:Regions)')
            ->setParameter('CustomerID',$customerID)
            ->setParameter('Regions',$region)
            ->getQuery()
            ->execute();
    }

    public function GetRegionGroupsFilter($customerID)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('r.regiongroupid as RegionGroupID, r.regiongroup as RegionGroup')
            ->where('r.customerid = (:CustomerID)')
            ->setParameter('CustomerID',$customerID)
            ->getQuery()
            ->execute();
    }
}