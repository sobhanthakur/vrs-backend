<?php
/**
 * Created by Sobhan Thakur.
 * Date: 16/9/19
 * Time: 10:11 AM
 */

namespace AppBundle\Repository;


/**
 * Class CustomersRepository
 * @package AppBundle\Repository
 */
class CustomersRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function PiecePayRestrictions($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c.piecepay, c.icaladdon')
            ->where('c.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}