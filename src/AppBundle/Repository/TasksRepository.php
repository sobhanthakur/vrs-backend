<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 12:09 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class TasksRepository
 * @package AppBundle\Repository
 */
class TasksRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @param $properties
     * @param $createDate
     * @param $completedDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTasks($customerID, $properties, $createDate, $completedDate, $limit, $offset)
    {
        $result = null;

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.taskid) from AppBundle:Integrationqbdbillingrecords b1 inner join AppBundle:Tasks t2 with  b1.taskid=t2.taskid inner join AppBundle:Properties p2 with t2.propertyid=p2.propertyid where p2.customerid=1')->getArrayResult();

        $result = $this
            ->createQueryBuilder('t2')
            ->select('t2.taskid as TaskID, t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate')
            ->andWhere('t2.active=1')
            ->innerJoin('t2.propertyid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');

        if ($subQuery) {
            $result
                ->andWhere('t2.taskid NOT IN (:SubQuery)')
                ->setParameter('SubQuery', $subQuery);
        }

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        if ($completedDate) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ($createDate) {
            $result->andWhere('t2.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
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
     * @return mixed
     */
    public function CountMapTasks($customerID, $properties, $createDate, $completedDate)
    {
        $result = null;

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.taskid) from AppBundle:Integrationqbdbillingrecords b1 inner join AppBundle:Tasks t2 with  b1.taskid=t2.taskid inner join AppBundle:Properties p2 with t2.propertyid=p2.propertyid where p2.customerid=1')->getArrayResult();

        $result = $this
            ->createQueryBuilder('t2')
            ->select('count(t2.taskid)')
            ->andWhere('t2.active=1')
            ->innerJoin('t2.propertyid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');

        if ($subQuery) {
            $result
                ->andWhere('t2.taskid NOT IN (:SubQuery)')
                ->setParameter('SubQuery', $subQuery);
        }

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        if ($completedDate) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ($createDate) {
            $result->andWhere('t2.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result
            ->getQuery()
            ->getResult();
    }
}