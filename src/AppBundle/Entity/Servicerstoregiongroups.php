<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerstoregiongroups
 *
 * @ORM\Table(name="ServicersToRegionGroups", indexes={@ORM\Index(name="IDX_4F3E73D240CBC1C7", columns={"RegionGroupID"}), @ORM\Index(name="IDX_4F3E73D23C7E7BEF", columns={"ServicerID"})})
 * @ORM\Entity
 */
class Servicerstoregiongroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServicerToRegionGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicertoregiongroupid;

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
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;



    /**
     * Get servicertoregiongroupid.
     *
     * @return int
     */
    public function getServicertoregiongroupid()
    {
        return $this->servicertoregiongroupid;
    }

    /**
     * Set regiongroupid.
     *
     * @param \AppBundle\Entity\Regiongroups|null $regiongroupid
     *
     * @return Servicerstoregiongroups
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Servicerstoregiongroups
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
