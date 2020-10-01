<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 *
 * @ORM\Table(name="NotificationsToSend", indexes={@ORM\Index(name="MessageID", columns={"MessageID", "AccountCustomerID", "TaskID"}), @ORM\Index(name="servicerID", columns={"ServicerID"}), @ORM\Index(name="TaskID", columns={"TaskID"}), @ORM\Index(name="IDX_D37EFB26C409BF01", columns={"AccountCustomerID"}), @ORM\Index(name="IDX_D37EFB26148DE471", columns={"OwnerID"}), @ORM\Index(name="IDX_D37EFB26CC6341F", columns={"PropertyBookingID"}), @ORM\Index(name="IDX_D37EFB26AC1A3790", columns={"FromServicerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Notifications
{
    /**
     * @var int
     *
     * @ORM\Column(name="NotificationToSendID", type="integer", nullable=false)
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
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;

    /**
     * @var Tasks
     *
     * @ORM\ManyToOne(targetEntity="Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TaskID", referencedColumnName="TaskID")
     * })
     */
    private $taskid;

    /**
     * @var Owners
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
}
