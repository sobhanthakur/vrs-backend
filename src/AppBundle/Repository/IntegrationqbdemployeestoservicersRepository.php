<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/10/19
 * Time: 3:07 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class IntegrationqbdemployeestoservicersRepository
 * @package AppBundle\Repository
 */
class IntegrationqbdemployeestoservicersRepository extends EntityRepository
{

    /**
     * @param $customerID
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function StaffsJoinMatched($customerID, $staffTags, $department, $createDate, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('ies')
            ->select('IDENTITY(ies.servicerid) StaffID, s.name AS StaffName, s.servicerabbreviation as ServicerAbbreviation,IDENTITY(ies.integrationqbdemployeeid) AS IntegrationQBDEmployeeID')
            ->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('ies.integrationqbdemployeeid', 'ic')
            ->innerJoin('ies.servicerid', 's')
            ->setParameter('CustomerID', $customerID);

        $result = $this->TrimMappingResults($result, $staffTags, $department, $createDate);

        $result
            ->orderBy('s.createdate', 'DESC')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @return mixed
     */
    public function CountStaffsJoinMatched($customerID, $staffTags, $department, $createDate)
    {
        $result = $this
            ->createQueryBuilder('ies')
            ->select('count(ies.integrationqbdemployeetoservicerid)')
            ->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('ies.integrationqbdemployeeid', 'ic')
            ->innerJoin('ies.servicerid', 's')
            ->setParameter('CustomerID', $customerID);

        $result = $this->TrimMappingResults($result, $staffTags, $department, $createDate);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @return mixed
     */
    public function TrimMappingResults($result, $staffTags, $department, $createDate)
    {
        if ($staffTags) {
            $result->andWhere('s.servicerid IN (:StaffTag)')
                ->setParameter('StaffTag', $staffTags);
        }
        if ($department) {
            $result->andWhere('s.servicerid IN (:Department)')
                ->setParameter('Department', $department);
        }
        if ($createDate) {
            $result->andWhere('s.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result;
    }
}