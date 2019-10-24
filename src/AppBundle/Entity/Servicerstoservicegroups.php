<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerstoservicegroups
 *
 * @ORM\Table(name="ServicersToServiceGroups", indexes={@ORM\Index(name="IDX_C9B68EF913C20B89", columns={"ServiceGroupID"}), @ORM\Index(name="IDX_C9B68EF93C7E7BEF", columns={"ServicerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServicerstoservicegroupsRepository")
 */
class Servicerstoservicegroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServicerToServiceGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicertoservicegroupid;

    /**
     * @var \Servicegroups
     *
     * @ORM\ManyToOne(targetEntity="Servicegroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceGroupID", referencedColumnName="ServiceGroupID")
     * })
     */
    private $servicegroupid;

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
     * Get servicertoservicegroupid.
     *
     * @return int
     */
    public function getServicertoservicegroupid()
    {
        return $this->servicertoservicegroupid;
    }

    /**
     * Set servicegroupid.
     *
     * @param \AppBundle\Entity\Servicegroups|null $servicegroupid
     *
     * @return Servicerstoservicegroups
     */
    public function setServicegroupid(\AppBundle\Entity\Servicegroups $servicegroupid = null)
    {
        $this->servicegroupid = $servicegroupid;

        return $this;
    }

    /**
     * Get servicegroupid.
     *
     * @return \AppBundle\Entity\Servicegroups|null
     */
    public function getServicegroupid()
    {
        return $this->servicegroupid;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Servicerstoservicegroups
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
