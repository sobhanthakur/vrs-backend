<?php
/**
 * Created by PhpStorr.
 * User: Sobhan
 * Date: 5/11/19
 * Time: 11:45 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RegionsRepository extends EntityRepository
{
    /**
     * @param $region
     * @param $customerID
     * @return mixed
     */
    public function GetRegionID($region, $customerID)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('IDENTITY(r.regiongroupid)')
            ->where('r.customerid= :CustomerID')
            ->andWhere('r.regionid IN (:Regions)')
            ->setParameter('CustomerID',$customerID)
            ->setParameter('Regions',$region)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetRegionIDLoggedInStaffID0($customerID)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('IDENTITY(r.regiongroupid)')
            ->where('r.customerid= :CustomerID')
            ->setParameter('CustomerID',$customerID)
            ->getQuery()
            ->execute();
    }
}