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

class IntegrationqbdtimetrackingrecordsRepository extends EntityRepository
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
    public function MapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate,$limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('IDENTITY(t1.timeclockdaysid) as TimeClockDaysID, t1.status AS Status, p2.name AS StaffName,IDENTITY(p2.timezoneid) AS TimeZoneID, t2.clockin AS ClockIn, t2.clockout AS ClockOut')
            ->innerJoin('t1.timeclockdaysid', 't2')
            ->innerJoin('t2.servicerid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t1.txnid IS NULL');

        if ($completedDate) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ($staff) {
            $result->andWhere('p2.servicerid IN (:Servicers)')
                ->setParameter('Servicers', $staff);
        }

        if ($createDate) {
            $result->andWhere('t.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        if ((count($status) === 1) && in_array(GeneralConstants::APPROVED, $status)) {
            $result->andWhere('t1.status=1');
        } elseif ((count($status) === 1) && in_array(GeneralConstants::EXCLUDED, $status)) {
            $result->andWhere('t1.status=0');
        } elseif ((count($status) === 2) && in_array(GeneralConstants::NEW, $status)) {
            if (in_array(GeneralConstants::APPROVED, $status)) {
                $result->andWhere('t1.status=1');
            } elseif (in_array(GeneralConstants::EXCLUDED, $status)) {
                $result->andWhere('t1.status=0');
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
    public function CountMapTimeTrackingQBDFilters($customerID, $status, $staff, $createDate, $completedDate)
    {
        $result = $this
            ->createQueryBuilder('t1')
            ->select('count(IDENTITY(t1.timeclockdaysid))')
            ->innerJoin('t1.timeclockdaysid', 't2')
            ->innerJoin('t2.servicerid', 'p2')
            ->where('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t1.txnid IS NULL');

        if ($completedDate) {
            $result->andWhere('t2.completeconfirmeddate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ($staff) {
            $result->andWhere('p2.servicerid IN (:Servicers)')
                ->setParameter('Servicers', $staff);
        }

        if ($createDate) {
            $result->andWhere('t.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        if ((count($status) === 1) && in_array(GeneralConstants::APPROVED, $status)) {
            $result->andWhere('t1.status=1');
        } elseif ((count($status) === 1) && in_array(GeneralConstants::EXCLUDED, $status)) {
            $result->andWhere('t1.status=0');
        } elseif ((count($status) === 2) && in_array(GeneralConstants::NEW, $status)) {
            if (in_array(GeneralConstants::APPROVED, $status)) {
                $result->andWhere('t1.status=1');
            } elseif (in_array(GeneralConstants::EXCLUDED, $status)) {
                $result->andWhere('t1.status=0');
            }
        }
        return $result->getQuery()->execute();
    }
}