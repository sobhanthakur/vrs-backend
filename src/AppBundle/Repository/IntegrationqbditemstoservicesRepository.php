<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 29/10/19
 * Time: 12:00 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class IntegrationqbditemstoservicesRepository
 * @package AppBundle\Repository
 */
class IntegrationqbditemstoservicesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function ServicesJoinMatched($customerID)
    {
        return $this
            ->createQueryBuilder('iis')
            ->select('IDENTITY(iis.serviceid)')
            ->where('i.customerid= :CustomerID')
            ->andWhere('i.active=1')
            ->innerJoin('iis.integrationqbditemid','i')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }
}