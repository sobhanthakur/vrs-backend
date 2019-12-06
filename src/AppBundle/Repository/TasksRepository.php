<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 12:09 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TasksRepository
 * @package AppBundle\Repository
 */
class TasksRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @param $properties
     * @return mixed
     */
    public function GetAllTimeZones($customerID, $properties)
    {
        $result = null;

        $result = $this
            ->createQueryBuilder('t2')
            ->select('t2.taskid as TaskID,t2.completeconfirmeddate AS CompleteConfirmedDate,t.region AS Region')
            ->andWhere('t2.active=1')
            ->innerJoin('t2.propertyid', 'p2')
            ->innerJoin('p2.regionid','r')
            ->innerJoin('r.timezoneid','t')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $properties
     * @param $createDate
     * @param $completedDate
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTasks($customerID, $properties, $createDate, $completedDate, $limit, $offset,$new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('t2.taskid as TaskID, b1.status AS Status,t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate, t.region AS TimeZoneRegion');

        $result = $this->TrimMapTasks($result,$new,$properties,$completedDate,$createDate,$customerID);

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
    public function CountMapTasks($customerID, $properties, $createDate, $completedDate,$new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('count(t2.taskid)');

        $result = $this->TrimMapTasks($result,$new,$properties,$completedDate,$createDate,$customerID);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $new
     * @param $properties
     * @param $completedDate
     * @param $createDate
     * @param $customerID
     * @return mixed
     */
    public function TrimMapTasks($result, $new, $properties, $completedDate, $createDate, $customerID)
    {
        $result
            ->leftJoin('AppBundle:Integrationqbdbillingrecords', 'b1', Expr\Join::WITH, 'b1.taskid=t2.taskid')
            ->andWhere('t2.active=1')
            ->innerJoin('t2.propertyid', 'p2')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');

        if($new) {
            $result->andWhere('b1.status IS NULL');
        }

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        if (is_array($completedDate)) {
            $result->andWhere('t2.taskid IN (:CompletedDate)')
                ->setParameter('CompletedDate', $completedDate);
        }

        if ($createDate) {
            $result->andWhere('t2.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result;
    }
}