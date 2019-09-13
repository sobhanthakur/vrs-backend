<?php
/**
 * Created by Sobhan.
 * Date: 13/9/19
 * Time: 6:51 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PropertyGroupsRepository extends EntityRepository
{
    public function GetPropertyGroupsRestrictions($customerID)
    {
        $propertyGroupRestrictions = $this
            ->createQueryBuilder('p')
            ->select('p.propertygroupid')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
        return $propertyGroupRestrictions;
    }
}