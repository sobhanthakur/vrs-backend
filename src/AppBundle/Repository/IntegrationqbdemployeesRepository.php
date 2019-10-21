<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 21/10/19
 * Time: 5:48 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbdemployeesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function QBDEmployees($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c.integrationqbdemployeeid AS IntegrationQBDEmployeeID,c.qbdemployeefullname AS QBDEmployeeFullName')
            ->where('c.customerid= :CustomerID')
            ->andWhere('c.active=1')
            ->setParameter('CustomerID', $customerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}