<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 29/10/19
 * Time: 2:40 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ServicesRepository extends EntityRepository
{
    public function SyncServices($itemsToServices, $department, $billable, $createDate, $limit, $offset, $customerID, $matchStatus)
    {
        $result = null;
        $result = $this
            ->createQueryBuilder('s')
            ->select('s.serviceid AS TaskRuleID, s.servicename as TaskRuleName')
            ->where('s.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('s.active=1');

        switch ($matchStatus) {
            case 0:
                $result->andWhere('s.serviceid NOT IN (:ItemsToServices)')
                    ->setParameter('ItemsToServices', $itemsToServices);
                break;
            case 1:
                $result->andWhere('s.serviceid IN (:ItemsToServices)')
                    ->setParameter('ItemsToServices', $itemsToServices);
                break;
        }

        if ($department) {
            $result->andWhere('s.servicegroupid IN (:Departments)')
                ->setParameter('Regions', $department);
        }
        if ($billable) {
            $result->andWhere('s.billable= :Owners')
                ->setParameter('Owners', $billable);
        }
        if ($createDate) {
            $result->andWhere('s.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }
        $result
            ->orderBy('s.createdate','DESC')
            ->setFirstResult(($offset-1)*$limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->getResult();
    }
}