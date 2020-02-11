<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 12:09 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TasksRepository
 * @package AppBundle\Repository
 */
class TasksRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function GetAllTimeZones($customerID)
    {
        $result = null;

        $result = $this
            ->createQueryBuilder('t2')
            ->select('DISTINCT(t.region) AS Region')
            ->where('t2.active=1')
            ->innerJoin('t2.propertyid', 'p2')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->andWhere('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $properties
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTasks($customerID, $properties, $createDate, $completedDate, $timezones, $limit, $offset, $new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('DISTINCT(t2.taskid) as TaskID, s2.servicename AS ServiceName,b1.status AS Status,t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate, t.region AS TimeZoneRegion')
            ->innerJoin('AppBundle:Services', 's2', Expr\Join::WITH, 't2.serviceid=s2.serviceid');

        $result = $this->TrimMapTasks($result, $new, $properties, $completedDate, $timezones, $createDate, $customerID);

        $result->orderBy('t2.completeconfirmeddate', 'ASC');
        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $properties
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @return mixed
     */
    public function CountMapTasks($customerID, $properties, $createDate, $completedDate, $timezones, $new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('count(DISTINCT(t2.taskid))')
            ->innerJoin('AppBundle:Services', 's2', Expr\Join::WITH, 't2.serviceid=s2.serviceid');

        $result = $this->TrimMapTasks($result, $new, $properties, $completedDate, $timezones, $createDate, $customerID);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $new
     * @param $properties
     * @param $completedDate ,$timezones
     * @param $createDate
     * @param $customerID
     * @return mixed
     */
    public function TrimMapTasks($result, $new, $properties, $completedDate, $timezones, $createDate, $customerID)
    {
        $result
            ->innerJoin('t2.propertyid', 'p2')
            ->leftJoin('AppBundle:Integrationqbdbillingrecords', 'b1', Expr\Join::WITH, 'b1.taskid=t2.taskid')
            ->innerJoin('AppBundle:Integrationqbdcustomerstoproperties', 'e1', Expr\Join::WITH, 'e1.propertyid=p2.propertyid')
            ->innerJoin('AppBundle:Integrationqbditemstoservices', 'e2', Expr\Join::WITH, 'e2.serviceid=s2.serviceid')
            ->where('t2.active=1')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus IS NULL OR b1.sentstatus=0')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->andWhere('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');
        if (!empty($timezones)) {
            $size = count($timezones);

            $query = 't2.completeconfirmeddate >= :TimeZone0';
            $result->setParameter('TimeZone0', $timezones[0]);
            for ($i = 1; $i < $size; $i++) {
                $query .= ' OR t2.completeconfirmeddate >= :TimeZone' . $i;
                $result->setParameter('TimeZone' . $i, $timezones[$i]);
            }
            $result->andWhere($query);
        }

        if (!empty($completedDate)) {
            $size = count($completedDate);
            $query = 't2.completeconfirmeddate BETWEEN :CompletedDateFrom0 AND :CompletedDateTo0';
            $result->setParameter('CompletedDateFrom0', $completedDate[0]['From']);
            $result->setParameter('CompletedDateTo0', $completedDate[0]['To']);
            for ($i = 1; $i < $size; $i++) {
                $query .= ' OR t2.completeconfirmeddate BETWEEN :CompletedDateFrom' . $i . ' AND :CompletedDateTo' . $i;
                $result->setParameter('CompletedDateFrom' . $i, $completedDate[$i]['From']);
                $result->setParameter('CompletedDateTo' . $i, $completedDate[$i]['To']);
            }
            $result->andWhere($query);
        }

        if ($new) {
            $condition1 = null;
            $condition2 = null;
            $condition3 = null;
            $condition = null;
            if (in_array(GeneralConstants::APPROVED, $new)) {
                $condition1 = 'b1.status=1';
                $condition = $condition1;
            }
            if (in_array(GeneralConstants::EXCLUDED, $new)) {
                $condition2 = $condition1 ? ' OR b1.status=0' : 'b1.status=0';
                $condition .= $condition2;
            }
            if (in_array(GeneralConstants::NEW, $new)) {
                $condition3 = $condition1 || $condition2 ? ' OR b1.status IS NULL OR b1.status=2' : 'b1.status IS NULL OR b1.status=2';
                $condition .= $condition3;
            }
            $result->andWhere($condition);
        }

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        if ($createDate) {
            $result->andWhere('t2.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result;
    }

    /**
     * Function to fetch task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchTasks($customerDetails, $queryParameter, $taskID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('t');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            dump('ff');
            die();
            foreach ($sortOrder as $field) {
                $result->orderBy('t.' . $field);
            }
        }

        //condition to filter by task id
        if ($taskID) {
            $result->andWhere('t.taskid IN (:TaskID)')
                ->setParameter('TaskIDpl', $taskID);
        }


        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return task details
         return $result
            ->innerJoin('t.propertybookingid', 'pb')
            ->innerJoin('t.propertyid', 'p')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to fetch task rules details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $taskID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all task field
        $issuesField = GeneralConstants::TASKS_MAPPING;

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

        return $this->fetchTasks($customerDetails, $queryParameter, $taskID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of task of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $taskID, $offset)
    {
        $query = "count(t.taskid)";
        return $this->fetchTasks($customerDetails, $queryParameter, $taskID, $offset, $query);

    }
}