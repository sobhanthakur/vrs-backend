<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mastercalendarnotes
 *
 * @ORM\Table(name="MasterCalendarNotes", indexes={@ORM\Index(name="CustomerID", columns={"CustomerID"}), @ORM\Index(name="EndDate", columns={"EndDate"}), @ORM\Index(name="PropertyID", columns={"PropertyID"}), @ORM\Index(name="StartDate", columns={"StartDate"}), @ORM\Index(name="IDX_9D105A90245F4372", columns={"CreatedByServicerID"})})
 * @ORM\Entity
 */
class Mastercalendarnotes
{
    /**
     * @var int
     *
     * @ORM\Column(name="MasterCalendarNoteID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mastercalendarnoteid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="StartDate", type="date", nullable=false)
     */
    private $startdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="EndDate", type="date", nullable=true)
     */
    private $enddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ShortNote", type="string", length=50, nullable=true)
     */
    private $shortnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="HoverNote", type="string", length=100, nullable=true)
     */
    private $hovernote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LongDescription", type="string", length=0, nullable=true)
     */
    private $longdescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';

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
     * @var \Properties
     *
     * @ORM\ManyToOne(targetEntity="Properties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyID", referencedColumnName="PropertyID")
     * })
     */
    private $propertyid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CreatedByServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $createdbyservicerid;



    /**
     * Get mastercalendarnoteid.
     *
     * @return int
     */
    public function getMastercalendarnoteid()
    {
        return $this->mastercalendarnoteid;
    }

    /**
     * Set startdate.
     *
     * @param \DateTime $startdate
     *
     * @return Mastercalendarnotes
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate.
     *
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate.
     *
     * @param \DateTime|null $enddate
     *
     * @return Mastercalendarnotes
     */
    public function setEnddate($enddate = null)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate.
     *
     * @return \DateTime|null
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set shortnote.
     *
     * @param string|null $shortnote
     *
     * @return Mastercalendarnotes
     */
    public function setShortnote($shortnote = null)
    {
        $this->shortnote = $shortnote;

        return $this;
    }

    /**
     * Get shortnote.
     *
     * @return string|null
     */
    public function getShortnote()
    {
        return $this->shortnote;
    }

    /**
     * Set hovernote.
     *
     * @param string|null $hovernote
     *
     * @return Mastercalendarnotes
     */
    public function setHovernote($hovernote = null)
    {
        $this->hovernote = $hovernote;

        return $this;
    }

    /**
     * Get hovernote.
     *
     * @return string|null
     */
    public function getHovernote()
    {
        return $this->hovernote;
    }

    /**
     * Set longdescription.
     *
     * @param string|null $longdescription
     *
     * @return Mastercalendarnotes
     */
    public function setLongdescription($longdescription = null)
    {
        $this->longdescription = $longdescription;

        return $this;
    }

    /**
     * Get longdescription.
     *
     * @return string|null
     */
    public function getLongdescription()
    {
        return $this->longdescription;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Mastercalendarnotes
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
     * @return Mastercalendarnotes
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

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Mastercalendarnotes
     */
    public function setPropertyid(\AppBundle\Entity\Properties $propertyid = null)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid.
     *
     * @return \AppBundle\Entity\Properties|null
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set createdbyservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $createdbyservicerid
     *
     * @return Mastercalendarnotes
     */
    public function setCreatedbyservicerid(\AppBundle\Entity\Servicers $createdbyservicerid = null)
    {
        $this->createdbyservicerid = $createdbyservicerid;

        return $this;
    }

    /**
     * Get createdbyservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getCreatedbyservicerid()
    {
        return $this->createdbyservicerid;
    }
}
