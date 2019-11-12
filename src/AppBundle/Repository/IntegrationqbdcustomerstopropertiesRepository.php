<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 17/10/19
 * Time: 12:31 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbdcustomerstopropertiesRepository extends EntityRepository
{
    public function PropertiesJoinMatched($customerID,$propertyTags, $region, $owner, $createDate, $limit, $offset)
    {
        $result =  $this
            ->createQueryBuilder('icp')
            ->select('IDENTITY(icp.propertyid) AS PropertyID, p.propertyname AS PropertyName, p.propertyabbreviation AS PropertyAbbreviation, r.region AS RegionName, o.ownername AS OwnerName, IDENTITY(icp.integrationqbdcustomerid) AS IntegrationQBDCustomerID')
            ->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('icp.integrationqbdcustomerid','ic')
            ->innerJoin('icp.propertyid','p')
            ->innerJoin('p.regionid','r')
            ->innerJoin('p.ownerid','o')
            ->setParameter('CustomerID', $customerID);

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

    public function CountPropertiesJoinMatched($customerID,$propertyTags, $region, $owner, $createDate)
    {
        $result =  $this
            ->createQueryBuilder('icp')
            ->select('count(icp.propertyid)')
            ->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('icp.integrationqbdcustomerid','ic')
            ->innerJoin('icp.propertyid','p')
            ->innerJoin('p.regionid','r')
            ->innerJoin('p.ownerid','o')
            ->setParameter('CustomerID', $customerID);

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
}