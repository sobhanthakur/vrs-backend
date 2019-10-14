<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 14/10/19
 * Time: 6:12 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PropertiesRepository extends EntityRepository
{
    public function GetProperties($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.propertyid as PropertyID, p.propertyname as PropertyName')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}