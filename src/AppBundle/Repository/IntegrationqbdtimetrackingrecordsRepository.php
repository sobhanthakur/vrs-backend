<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/11/19
 * Time: 5:22 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;

/**
 * Class IntegrationqbdtimetrackingrecordsRepository
 * @package AppBundle\Repository
 */
class IntegrationqbdtimetrackingrecordsRepository extends EntityRepository
{
    /**
     * @param $status
     * @param $customerID
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate,$timezones,$limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('IDENTITY(t1.timeclockdaysid) as TimeClockDaysID, t1.status AS Status, p2.name AS StaffName,t1.day AS Date, t1.timetrackedseconds AS TimeTracked')
            ->innerJoin('t1.timeclockdaysid', 't2')
            ->innerJoin('t2.servicerid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t1.txnid IS NULL');

        $result = $this->TrimTimeTrackingFilter($result,$completedDate,$timezones, $staff, $createDate, $status);

        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result->getQuery()->execute();
    }

    /**
     * @param $customerID
     * @param $status
     * @param $staff
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @return mixed
     */
    public function CountMapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate,$timezones)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('count(IDENTITY(t1.timeclockdaysid))')
            ->innerJoin('t1.timeclockdaysid', 't2')
            ->innerJoin('t2.servicerid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t1.txnid IS NULL');

        $result = $this->TrimTimeTrackingFilter($result,$completedDate,$timezones, $staff, $createDate, $status);

        return $result->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function DistinctBatchCountFailed($batchID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbdtimetrackingrecords)')
            ->where('b1.integrationqbbatchid = :BatchID')
            ->andWhere('b1.txnid IS NULL')
            ->setParameter('BatchID',$batchID)
            ->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function DistinctBatchCountSuccess($batchID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbdtimetrackingrecords)')
            ->where('b1.integrationqbbatchid = :BatchID')
            ->andWhere('b1.txnid IS NOT NULL')
            ->setParameter('BatchID',$batchID)
            ->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function CountBatches($batchID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbdtimetrackingrecords)')
            ->where('b1.integrationqbbatchid = :BatchID')
            ->setParameter('BatchID', $batchID)
            ->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function BatchWiseLog($batchID,$limit,$offset,$timeTrackingType=null)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('b1.day AS Date,b1.timetrackedseconds AS TimeTrackedSeconds,s2.name AS Staff,b1.txnid AS TxnID,(CASE WHEN b1.sentstatus=1 AND b1.txnid IS NULL THEN 0 ELSE 1 END) AS Status');
        if ($timeTrackingType) {
            if($timeTrackingType === 2) {
                $result->addSelect('serviceid.servicename AS ServiceName,propertyid.propertyname AS PropertyName,taskid.taskname AS TaskName');
                $result->innerJoin('b1.timeclocktasksid', 't2');
            }
            else {
                $result->addSelect('(CASE WHEN b1.drivetimeclocktaskid IS NOT NULL THEN \'\' ELSE \'\' END) AS PropertyName');
                $result->addSelect('(CASE WHEN b1.drivetimeclocktaskid IS NOT NULL THEN \'\' ELSE \'\' END) AS ServiceNameName');
                $result->addSelect('(CASE WHEN b1.drivetimeclocktaskid IS NOT NULL THEN \'Drive / Load Time\' ELSE \'\' END) AS TaskName');
                $result->innerJoin('b1.drivetimeclocktaskid', 't2');
            }

            $result->innerJoin('t2.taskid', 'taskid')
                ->leftJoin('AppBundle:Services', 'serviceid', Expr\Join::WITH, 'taskid.serviceid=serviceid.serviceid')
                ->leftJoin('taskid.propertyid', 'propertyid');
        } else {
            $result->innerJoin('b1.timeclockdaysid', 't2');
        }

        $result->innerJoin('t2.servicerid', 's2')
            ->where('b1.integrationqbbatchid = :BatchID')
            ->setParameter('BatchID', $batchID)
            ->setFirstResult(($offset - 1) * $limit);

        return $result->setMaxResults($limit)
            ->getQuery()->execute();
    }

    /**
     * @param QueryBuilder $result
     * @param $completedDate,$timezones
     * @param $staff
     * @param $createDate
     * @param $status
     * @return mixed
     */
    public function TrimTimeTrackingFilter($result, $completedDate,$timezones, $staff, $createDate, $status)
    {
        if(!empty($timezones)) {
            $size = count($timezones);

            $query = 't2.clockin >= :TimeZone0';
            $result->setParameter('TimeZone0',$timezones[0]);
            for ($i=1;$i<$size;$i++) {
                $query .= ' OR t2.clockin >= :TimeZone'.$i;
                $result->setParameter('TimeZone'.$i,$timezones[$i]);
            }
            $result->andWhere($query);
        }

        if(!empty($completedDate)) {
            $size = count($completedDate);
            $query = 't2.clockin BETWEEN :CompletedDateFrom0 AND :CompletedDateTo0';
            $result->setParameter('CompletedDateFrom0',$completedDate[0]['From']);
            $result->setParameter('CompletedDateTo0', $completedDate[0]['To']);
            for ($i=1;$i<$size;$i++) {
                $query .= ' OR t2.clockin BETWEEN :CompletedDateFrom'.$i.' AND :CompletedDateTo'.$i;
                $result->setParameter('CompletedDateFrom'.$i,$completedDate[$i]['From']);
                $result->setParameter('CompletedDateTo'.$i, $completedDate[$i]['To']);
            }
            $result->andWhere($query);
        }

        if ($staff) {
            $result->andWhere('p2.servicerid IN (:Servicers)')
                ->setParameter('Servicers', $staff);
        }

        if ((count($status) === 1) && in_array(GeneralConstants::APPROVED, $status)) {
            $result->andWhere('t1.status=1');
        } elseif ((count($status) === 1) && in_array(GeneralConstants::EXCLUDED, $status)) {
            $result->andWhere('t1.status=0');
        }

        return $result;
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetTimeTrackingRecordsToSync($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('ie.isContractor AS IsContractor,b1.day AS Date,SUM(b1.timetrackedseconds) AS TimeTrackedSeconds,IDENTITY(t2.servicerid) AS ServicerID,ie.qbdemployeelistid AS QBDEmployeeListID,AVG(s2.payrate) AS PayRate')
            ->innerJoin('b1.timeclockdaysid','t2')
            ->innerJoin('t2.servicerid','s2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('ies.integrationqbdemployeeid','ie')
            ->groupBy('b1.day,t2.servicerid,ie.qbdemployeelistid,ie.isContractor')
            ->where('b1.status=1')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus=0 OR b1.sentstatus IS NULL')
            ->andWhere('s2.customerid = :CustomerID')
            ->setParameter('CustomerID',$customerID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetUnsycedTimeTrackingBatch($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('b1.integrationqbdtimetrackingrecords')
            ->innerJoin('b1.timeclockdaysid','t2')
            ->innerJoin('t2.servicerid','s2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->where('b1.status=1')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus=0 OR b1.sentstatus IS NULL')
            ->andWhere('s2.customerid = :CustomerID')
            ->setParameter('CustomerID',$customerID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $batchID
     * @param $integrationQBDTimetrackingID
     * @return bool
     */
    public function UpdateTimeTrackingBatches($batchID, $integrationQBDTimetrackingID)
    {
        foreach ($integrationQBDTimetrackingID as $item) {
            $this
                ->getEntityManager()
                ->createQuery('UPDATE AppBundle:Integrationqbdtimetrackingrecords b1 SET b1.integrationqbbatchid='.$batchID.',b1.sentstatus=1 WHERE b1.integrationqbdtimetrackingrecords= :BatchRecordID')
                ->setParameter('BatchRecordID',$item['integrationqbdtimetrackingrecords'])
                ->getArrayResult();
        }
        return true;
    }

    /**
     * @param $batchID
     * @param $txnDate
     * @param $listID
     * @param $txnID
     * @return mixed
     */
    public function UpdateSuccessTxnID($batchID, $txnDate, $listID)
    {
        return $this
            ->createQueryBuilder('b2')
            ->select('b2.integrationqbdtimetrackingrecords')
            ->innerJoin('b2.timeclockdaysid','t2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('ies.integrationqbdemployeeid','ie')
            ->where('b2.integrationqbbatchid= :BatchID')
            ->andWhere('b2.day= :TxnDate')
            ->andWhere('b2.sentstatus=1')
            ->andWhere('ie.qbdemployeelistid= :ListID')
            ->setParameter('BatchID',$batchID)
            ->setParameter('TxnDate',$txnDate)
            ->setParameter('ListID',$listID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetFailedTimeTrackingRecord($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('b1.day,ie.qbdemployeelistid')
            ->innerJoin('b1.timeclockdaysid','t2')
            ->innerJoin('t2.servicerid','s2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('ies.integrationqbdemployeeid','ie')
            ->groupBy('b1.day,t2.servicerid,ie.qbdemployeelistid')
            ->where('b1.status=1')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus=1')
            ->andWhere('s2.customerid = :CustomerID')
            ->setParameter('CustomerID',$customerID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $day
     * @param $listID
     * @return mixed
     */
    public function UpdateFailedRecords($customerID, $day, $listID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('b1.integrationqbdtimetrackingrecords')
            ->innerJoin('b1.timeclockdaysid','t2')
            ->innerJoin('t2.servicerid','s2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('ies.integrationqbdemployeeid','ie')
            ->where('ie.qbdemployeelistid= :ListID')
            ->andWhere('s2.customerid = :CustomerID')
            ->andWhere('b1.day= :Day')
            ->setParameter('CustomerID',$customerID)
            ->setParameter('Day',$day)
            ->setParameter('ListID',$listID)
            ->getQuery()
            ->execute();

    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function ResetTimeTrackingBatch($batchID)
    {
        return $this
            ->getEntityManager()
            ->createQuery('UPDATE AppBundle:Integrationqbdtimetrackingrecords b1 SET b1.sentstatus=NULL,b1.integrationqbbatchid=NULL WHERE b1.txnid IS NULL AND b1.integrationqbbatchid='.$batchID)
            ->getArrayResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function TimeClockTasksForQuickbooksOnline($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('ie.isContractor AS IsContractor,ii.qbditemlistid AS ItemListID,ii.unitprice AS UnitPrice,serviceid.serviceid AS ServiceID,b1.integrationqbdtimetrackingrecords AS IntegrationQBDTimeTrackingRecordID,b1.day AS Date,b1.timetrackedseconds AS TimeTrackedSeconds,IDENTITY(t2.servicerid) AS ServicerID,ie.qbdemployeefullname AS EmployeeName,ie.qbdemployeelistid AS EmployeeValue,ic.qbdcustomerlistid AS CustomerValue,s2.payrate AS PayRate,propertyid.propertyname AS PropertyName,taskid.taskname AS TaskName,serviceid.servicename AS ServiceName, IDENTITY(b1.drivetimeclocktaskid) AS DriveTimeClockTaskID')
            ->innerJoin('b1.timeclocktasksid', 't2')
            ->innerJoin('t2.servicerid', 's2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers', 'ies', Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('t2.taskid', 'taskid')
            ->leftJoin('AppBundle:Services', 'serviceid', Expr\Join::WITH, 'taskid.serviceid=serviceid.serviceid')
            ->innerJoin('taskid.propertyid', 'propertyid')
            ->innerJoin('ies.integrationqbdemployeeid', 'ie')
            ->leftJoin('AppBundle:Integrationqbdcustomerstoproperties', 'icp', Expr\Join::WITH, 'propertyid.propertyid=icp.propertyid')
            ->leftJoin('AppBundle:Integrationqbditemstoservices', 'iis', Expr\Join::WITH, 'iis.serviceid=serviceid.serviceid')
            ->leftJoin('iis.integrationqbditemid','ii')
            ->leftJoin('icp.integrationqbdcustomerid','ic')
            ->where('b1.status=1')
            ->andWhere('iis.laborormaterials=0 OR iis.laborormaterials IS NULL')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus=0 OR b1.sentstatus IS NULL')
            ->andWhere('s2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $staff
     * @param $completedDate
     * @param $timezones
     * @param $new
     * @return mixed
     */
    public function GetDriveTimeRecords($customerID, $staff, $completedDate, $timezones, $new)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('b1.integrationqbdtimetrackingrecords AS IntegrationQBDTimeTrackingRecordID,IDENTITY(b1.drivetimeclocktaskid) AS DriveTimeClockTaskID,serviceid.servicename AS ServiceName,taskid.taskname AS TaskName,propertyid.propertyname AS PropertyName,t1.timeclocktaskid as TimeClockTasksID,b1.status AS Status,b1.day As Date,b1.timetrackedseconds AS TimeTracked, s2.name AS StaffName,t2.region AS TimeZoneRegion, t1.clockin AS ClockIn, t1.clockout AS ClockOut')
            ->leftJoin('b1.drivetimeclocktaskid','t1')
            ->innerJoin('t1.taskid','taskid')
            ->innerJoin('AppBundle:Services','serviceid',Expr\Join::WITH, 'taskid.serviceid=serviceid.serviceid')
            ->innerJoin('taskid.propertyid','propertyid')
            ->innerJoin('t1.servicerid','s2')
            ->innerJoin('s2.timezoneid','t2')
            ->where('s2.customerid='.$customerID)
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('s2.servicertype=0')
            ->andWhere('b1.sentstatus IS NULL OR b1.sentstatus=0')
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
//            $result->andWhere('s2.servicerid IN (:Staffs)')
//                ->setParameter('Staffs', $staff);
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

        return $result->getQuery()->getSQL();

    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function DriveTimeClockTasksForQuickbooksOnline($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('ie.isContractor AS IsContractor,b1.integrationqbdtimetrackingrecords AS IntegrationQBDTimeTrackingRecordID,b1.day AS Date,b1.timetrackedseconds AS TimeTrackedSeconds,IDENTITY(t2.servicerid) AS ServicerID,ie.qbdemployeefullname AS EmployeeName,ie.qbdemployeelistid AS EmployeeValue,ic.qbdcustomerlistid AS CustomerValue,s2.payrate AS PayRate,propertyid.propertyname AS PropertyName,taskid.taskname AS TaskName,serviceid.servicename AS ServiceName, IDENTITY(b1.drivetimeclocktaskid) AS DriveTimeClockTaskID')
            ->innerJoin('b1.drivetimeclocktaskid', 't2')
            ->innerJoin('t2.servicerid', 's2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers', 'ies', Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('t2.taskid', 'taskid')
            ->leftJoin('AppBundle:Services', 'serviceid', Expr\Join::WITH, 'taskid.serviceid=serviceid.serviceid')
            ->innerJoin('taskid.propertyid', 'propertyid')
            ->innerJoin('ies.integrationqbdemployeeid', 'ie')
            ->leftJoin('AppBundle:Integrationqbdcustomerstoproperties', 'icp', Expr\Join::WITH, 'propertyid.propertyid=icp.propertyid')
            ->leftJoin('icp.integrationqbdcustomerid','ic')
            ->where('b1.status=1')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus=0 OR b1.sentstatus IS NULL')
            ->andWhere('s2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $batchID
     * @param $txnDate
     * @param $listID
     * @param $txnID
     * @return mixed
     */
    public function UpdateSuccessTxnIDOnline($txnDate, $listID,$customerID)
    {
        return $this
            ->createQueryBuilder('b2')
            ->select('b2.integrationqbdtimetrackingrecords')
            ->innerJoin('b2.timeclockdaysid','t2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('ies.integrationqbdemployeeid','ie')
            ->where('b2.day= :TxnDate')
            ->andWhere('b2.sentstatus IS NULL OR b2.sentstatus=0')
            ->andWhere('ie.customerid='.$customerID)
            ->andWhere('ie.qbdemployeelistid= :ListID')
            ->setParameter('TxnDate',$txnDate)
            ->setParameter('ListID',$listID)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetUnsycedTimeTrackingBatchOnline($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('b1.integrationqbdtimetrackingrecords')
            ->innerJoin('b1.timeclockdaysid','t2')
            ->innerJoin('t2.servicerid','s2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->where('b1.status=1')
            ->andWhere('b1.txnid IS NOT NULL')
            ->andWhere('b1.sentstatus=0 OR b1.sentstatus IS NULL')
            ->andWhere('s2.customerid = :CustomerID')
            ->setParameter('CustomerID',$customerID)
            ->getQuery()
            ->getResult();
    }
}