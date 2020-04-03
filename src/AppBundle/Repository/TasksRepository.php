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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
            ->select('DISTINCT(t2.taskid) as TaskID, s2.serviceid AS ServiceID,s2.servicename AS ServiceName,b1.status AS Status,t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate, t.region AS TimeZoneRegion')
            ->innerJoin('AppBundle:Services','s2',Expr\Join::WITH, 't2.serviceid=s2.serviceid');

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

        //check for task rule id in query paramter
        isset($queryParameter['taskruleid']) ? $taskRuleID = $queryParameter['taskruleid'] : $taskRuleID = null;

        //check for task rule id in query paramter
        isset($queryParameter['propertybookingid']) ? $propertyBookingID = $queryParameter['propertybookingid'] : $propertyBookingID = null;

        //check for property id in query paramter
        isset($queryParameter['propertyid']) ? $propertyID = $queryParameter['propertyid'] : $propertyID = null;

        //check for approved in query paramter
        isset($queryParameter['completed']) ? $completed = $queryParameter['completed'] : $completed = null;

        //check for approved in query paramter
        isset($queryParameter['approved']) ? $approved = $queryParameter['approved'] : $approved = null;

        //check for billable in query paramter
        isset($queryParameter['billable']) ? $billable = $queryParameter['billable'] : $billable = null;

        //check for approvedstartdate and approvedenddate in query paramter
        isset($queryParameter['approvedstartdate']) ? $approvedStartDate = $queryParameter['approvedstartdate'] : $approvedStartDate = null;
        isset($queryParameter['approvedenddate']) ? $approvedEndDate = $queryParameter['approvedenddate'] : $approvedEndDate = null;

        //check for completedstartdate and completedenddate in query paramter
        isset($queryParameter['completedstartdate']) ? $completedStartDate = $queryParameter['completedstartdate'] : $completedStartDate = null;
        isset($queryParameter['completedenddate']) ? $completedEndDate = $queryParameter['completedenddate'] : $completedEndDate = null;

        //check for taskstartdate and taskenddate in query paramter
        isset($queryParameter['taskstartdate']) ? $taskStartDate = $queryParameter['taskstartdate'] : $taskStartDate = null;
        isset($queryParameter['taskenddate']) ? $taskEndDate = $queryParameter['taskenddate'] : $taskEndDate = null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('t.' . $field);
            }
        }

        //condition to filter by property booking id
        if (isset($propertyBookingID)) {
            $result->andWhere('t.propertybookingid = (:PropertyBookingId)')
                ->setParameter('PropertyBookingId', $propertyBookingID);
        }

        //condition to filter by  task rule id
        if (isset($taskRuleID)) {
            $result->andWhere('t.serviceid =  (:TaskRuleID)')
                ->setParameter('TaskRuleID', $taskRuleID);
        }

        //condition to filter by  billable
        if (isset($billable)) {
            $result->andWhere('t.billable = (:Billable)')
                ->setParameter('Billable', $billable);
        }

        //condition to filter by task id
        if ($taskID) {
            $result->andWhere('t.taskid IN (:TaskID)')
                ->setParameter('TaskID', $taskID);
        }

        //condition to check for customer specific data
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to check for approved task
        if ($approved) {
            $result->andWhere('t.approved = (:Approved)')
                ->setParameter('Approved', $approved);
        }

        //condition to check for completed task
        if (isset($completed)) {
            if ($completed == 1) {
                $result->andWhere('t.completeconfirmeddate IS NOT NULL');
            } else {
                $result->andWhere('t.completeconfirmeddate IS NULL');
            }
        }

        //condition to filter by  approvedStartDate
        if ($approvedStartDate) {
            $approvedStartDate = date("Y-m-d", strtotime($approvedStartDate));
            $result->andWhere('t.approveddate >= (:ApprovedDate)')
                ->setParameter('ApprovedDate', $approvedStartDate);
        }
        //condition to filter by  approvedEndDate
        if ($approvedEndDate) {
            $approvedEndDate = date("Y-m-d", strtotime($approvedEndDate . ' +1 day'));
            $result->andWhere('t.approveddate <= (:ApprovedEndDate)')
                ->setParameter('ApprovedEndDate', $approvedEndDate);
        }

        //condition to filter by  completedStartDate
        if ($completedStartDate) {
            $completedStartDate = date("Y-m-d", strtotime($completedStartDate));
            $result->andWhere('t.completeconfirmeddate >= (:ConfirmationDate)')
                ->setParameter('ConfirmationDate', $completedStartDate);
        }
        //condition to filter by  $completedEndDate
        if ($completedEndDate) {
            $completedEndDate = date("Y-m-d", strtotime($completedEndDate . ' +1 day'));
            $result->andWhere('t.completeconfirmeddate <= (:ConfirmationDate)')
                ->setParameter('ConfirmationDate', $completedEndDate);
        }

        //condition to filter by taskStartDate
        if ($taskStartDate) {
            $taskStartDate = date("Y-m-d", strtotime($taskStartDate));
            $result->andWhere('t.taskdate >= (:TaskDate)')
                ->setParameter('TaskDate', $taskStartDate);
        }
        //condition to filter by  taskEndDate
        if ($taskEndDate) {
            $taskEndDate = date("Y-m-d", strtotime($taskEndDate));
            $result->andWhere('t.taskdate <= (:TaskEndDate)')
                ->setParameter('TaskEndDate', $taskEndDate);
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
    public function getItems($customerDetails, $queryParameter, $taskID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all task field
        $taskFields = GeneralConstants::TASKS_MAPPING;

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

    /**
     * @param $servicerID
     * @return mixed
     */
    public function FetchTasksForDashboard($servicerID, $servicers)
    {
        $today = (new \DateTime('now'))->format('Y-m-d');
        $result =  $this
            ->createQueryBuilder('t2');

        // Fetch Basic task details
        $result->select('serviceid.servicename AS ServiceName,propertyid.propertyname AS PropertyName,t2.taskdescription AS TaskDescription,t2.taskstarttimeminutes AS TaskStartTimeMinutes,t2.taskcompletebytimeminutes AS TaskCompleteByTimeMinutes,t2.taskcompletebytime AS TaskCompleteByTime,t2.taskstarttime AS TaskStartTime,ts.islead AS IsLead,t2.taskcompletebydate AS TaskCompleteByDate,t2.taskstartdate As TaskStartDate,ts.accepteddate as AcceptedDate,t2.taskid AS TaskID, t2.taskname AS TaskName, r2.region AS Region,r2.color AS RegionColor, p2.lat AS Lat, p2.lon AS Lon,t2.taskdate AS AssignedDate')
            ->leftJoin('t2.propertyid','p2')
            ->leftJoin('p2.regionid','r2')
            ->leftJoin('t2.propertybookingid','pb2')
            ->leftJoin('p2.customerid','c2')
            ->leftJoin('AppBundle:Taskstoservicers','ts',Expr\Join::WITH, 't2.taskid=ts.taskid')
            ->leftJoin('AppBundle:Servicers','s2',Expr\Join::WITH, 'ts.servicerid=s2.servicerid')
            ->leftJoin('t2.propertyid','propertyid')
            ->leftJoin('AppBundle:Services', 'serviceid', Expr\Join::WITH, 't2.serviceid=serviceid.serviceid')
            ->where('s2.servicerid='.$servicerID)
            ->andWhere('p2.active=1')
            ->andWhere('t2.active=1')
            ->andWhere('t2.completeconfirmeddate IS NULL')
            ->andWhere('p2.customerid=s2.customerid')
            ->andWhere('t2.taskdate >= c2.golivedate OR c2.golivedate IS NULL')
            ->andWhere("t2.taskdate < :Today")
            ->setParameter('Today',$today)
            ->orderBy('t2.taskdate','ASC')
        ;

        // If Task Estimates is true then select minimum and maximum time (In Hours)
        if($servicers[0]['ShowTaskEstimates']) {
            $result->addSelect('t2.mintimetocomplete AS Min, t2.maxtimetocomplete AS Max');
        }

        // Fetch Guest Details based on conditions
        if($servicers[0]['IncludeGuestNumbers']) {
            $result->addSelect('pb2.numberofguests AS Number');
        }

        if($servicers[0]['IncludeGuestEmailPhone']) {
            $result->addSelect('pb2.guestemail AS Email,pb2.guestphone AS Phone');
        }

        if($servicers[0]['IncludeGuestName']) {
            $result->addSelect('pb2.guest AS Name');
        }

        return $result->getQuery()
            ->getResult();
    }
}