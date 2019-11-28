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

/**
 * Class IntegrationqbdbillingrecordsRepository
 * @package AppBundle\Repository
 */
class IntegrationqbdbillingrecordsRepository extends EntityRepository
{
    /**
     * @param $status
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
            ->select('IDENTITY(b1.taskid) as TaskID, t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,b1.status AS Status,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate')
            ->innerJoin('b1.taskid', 't2')
            ->innerJoin('t2.propertyid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('b1.txnid IS NULL');

        if ($completedDate && !empty($completedDate['From']) && !empty($completedDate['To'])) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
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
            $result->andWhere('b1.status=1');
        } elseif ((count($status) === 1) && in_array(GeneralConstants::EXCLUDED, $status)) {
            $result->andWhere('b1.status=0');
        } elseif ((count($status) === 2) && in_array(GeneralConstants::NEW, $status)) {
            if (in_array(GeneralConstants::APPROVED, $status)) {
                $result->andWhere('b1.status=1');
            } elseif (in_array(GeneralConstants::EXCLUDED, $status)) {
                $result->andWhere('b1.status=0');
            }
        }

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
            ->select('count(IDENTITY(b1.taskid))')
            ->innerJoin('b1.taskid', 't2')
            ->innerJoin('t2.propertyid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('b1.txnid IS NULL');

        if ($completedDate && !empty($completedDate['From']) && !empty($completedDate['To'])) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
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
            $result->andWhere('b1.status=1');
        } elseif ((count($status) === 1) && in_array(GeneralConstants::EXCLUDED, $status)) {
            $result->andWhere('b1.status=0');
        } elseif ((count($status) === 2) && in_array(GeneralConstants::NEW, $status)) {
            if (in_array(GeneralConstants::APPROVED, $status)) {
                $result->andWhere('b1.status=1');
            } elseif (in_array(GeneralConstants::EXCLUDED, $status)) {
                $result->andWhere('b1.status=0');
            }
        }
        return $result->getQuery()->execute();
    }
}