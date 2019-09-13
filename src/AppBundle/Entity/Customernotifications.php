<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customernotifications
 *
 * @ORM\Table(name="CustomerNotifications", indexes={@ORM\Index(name="IDX_10357F4E854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Customernotifications
{
    /**
     * @var int
     *
     * @ORM\Column(name="CustomerNotificationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customernotificationid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CustomerNotification", type="string", length=5000, nullable=true)
     */
    private $customernotification;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Subject", type="string", length=200, nullable=true)
     */
    private $subject;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ForOwner", type="boolean", nullable=true)
     */
    private $forowner;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ForVendor", type="boolean", nullable=true)
     */
    private $forvendor;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ForServicer", type="boolean", nullable=true)
     */
    private $forservicer;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;



    /**
     * Get customernotificationid.
     *
     * @return int
     */
    public function getCustomernotificationid()
    {
        return $this->customernotificationid;
    }

    /**
     * Set customernotification.
     *
     * @param string|null $customernotification
     *
     * @return Customernotifications
     */
    public function setCustomernotification($customernotification = null)
    {
        $this->customernotification = $customernotification;

        return $this;
    }

    /**
     * Get customernotification.
     *
     * @return string|null
     */
    public function getCustomernotification()
    {
        return $this->customernotification;
    }

    /**
     * Set subject.
     *
     * @param string|null $subject
     *
     * @return Customernotifications
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
     * Set forowner.
     *
     * @param bool|null $forowner
     *
     * @return Customernotifications
     */
    public function setForowner($forowner = null)
    {
        $this->forowner = $forowner;

        return $this;
    }

    /**
     * Get forowner.
     *
     * @return bool|null
     */
    public function getForowner()
    {
        return $this->forowner;
    }

    /**
     * Set forvendor.
     *
     * @param bool|null $forvendor
     *
     * @return Customernotifications
     */
    public function setForvendor($forvendor = null)
    {
        $this->forvendor = $forvendor;

        return $this;
    }

    /**
     * Get forvendor.
     *
     * @return bool|null
     */
    public function getForvendor()
    {
        return $this->forvendor;
    }

    /**
     * Set forservicer.
     *
     * @param bool|null $forservicer
     *
     * @return Customernotifications
     */
    public function setForservicer($forservicer = null)
    {
        $this->forservicer = $forservicer;

        return $this;
    }

    /**
     * Get forservicer.
     *
     * @return bool|null
     */
    public function getForservicer()
    {
        return $this->forservicer;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Customernotifications
     */
    public function setCreatedate($createdate = null)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime|null
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Customernotifications
     */
    public function setCustomerid(\AppBundle\Entity\Customers $customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return \AppBundle\Entity\Customers|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }
}
