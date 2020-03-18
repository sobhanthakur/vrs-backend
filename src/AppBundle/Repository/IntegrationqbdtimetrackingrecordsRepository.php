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
    public function BatchWiseLog($batchID,$limit,$offset,$timeTrackingType)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('s2.name AS Staff,b1.txnid AS TxnID,(CASE WHEN b1.sentstatus=1 AND b1.txnid IS NULL THEN 0 ELSE 1 END) AS Status');
        if ($timeTrackingType) {
            $result->innerJoin('b1.timeclocktasksid', 't2');
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
            ->select('b1.day AS Date,SUM(b1.timetrackedseconds) AS TimeTrackedSeconds,IDENTITY(t2.servicerid) AS ServicerID,ie.qbdemployeelistid AS QBDEmployeeListID')
            ->innerJoin('b1.timeclockdaysid','t2')
            ->innerJoin('t2.servicerid','s2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers','ies',Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('ies.integrationqbdemployeeid','ie')
            ->groupBy('b1.day,t2.servicerid,ie.qbdemployeelistid')
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

    public function UpdateFailedRecords($customerID,$day,$listID)
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
            ->select('b1.integrationqbdtimetrackingrecords AS IntegrationQBDTimeTrackingRecordID,b1.day AS Date,b1.timetrackedseconds AS TimeTrackedSeconds,IDENTITY(t2.servicerid) AS ServicerID,ie.qbdemployeefullname AS EmployeeName,ie.qbdemployeelistid AS EmployeeValue,ic.qbdcustomerlistid AS CustomerValue')
            ->innerJoin('b1.timeclocktasksid', 't2')
            ->innerJoin('t2.servicerid', 's2')
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers', 'ies', Expr\Join::WITH, 't2.servicerid=ies.servicerid')
            ->innerJoin('t2.taskid', 'taskid')
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
}