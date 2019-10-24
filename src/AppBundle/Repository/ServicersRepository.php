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
            ->select('s.allowadminaccess, s.allowtracking, s.allowmanage, s.allowreports, s.allowsetupaccess, s.allowaccountaccess, s.allowissuesaccess, s.allowquickreports, s.allowscheduleaccess, s.allowmastercalendar')
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

    public function SyncServicers($employeeToServicers, $staffTags, $department, $createDate, $limit, $offset, $customerID, $matchStatus)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('s.servicerid AS StaffID, s.name AS StaffName, s.servicerabbreviation as ServicerAbbreviation')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.active=1');

        switch ($matchStatus) {
            case 0:
                $result->andWhere('s.servicerid NOT IN (:EmployeeToServicers)')
                    ->setParameter('EmployeeToServicers', $employeeToServicers);
                break;
            case 1:
                $result->andWhere('s.servicerid IN (:EmployeeToServicers)')
                    ->setParameter('EmployeeToServicers', $employeeToServicers);
                break;
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
}