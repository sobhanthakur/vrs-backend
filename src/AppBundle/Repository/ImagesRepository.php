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
    public function GetImagesForImageTab($propertyID, $serviceID=null,$limit = null)
    {
        $result = $this->createQueryBuilder('i')
            ->select('i.embedtag AS EmbedTag,i.pdf AS PDF,i.sortorder AS SortOrder,i.imageid AS ImageID,i.imagetitle AS ImageTitle,i.image AS Image,i.imagedescription AS ImageDescription')
            ->where('i.propertyid=' . $propertyID);

        if ($serviceID) {
            $result->andWhere('i.serviceids like :LikeServiceID OR i.serviceids= :Blank OR i.serviceids IS NULL')
                ->setParameter('LikeServiceID', '%' . $serviceID . '%')
                ->setParameter('Blank', '');
        } else {
            $result->andWhere('i.serviceids= :Blank OR i.serviceids IS NULL')
                ->setParameter('Blank', '');
        }

        if ($limit) {
            $result->setMaxResults(1);
        }

        return $result->getQuery()
            ->execute();
    }
}