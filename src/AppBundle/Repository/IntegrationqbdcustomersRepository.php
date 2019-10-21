<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 21/10/19
 * Time: 4:07 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbdcustomersRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function QBDCustomers($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c.integrationqbdcustomerid AS IntegrationQBDCustomerID,c.qbdcustomerfullname AS QBDCustomerFullName')
            ->where('c.customerid= :CustomerID')
            ->andWhere('c.active=1')
            ->setParameter('CustomerID', $customerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}