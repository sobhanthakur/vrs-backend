<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/2/20
 * Time: 2:55 PM
 */

namespace AppBundle\Repository;

use AppBundle\Constants\GeneralConstants;
use AppBundle\CustomClasses\TimeZoneConverter;
use AppBundle\DatabaseViews\TimeClockTasks;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TimeClockTasksRepository
 * @package AppBundle\Repository
 */
class TimeClockTasksRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @param $staff
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $limit
     * @param $offset
     * @param $new
     * @return mixed
     */
    public function MapTimeClockTasks($customerID, $staff, $completedDate, $timezones,$new)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('b1.integrationqbdtimetrackingrecords AS IntegrationQBDTimeTrackingRecordID,IDENTITY(b1.drivetimeclocktaskid) AS DriveTimeClockTaskID,serviceid.servicename AS ServiceName,taskid.taskname AS TaskName,propertyid.propertyname AS PropertyName,t1.timeclocktaskid as TimeClockTasksID,b1.status AS Status,b1.day As Date,b1.timetrackedseconds AS TimeTracked, s2.name AS StaffName,t2.region AS TimeZoneRegion, t1.clockin AS ClockIn, t1.clockout AS ClockOut');
        $result = $this->TrimMapTimeClockTasks($result, $completedDate,$timezones, $new, $staff,$customerID);

        return $result->getQuery()->getSQL();

    }

    /**
     * @param QueryBuilder $result
     * @param $completedDate
     * @param $timezones
     * @param $new
     * @param $staff
     * @param $createDate
     * @return mixed
     */
    public function TrimMapTimeClockTasks($result, $completedDate, $timezones, $new, $staff,$customerID)
    {
        $result
            ->leftJoin('AppBundle:Integrationqbdtimetrackingrecords', 'b1', Expr\Join::WITH, 'b1.timeclocktasksid=t1.timeclocktaskid')
            ->innerJoin('t1.servicerid', 's2')
            ->innerJoin('s2.timezoneid','t2')
            ->where('s2.customerid='.$customerID)
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('s2.servicertype=0')
            ->andWhere('b1.sentstatus IS NULL OR b1.sentstatus=0');
        $result
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','e1',Expr\Join::WITH, 'e1.servicerid=t1.servicerid')
            ->innerJoin('AppBundle:Integrationstocustomers','e2',Expr\Join::WITH, 'e2.customerid=s2.customerid')
            ->innerJoin('t1.taskid','taskid')
            ->leftJoin('AppBundle:Services','serviceid',Expr\Join::WITH, 'taskid.serviceid=serviceid.serviceid')
            ->leftJoin('taskid.propertyid','propertyid')
            ->andWhere('propertyid.customerid='.$customerID)
            ->andWhere('t1.clockin IS NOT NULL')
            ->andWhere('t1.clockout IS NOT NULL');

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


        if ($staff) {
            $condition = 's2.servicerid IN (';
            $i=0;
            for (;$i<count($staff)-1;$i++) {
                $condition .= $staff[$i].',';
            }
            $condition .= $staff[$i].')';
            $result->andWhere($condition);
        }

        if (!empty($timezones)) {
            $size = count($timezones);
            $query = "t1.clockin>='".$timezones[0]->format('Y-m-d')." ".$timezones[0]->format('H:i:s')."'";
            for ($i=1;$i<$size;$i++) {
                $query .= " OR t1.clockin>='".$timezones[$i]->format('Y-m-d')." ".$timezones[$i]->format('H:i:s')."'";
            }
            $result->andWhere($query);
        }

        if (!empty($completedDate)) {
            $size = count($completedDate);
            $query = "t1.clockin>='".$completedDate[0]['From']->format('Y-m-d')." ".$completedDate[0]['From']->format('H:i:s')."' AND t1.clockin<='".$completedDate[0]['To']->format('Y-m-d')." ".$completedDate[0]['To']->format('H:i:s')."'";
            for ($i=1;$i<$size;$i++) {
                $query .= " OR t1.clockin>='".$completedDate[$i]['From']->format('Y-m-d')." ".$completedDate[$i]['From']->format('H:i:s')."' t1.clockin<='".$completedDate[$i]['To']->format('Y-m-d')." ".$completedDate[$i]['To']->format('H:i:s')."'";
            }
            $result->andWhere($query);
        }

        return $result;
    }
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

        //check for  option in query paramter
        isset($queryParameter['startdate']) ? $startDate = $queryParameter['startdate'] : $startDate = null;

        //check for  option in query paramter
        isset($queryParameter['enddate']) ? $endDate =  $queryParameter['enddate']: $endDate = null;

        //check for staffid option in query paramter
        isset($queryParameter['staffid']) ? $staffID = $queryParameter['staffid'] : $staffID = null;

        //check for taskid option in query paramter
        isset($queryParameter['taskid']) ? $taskID = $queryParameter['taskid'] : $taskID = null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('tct.' . $field);
            }
        }

        //condition to check for customer specific data
        if ($customerDetails) {
            $result->andWhere('sr.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }


        //condition to check for task id data
        if ($taskID) {
            $result->andWhere('t.taskid IN (:TaskID)')
                ->setParameter('TaskID', $taskID);
        }

        //condition to check for staff id data
        if ($staffID) {
            $result->andWhere('sr.servicerid IN (:StaffID)')
                ->setParameter('StaffID', $staffID);
        }

        //condition to check for data after this date
        if (isset($startDate)) {
            $startDate = date("Y-m-d", strtotime($startDate));
            $result->andWhere('tct.clockin >= (:StartDate)')
                ->setParameter('StartDate', $startDate);
        }

        if (isset($endDate)) {
            $endDate = date("Y-m-d", strtotime($endDate . ' +1 day'));
            $result->andWhere('tct.clockout <= (:EndDate)')
                ->setParameter('EndDate', $endDate);
        }

        //return staff task times details
        return $result
            ->leftJoin('tct.servicerid', 'sr')
            ->leftJoin('tct.taskid', 't')
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

    /**
     * @param $servicerID
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function CheckOtherStartedTasks($servicerID,$region,$dateTime = null)
    {
        if (!$dateTime) {
            $dateTime = 'now';
        }
        $timeClockTasks = null;
        $timeZone = new \DateTimeZone($region);

        $today = (new \DateTime($dateTime))->setTimezone($timeZone)->setTime(0,0,0)->setTimezone(new \DateTimeZone('UTC'));
        $todayEOD = (new \DateTime($dateTime))->setTimezone($timeZone)->modify('+1 day')->setTime(0,0,0)->setTimezone(new \DateTimeZone('UTC'));

//        $today = (new \DateTime($dateTime))->setTime(0,0,0);
//        $todayEOD = (new \DateTime($dateTime))->modify('+1 day')->setTime(0,0,0);

//        $result = $this->getEntityManager()->getConnection()->prepare("SELECT TOP 1 TimeClockTaskID,ClockIn,ClockOut,TaskID,TimeZoneRegion FROM (".TimeClockTasks::vTimeClockTasks.") AS tct where tct.ClockOut IS NULL AND tct.ServicerID=".$servicerID." AND tct.ClockIn>='".$today->format('Y-m-d H:i:s')."' AND tct.ClockIn<='".$todayEOD->format('Y-m-d H:i:s')."'");
        return $this->createQueryBuilder('tct')
            ->select('IDENTITY(tct.taskid) AS TaskID,tct.clockout AS ClockOut,tct.clockin AS ClockIn,tct.timeclocktaskid AS TimeClockTaskID')
            ->where('tct.clockout IS NULL')
            ->andWhere('tct.servicerid='.(int)$servicerID)
            ->andWhere('tct.clockin >= :ClockIn')
            ->setParameter('ClockIn',$today)
            ->andWhere('tct.clockout <= :ClockOut')
            ->setParameter('ClockOut',$todayEOD)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

//        $result->execute();
//        return $result->fetchAll();
    }

    /**
     * @param $customerID
     * @param $startDate
     * @return mixed
     */
    public function TimeClockTasksForDriveTime($customerID, $startDate)
    {
        return $this
            ->createQueryBuilder('t1')
            ->select('t1.timeclocktaskid AS TimeClockTaskID,t1.clockin AS ClockIn, t1.clockout AS ClockOut, s2.servicerid AS ServicerID')
            ->innerJoin('t1.servicerid', 's2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers', 'e1', Expr\Join::WITH, 'e1.servicerid=t1.servicerid')
            ->innerJoin('t1.taskid', 't2')
            ->where('s2.customerid=' . $customerID)
            ->andWhere('t1.clockin IS NOT NULL')
            ->andWhere('t1.clockout IS NOT NULL')
            ->andWhere('t1.clockin >= :StartDate')
            ->setParameter('StartDate', $startDate)
            ->getQuery()
            ->execute();
    }

}