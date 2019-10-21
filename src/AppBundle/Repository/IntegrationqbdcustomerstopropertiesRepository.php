<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 17/10/19
 * Time: 12:31 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbdcustomerstopropertiesRepository extends EntityRepository
{
    public function PropertiesJoinMatched($customerID)
    {
        return $this
            ->createQueryBuilder('icp')
            ->select('IDENTITY(icp.propertyid)')
            ->where('ic.customerid= :CustomerID')
            ->andWhere('ic.active=1')
            ->innerJoin('icp.integrationqbdcustomerid','ic')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}