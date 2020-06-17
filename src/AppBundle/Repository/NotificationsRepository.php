<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 17/6/20
 * Time: 5:13 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class NotificationsRepository
 * @package AppBundle\Repository
 */
class NotificationsRepository extends EntityRepository
{
    /**
     * @param $taskID
     * @param $customerID
     * @return mixed
     */
    public function TaskNotificationInLastOneMinute($taskID, $customerID)
    {
        $now = (new \DateTime('now'))->modify('-1 minutes');
        return $this->createQueryBuilder('n')
            ->select('COUNT(n.notificationid) AS Count')
            ->where('n.taskid='.$taskID)
            ->andWhere('n.customerid='.$customerID)
            ->andWhere('n.createdate > :CreateDate')
            ->andWhere('n.messageid=27')
            ->setParameter('CreateDate',$now)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();


    }
}