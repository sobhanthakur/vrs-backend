<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 *
 * @ORM\Table(name="Notifications", indexes={@ORM\Index(name="MessageID", columns={"MessageID", "AccountCustomerID", "TaskID"}), @ORM\Index(name="servicerID", columns={"ServicerID"}), @ORM\Index(name="TaskID", columns={"TaskID"}), @ORM\Index(name="TypeID", columns={"TypeID"}), @ORM\Index(name="IDX_D37EFB26C409BF01", columns={"AccountCustomerID"}), @ORM\Index(name="IDX_D37EFB26148DE471", columns={"OwnerID"}), @ORM\Index(name="IDX_D37EFB26CC6341F", columns={"PropertyBookingID"}), @ORM\Index(name="IDX_D37EFB26AC1A3790", columns={"FromServicerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Notifications
{
    /**
     * @var int
     *
     * @ORM\Column(name="NotificationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $notificationid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MessageID", type="integer", nullable=true)
     */
    private $messageid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SendToMaintenanceStaff", type="integer", nullable=true)
     */
    private $sendtomaintenancestaff;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SendToManagers", type="integer", nullable=true)
     */
    private $sendtomanagers;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CustomerNotificationID", type="integer", nullable=true)
     */
    private $customernotificationid;

    /**
     * @var int
     *
     * @ORM\Column(name="TypeID", type="integer", nullable=false)
     */
    private $typeid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CustomerID", type="integer", nullable=true)
     */
    private $customerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ToCustomerID", type="integer", nullable=true)
     */
    private $tocustomerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="FromCustomerID", type="integer", nullable=true)
     */
    private $fromcustomerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SubmittedByServicerID", type="integer", nullable=true)
     */
    private $submittedbyservicerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="IssueID", type="integer", nullable=true)
     */
    private $issueid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ListStartDate", type="date", nullable=true)
     */
    private $liststartdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ListEndDate", type="date", nullable=true)
     */
    private $listenddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Subject", type="text", length=-1, nullable=true)
     */
    private $subject;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AdditionalTextMessage", type="text", nullable=true)
     */
    private $additionaltextmessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AdditionalMessage", type="text", nullable=true)
     */
    private $additionalmessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Email", type="text", length=-1, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Phone", type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Notification", type="text", length=-1, nullable=true)
     */
    private $notification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;

    /**
     * @var \Tasks
     *
     * @ORM\ManyToOne(targetEntity="Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TaskID", referencedColumnName="TaskID")
     * })
     */
    private $taskid;

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="AccountCustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $accountcustomerid;

    /**
     * @var \Owners
     *
     * @ORM\ManyToOne(targetEntity="Owners")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OwnerID", referencedColumnName="OwnerID")
     * })
     */
    private $ownerid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;

    /**
     * @var \Propertybookings
     *
     * @ORM\ManyToOne(targetEntity="Propertybookings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyBookingID", referencedColumnName="PropertyBookingID")
     * })
     */
    private $propertybookingid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FromServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $fromservicerid;



    /**
     * Get notificationid.
     *
     * @return int
     */
    public function getNotificationid()
    {
        return $this->notificationid;
    }

    /**
     * Set messageid.
     *
     * @param int|null $messageid
     *
     * @return Notifications
     */
    public function setMessageid($messageid = null)
    {
        $this->messageid = $messageid;

        return $this;
    }

    /**
     * Get messageid.
     *
     * @return int|null
     */
    public function getMessageid()
    {
        return $this->messageid;
    }

    /**
     * Set customernotificationid.
     *
     * @param int|null $customernotificationid
     *
     * @return Notifications
     */
    public function setCustomernotificationid($customernotificationid = null)
    {
        $this->customernotificationid = $customernotificationid;

        return $this;
    }

    /**
     * Get customernotificationid.
     *
     * @return int|null
     */
    public function getCustomernotificationid()
    {
        return $this->customernotificationid;
    }

    /**
     * Set typeid.
     *
     * @param int $typeid
     *
     * @return Notifications
     */
    public function setTypeid($typeid)
    {
        $this->typeid = $typeid;

        return $this;
    }

    /**
     * Get typeid.
     *
     * @return int
     */
    public function getTypeid()
    {
        return $this->typeid;
    }

    /**
     * Set customerid.
     *
     * @param int|null $customerid
     *
     * @return Notifications
     */
    public function setCustomerid($customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return int|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set fromcustomerid.
     *
     * @param int|null $fromcustomerid
     *
     * @return Notifications
     */
    public function setFromcustomerid($fromcustomerid = null)
    {
        $this->fromcustomerid = $fromcustomerid;

        return $this;
    }

    /**
     * Get fromcustomerid.
     *
     * @return int|null
     */
    public function getFromcustomerid()
    {
        return $this->fromcustomerid;
    }

    /**
     * Set issueid.
     *
     * @param int|null $issueid
     *
     * @return Notifications
     */
    public function setIssueid($issueid = null)
    {
        $this->issueid = $issueid;

        return $this;
    }

    /**
     * Get issueid.
     *
     * @return int|null
     */
    public function getIssueid()
    {
        return $this->issueid;
    }

    /**
     * Set liststartdate.
     *
     * @param \DateTime|null $liststartdate
     *
     * @return Notifications
     */
    public function setListstartdate($liststartdate = null)
    {
        $this->liststartdate = $liststartdate;

        return $this;
    }

    /**
     * Get liststartdate.
     *
     * @return \DateTime|null
     */
    public function getListstartdate()
    {
        return $this->liststartdate;
    }

    /**
     * Set listenddate.
     *
     * @param \DateTime|null $listenddate
     *
     * @return Notifications
     */
    public function setListenddate($listenddate = null)
    {
        $this->listenddate = $listenddate;

        return $this;
    }

    /**
     * Get listenddate.
     *
     * @return \DateTime|null
     */
    public function getListenddate()
    {
        return $this->listenddate;
    }

    /**
     * Set subject.
     *
     * @param string|null $subject
     *
     * @return Notifications
     */
    public function setSubject($subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Notifications
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Notifications
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set notification.
     *
     * @param string|null $notification
     *
     * @return Notifications
     */
    public function setNotification($notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification.
     *
     * @return string|null
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Notifications
     */
    public function setCreatedate($createdate)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set taskid.
     *
     * @param \AppBundle\Entity\Tasks|null $taskid
     *
     * @return Notifications
     */
    public function setTaskid(\AppBundle\Entity\Tasks $taskid = null)
    {
        $this->taskid = $taskid;

        return $this;
    }

    /**
     * Get taskid.
     *
     * @return \AppBundle\Entity\Tasks|null
     */
    public function getTaskid()
    {
        return $this->taskid;
    }

    /**
     * Set accountcustomerid.
     *
     * @param \AppBundle\Entity\Customers|null $accountcustomerid
     *
     * @return Notifications
     */
    public function setAccountcustomerid(\AppBundle\Entity\Customers $accountcustomerid = null)
    {
        $this->accountcustomerid = $accountcustomerid;

        return $this;
    }

    /**
     * Get accountcustomerid.
     *
     * @return \AppBundle\Entity\Customers|null
     */
    public function getAccountcustomerid()
    {
        return $this->accountcustomerid;
    }

    /**
     * Set ownerid.
     *
     * @param \AppBundle\Entity\Owners|null $ownerid
     *
     * @return Notifications
     */
    public function setOwnerid(\AppBundle\Entity\Owners $ownerid = null)
    {
        $this->ownerid = $ownerid;

        return $this;
    }

    /**
     * Get ownerid.
     *
     * @return \AppBundle\Entity\Owners|null
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Notifications
     */
    public function setServicerid(\AppBundle\Entity\Servicers $servicerid = null)
    {
        $this->servicerid = $servicerid;

        return $this;
    }

    /**
     * Get servicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }

    /**
     * Set propertybookingid.
     *
     * @param \AppBundle\Entity\Propertybookings|null $propertybookingid
     *
     * @return Notifications
     */
    public function setPropertybookingid(\AppBundle\Entity\Propertybookings $propertybookingid = null)
    {
        $this->propertybookingid = $propertybookingid;

        return $this;
    }

    /**
     * Get propertybookingid.
     *
     * @return \AppBundle\Entity\Propertybookings|null
     */
    public function getPropertybookingid()
    {
        return $this->propertybookingid;
    }

    /**
     * Set fromservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $fromservicerid
     *
     * @return Notifications
     */
    public function setFromservicerid(\AppBundle\Entity\Servicers $fromservicerid = null)
    {
        $this->fromservicerid = $fromservicerid;

        return $this;
    }

    /**
     * Get fromservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getFromservicerid()
    {
        return $this->fromservicerid;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedate() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }

    /**
     * Set sendtomaintenancestaff.
     *
     * @param int|null $sendtomaintenancestaff
     *
     * @return Notifications
     */
    public function setSendtomaintenancestaff($sendtomaintenancestaff = null)
    {
        $this->sendtomaintenancestaff = $sendtomaintenancestaff;

        return $this;
    }

    /**
     * Get sendtomaintenancestaff.
     *
     * @return int|null
     */
    public function getSendtomaintenancestaff()
    {
        return $this->sendtomaintenancestaff;
    }

    /**
     * Set sendtomanagers.
     *
     * @param int|null $sendtomanagers
     *
     * @return Notifications
     */
    public function setSendtomanagers($sendtomanagers = null)
    {
        $this->sendtomanagers = $sendtomanagers;

        return $this;
    }

    /**
     * Get sendtomanagers.
     *
     * @return int|null
     */
    public function getSendtomanagers()
    {
        return $this->sendtomanagers;
    }

    /**
     * Set submittedbyservicerid.
     *
     * @param int|null $submittedbyservicerid
     *
     * @return Notifications
     */
    public function setSubmittedbyservicerid($submittedbyservicerid = null)
    {
        $this->submittedbyservicerid = $submittedbyservicerid;

        return $this;
    }

    /**
     * Get submittedbyservicerid.
     *
     * @return int|null
     */
    public function getSubmittedbyservicerid()
    {
        return $this->submittedbyservicerid;
    }

    /**
     * Set additionaltextmessage.
     *
     * @param string|null $additionaltextmessage
     *
     * @return Notifications
     */
    public function setAdditionaltextmessage($additionaltextmessage = null)
    {
        $this->additionaltextmessage = $additionaltextmessage;

        return $this;
    }

    /**
     * Get additionaltextmessage.
     *
     * @return string|null
     */
    public function getAdditionaltextmessage()
    {
        return $this->additionaltextmessage;
    }

    /**
     * Set additionalmessage.
     *
     * @param string|null $additionalmessage
     *
     * @return Notifications
     */
    public function setAdditionalmessage($additionalmessage = null)
    {
        $this->additionalmessage = $additionalmessage;

        return $this;
    }

    /**
     * Get additionalmessage.
     *
     * @return string|null
     */
    public function getAdditionalmessage()
    {
        return $this->additionalmessage;
    }

    /**
     * Set tocustomerid.
     *
     * @param int|null $tocustomerid
     *
     * @return Notifications
     */
    public function setTocustomerid($tocustomerid = null)
    {
        $this->tocustomerid = $tocustomerid;

        return $this;
    }

    /**
     * Get tocustomerid.
     *
     * @return int|null
     */
    public function getTocustomerid()
    {
        return $this->tocustomerid;
    }
}
