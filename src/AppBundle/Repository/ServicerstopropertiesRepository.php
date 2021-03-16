<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 9/12/20
 * Time: 4:08 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class ServicerstopropertiesRepository
 * @package AppBundle\Repository
 */
class ServicerstopropertiesRepository extends EntityRepository
{
    /**
     * @param $vendorID
     * @return mixed
     */
    public function PropertiesForVendors($vendorID, $limit = null)
    {
        $result = $this->createQueryBuilder('v')
            ->select('p.propertyid AS PropertyID,p.propertyname AS PropertyName')
            ->leftJoin('v.propertyid', 'p')
            ->where('p.active=1')
            ->andWhere('v.servicerid= :VendorID')
            ->setParameter('VendorID', (int)$vendorID);
        if ($limit) {
            $result->setMaxResults($limit);
        }

        return $result
            ->getQuery()
            ->execute();
    }
}