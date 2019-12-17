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
            ->innerJoin('p2.regionid','r')
            ->innerJoin('r.timezoneid','t')
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
    public function MapTasks($customerID, $properties, $createDate, $completedDate,$timezones, $limit, $offset,$new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('t2.taskid as TaskID, b1.status AS Status,t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate, t.region AS TimeZoneRegion');

        $result = $this->TrimMapTasks($result,$new,$properties,$completedDate,$timezones,$createDate,$customerID);

        $result->orderBy('t2.completeconfirmeddate','ASC');
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
    public function CountMapTasks($customerID, $properties, $createDate, $completedDate,$timezones,$new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('count(t2.taskid)');

        $result = $this->TrimMapTasks($result,$new,$properties,$completedDate,$timezones,$createDate,$customerID);

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $result
     * @param $new
     * @param $properties
     * @param $completedDate,$timezones
     * @param $createDate
     * @param $customerID
     * @return mixed
     */
    public function TrimMapTasks($result, $new, $properties, $completedDate,$timezones, $createDate, $customerID)
    {
        $result
            ->leftJoin('AppBundle:Integrationqbdbillingrecords', 'b1', Expr\Join::WITH, 'b1.taskid=t2.taskid')
            ->where('t2.active=1')
            ->andWhere('b1.txnid IS NULL')
            ->innerJoin('t2.propertyid', 'p2')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->andWhere('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');
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

        if($new) {
            $result->andWhere('b1.status IS NULL OR b1.status=2');
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
}