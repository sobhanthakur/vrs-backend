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
    public function fetchStaffTasks($customerDetails, $queryParameter, $staffTaskID, $offset, $query, $limit = null , $ids = array())
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('st');


        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for property id in query paramter
        isset($queryParameter['propertyid']) ? $propertyID = $queryParameter['propertyid'] : $propertyID = null;

        //check for property id in query paramter
        isset($queryParameter['taskid']) ? $taskID = $queryParameter['taskid'] : $taskID = null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('st.' . $field);
            }
        }

        //condition to filter by staff tasks id
        if ($ids) {
            $result->andWhere('st.taskid IN (:TaskID)')
                ->setParameter('TaskID', $ids);
        }


        //condition to filter by staff tasks id
        if ($staffTaskID) {
            $result->andWhere('st.tasktoservicerid = (:StaffTaskID)')
                ->setParameter('StaffTaskID', $staffTaskID);
        }

        //condition to filter by staff tasks id
        /*if ($taskID) {
            $result->andWhere('st.taskid = (:TaskID)')
                ->setParameter('TaskID', $taskID);
        }*/

        //condition to check for customer specific data
        if ($customerDetails) {
            $result->andWhere('sr.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return task details
        return $result1= $result
            ->innerJoin('AppBundle:Timeclocktasks', 'tct', Expr\Join::WITH, 'st.taskid = tct.taskid')
            ->innerJoin('st.taskid', 't')
            ->innerJoin('st.servicerid', 'sr')
            //->groupBy('st.tasktoservicerid, t.taskid, sr.servicerid')
            //->setFirstResult(($offset - 1) * $limit)
            //->setMaxResults($limit)
            ->getQuery()
            ->execute();



        /*const STAFF_TASKS_MAPPING = [
            'tasktoservicerid' => 'st.tasktoservicerid as StaffTaskID',
            'taskid' => 't.taskid as TaskID',
            'servicerid' => 'sr.servicerid as StaffID',
            'paytype' => 'st.paytype as PayType',
            'payrate' => 'st.payrate as PayRate',
            'piecepay' => 'st.piecepay as PiecePay',
            'TimeTracked' => 'tct.clockout - tct.clockin as TimeTracked',
            'Pay' => '\'\' as Pay',
            'approved' => 'CASE WHEN st.piecepaystatus != 0 THEN 1 ELSE 0 END as Approved',
            'servicerPayRate' => 'st.payrate as ServicerPayRate',
        ];*/
    }

    /**
     * Function to fetch task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $taskID, $offset, $limit, $allIds)
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
       $result1 = $this->fetchStaffTasks($customerDetails, $queryParameter, $taskID, $offset, $query, $limit, $allIds);
       return $result1;

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
        $query = "count(st.tasktoservicerid)";
        return $this->fetchStaffTasks($customerDetails, $queryParameter, $taskID, $offset, $query);

    }

   /* public function getAllID(){
        $result = $this
            ->createQueryBuilder('st')
            ->select('distinct(t.taskid)')
            ->innerJoin('AppBundle:Timeclocktasks', 'tct', Expr\Join::WITH, 'st.taskid = tct.taskid')
            ->innerJoin('st.taskid', 't')
            ->setFirstResult(0)
            ->setMaxResults(20)
            ->getQuery()
            ->getArrayResult();
        dump( array_column($result, 1)); die();
    }*/

    public function getAllTaskID($customerDetails, $queryParameter, $taskID, $offset, $limit){
        $limit= 100;
        $result = $this
            ->createQueryBuilder('st')
            ->select('distinct(t.taskid)')
            ->innerJoin('AppBundle:Timeclocktasks', 'tct', Expr\Join::WITH, 'st.taskid = tct.taskid')
            ->innerJoin('st.taskid', 't')
            ->innerJoin('st.servicerid', 'sr')
            ->andWhere('sr.customerid IN (:CustomerID)')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter('CustomerID', $customerDetails)
            ->getQuery()
            ->getArrayResult();

        return array_column($result, 1);
    }

}