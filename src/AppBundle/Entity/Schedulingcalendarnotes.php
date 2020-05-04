<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Schedulingcalendarnotes
 *
 * @ORM\Table(name="SchedulingCalendarNotes", indexes={@ORM\Index(name="customerid", columns={"CustomerID"}), @ORM\Index(name="servicerid", columns={"ServicerID"}), @ORM\Index(name="StartDate", columns={"StartDate"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SchedulingcalendarnotesRepository")
 */
class Schedulingcalendarnotes
{
    /**
     * @var int
     *
     * @ORM\Column(name="SchedulingCalendarNoteID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $schedulingcalendarnoteid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="StartDate", type="date", nullable=false)
     */
    private $startdate;

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
     * @var bool
     *
     * @ORM\Column(name="ShowOnEmployeeDashboard", type="boolean", nullable=false)
     */
    private $showonemployeedashboard = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="CreatedByServicerID", type="integer", nullable=false)
     */
    private $createdbyservicerid;

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
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;



    /**
     * Get schedulingcalendarnoteid.
     *
     * @return int
     */
    public function getSchedulingcalendarnoteid()
    {
        return $this->schedulingcalendarnoteid;
    }

    /**
     * Set startdate.
     *
     * @param \DateTime $startdate
     *
     * @return Schedulingcalendarnotes
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
     * Set shortnote.
     *
     * @param string|null $shortnote
     *
     * @return Schedulingcalendarnotes
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
     * @return Schedulingcalendarnotes
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
     * @return Schedulingcalendarnotes
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
     * Set showonemployeedashboard.
     *
     * @param bool $showonemployeedashboard
     *
     * @return Schedulingcalendarnotes
     */
    public function setShowonemployeedashboard($showonemployeedashboard)
    {
        $this->showonemployeedashboard = $showonemployeedashboard;

        return $this;
    }

    /**
     * Get showonemployeedashboard.
     *
     * @return bool
     */
    public function getShowonemployeedashboard()
    {
        return $this->showonemployeedashboard;
    }

    /**
     * Set createdbyservicerid.
     *
     * @param int $createdbyservicerid
     *
     * @return Schedulingcalendarnotes
     */
    public function setCreatedbyservicerid($createdbyservicerid)
    {
        $this->createdbyservicerid = $createdbyservicerid;

        return $this;
    }

    /**
     * Get createdbyservicerid.
     *
     * @return int
     */
    public function getCreatedbyservicerid()
    {
        return $this->createdbyservicerid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Schedulingcalendarnotes
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
     * @return Schedulingcalendarnotes
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Schedulingcalendarnotes
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
