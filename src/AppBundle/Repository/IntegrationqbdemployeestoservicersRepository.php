<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/10/19
 * Time: 3:07 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbdemployeestoservicersRepository extends EntityRepository
{
    public function PropertiesJoinMatched($customerID)
    {
        return $this
            ->createQueryBuilder('ies')
            ->select('IDENTITY(ies.servicerid)')
            ->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('ies.integrationqbdemployeeid','ic')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}