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
            ->select('p.propertygroupid as PropertyGroupID, p.propertygroup as PropertyGroup')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
        return $propertyGroupRestrictions;
    }
}