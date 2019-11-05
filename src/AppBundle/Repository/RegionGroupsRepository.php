<?php
/**
 * Created by Sobhan Thakur.
 * Date: 13/9/19
 * Time: 6:18 PM
 */

namespace AppBundle\Repository;


class RegionGroupsRepository extends \Doctrine\ORM\EntityRepository
{
    public function GetRegionGroupsRestrictions($customerID, $regions)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('r.regiongroupid as RegionGroupID, r.regiongroup as RegionGroup')
            ->where('r.regiongroupid IN (:Regions)')
            ->setParameter('Regions',$regions)
            ->getQuery()
            ->execute();
    }
}