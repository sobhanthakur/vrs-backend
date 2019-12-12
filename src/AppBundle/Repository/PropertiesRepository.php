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
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

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
            ->andWhere('p.active=1')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $unmatched
     * @return mixed
     */
    public function PropertiesMap($customerID, $propertyTags, $region, $owner, $createDate, $limit, $offset, $unmatched)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('p')
            ->select('p.propertyid AS PropertyID, IDENTITY(m.integrationqbdcustomerid) AS IntegrationQBDCustomerID,p.propertyname AS PropertyName, p.propertyabbreviation as PropertyAbbreviation, r.region AS RegionName, o.ownername AS OwnerName')
            ->leftJoin('AppBundle:Integrationqbdcustomerstoproperties', 'm', Expr\Join::WITH, 'm.propertyid=p.propertyid')
            ->innerJoin('p.regionid', 'r')
            ->innerJoin('p.ownerid', 'o')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('p.active=1');

        $result = $this->TrimMapProperties($result, $unmatched, $region, $owner, $propertyTags, $createDate);

        $result
            ->setFirstResult(($offset-1)*$limit)
            ->setMaxResults($limit);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $unmatched
     * @return mixed
     */
    public function CountPropertiesMap($customerID, $propertyTags, $region, $owner, $createDate, $unmatched)
    {
        $result = $this
            ->createQueryBuilder('p')
            ->select('count(p.propertyid)')
            ->leftJoin('AppBundle:Integrationqbdcustomerstoproperties', 'm', Expr\Join::WITH, 'm.propertyid=p.propertyid')
            ->innerJoin('p.regionid', 'r')
            ->innerJoin('p.ownerid', 'o')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('p.active=1');

        $result = $this->TrimMapProperties($result, $unmatched, $region, $owner, $propertyTags, $createDate);

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
     * @param $managersToProperties
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

    /**
     * @param QueryBuilder $result
     * @param $unmatched
     * @param $region
     * @param $owner
     * @param $propertyTags
     * @param $createDate
     * @return mixed
     */
    public function TrimMapProperties($result, $unmatched, $region, $owner, $propertyTags, $createDate)
    {
        if($unmatched) {
            $result->andWhere('m.integrationqbdcustomerid IS NULL');
        }

        if ($region) {
            $result->andWhere('r.regiongroupid IN (:Regions)')
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

        return $result;
    }
}