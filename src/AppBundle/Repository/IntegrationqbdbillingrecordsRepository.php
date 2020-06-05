<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 3:10 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;

/**
 * Class IntegrationqbdbillingrecordsRepository
 * @package AppBundle\Repository
 */
class IntegrationqbdbillingrecordsRepository extends EntityRepository
{
    /**
     * @var string
     */
    private $taskid = 'b1.taskid';
    /**
     * @var string
     */
    private $propertyid = 't2.propertyid';
    /**
     * @var string
     */
    private $customerCondition = 'p2.customerid = :CustomerID';
    /**
     * @var string
     */
    private $txnid = 'b1.txnid IS NULL';

    /**
     * @param $status
     * @param $properties
     * @param $customerID
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate, $timezones, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('IDENTITY(b1.taskid) as TaskID, s2.servicename AS ServiceName,t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,b1.status AS Status,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate,t.region AS TimeZoneRegion')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid, 'p2')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->innerJoin('AppBundle:Services','s2',Expr\Join::WITH, 't2.serviceid=s2.serviceid')
            ->where($this->customerCondition)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->andWhere($this->txnid);

        $result = $this->TrimBillingRecords($result,$completedDate,$timezones,$properties,$createDate,$status);

        $result->orderBy('t2.completeconfirmeddate','ASC');

        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result->getQuery()->execute();
    }


    /**
     * @param $status
     * @param $properties
     * @param $customerID
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @return mixed
     */
    public function CountMapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate, $timezones)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbdbillingrecordid)')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid, 'p2')
            ->where($this->customerCondition)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->andWhere($this->txnid);

        $result = $this->TrimBillingRecords($result,$completedDate,$timezones,$properties,$createDate,$status);

        return $result->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function DistinctBatchCountFailed($batchID)
    {
        $result = $this
            ->createQueryBuilder('b1');
        $result = $this->TrimBatchCount($result,$batchID);
        $result->andWhere('b1.txnid IS NULL');
        return $result->getQuery()->getResult();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function DistinctBatchCountSuccess($batchID)
    {
        $result = $this
            ->createQueryBuilder('b1');
        $result = $this->TrimBatchCount($result,$batchID);
        $result->andWhere('b1.txnid IS NOT NULL');
        return $result->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @return mixed
     */
    public function TrimBatchCount($result,$batchID)
    {
        $result->select('COUNT(b1.integrationqbdbillingrecordid)')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid,'p2')
            ->innerJoin('AppBundle:Integrationqbdcustomerstoproperties','icp',Expr\Join::WITH, 't2.propertyid=icp.propertyid')
            ->innerJoin('icp.integrationqbdcustomerid','ic')
            ->leftJoin('AppBundle:Integrationqbditemstoservices','iis',Expr\Join::WITH, 'iis.serviceid=t2.serviceid')
            ->innerJoin('iis.integrationqbditemid','ii')
            ->andWhere('b1.sentstatus=1')
            ->andWhere('b1.integrationqbbatchid='.$batchID)
            ->groupBy('b1.txnid')
        ;

        return $result;
    }

    /**
     * @param $batchID
     * @return array
     */
    public function GetBatchDetails($batchID, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('b1.txnid AS TxnID,(CASE WHEN iis.laborormaterials=1 THEN 1 ELSE 0 END) AS LaborOrMaterial,(CASE WHEN b1.txnid IS NULL AND b1.sentstatus=1 THEN 0 ELSE 1 END) AS Status,p2.propertyname AS PropertyName,t2.taskname AS TaskName,(CASE WHEN iis.laborormaterials=1 THEN t2.expenseamount ELSE t2.amount END) AS Amount,s2.servicename AS ServiceName,t2.completeconfirmeddate AS CompleteConfirmedDate')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid,'p2')
            ->innerJoin('AppBundle:Services','s2',Expr\Join::WITH, 't2.serviceid=s2.serviceid')
            ->innerJoin('AppBundle:Integrationqbdcustomerstoproperties','icp',Expr\Join::WITH, 't2.propertyid=icp.propertyid')
            ->leftJoin('AppBundle:Integrationqbditemstoservices','iis',Expr\Join::WITH, 'iis.serviceid=t2.serviceid')
            ->innerJoin('iis.integrationqbditemid','ii')
            ->andWhere('b1.sentstatus=1')
            ->andWhere('b1.integrationqbbatchid='.$batchID)
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result->getQuery()->getResult();

    }

    /**
     * @param $batchID
     * @return array
     */
    public function CountGetBatchDetails($batchID)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('count(b1.taskid)')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid,'p2')
            ->innerJoin('AppBundle:Integrationqbdcustomerstoproperties','icp',Expr\Join::WITH, 't2.propertyid=icp.propertyid')
            ->leftJoin('AppBundle:Integrationqbditemstoservices','iis',Expr\Join::WITH, 'iis.serviceid=t2.serviceid')
            ->innerJoin('iis.integrationqbditemid','ii')
            ->andWhere('b1.sentstatus=1')
            ->andWhere('b1.integrationqbbatchid='.$batchID);
        return $result->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $completedDate
     * @param $timezones
     * @param $properties
     * @param $createDate
     * @param $status
     * @return mixed
     */
    public function TrimBillingRecords($result, $completedDate, $timezones, $properties, $createDate, $status)
    {
        if(!empty($timezones)) {
            $size = count($timezones);

            $query = 't2.completeconfirmeddate >= :TimeZone0';
            $result->setParameter('TimeZone0',$timezones[0]);
            for ($i=1;$i<$size;$i++) {
                $query .= ' OR t2.completeconfirmeddate >= :TimeZone'.$i;
                $result->setParameter('TimeZone'.$i,$timezones[$i]);
            }
            $result->andWhere($query);

        }

        if(!empty($completedDate)) {
            $size = count($completedDate);
            $query = 't2.completeconfirmeddate BETWEEN :CompletedDateFrom0 AND :CompletedDateTo0';
            $result->setParameter('CompletedDateFrom0',$completedDate[0]['From']);
            $result->setParameter('CompletedDateTo0', $completedDate[0]['To']);
            for ($i=1;$i<$size;$i++) {
                $query .= ' OR t2.completeconfirmeddate BETWEEN :CompletedDateFrom'.$i.' AND :CompletedDateTo'.$i;
                $result->setParameter('CompletedDateFrom'.$i,$completedDate[$i]['From']);
                $result->setParameter('CompletedDateTo'.$i, $completedDate[$i]['To']);
            }
            $result->andWhere($query);
        }

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        if ($createDate) {
            $result->andWhere('t.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        if ((count($status) === 1) && in_array(GeneralConstants::APPROVED, $status)) {
            $result->andWhere(GeneralConstants::BILLING_STATUS_1);
        } elseif ((count($status) === 1) && in_array(GeneralConstants::EXCLUDED, $status)) {
            $result->andWhere(GeneralConstants::BILLING_STATUS_0);
        }

        return $result;
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetTasksForSalesOrder($customerID,$qbo=null)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('b1.integrationqbdbillingrecordid AS IntegrationQBDBillingRecordID,ii.qbditemlistid AS QBDListID,ic.qbdcustomerlistid as QBDCustomerListID,IDENTITY(iis.serviceid) AS ServiceID,iis.laborormaterials AS LaborOrMaterial,t2.taskname AS TaskName,p2.propertyname AS PropertyName,s2.servicename AS ServiceName,t2.completeconfirmeddate AS CompleteConfirmedDate,t.region AS Region,(CASE WHEN iis.laborormaterials=1 THEN t2.expenseamount ELSE t2.amount END) AS Amount')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid, 'p2')
            ->innerJoin('AppBundle:Integrationqbdcustomerstoproperties', 'icp', Expr\Join::WITH, 't2.propertyid=icp.propertyid')
            ->innerJoin('icp.integrationqbdcustomerid', 'ic')
            ->innerJoin('AppBundle:Services', 's2', Expr\Join::WITH, 't2.serviceid=s2.serviceid')
            ->leftJoin('AppBundle:Integrationqbditemstoservices', 'iis', Expr\Join::WITH, 'iis.serviceid=t2.serviceid')
            ->innerJoin('iis.integrationqbditemid', 'ii')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->where($this->customerCondition)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->andWhere($this->txnid)
            ->andWhere('b1.sentstatus=0 OR b1.sentstatus IS NULL');

        // Don't look for refnumber if the version is QBO
        if (!$qbo) {
            $result->andWhere('b1.refnumber IS NULL OR b1.refnumber=0');
        }

        $result->andWhere('b1.status=1');
        return $result->getQuery()->getResult();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetFailedBillingRecords($customerID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('b1.refnumber AS RefNumber')
            ->innerJoin($this->taskid, 't2')
            ->innerJoin($this->propertyid,'p2')
            ->where($this->customerCondition)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->andWhere($this->txnid)
            ->andWhere('b1.sentstatus=1')
            ->andWhere('b1.refnumber IS NOT NULL')
            ->andWhere('b1.status=1')
            ->getQuery()
            ->getResult();

    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function ResetBillingBatch($batchID)
    {
        return $this
            ->getEntityManager()
            ->createQuery('UPDATE AppBundle:Integrationqbdbillingrecords b1 SET b1.sentstatus=NULL,b1.refnumber=NULL,b1.integrationqbbatchid=NULL WHERE b1.txnid IS NULL AND b1.integrationqbbatchid='.$batchID)
            ->getArrayResult();
    }
}