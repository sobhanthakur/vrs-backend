<?php
/**
 * User: Sobhan Thakur
 * Date: 13/9/19
 * Time: 12:52 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

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
     * @param $customerID
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $unmatched
     * @return mixed
     */
    public function SyncServicers($customerID, $staffTags, $department, $createDate, $limit, $offset, $unmatched)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('s.servicerid AS StaffID, IDENTITY(m.integrationqbdemployeeid) AS IntegrationQBDEmployeeID, s.name AS StaffName, s.servicerabbreviation as ServicerAbbreviation')
            ->leftJoin('AppBundle:Integrationqbdemployeestoservicers', 'm', Expr\Join::WITH, 'm.servicerid=s.servicerid')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.servicertype=0')
            ->andWhere('s.active=1');

        $result = $this->TrimMappingResult($result,$unmatched,$staffTags,$department,$createDate);

        $result
            ->setFirstResult(($offset-1)*$limit)
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
     * @param $unmatched
     * @return mixed
     */
    public function CountSyncServicers($customerID, $staffTags, $department, $createDate,$unmatched)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('count(s.servicerid)')
            ->leftJoin('AppBundle:Integrationqbdemployeestoservicers', 'm', Expr\Join::WITH, 'm.servicerid=s.servicerid')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.servicertype=0')
            ->andWhere('s.active=1');

        $result = $this->TrimMappingResult($result,$unmatched,$staffTags,$department,$createDate);

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
            ->andWhere('s.active=1')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.servicertype=0')
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $staff
     * @return mixed
     */
    public function SearchStaffByID($customerID, $staff)
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.servicerid')
            ->where('s.customerid= :CustomerID')
            ->andWhere('s.active=1')
            ->andWhere('s.servicertype=0')
            ->andWhere('s.servicerid IN (:Staffs)')
            ->setParameter('Staffs',$staff)
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param QueryBuilder $result
     * @param $unmatched
     * @param $staffTags
     * @param $department
     * @param $createDate
     * @return mixed
     */
    public function TrimMappingResult($result, $unmatched, $staffTags, $department, $createDate)
    {
        if($unmatched) {
            $result->andWhere('m.integrationqbdemployeeid IS NULL');
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

        return $result;
    }

    /**
     * @param $servicerid
     * @param $password
     * @return mixed
     */
    public function ValidateAuthentication($servicerid, $password)
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.servicerid AS ServicerID, s.name AS ServicerName, s.timetracking AS TimeTracking, s.timetrackingmileage AS Mileage, s.allowstartearly AS StartEarly, s.allowchangetaskdate AS ChangeDate')
            ->where('s.servicerid= :ServicerID')
            ->andWhere('s.password= :Password')
            ->setParameter('ServicerID',$servicerid)
            ->setParameter('Password', $password)
            ->getQuery()
            ->execute();
    }
}