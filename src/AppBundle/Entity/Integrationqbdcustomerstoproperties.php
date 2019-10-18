<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdcustomerstoproperties
 *
 * @ORM\Table(name="IntegrationQBDCustomersToProperties", indexes={@ORM\Index(name="IDX_F2D44FF65224246", columns={"IntegrationQBDCustomerID"}), @ORM\Index(name="IDX_F2D44FF655345FC6", columns={"PropertyID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbdcustomerstopropertiesRepository")
 */
class Integrationqbdcustomerstoproperties
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDCustomerToPropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdcustomertopropertyid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Integrationqbdcustomers
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbdcustomers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBDCustomerID", referencedColumnName="IntegrationQBDCustomerID")
     * })
     */
    private $integrationqbdcustomerid;

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
     * Get integrationqbdcustomertopropertyid.
     *
     * @return int
     */
    public function getIntegrationqbdcustomertopropertyid()
    {
        return $this->integrationqbdcustomertopropertyid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbdcustomerstoproperties
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
     * Set integrationqbdcustomerid.
     *
     * @param \AppBundle\Entity\Integrationqbdcustomers|null $integrationqbdcustomerid
     *
     * @return Integrationqbdcustomerstoproperties
     */
    public function setIntegrationqbdcustomerid(\AppBundle\Entity\Integrationqbdcustomers $integrationqbdcustomerid = null)
    {
        $this->integrationqbdcustomerid = $integrationqbdcustomerid;

        return $this;
    }

    /**
     * Get integrationqbdcustomerid.
     *
     * @return \AppBundle\Entity\Integrationqbdcustomers|null
     */
    public function getIntegrationqbdcustomerid()
    {
        return $this->integrationqbdcustomerid;
    }

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Integrationqbdcustomerstoproperties
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
}
