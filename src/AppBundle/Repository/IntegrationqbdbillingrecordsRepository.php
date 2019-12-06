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

/**
 * Class IntegrationqbdbillingrecordsRepository
 * @package AppBundle\Repository
 */
class IntegrationqbdbillingrecordsRepository extends EntityRepository
{
    /**
     * @param $status
     * @param $properties
     * @param $customerID
     * @param $createDate
     * @param $completedDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('IDENTITY(b1.taskid) as TaskID, t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,b1.status AS Status,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate,t.region AS TimeZoneRegion')
            ->innerJoin('b1.taskid', 't2')
            ->innerJoin('t2.propertyid', 'p2')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('b1.txnid IS NULL');

        $result = $this->TrimBillingRecords($result,$completedDate,$properties,$createDate,$status);

        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result->getQuery()->execute();
    }

    /**
     * @param $status
     * @param $customerID
     * @param $createDate
     * @param $completedDate
     * @return mixed
     */
    public function CountMapTasksQBDFilters($status, $properties, $customerID, $createDate, $completedDate)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbdbillingrecordid)')
            ->innerJoin('b1.taskid', 't2')
            ->innerJoin('t2.propertyid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('b1.txnid IS NULL');

        $result = $this->TrimBillingRecords($result,$completedDate,$properties,$createDate,$status);

        return $result->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return mixed
     */
    public function DistinctBatchCount($batchID)
    {
        return $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbdbillingrecordid)')
            ->where('b1.integrationqbbatchid = :BatchID')
            ->setParameter('BatchID', $batchID)
            ->getQuery()->execute();
    }

    /**
     * @param $batchID
     * @return array
     */
    public function GetBatchDetails($batchID, $limit, $offset)
    {
        return $this
            ->getEntityManager()
            ->createQuery('Select b.sentstatus AS SentStatus,sp.laboramount AS LaborAmount,sp.materialsamount AS MaterialsAmount,S.laborormaterials AS LaborOrMaterial,b.txnid as TxnID,b.itemtxnid ItemTxnID,p.propertyname AS PropertyName,t.taskname AS TaskName from AppBundle:Integrationqbdbillingrecords b inner join AppBundle:Tasks t WITH b.taskid=t.taskid inner join AppBundle:Properties p WITH t.propertyid=p.propertyid inner join AppBundle:Servicestoproperties sp WITH sp.propertyid=p.propertyid inner join AppBundle:Integrationqbditemstoservices S with S.serviceid=sp.serviceid where b.integrationqbbatchid=' . $batchID)
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getArrayResult();
    }

    /**
     * @param $batchID
     * @return array
     */
    public function CountGetBatchDetails($batchID)
    {
        return $this
            ->getEntityManager()
            ->createQuery('Select count(IDENTITY(sp.serviceid)) from AppBundle:Integrationqbdbillingrecords b inner join AppBundle:Tasks t WITH b.taskid=t.taskid inner join AppBundle:Properties p WITH t.propertyid=p.propertyid inner join AppBundle:Servicestoproperties sp WITH sp.propertyid=p.propertyid inner join AppBundle:Integrationqbditemstoservices S with S.serviceid=sp.serviceid where b.integrationqbbatchid=' . $batchID)
            ->getArrayResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $completedDate
     * @param $properties
     * @param $createDate
     * @param $status
     * @return mixed
     */
    public function TrimBillingRecords($result, $completedDate, $properties, $createDate, $status)
    {
        if (is_array($completedDate)) {
            $result->andWhere('t2.taskid IN (:CompletedDate)')
                ->setParameter('CompletedDate', $completedDate);
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
        } elseif ((count($status) === 2) && in_array(GeneralConstants::NEW, $status)) {
            if (in_array(GeneralConstants::APPROVED, $status)) {
                $result->andWhere(GeneralConstants::BILLING_STATUS_1);
            } elseif (in_array(GeneralConstants::EXCLUDED, $status)) {
                $result->andWhere(GeneralConstants::BILLING_STATUS_0);
            }
        }

        return $result;
    }
}