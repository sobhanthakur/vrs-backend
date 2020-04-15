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
}