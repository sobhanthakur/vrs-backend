<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 14/10/19
 * Time: 4:30 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class OwnersRepository extends EntityRepository
{

    public function GetOwners($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.ownerid as OwnerID, p.ownername as OwnerName')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}