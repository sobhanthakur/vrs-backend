<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 1/11/19
 * Time: 2:38 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbdpayrollitemwagesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function QBDPayrollItemWages($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.integrationqbdpayrollitemwageid AS IntegrationQBDPayrollItemWageID,p.qbdpayrollitemwagename AS QBDPayrollItemWageName')
            ->where('p.customerid= :CustomerID')
            ->andWhere('p.active=1')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}