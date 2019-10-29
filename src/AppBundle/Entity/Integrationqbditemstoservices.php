<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbditemstoservices
 *
 * @ORM\Table(name="IntegrationQBDItemsToServices", indexes={@ORM\Index(name="IDX_F80C598846C236CC", columns={"IntegrationQBDItemID"}), @ORM\Index(name="IDX_F80C598830F6DDC3", columns={"ServiceID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbditemstoservicesRepository")
 */
class Integrationqbditemstoservices
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDItemToServiceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbditemtoserviceid;

    /**
     * @var bool
     *
     * @ORM\Column(name="LaborOrMaterials", type="boolean", nullable=false, options={"comment"="Labor=0,Materials=1"})
     */
    private $laborormaterials;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Integrationqbditems
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbditems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBDItemID", referencedColumnName="IntegrationQBDItemID")
     * })
     */
    private $integrationqbditemid;

    /**
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceID", referencedColumnName="ServiceID")
     * })
     */
    private $serviceid;



    /**
     * Get integrationqbditemtoserviceid.
     *
     * @return int
     */
    public function getIntegrationqbditemtoserviceid()
    {
        return $this->integrationqbditemtoserviceid;
    }

    /**
     * Set laborormaterials.
     *
     * @param bool $laborormaterials
     *
     * @return Integrationqbditemstoservices
     */
    public function setLaborormaterials($laborormaterials)
    {
        $this->laborormaterials = $laborormaterials;

        return $this;
    }

    /**
     * Get laborormaterials.
     *
     * @return bool
     */
    public function getLaborormaterials()
    {
        return $this->laborormaterials;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbditemstoservices
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
     * Set integrationqbditemid.
     *
     * @param \AppBundle\Entity\Integrationqbditems|null $integrationqbditemid
     *
     * @return Integrationqbditemstoservices
     */
    public function setIntegrationqbditemid(\AppBundle\Entity\Integrationqbditems $integrationqbditemid = null)
    {
        $this->integrationqbditemid = $integrationqbditemid;

        return $this;
    }

    /**
     * Get integrationqbditemid.
     *
     * @return \AppBundle\Entity\Integrationqbditems|null
     */
    public function getIntegrationqbditemid()
    {
        return $this->integrationqbditemid;
    }

    /**
     * Set serviceid.
     *
     * @param \AppBundle\Entity\Services|null $serviceid
     *
     * @return Integrationqbditemstoservices
     */
    public function setServiceid(\AppBundle\Entity\Services $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid.
     *
     * @return \AppBundle\Entity\Services|null
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
}
