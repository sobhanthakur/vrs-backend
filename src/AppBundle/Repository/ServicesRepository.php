<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 29/10/19
 * Time: 2:40 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;

/**
 * Class ServicesRepository
 * @package AppBundle\Repository
 */
class ServicesRepository extends EntityRepository
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
    public function SyncServices($customerID, $department, $billable, $createDate, $limit, $offset)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('s.serviceid AS TaskRuleID, s.servicename as TaskRuleName')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.active=1');

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.serviceid) from AppBundle:Integrationqbditemstoservices b1 inner join AppBundle:Integrationqbditems t2 with b1.integrationqbditemid=t2.integrationqbditemid where t2.customerid='.$customerID)
            ->getArrayResult();
        if ($subQuery) {
            $result
                ->andWhere('s.serviceid NOT IN (:Subquery)')
                ->setParameter('Subquery',$subQuery
                );
        }

        if ($department) {
            $result->andWhere('s.servicegroupid IN (:Departments)')
                ->setParameter('Departments', $department);
        }
        if ($billable) {
            if(count($billable) === 1 &&
                in_array(GeneralConstants::BILLABLE,$billable)
            ) {
                $result->andWhere('s.billable=1');
            } elseif(count($billable) === 1 &&
                in_array(GeneralConstants::NOT_BILLABLE,$billable)) {
                $result->andWhere('s.billable=0');
            }
        }
        if ($createDate) {
            $result->andWhere('s.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        $result
            ->orderBy('s.createdate','DESC')
            ->setFirstResult(($offset-1)*$limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $department
     * @param $billable
     * @param $createDate
     * @return mixed
     */
    public function CountSyncServices($customerID, $department, $billable, $createDate)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('count(s.serviceid)')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.active=1');

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.serviceid) from AppBundle:Integrationqbditemstoservices b1 inner join AppBundle:Integrationqbditems t2 with b1.integrationqbditemid=t2.integrationqbditemid where t2.customerid='.$customerID)
            ->getArrayResult();
        if ($subQuery) {
            $result
                ->andWhere('s.serviceid NOT IN (:Subquery)')
                ->setParameter('Subquery',$subQuery
                );
        }

        if ($department) {
            $result->andWhere('s.servicegroupid IN (:Departments)')
                ->setParameter('Departments', $department);
        }
        if ($billable) {
            if(count($billable) === 1 &&
                in_array(GeneralConstants::BILLABLE,$billable)
            ) {
                $result->andWhere('s.billable=1');
            } elseif(count($billable) === 1 &&
                in_array(GeneralConstants::NOT_BILLABLE,$billable)) {
                $result->andWhere('s.billable=0');
            }
        }
        if ($createDate) {
            $result->andWhere('s.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        return $result
            ->getQuery()
            ->getResult();
    }
}

