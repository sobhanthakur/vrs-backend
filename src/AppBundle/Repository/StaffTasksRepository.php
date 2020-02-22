<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 13/2/20
 * Time: 3:47 PM
 */

namespace AppBundle\Repository;

use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;


/**
 * Class StaffTasksRepository
 * @package AppBundle\Repository
 */
class StaffTasksRepository extends EntityRepository
{
    /**
     * Function to fetch staff task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchStaffTasks($customerDetails, $queryParameter, $staffTaskID, $offset, $query, $limit = null, $ids = array())
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('st');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('st.' . $field);
            }
        }

        //condition to filter by staff tasks id
        if ($staffTaskID) {
            $result->andWhere('st.tasktoservicerid = (:StaffTaskID)')
                ->setParameter('StaffTaskID', $staffTaskID);
        }

        //condition to check for customer specific data
        if ($customerDetails) {
            $result->andWhere('sr.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return task details
        return $result1 = $result
            ->innerJoin('AppBundle:Timeclocktasks', 'tct', Expr\Join::WITH, 'st.servicerid = tct.servicerid')
            ->innerJoin('st.taskid', 't')
            ->innerJoin('st.servicerid', 'sr')
            ->andWhere('st.taskid=tct.taskid')
            ->andWhere('st.taskid IN (:TaskID)')
            ->setParameter('TaskID', $ids)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to fetch all data according to taskid
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $staffTaskID, $offset, $limit, $allIds)
    {

        $query = "";
        $fields = array();

        //Get all task field
        $taskFields = GeneralConstants::STAFF_TASKS_MAPPING;

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : $fields;

        //condition to set query for all or some required fields according to resquest
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $taskFields[$field];
            }
        } else {
            $query .= implode(',', $taskFields);
        }
        $query = trim($query, ',');

        //return task results
        return $this->fetchStaffTasks($customerDetails, $queryParameter, $staffTaskID, $offset, $query, $limit, $allIds);
    }

    /**
     * Function to data according to query
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function getData($customerDetails, $queryParameter, $staffTaskID, $offset, $query, $limit = null)
    {
        $result = $this
            ->createQueryBuilder('st');

        //check for task id in query paramter
        isset($queryParameter['taskid']) ? $taskID = $queryParameter['taskid'] : $taskID = null;

        //check for staff id in query paramter
        isset($queryParameter['staffid']) ? $staffID = $queryParameter['staffid'] : $staffID = null;

        //condition to check for task id data
        if ($taskID) {
            $result->andWhere('st.taskid  = (:TaskID)')
                ->setParameter('TaskID', $taskID);
        }

        //condition to check data form staff id
        if ($staffTaskID) {
            $result->andWhere('st.tasktoservicerid  = (:StaffTaskID)')
                ->setParameter('StaffTaskID', $staffTaskID);
        }

        //setting query
        $result->select($query);

        return $result
            ->innerJoin('AppBundle:Timeclocktasks', 'tct', Expr\Join::WITH, 'st.servicerid = tct.servicerid')
            ->innerJoin('st.servicerid', 'sr')
            ->andWhere('st.taskid=tct.taskid')
            ->innerJoin('st.taskid', 't')
            ->setFirstResult(($offset - 1) * $limit)
            ->andWhere('sr.customerid = (:CustomerID)')
            ->setParameter('CustomerID', $customerDetails)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();

    }

    /**
     * Function to get no. of task of the consumer
     *
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $staffTaskID, $offset)
    {
        $query = 'count(distinct(st.taskid))';
        $result = $this->getData($customerDetails, $queryParameter, $staffTaskID, $offset, $query);
        return $result;
    }

    /**
     * Function to fetch distinct task id
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function getAllTaskID($customerDetails, $queryParameter, $staffTaskID, $offset, $limit)
    {
        $query = 'distinct(st.taskid)';
        $result = $this->getData($customerDetails, $queryParameter, $staffTaskID, $offset, $query, $limit);
        return array_column($result, 1);
    }

}