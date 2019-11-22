<?php
/**
 * User: Sobhan Thakur
 * Date: 13/9/19
 * Time: 12:52 PM
 */

namespace AppBundle\Repository;

/**
 * Class ServicersRepository
 * @package AppBundle\Repository
 */
/**
 * Class ServicersRepository
 * @package AppBundle\Repository
 */
class ServicersRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $staffID
     * @return mixed
     */
    public function GetRestrictions($staffID)
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.name,s.password2,s.allowadminaccess, s.alloweditbookings,s.allowtracking, s.allowmanage, s.allowreports, s.allowsetupaccess, s.allowaccountaccess, s.allowissuesaccess, s.allowquickreports, s.allowscheduleaccess, s.allowmastercalendar')
            ->where('s.servicerid= :StaffID')
            ->setParameter('StaffID', $staffID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @return mixed
     * check time tracking if customer is active
     */
    public function GetTimeTrackingRestrictions($customerID)
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.timetracking')
            ->where('s.customerid= :CustomerID')
            ->andWhere('s.active=1')
            ->setParameter('CustomerID', $customerID)
            ->addOrderBy('s.timetracking','DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $employeeToServicers
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $customerID
     * @param $matchStatus
     * @return mixed
     */
    public function SyncServicers($customerID,$staffTags, $department, $createDate, $limit, $offset)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('s.servicerid AS StaffID, s.name AS StaffName, s.servicerabbreviation as ServicerAbbreviation')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.active=1');

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.servicerid) from AppBundle:Integrationqbdemployeestoservicers b1 inner join AppBundle:Integrationqbdemployees t2 with b1.integrationqbdemployeeid=t2.integrationqbdemployeeid where t2.customerid='.$customerID)
            ->getArrayResult();
        if($subQuery) {
            $result->andWhere('s.servicerid NOT IN (:Subquery)')
                ->setParameter('Subquery',$subQuery);
        }

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
        $result
            ->orderBy('s.createdate','DESC')
            ->setFirstResult(($offset-1)*$limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $employeeToServicers
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $customerID
     * @param $matchStatus
     * @return mixed
     */
    public function CountSyncServicers($customerID,$staffTags, $department, $createDate)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('count(s.servicerid)')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.active=1');

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.servicerid) from AppBundle:Integrationqbdemployeestoservicers b1 inner join AppBundle:Integrationqbdemployees t2 with b1.integrationqbdemployeeid=t2.integrationqbdemployeeid where t2.customerid='.$customerID)
            ->getArrayResult();
        if($subQuery) {
            $result->andWhere('s.servicerid NOT IN (:Subquery)')
                ->setParameter('Subquery',$subQuery);
        }

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
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function StaffFilter($customerID)
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.servicerid as StaffID, s.name as StaffName')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}