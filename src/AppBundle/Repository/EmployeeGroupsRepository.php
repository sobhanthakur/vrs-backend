<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 14/10/19
 * Time: 5:26 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class EmployeeGroupsRepository extends EntityRepository
{
    public function GetEmployeeGroups($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.employeegroupid as EmployeeGroupID, p.employeegroup as EmployeeGroup')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}