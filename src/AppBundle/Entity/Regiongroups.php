<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Regiongroups
 *
 * @ORM\Table(name="RegionGroups", indexes={@ORM\Index(name="IDX_DDAC083A854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegionGroupsRepository")
 */
class Regiongroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="RegionGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $regiongroupid;

    /**
     * @var string
     *
     * @ORM\Column(name="RegionGroup", type="string", length=50, nullable=false)
     */
    private $regiongroup;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDAte", type="datetime", nullable=false, options={"default"="getutcdate()"})
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
     * Get regiongroupid.
     *
     * @return int
     */
    public function getRegiongroupid()
    {
        return $this->regiongroupid;
    }

    /**
     * Set regiongroup.
     *
     * @param string $regiongroup
     *
     * @return Regiongroups
     */
    public function setRegiongroup($regiongroup)
    {
        $this->regiongroup = $regiongroup;

        return $this;
    }

    /**
     * Get regiongroup.
     *
     * @return string
     */
    public function getRegiongroup()
    {
        return $this->regiongroup;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Regiongroups
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
     * @param \DateTime $createdate
     *
     * @return Regiongroups
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
     * @return Regiongroups
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
