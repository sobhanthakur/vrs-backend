<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdemployeestoservicers
 *
 * @ORM\Table(name="IntegrationQBDEmployeesToServicers", indexes={@ORM\Index(name="IDX_C9B852E049938395", columns={"IntegrationQBDEmployeeID"}), @ORM\Index(name="IDX_C9B852E03C7E7BEF", columns={"ServicerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbdemployeestoservicersRepository")
 */
class Integrationqbdemployeestoservicers
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDEmployeeToServicerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdemployeetoservicerid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Integrationqbdemployees
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbdemployees")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBDEmployeeID", referencedColumnName="IntegrationQBDEmployeeID")
     * })
     */
    private $integrationqbdemployeeid;

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
     * Get integrationqbdemployeetoservicerid.
     *
     * @return int
     */
    public function getIntegrationqbdemployeetoservicerid()
    {
        return $this->integrationqbdemployeetoservicerid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbdemployeestoservicers
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
     * Set integrationqbdemployeeid.
     *
     * @param \AppBundle\Entity\Integrationqbdemployees|null $integrationqbdemployeeid
     *
     * @return Integrationqbdemployeestoservicers
     */
    public function setIntegrationqbdemployeeid(\AppBundle\Entity\Integrationqbdemployees $integrationqbdemployeeid = null)
    {
        $this->integrationqbdemployeeid = $integrationqbdemployeeid;

        return $this;
    }

    /**
     * Get integrationqbdemployeeid.
     *
     * @return \AppBundle\Entity\Integrationqbdemployees|null
     */
    public function getIntegrationqbdemployeeid()
    {
        return $this->integrationqbdemployeeid;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Integrationqbdemployeestoservicers
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
