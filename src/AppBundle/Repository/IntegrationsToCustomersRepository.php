<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 10:55 AM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;

class IntegrationsToCustomersRepository extends EntityRepository
{
    public function GetAllIntegrations($customerID)
    {
        return $this
            ->createQueryBuilder('i')
            ->select('i.active, i.createdate, i.qbdsyncbilling, i.qbdsyncpayroll, IDENTITY(i.integrationid) as integrationid, i.startdate')
            ->where(GeneralConstants::CUSTOMER_CONDITION)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->getQuery()
            ->execute();
    }

    public function CheckIntegration($integrationID, $customerID)
    {
        return $this
            ->createQueryBuilder('i')
            ->select('i.integrationtocustomerid')
            ->where(GeneralConstants::CUSTOMER_CONDITION)
            ->andWhere('i.integrationid= :IntegrationID')
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->setParameter(GeneralConstants::INTEGRATION_ID, $integrationID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    public function GetSyncRecords($integrationID, $customerID)
    {
        return $this
            ->createQueryBuilder('i')
            ->select('i.username, i.qbdsyncbilling, i.qbdsyncpayroll, i.active')
            ->where(GeneralConstants::CUSTOMER_CONDITION)
            ->andWhere('i.integrationid= :IntegrationID')
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->setParameter(GeneralConstants::INTEGRATION_ID, $integrationID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}