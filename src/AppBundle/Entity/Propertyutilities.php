<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertyutilities
 *
 * @ORM\Table(name="PropertyUtilities", indexes={@ORM\Index(name="propertyid", columns={"PropertyID"}), @ORM\Index(name="propertyutilityid", columns={"PropertyUtilityTypeID"}), @ORM\Index(name="sortorder", columns={"SortOrder"})})
 * @ORM\Entity
 */
class Propertyutilities
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyUtilityID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertyutilityid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PropertyUtility", type="string", length=150, nullable=true)
     */
    private $propertyutility;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Phone", type="string", length=150, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Web", type="string", length=400, nullable=true)
     */
    private $web;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Contact", type="string", length=150, nullable=true)
     */
    private $contact;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Location", type="string", length=150, nullable=true)
     */
    private $location;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=0, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false, options={"default"="1"})
     */
    private $sortorder = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * @var \Propertyutilitytypes
     *
     * @ORM\ManyToOne(targetEntity="Propertyutilitytypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyUtilityTypeID", referencedColumnName="PropertyUtilityTypeID")
     * })
     */
    private $propertyutilitytypeid;



    /**
     * Get propertyutilityid.
     *
     * @return int
     */
    public function getPropertyutilityid()
    {
        return $this->propertyutilityid;
    }

    /**
     * Set propertyutility.
     *
     * @param string|null $propertyutility
     *
     * @return Propertyutilities
     */
    public function setPropertyutility($propertyutility = null)
    {
        $this->propertyutility = $propertyutility;

        return $this;
    }

    /**
     * Get propertyutility.
     *
     * @return string|null
     */
    public function getPropertyutility()
    {
        return $this->propertyutility;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Propertyutilities
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
     * Set web.
     *
     * @param string|null $web
     *
     * @return Propertyutilities
     */
    public function setWeb($web = null)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web.
     *
     * @return string|null
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set contact.
     *
     * @param string|null $contact
     *
     * @return Propertyutilities
     */
    public function setContact($contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact.
     *
     * @return string|null
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set location.
     *
     * @param string|null $location
     *
     * @return Propertyutilities
     */
    public function setLocation($location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location.
     *
     * @return string|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Propertyutilities
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Propertyutilities
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Propertyutilities
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
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Propertyutilities
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
     * Set propertyutilitytypeid.
     *
     * @param \AppBundle\Entity\Propertyutilitytypes|null $propertyutilitytypeid
     *
     * @return Propertyutilities
     */
    public function setPropertyutilitytypeid(\AppBundle\Entity\Propertyutilitytypes $propertyutilitytypeid = null)
    {
        $this->propertyutilitytypeid = $propertyutilitytypeid;

        return $this;
    }

    /**
     * Get propertyutilitytypeid.
     *
     * @return \AppBundle\Entity\Propertyutilitytypes|null
     */
    public function getPropertyutilitytypeid()
    {
        return $this->propertyutilitytypeid;
    }
}
