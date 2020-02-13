<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 14/10/19
 * Time: 5:48 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ServiceGroupRepository extends EntityRepository
{
    public function GetServiceGroups($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.servicegroupid as ServiceGroupID, p.servicegroup as ServiceGroup')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->orderBy('p.servicegroup','ASC')
            ->getQuery()
            ->execute();
    }
}