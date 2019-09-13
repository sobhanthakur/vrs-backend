<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Managerstoservicers
 *
 * @ORM\Table(name="ManagersToServicers", indexes={@ORM\Index(name="ManagerServicerID", columns={"ManagerServicerID"}), @ORM\Index(name="ServicerID", columns={"ServicerID"})})
 * @ORM\Entity
 */
class Managerstoservicers
{
    /**
     * @var int
     *
     * @ORM\Column(name="ManagerToServicerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $managertoservicerid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ManagerServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $managerservicerid;

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
     * Get managertoservicerid.
     *
     * @return int
     */
    public function getManagertoservicerid()
    {
        return $this->managertoservicerid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Managerstoservicers
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
     * Set managerservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $managerservicerid
     *
     * @return Managerstoservicers
     */
    public function setManagerservicerid(\AppBundle\Entity\Servicers $managerservicerid = null)
    {
        $this->managerservicerid = $managerservicerid;

        return $this;
    }

    /**
     * Get managerservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getManagerservicerid()
    {
        return $this->managerservicerid;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Managerstoservicers
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
