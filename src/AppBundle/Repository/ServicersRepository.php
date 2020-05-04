<?php
/**
 * User: Sobhan Thakur
 * Date: 13/9/19
 * Time: 12:52 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Constants\GeneralConstants;

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
            ->addOrderBy('s.timetracking', 'DESC')
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
            ->orderBy('s.name','ASC')
            ->andWhere('s.servicertype=0')
            ->andWhere('s.active=1');

        $result = $this->TrimMappingResult($result, $unmatched, $staffTags, $department, $createDate);

        $result
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
     * @param $unmatched
     * @return mixed
     */
    public function CountSyncServicers($customerID, $staffTags, $department, $createDate, $unmatched)
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

        $result = $this->TrimMappingResult($result, $unmatched, $staffTags, $department, $createDate);

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
            ->orderBy('s.name', 'ASC')
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
            ->setParameter('Staffs', $staff)
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
        if ($unmatched) {
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
     * Function to fetch staff details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchStaff($customerDetails, $queryParameter, $staffID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('s');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('s.' . $field);
            }
        }

        //condition to filter by staff id
        if ($staffID) {
            $result->andWhere('s.servicerid IN (:StaffID)')
                ->setParameter('StaffID', $staffID);
        }


        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('s.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return staff details
        return $result
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();


    }

    /**
     * Function to fetch task rules details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $staffID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all task rules field
        $issuesField = GeneralConstants::STAFF_MAPPING;

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : $fields;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $issuesField[$field];
            }
        } else {
            $query .= implode(',', $issuesField);
        }
        $query = trim($query, ',');

        return $this->fetchStaff($customerDetails, $queryParameter, $staffID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of task rules of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $staffID, $offset)
    {
        $query = "count(s.servicerid)";
        return $this->fetchStaff($customerDetails, $queryParameter, $staffID, $offset, $query);

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
            ->select('s.languageid AS Locale,c.phone AS Phone,c.email AS Email,c.customername AS CustomerName,c.businessinfo AS BusinessInfo,s.servicerid AS ServicerID, s.name AS ServicerName, (CASE WHEN s.timetracking=1 THEN 1 ELSE 0 END) AS TimeTracking, (CASE WHEN s.timetrackingmileage=1 THEN 1 ELSE 0 END) AS Mileage, (CASE WHEN s.allowchangetaskdate=1 THEN 1 ELSE 0 END) AS ChangeDate')
            ->innerJoin('s.customerid','c')
            ->where('s.servicerid= :ServicerID')
            ->andWhere('s.password= :Password')
            ->setParameter('ServicerID',$servicerid)
            ->setParameter('Password', $password)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $servicerID
     * @return mixed
     */
    public function ServicerDashboardRestrictions($servicerID)
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s.email AS Email,s.allowadminaccess AS AllowAdminAccess,s.allowstartearly AS AllowStartEarly,s.allowmanage AS AllowManage,s.showissueslog AS ShowIssueLog,t.region AS Region,s.timetracking AS TimeTracking,s.requestaccepttasks AS RequestAcceptTasks, (CASE WHEN s.showtasktimeestimates=1 THEN 1 ELSE 0 END) AS ShowTaskEstimates,(CASE WHEN s.includeguestnumbers=1 THEN 1 ELSE 0 END) AS IncludeGuestNumbers,(CASE WHEN s.includeguestemailphone=1 THEN 1 ELSE 0 END) AS IncludeGuestEmailPhone,(CASE WHEN s.includeguestname=1 THEN 1 ELSE 0 END) AS IncludeGuestName')
            ->where('s.servicerid= :ServicerID')
            ->innerJoin('s.timezoneid','t')
            ->setParameter('ServicerID',$servicerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}