<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 29/10/19
 * Time: 12:00 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class IntegrationqbditemstoservicesRepository
 * @package AppBundle\Repository
 */
class IntegrationqbditemstoservicesRepository extends EntityRepository
{

    /**
     * @param $customerID
     * @param $department
     * @param $billable
     * @param $createDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function ServicesJoinMatched($customerID, $department, $billable, $createDate, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('iis')
            ->select('IDENTITY(iis.serviceid) AS TaskRuleID, s.servicename as TaskRuleName,IDENTITY(iis.integrationqbditemid) AS IntegrationQBDItemID')
            ->innerJoin('iis.integrationqbditemid', 'i')
            ->innerJoin('iis.serviceid', 's')
            ->where('i.customerid= :CustomerID')
            ->andWhere('i.active=1')
            ->setParameter('CustomerID', $customerID);

        if ($department) {
            $result->andWhere('s.servicegroupid IN (:Departments)')
                ->setParameter('Regions', $department);
        }
        if ($billable) {
            $result->andWhere('s.billable= :Owners')
                ->setParameter('Owners', $billable);
        }
        if ($createDate) {
            $result->andWhere('s.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        $result
            ->orderBy('s.createdate', 'DESC')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);

        return $result->getQuery()->execute();
    }

    /**
     * @param $customerID
     * @param $department
     * @param $billable
     * @param $createDate
     * @return mixed
     */
    public function CountServicesJoinMatched($customerID, $department, $billable, $createDate)
    {
        $result = $this
            ->createQueryBuilder('iis')
            ->select('count(IDENTITY(iis.serviceid))')
            ->innerJoin('iis.integrationqbditemid', 'i')
            ->innerJoin('iis.serviceid', 's')
            ->where('i.customerid= :CustomerID')
            ->andWhere('i.active=1')
            ->setParameter('CustomerID', $customerID);

        if ($department) {
            $result->andWhere('s.servicegroupid IN (:Departments)')
                ->setParameter('Regions', $department);
        }
        if ($billable) {
            $result->andWhere('s.billable= :Owners')
                ->setParameter('Owners', $billable);
        }
        if ($createDate) {
            $result->andWhere('s.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result->getQuery()->execute();
    }
}