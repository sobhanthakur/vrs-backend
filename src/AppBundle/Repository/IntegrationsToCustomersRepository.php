<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 10:55 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationsToCustomersRepository extends EntityRepository
{
    public function GetAllIntegrations($customerID)
    {
        $integrationsToCustomers = $this
            ->createQueryBuilder('i')
            ->select('i.active, i.createdate, i.qbdsyncbilling, i.qbdsyncpayroll, IDENTITY(i.integrationid) as integrationid')
            ->where('i.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
        return $integrationsToCustomers;
    }
}