<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 16/6/20
 * Time: 3:28 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Notifications;
use AppBundle\Constants\GeneralConstants;

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
        $notification->setTaskid($result[GeneralConstants::TASK_ID]);
        $notification->setMessageid($result[GeneralConstants::MESSAGE_ID]);
        $notification->setCustomerid($result[GeneralConstants::CUSTOMER_ID]);

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
        if ($result[GeneralConstants::TASK_ID]) {
            $notification->setTaskid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($result[GeneralConstants::TASK_ID]));
        }
        $notification->setMessageid($result[GeneralConstants::MESSAGE_ID]);
        $notification->setCustomerid($result[GeneralConstants::CUSTOMER_ID]);
        $notification->setIssueid($result['IssueID']);
        $notification->setOwnerid($result[GeneralConstants::OWNERID] && $result[GeneralConstants::OWNERID] !== 0 ? $result[GeneralConstants::OWNERID] : null);
        $notification->setSendtomaintenancestaff($result['SendToMaintenanceStaff']);
        $notification->setSendtomanagers($result[GeneralConstants::SENDTOMANAGERS]);
        $notification->setSubmittedbyservicerid($result[GeneralConstants::SERVICERID]);

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
        $notification->setTaskid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($result[GeneralConstants::TASK_ID]));
        $notification->setMessageid($result[GeneralConstants::MESSAGE_ID]);
        $notification->setCustomerid($result[GeneralConstants::CUSTOMER_ID]);
        $notification->setSendtomanagers($result[GeneralConstants::SENDTOMANAGERS]);
        $notification->setSubmittedbyservicerid($result['SubmittedByServicerID']);

        if ($servicerID) {
            $notification->setServicerid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->find($result['BackupServicerID']));
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
        $notification->setTaskid($this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->find($result[GeneralConstants::TASK_ID]));
        $notification->setMessageid($result[GeneralConstants::MESSAGE_ID]);
        $notification->setCustomerid($result[GeneralConstants::CUSTOMER_ID]);
        $notification->setTocustomerid($result['ToCustomerID']);

        if (array_key_exists(GeneralConstants::SERVICERID,$result)) {
            $notification->setServicerid($result[GeneralConstants::SERVICERID]);
        }

        if ($currentDate) {
            $notification->setCreatedate($currentDate);
        }

        // Set null if owner ID is 0
        $result[GeneralConstants::OWNERID] !== 0 ? $ownerID= $this->entityManager->getRepository('AppBundle:Owners')->find($result[GeneralConstants::OWNERID]) : $ownerID = null;

        $notification->setOwnerid($ownerID);
        $notification->setSendtomaintenancestaff($result['SendToMaintenanceStaff']);
        $notification->setSendtomanagers($result[GeneralConstants::SENDTOMANAGERS]);
        $notification->setSubmittedbyservicerid($result['SubmittedByServicerID']);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification->getNotificationid();
    }
}