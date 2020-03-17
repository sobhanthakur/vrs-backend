<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 25/11/19
 * Time: 12:18 PM
 */

namespace AppBundle\Repository;

use AppBundle\Constants\GeneralConstants;
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
     * @param $customerID
     * @param $staff
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $limit
     * @param $offset
     * @param $new
     * @return mixed
     */
    public function MapTimeClockDaysWithFilters($customerID, $staff, $completedDate, $timezones, $limit, $offset, $new,$qbo)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('t1.timeclockdayid as TimeClockDaysID,b1.status AS Status,b1.day As Date,b1.timetrackedseconds AS TimeTracked, s2.name AS StaffName,t2.region AS TimeZoneRegion, t1.clockin AS ClockIn, t1.clockout AS ClockOut');
        $result = $this->TrimMapTimeClockDays($result, $completedDate,$timezones, $new, $staff,$customerID,$qbo);

        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result->getQuery()->execute();
    }


    /**
     * @param $customerID
     * @param $staff
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $new
     * @return mixed
     */
    public function CountMapTimeClockDaysWithFilters($customerID, $staff, $completedDate, $timezones, $new,$qbo)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('count(t1.timeclockdayid)');

        $result = $this->TrimMapTimeClockDays($result, $completedDate,$timezones, $new, $staff,$customerID,$qbo);

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
            ->select('DISTINCT(t2.region) AS Region')
            ->innerJoin('t1.servicerid', 's2')
            ->innerJoin('s2.timezoneid','t2')
            ->where('s2.customerid = :CustomerID')
            ->andWhere('s2.servicertype=0')
            ->setParameter('CustomerID', $customerID);

        if ($staff) {
            $result
                ->andWhere('s2.servicerid IN (:Staffs)')
                ->setParameter('Staffs', $staff);
        }

        return $result->getQuery()->execute();
    }

    /**
     * @param QueryBuilder $result
     * @param $completedDate
     * @param $timezones
     * @param $new
     * @param $staff
     * @param $createDate
     * @return mixed
     */
    public function TrimMapTimeClockDays($result, $completedDate, $timezones, $new, $staff, $customerID,$qbo)
    {
        $result->leftJoin('AppBundle:Integrationqbdtimetrackingrecords', 'b1', Expr\Join::WITH, 'b1.timeclockdaysid=t1.timeclockdayid')
        ->innerJoin('t1.servicerid', 's2')
        ->innerJoin('s2.timezoneid','t2')
        ->where('s2.customerid = :CustomerID')
        ->andWhere('b1.txnid IS NULL')
        ->andWhere('s2.servicertype=0')
        ->andWhere('b1.sentstatus IS NULL OR b1.sentstatus=0')
        ->setParameter('CustomerID', $customerID);
        $result
            ->innerJoin('AppBundle:Integrationqbdemployeestoservicers', 'e1', Expr\Join::WITH, 'e1.servicerid=t1.servicerid')
            ->andWhere('t1.clockin IS NOT NULL')
            ->andWhere('t1.clockout IS NOT NULL');

        if (!$qbo) {
            $result
                ->innerJoin('AppBundle:Integrationstocustomers', 'e2', Expr\Join::WITH, 'e2.customerid=s2.customerid')
                ->andWhere('e2.integrationqbdhourwagetypeid IS NOT NULL');
        }
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
            $result->andWhere('s2.servicerid IN (:Staffs)')
                ->setParameter('Staffs', $staff);
        }

        if(!empty($timezones)) {
            $size = count($timezones);

            $query = 't1.clockin >= :TimeZone0';
            $result->setParameter('TimeZone0',$timezones[0]);
            for ($i=1;$i<$size;$i++) {
                $query .= ' OR t1.clockin >= :TimeZone'.$i;
                $result->setParameter('TimeZone'.$i,$timezones[$i]);
            }
            $result->andWhere($query);
        }

        if(!empty($completedDate)) {
            $size = count($completedDate);
            $query = 't1.clockin BETWEEN :CompletedDateFrom0 AND :CompletedDateTo0';
            $result->setParameter('CompletedDateFrom0',$completedDate[0]['From']);
            $result->setParameter('CompletedDateTo0', $completedDate[0]['To']);
            for ($i=1;$i<$size;$i++) {
                $query .= ' OR t1.clockin BETWEEN :CompletedDateFrom'.$i.' AND :CompletedDateTo'.$i;
                $result->setParameter('CompletedDateFrom'.$i,$completedDate[$i]['From']);
                $result->setParameter('CompletedDateTo'.$i, $completedDate[$i]['To']);
            }
            $result->andWhere($query);
        }

        return $result;
    }
}