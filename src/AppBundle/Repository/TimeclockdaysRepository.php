<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 25/11/19
 * Time: 12:18 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TimeclockdaysRepository
 * @package AppBundle\Repository
 */
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
    public function MapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate, $limit, $offset,$new)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('t1.timeclockdayid as TimeClockDaysID,b1.status AS Status, s2.name AS StaffName,t2.region AS TimeZoneRegion, t1.clockin AS ClockIn, t1.clockout AS ClockOut')
            ->leftJoin('AppBundle:Integrationqbdtimetrackingrecords', 'b1', Expr\Join::WITH, 'b1.timeclockdaysid=t1.timeclockdayid')
            ->innerJoin('t1.servicerid', 's2')
            ->innerJoin('s2.timezoneid','t2')
            ->where('s2.customerid = :CustomerID')
            ->andWhere('s2.servicertype=0')
            ->setParameter('CustomerID', $customerID);

        $result = $this->TrimMapTimeClockDays($result, $completedDate, $new, $staff, $createDate);

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
    public function CountMapTimeClockDaysWithFilters($customerID, $staff, $createDate, $completedDate,$new)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('count(t1.timeclockdayid)')
            ->leftJoin('AppBundle:Integrationqbdtimetrackingrecords', 'b1', Expr\Join::WITH, 'b1.timeclockdaysid=t1.timeclockdayid')
            ->innerJoin('t1.servicerid', 's2')
            ->where('s2.customerid = :CustomerID')
            ->andWhere('s2.servicertype=0')
            ->setParameter('CustomerID', $customerID);

        $result = $this->TrimMapTimeClockDays($result, $completedDate, $new, $staff, $createDate);

        return $result->getQuery()->execute();
    }

    /**
     * @param $customerID
     * @param $staff
     * @return mixed
     */
    public function GetAllTimeZones($customerID, $staff)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('t1.timeclockdayid as TimeClockDaysID,t2.region AS Region, t1.clockout AS ClockOut')
            ->innerJoin('t1.servicerid', 's2')
            ->innerJoin('s2.timezoneid','t2')
            ->where('s2.customerid = :CustomerID')
            ->andWhere('s2.servicertype=0')
            ->setParameter('CustomerID', $customerID);

        if ($staff) {
            $result->andWhere('s2.servicerid IN (:Staffs)')
                ->setParameter('Staffs', $staff);
        }

        return $result->getQuery()->execute();
    }

    /**
     * @param QueryBuilder $result
     * @param $completedDate
     * @param $new
     * @param $staff
     * @param $createDate
     * @return mixed
     */
    public function TrimMapTimeClockDays($result, $completedDate, $new, $staff, $createDate)
    {
        if($new) {
            $result->andWhere('b1.status IS NULL');
        }

        if (is_array($completedDate)) {
            $result->andWhere('t1.timeclockdayid IN (:CompletedDate)')
                ->setParameter('CompletedDate', $completedDate);
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

        return $result;
    }
}