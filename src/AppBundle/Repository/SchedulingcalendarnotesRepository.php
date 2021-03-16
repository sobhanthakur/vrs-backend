<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/4/20
 * Time: 1:02 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class SchedulingcalendarnotesRepository
 * @package AppBundle\Repository
 */
class SchedulingcalendarnotesRepository extends EntityRepository
{
    /**
     * @param $servicerID
     * @param \DateTime $today
     * @return mixed
     */
    public function SchedulingNotesForDashboard($servicerID, $today)
    {
        return $this->createQueryBuilder('n')
            ->select('n.hovernote AS HoverNote')
            ->addSelect('n.longdescription AS LongDescription')
            ->addSelect('n.shortnote AS Shortnote')
            ->where('n.servicerid='.$servicerID)
            ->andWhere('n.startdate= :Today')
            ->setParameter('Today',$today)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $servicerID
     * @param \DateTime $today
     * @return mixed
     */
    public function SchedulingNotesForAuthentication($servicerID, $today)
    {
        return $this->createQueryBuilder('n')
            ->select('n.schedulingcalendarnoteid')
            ->where('n.servicerid='.$servicerID)
            ->andWhere('n.startdate >= :Today')
            ->setParameter('Today',$today)
            ->andWhere('n.showonemployeedashboard=1')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}