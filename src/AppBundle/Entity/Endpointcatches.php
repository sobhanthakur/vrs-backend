<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Endpointcatches
 *
 * @ORM\Table(name="EndpointCatches", indexes={@ORM\Index(name="IDX_D5442D99854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Endpointcatches
{
    /**
     * @var int
     *
     * @ORM\Column(name="EndpointCatchID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $endpointcatchid;

    /**
     * @var int
     *
     * @ORM\Column(name="PartnerID", type="integer", nullable=false, options={"comment"="1=Pointcentral, 2="})
     */
    private $partnerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PointCentralEventType", type="integer", nullable=true)
     */
    private $pointcentraleventtype;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PointCentralEventSubType", type="integer", nullable=true)
     */
    private $pointcentraleventsubtype;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PointCentralCustomerID", type="integer", nullable=true)
     */
    private $pointcentralcustomerid;

    /**
     * @var bool
     *
     * @ORM\Column(name="Handled", type="boolean", nullable=false)
     */
    private $handled = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="PartnerPropertyID", type="string", length=50, nullable=true)
     */
    private $partnerpropertyid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CatchData", type="text", length=-1, nullable=true)
     */
    private $catchdata;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="HandledDate", type="datetime", nullable=true)
     */
    private $handleddate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
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
     * Get endpointcatchid.
     *
     * @return int
     */
    public function getEndpointcatchid()
    {
        return $this->endpointcatchid;
    }

    /**
     * Set partnerid.
     *
     * @param int $partnerid
     *
     * @return Endpointcatches
     */
    public function setPartnerid($partnerid)
    {
        $this->partnerid = $partnerid;

        return $this;
    }

    /**
     * Get partnerid.
     *
     * @return int
     */
    public function getPartnerid()
    {
        return $this->partnerid;
    }

    /**
     * Set pointcentraleventtype.
     *
     * @param int|null $pointcentraleventtype
     *
     * @return Endpointcatches
     */
    public function setPointcentraleventtype($pointcentraleventtype = null)
    {
        $this->pointcentraleventtype = $pointcentraleventtype;

        return $this;
    }

    /**
     * Get pointcentraleventtype.
     *
     * @return int|null
     */
    public function getPointcentraleventtype()
    {
        return $this->pointcentraleventtype;
    }

    /**
     * Set pointcentraleventsubtype.
     *
     * @param int|null $pointcentraleventsubtype
     *
     * @return Endpointcatches
     */
    public function setPointcentraleventsubtype($pointcentraleventsubtype = null)
    {
        $this->pointcentraleventsubtype = $pointcentraleventsubtype;

        return $this;
    }

    /**
     * Get pointcentraleventsubtype.
     *
     * @return int|null
     */
    public function getPointcentraleventsubtype()
    {
        return $this->pointcentraleventsubtype;
    }

    /**
     * Set pointcentralcustomerid.
     *
     * @param int|null $pointcentralcustomerid
     *
     * @return Endpointcatches
     */
    public function setPointcentralcustomerid($pointcentralcustomerid = null)
    {
        $this->pointcentralcustomerid = $pointcentralcustomerid;

        return $this;
    }

    /**
     * Get pointcentralcustomerid.
     *
     * @return int|null
     */
    public function getPointcentralcustomerid()
    {
        return $this->pointcentralcustomerid;
    }

    /**
     * Set handled.
     *
     * @param bool $handled
     *
     * @return Endpointcatches
     */
    public function setHandled($handled)
    {
        $this->handled = $handled;

        return $this;
    }

    /**
     * Get handled.
     *
     * @return bool
     */
    public function getHandled()
    {
        return $this->handled;
    }

    /**
     * Set partnerpropertyid.
     *
     * @param string|null $partnerpropertyid
     *
     * @return Endpointcatches
     */
    public function setPartnerpropertyid($partnerpropertyid = null)
    {
        $this->partnerpropertyid = $partnerpropertyid;

        return $this;
    }

    /**
     * Get partnerpropertyid.
     *
     * @return string|null
     */
    public function getPartnerpropertyid()
    {
        return $this->partnerpropertyid;
    }

    /**
     * Set catchdata.
     *
     * @param string|null $catchdata
     *
     * @return Endpointcatches
     */
    public function setCatchdata($catchdata = null)
    {
        $this->catchdata = $catchdata;

        return $this;
    }

    /**
     * Get catchdata.
     *
     * @return string|null
     */
    public function getCatchdata()
    {
        return $this->catchdata;
    }

    /**
     * Set handleddate.
     *
     * @param \DateTime|null $handleddate
     *
     * @return Endpointcatches
     */
    public function setHandleddate($handleddate = null)
    {
        $this->handleddate = $handleddate;

        return $this;
    }

    /**
     * Get handleddate.
     *
     * @return \DateTime|null
     */
    public function getHandleddate()
    {
        return $this->handleddate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Endpointcatches
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
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Endpointcatches
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
