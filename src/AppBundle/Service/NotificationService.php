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
     * @param null|\DateTime $currentDate
     * @return int
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateTaskNotification($result, $currentDate=null)
    {
        $notification = new Notifications();
        $notification->setTaskid($result['TaskID']);
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);

        if ($currentDate) {
            $notification->setCreatedate($currentDate);
        }

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
    public function CreateIssueNotification($result,$currentDate = null)
    {
        $notification = new Notifications();
        if ($result['TaskID']) {
            $notification->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($result['TaskID']));
        }
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setIssueid($result['IssueID']);
        $notification->setOwnerid($result['OwnerID'] && $result['OwnerID'] !== 0 ? $result['OwnerID'] : null);
        $notification->setSendtomaintenancestaff($result['SendToMaintenanceStaff']);
        $notification->setSendtomanagers($result['SendToManagers']);
        $notification->setSubmittedbyservicerid($result['ServicerID']);

        if ($currentDate) {
            $notification->setCreatedate($currentDate);
        }

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
    public function CreateTaskAcceptDeclineNotification($result,$currentTime=null,$servicerID=null,$additionalTextMessage = null,$additionalMessage = null)
    {
        $notification = new Notifications();
        $notification->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($result['TaskID']));
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setSendtomanagers($result['SendToManagers']);
        $notification->setSubmittedbyservicerid($result['SubmittedByServicerID']);

        if ($servicerID) {
            $notification->setServicerid($this->entityManager->getRepository('AppBundle:Servicers')->find($result['BackupServicerID']));
        }

        if ($additionalMessage) {
            $notification->setAdditionalmessage($result['AdditionalMessage']);
        }

        if ($additionalTextMessage) {
            $notification->setAdditionaltextmessage($result['AdditionalTextMessage']);
        }

        if ($currentTime) {
            $notification->setCreatedate($currentTime);
        }

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
    public function CreateManageCompleteNotification($result,$currentDate=null)
    {
        $notification = new Notifications();
        $notification->setTaskid($this->entityManager->getRepository('AppBundle:Tasks')->find($result['TaskID']));
        $notification->setMessageid($result['MessageID']);
        $notification->setCustomerid($result['CustomerID']);
        $notification->setTocustomerid($result['ToCustomerID']);

        if (array_key_exists('ServicerID',$result)) {
            $notification->setServicerid($result['ServicerID']);
        }

        if ($currentDate) {
            $notification->setCreatedate($currentDate);
        }

        $notification->setOwnerid($this->entityManager->getRepository('AppBundle:Owners')->find($result['OwnerID']));
        $notification->setSendtomaintenancestaff($result['SendToMaintenanceStaff']);
        $notification->setSendtomanagers($result['SendToManagers']);
        $notification->setSubmittedbyservicerid($result['SubmittedByServicerID']);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification->getNotificationid();
    }
}