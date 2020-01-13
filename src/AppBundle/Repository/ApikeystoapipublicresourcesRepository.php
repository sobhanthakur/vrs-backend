<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 2/1/20
 * Time: 4:06 PM
 */

namespace AppBundle\Repository;

/**
 * Class ApikeystoapipublicresourcesRepository
 * @package AppBundle\Repository
 */
class ApikeystoapipublicresourcesRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * fetch resource details with its restriction for particular user
     *
     * @param $apiKey
     * @return array
     */
    public function fetchResource($apiKey)
    {
        return $this
            ->createQueryBuilder('akpr')
            ->select('apr.resourcename as resourseName')
            ->addSelect('akpr.accesslevel as accessLevel')
            ->join('akpr.apikeyid', 'ak')
            ->join('akpr.apipublicresourceid', 'apr')
            ->where('akpr.apikeyid = :ApiKey')
            ->setParameter('ApiKey', $apiKey)
            ->getQuery()
            ->execute();
    }

}