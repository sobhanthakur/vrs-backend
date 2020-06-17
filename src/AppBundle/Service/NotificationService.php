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
     * @return integer
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateTaskNotification($result)
    {
        $notification = new Notifications();
        $notification->setTaskid($result['TaskID']);
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setTypeid($result['TypeID']);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification->getNotificationid();
    }

    /**
     * @param $result
     * @return int
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateIssueNotification($result)
    {
        $notification = new Notifications();
        $notification->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($result['TaskID']));
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setIssueid($result['IssueID']);
        $notification->setOwnerid($result['OwnerID'] && $result['OwnerID'] !== 0 ? $result['OwnerID'] : null);
        $notification->setSendtomaintenancestaff($result['SendToMaintenanceStaff']);
        $notification->setSendtomanagers($result['SendToManagers']);
        $notification->setSubmittedbyservicerid($result['ServicerID']);
        $notification->setTypeid($result['TypeID']);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification->getNotificationid();
    }

    /**
     * @param $result
     * @return int
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateTaskAcceptDeclineNotification($result,$servicerID=null,$additionalTextMessage = null,$additionalMessage = null)
    {
        $notification = new Notifications();
        $notification->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($result['TaskID']));
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setSendtomanagers($result['SendToManagers']);
        $notification->setSubmittedbyservicerid($result['SubmittedByServicerID']);
        $notification->setTypeid($result['TypeID']);

        if ($servicerID) {
            $notification->setServicerid($this->entityManager->getRepository('AppBundle:Servicers')->find($result['BackupServicerID']));
        }

        if ($additionalMessage) {
            $notification->setAdditionalmessage($result['AdditionalMessage']);
        }

        if ($additionalTextMessage) {
            $notification->setAdditionaltextmessage($result['AdditionalTextMessage']);
        }

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification->getNotificationid();

    }
}