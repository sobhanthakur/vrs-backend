<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Regions
 *
 * @ORM\Table(name="Regions", indexes={@ORM\Index(name="customerid", columns={"CustomerID"}), @ORM\Index(name="sortorder", columns={"SortOrder"}), @ORM\Index(name="IDX_6DDA406F40CBC1C7", columns={"RegionGroupID"}), @ORM\Index(name="IDX_6DDA406F424D9CA0", columns={"TimeZoneID"})})
 * @ORM\Entity
 */
class Regions
{
    /**
     * @var int
     *
     * @ORM\Column(name="RegionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $regionid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Region", type="string", length=50, nullable=true)
     */
    private $region;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Color", type="string", length=7, nullable=true, options={"fixed"=true})
     */
    private $color;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

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
     * @var \Regiongroups
     *
     * @ORM\ManyToOne(targetEntity="Regiongroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RegionGroupID", referencedColumnName="RegionGroupID")
     * })
     */
    private $regiongroupid;

    /**
     * @var \Timezones
     *
     * @ORM\ManyToOne(targetEntity="Timezones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TimeZoneID", referencedColumnName="TimeZoneID")
     * })
     */
    private $timezoneid;



    /**
     * Get regionid.
     *
     * @return int
     */
    public function getRegionid()
    {
        return $this->regionid;
    }

    /**
     * Set region.
     *
     * @param string|null $region
     *
     * @return Regions
     */
    public function setRegion($region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set color.
     *
     * @param string|null $color
     *
     * @return Regions
     */
    public function setColor($color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Regions
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
     * @return Regions
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
     * @return Regions
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
     * Set regiongroupid.
     *
     * @param \AppBundle\Entity\Regiongroups|null $regiongroupid
     *
     * @return Regions
     */
    public function setRegiongroupid(\AppBundle\Entity\Regiongroups $regiongroupid = null)
    {
        $this->regiongroupid = $regiongroupid;

        return $this;
    }

    /**
     * Get regiongroupid.
     *
     * @return \AppBundle\Entity\Regiongroups|null
     */
    public function getRegiongroupid()
    {
        return $this->regiongroupid;
    }

    /**
     * Set timezoneid.
     *
     * @param \AppBundle\Entity\Timezones|null $timezoneid
     *
     * @return Regions
     */
    public function setTimezoneid(\AppBundle\Entity\Timezones $timezoneid = null)
    {
        $this->timezoneid = $timezoneid;

        return $this;
    }

    /**
     * Get timezoneid.
     *
     * @return \AppBundle\Entity\Timezones|null
     */
    public function getTimezoneid()
    {
        return $this->timezoneid;
    }
}
