<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 14/4/20
 * Time: 3:07 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class ImagesRepository
 * @package AppBundle\Repository
 */
class ImagesRepository extends EntityRepository
{
    /**
     * @param $propertyID
     * @return mixed
     */
    public function GetImageCountForDashboard($propertyID)
    {
        return $this->createQueryBuilder('i')
            ->select('i.imageid')
            ->where('i.propertyid=' . $propertyID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $propertyID
     * @param $serviceID
     * @return mixed
     */
    public function GetImagesForImageTab($propertyID, $serviceID)
    {
        return $this->createQueryBuilder('i')
            ->select('i.imagetitle AS ImageTitile,i.image AS Image,i.imagedescription AS ImageDescription')
            ->where('i.propertyid='.$propertyID)
            ->andWhere('i.serviceids like :LikeServiceID OR i.serviceids= :Blank OR i.serviceids IS NULL')
            ->setParameter('LikeServiceID','%'.$serviceID.'%')
            ->setParameter('Blank','')
            ->getQuery()
            ->execute();
    }
}