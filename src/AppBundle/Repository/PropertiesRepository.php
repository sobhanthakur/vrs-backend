<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 14/10/19
 * Time: 6:12 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;

/**
 * Class PropertiesRepository
 * @package AppBundle\Repository
 */
class PropertiesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function GetProperties($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid as PropertyID, p.propertyname as PropertyName')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $properties
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $customerID
     * @param $matchStatus
     * @return mixed
     */
    public function SyncProperties($properties, $propertyTags, $region, $owner, $createDate, $limit, $offset, $customerID, $matchStatus)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('p')
            ->select('p.propertyid AS PropertyID, p.propertyname AS PropertyName, p.propertyabbreviation as PropertyAbbreviation, r.region AS RegionName, o.ownername AS OwnerName')
            ->innerJoin('p.regionid', 'r')
            ->innerJoin('p.ownerid', 'o')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('p.active=1');

        switch ($matchStatus) {
            case 0:
                $result->andWhere('p.propertyid NOT IN (:Properties)')
                    ->setParameter('Properties', $properties);
                break;
            case 1:
                $result->andWhere('p.propertyid IN (:Properties)')
                    ->setParameter('Properties', $properties);
                break;
        }

        if ($region) {
            $result->andWhere('p.regionid IN (:Regions)')
                ->setParameter('Regions', $region);
        }
        if ($owner) {
            $result->andWhere('p.ownerid IN (:Owners)')
                ->setParameter('Owners', $owner);
        }
        if ($propertyTags) {
            $result->andWhere('p.propertyid IN (:PropertyTags)')
                ->setParameter('PropertyTags', $propertyTags);
        }
        if ($createDate) {
            $result->andWhere('p.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        $result
            ->orderBy('p.createdate','DESC')
            ->setFirstResult(($offset-1)*$limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $properties
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $customerID
     * @param $matchStatus
     * @return mixed
     */
    public function CountSyncProperties($properties, $propertyTags, $region, $owner, $createDate, $limit, $offset, $customerID, $matchStatus)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('p')
            ->select('count(p.propertyid) AS Count')
            ->innerJoin('p.regionid', 'r')
            ->innerJoin('p.ownerid', 'o')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('p.active=1');

        switch ($matchStatus) {
            case 0:
                $result->andWhere('p.propertyid NOT IN (:Properties)')
                    ->setParameter('Properties', $properties);
                break;
            case 1:
                $result->andWhere('p.propertyid IN (:Properties)')
                    ->setParameter('Properties', $properties);
                break;
        }

        if ($region) {
            $result->andWhere('p.regionid IN (:Regions)')
                ->setParameter('Regions', $region);
        }
        if ($owner) {
            $result->andWhere('p.ownerid IN (:Owners)')
                ->setParameter('Owners', $owner);
        }
        if ($propertyTags) {
            $result->andWhere('p.propertyid IN (:PropertyTags)')
                ->setParameter('PropertyTags', $propertyTags);
        }
        if ($createDate) {
            $result->andWhere('p.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetPropertiesID($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid')
            ->where('p.customerid= :CustomerID')
            ->andWhere('p.active=1')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $properties
     * @return mixed
     */
    public function SearchPropertiesByID($customerID, $properties)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid')
            ->where('p.customerid= :CustomerID')
            ->andWhere('p.active=1')
            ->andWhere('p.propertyid IN (:Properties)')
            ->setParameter('Properties',$properties)
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $properties
     * @return mixed
     */
    public function GetRegionByID($managersToProperties)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('IDENTITY(p.regionid)')
            ->where('p.active=1')
            ->andWhere('p.propertyid IN (:Properties)')
            ->setParameter('Properties',$managersToProperties)
            ->getQuery()
            ->execute();
    }
}