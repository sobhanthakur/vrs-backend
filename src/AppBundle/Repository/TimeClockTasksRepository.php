<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/2/20
 * Time: 2:55 PM
 */

namespace AppBundle\Repository;

use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;

/**
 * Class TimeClockTasksRepository
 * @package AppBundle\Repository
 */
class TimeClockTasksRepository extends EntityRepository
{

    /**
     * Function to fetch task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskTimeID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchTimeClockTasks($customerDetails, $queryParameter, $staffTaskTimeID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('tct');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for staffid option in query paramter
        isset($queryParameter['staffid']) ? $staffID = $queryParameter['staffid'] : $staffID = null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('t.' . $field);
            }
        }

        //condition to check for customer specific data
        if ($customerDetails) {
            $result->andWhere('sr.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to check for staff id data
        if ($staffID) {
            $result->andWhere('sr.servicerid IN (:StaffID)')
                ->setParameter('StaffID', $staffID);
        }

        //return staff task times details
        return $result
            ->innerJoin('tct.servicerid', 'sr')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to fetch task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskTimeID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $staffTaskTimeID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all task field
        $taskFields = GeneralConstants::STAFF_TASKS_TIMES_MAPPING;

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
        return $this->fetchTimeClockTasks($customerDetails, $queryParameter, $staffTaskTimeID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of task of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $staffTaskTimeID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $staffTaskTimeID, $offset)
    {
        $query = "count(tct.timeclocktaskid)";
        return $this->fetchTimeClockTasks($customerDetails, $queryParameter, $staffTaskTimeID, $offset, $query);

    }

}