<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/4/20
 * Time: 1:02 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
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
            ->andWhere('n.startdate = :Today')
            ->andWhere('n.showonemployeedashboard = 1')
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
    public function SchedulingNotesForDashboard2($servicerID,$servicers, $currentTaskDate)
    {
        $currentDateTime = new \DateTime('now');
        $currentDateTime->setTimezone(new \DateTimeZone($servicers[0][GeneralConstants::REGION]));
        $currentDateTime->setTime(0,0,0);

        $currentDateTime7Days = clone $currentDateTime;
        $currentDateTime7Days->modify('+7 days');
        $currentDateTime7Days->setTime(0,0,0);

        if ($currentTaskDate && $currentTaskDate < $currentDateTime) {
            $today = $currentTaskDate;
        } else {
            $today = $currentDateTime;
        }

        return $this->createQueryBuilder('n')
            ->select('n.hovernote AS HoverNote')
            ->addSelect('n.longdescription AS LongDescription')
            ->addSelect('n.shortnote AS Shortnote')
            ->addSelect('n.startdate AS StartDate')
            ->where('n.servicerid='.(int)$servicerID)
            ->andWhere('n.startdate <= :After7Days')
            ->andWhere('n.startdate >= :Today')
            ->andWhere('n.showonemployeedashboard = 1')
            ->setParameter('After7Days',$currentDateTime7Days)
            ->setParameter('Today',$today)
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