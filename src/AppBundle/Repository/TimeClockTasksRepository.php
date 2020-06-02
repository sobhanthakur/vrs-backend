<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 13/3/20
 * Time: 3:06 PM
 */

namespace AppBundle\Repository;

use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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

        if($new) {
            $condition1 = null;
            $condition2 = null;
            $condition3 = null;
            $condition = null;
            if(in_array(GeneralConstants::APPROVED,$new)) {
                $condition1 = 'b1.status=1';
                $condition = $condition1;
            }
            if(in_array(GeneralConstants::EXCLUDED,$new)) {
                $condition2 = $condition1 ? ' OR b1.status=0' : 'b1.status=0';
                $condition .= $condition2;
            }
            if(in_array(GeneralConstants::NEW,$new)) {
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

        if(!empty($timezones)) {
            $size = count($timezones);
            $query = "t1.clockin>='".$timezones[0]->format('Y-m-d')." ".$timezones[0]->format('H:i:s')."'";
            for ($i=1;$i<$size;$i++) {
                $query .= " OR t1.clockin>='".$timezones[$i]->format('Y-m-d')." ".$timezones[$i]->format('H:i:s')."'";
            }
            $result->andWhere($query);
        }

        if(!empty($completedDate)) {
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