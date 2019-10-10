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
            ->select('i.active, i.createdate, i.qbdsyncbilling, i.qbdsyncpayroll, IDENTITY(i.integrationid) as integrationid, i.startdate')
            ->where('i.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
        return $integrationsToCustomers;
    }

    public function CheckIntegration($integrationID, $customerID)
    {
        $integrationsToCustomers = $this
            ->createQueryBuilder('i')
            ->select('i.integrationtocustomerid')
            ->where('i.customerid= :CustomerID')
            ->andWhere('i.integrationid= :IntegrationID')
            ->setParameter('CustomerID', $customerID)
            ->setParameter('IntegrationID', $integrationID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
        return $integrationsToCustomers;
    }

    public function GetSyncRecords($integrationID, $customerID)
    {
        $integrationsToCustomers = $this
            ->createQueryBuilder('i')
            ->select('i.username, i.qbdsyncbilling, i.qbdsyncpayroll, i.active')
            ->where('i.customerid= :CustomerID')
            ->andWhere('i.integrationid= :IntegrationID')
            ->setParameter('CustomerID', $customerID)
            ->setParameter('IntegrationID', $integrationID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
        return $integrationsToCustomers;
    }
}