<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 30/10/19
 * Time: 2:25 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class IntegrationqbditemsRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function QBDItems($customerID)
    {
        return $this
            ->createQueryBuilder('i')
            ->select('i.integrationqbditemid AS IntegrationQBDItemID,i.qbditemfullname AS QBDItemFullName')
            ->where('i.customerid= :CustomerID')
            ->andWhere('i.active=1')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetAllItems($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c.qbditemlistid AS QBDItemListID')
            ->where('c.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}