<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 16/6/20
 * Time: 3:28 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Notifications;

/**
 * Class NotificationService
 * @package AppBundle\Service
 */
class NotificationService extends BaseService
{
    /**
     * @param $result
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateTaskNotification($result)
    {
        $notification = new Notifications();
        $notification->setTaskid($result['TaskID']);
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setTypeid(0);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}