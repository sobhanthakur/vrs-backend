<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 25/11/19
 * Time: 12:18 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TimeclockdaysRepository extends EntityRepository
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
    public function MapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('t1.timeclockdayid as TimeClockDaysID, s2.name AS StaffName,IDENTITY(s2.timezoneid) AS TimeZoneID, t1.clockin AS ClockIn, t1.clockout AS ClockOut')
            ->innerJoin('t1.servicerid', 's2')
            ->where('s2.customerid = :CustomerID')
            ->andWhere('s2.servicertype=0')
            ->setParameter('CustomerID', $customerID);

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.timeclockdaysid) from AppBundle:Integrationqbdtimetrackingrecords b1 inner join AppBundle:Timeclockdays t2 with  b1.timeclockdaysid=t2.timeclockdayid inner join AppBundle:Servicers p2 with t2.servicerid=p2.servicerid where p2.customerid='.$customerID)->getArrayResult();
        if($subQuery) {
            $result->andWhere('t1.timeclockdayid NOT IN (:SubQuery)')
                ->setParameter('SubQuery',$subQuery);
        }

        if ($completedDate) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ($staff) {
            $result->andWhere('s2.servicerid IN (:Staffs)')
                ->setParameter('Staffs', $staff);
        }

        if ($createDate) {
            $result->andWhere('t.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
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
    public function CountMapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('count(t1.timeclockdayid)')
            ->innerJoin('t1.servicerid', 's2')
            ->where('s2.customerid = :CustomerID')
            ->andWhere('s2.servicertype=0')
            ->setParameter('CustomerID', $customerID);

        $subQuery = $this
            ->getEntityManager()
            ->createQuery('select IDENTITY(b1.timeclockdaysid) from AppBundle:Integrationqbdtimetrackingrecords b1 inner join AppBundle:Timeclockdays t2 with  b1.timeclockdaysid=t2.timeclockdayid inner join AppBundle:Servicers p2 with t2.servicerid=p2.servicerid where p2.customerid='.$customerID)->getArrayResult();
        if($subQuery) {
            $result->andWhere('t1.timeclockdayid NOT IN (:SubQuery)')
                ->setParameter('SubQuery',$subQuery);
        }

        if ($completedDate && !empty($completedDate['From']) && !empty($completedDate['To'])) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ($staff) {
            $result->andWhere('s2.servicerid IN (:Staffs)')
                ->setParameter('Staffs', $staff);
        }

        if ($createDate) {
            $result->andWhere('t.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result->getQuery()->execute();
    }
}