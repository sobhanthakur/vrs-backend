<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerstoregiongroups
 *
 * @ORM\Table(name="ServicersToRegionGroups", indexes={@ORM\Index(name="IDX_4F3E73D23C7E7BEF", columns={"ServicerID"})})
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
     * @var int
     *
     * @ORM\Column(name="RegionGroupID", type="integer", nullable=false)
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
     * @param int $regiongroupid
     *
     * @return Servicerstoregiongroups
     */
    public function setRegiongroupid($regiongroupid)
    {
        $this->regiongroupid = $regiongroupid;

        return $this;
    }

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
