<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 17/10/19
 * Time: 12:31 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class IntegrationqbdcustomerstopropertiesRepository
 * @package AppBundle\Repository
 */
class IntegrationqbdcustomerstopropertiesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @param $propertyTags
     * @param $region
     * @param $owner
     * @param $createDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function PropertiesJoinMatched($customerID, $propertyTags, $region, $owner, $createDate, $limit, $offset)
    {
        $result =  $this
            ->createQueryBuilder('icp')
            ->select('IDENTITY(icp.propertyid) AS PropertyID, p.propertyname AS PropertyName, p.propertyabbreviation AS PropertyAbbreviation, r.region AS RegionName, o.ownername AS OwnerName, IDENTITY(icp.integrationqbdcustomerid) AS IntegrationQBDCustomerID');

        $result = $this->TrimResult($result,$region,$owner,$propertyTags,$createDate,$customerID);

        $result
            ->orderBy('p.createdate','DESC')
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
     * @return mixed
     */
    public function CountPropertiesJoinMatched($customerID, $propertyTags, $region, $owner, $createDate)
    {
        $result =  $this
            ->createQueryBuilder('icp')
            ->select('count(icp.propertyid)');

        $result = $this->TrimResult($result,$region,$owner,$propertyTags,$createDate,$customerID);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $region
     * @param $owner
     * @param $propertyTags
     * @param $createDate
     * @param $customerID
     * @return mixed
     */
    public function TrimResult($result, $region, $owner, $propertyTags, $createDate, $customerID)
    {
        $result->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('icp.integrationqbdcustomerid','ic')
            ->innerJoin('icp.propertyid','p')
            ->innerJoin('p.regionid','r')
            ->innerJoin('p.ownerid','o')
            ->setParameter('CustomerID', $customerID);
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

    public function DeleteCustomersToProperties($customerID)
    {
        $result = $this
            ->getEntityManager()->createQuery(
             'DELETE c AppBundle:Integrationqbdcustomerstoproperties c INNER JOIN AppBundle:Integrationqbdcustomers ic ON c.integrationqbdcustomerid=ic.integrationqbdcustomerid WHERE ic.customerid=1'
            );
        print_r($result->getSQL());die();

    }
}