<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerstoproperties
 *
 * @ORM\Table(name="ServicersToProperties", indexes={@ORM\Index(name="ClusteredIndex-20190325-224128", columns={"ServicerID", "PropertyID"}), @ORM\Index(name="propertyid", columns={"PropertyID"}), @ORM\Index(name="servicerid", columns={"ServicerID"})})
 * @ORM\Entity
 */
class Servicerstoproperties
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServicerToPropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicertopropertyid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LinkedPropertyCode", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $linkedpropertycode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
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
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;



    /**
     * Get servicertopropertyid.
     *
     * @return int
     */
    public function getServicertopropertyid()
    {
        return $this->servicertopropertyid;
    }

    /**
     * Set linkedpropertycode.
     *
     * @param string|null $linkedpropertycode
     *
     * @return Servicerstoproperties
     */
    public function setLinkedpropertycode($linkedpropertycode = null)
    {
        $this->linkedpropertycode = $linkedpropertycode;

        return $this;
    }

    /**
     * Get linkedpropertycode.
     *
     * @return string|null
     */
    public function getLinkedpropertycode()
    {
        return $this->linkedpropertycode;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Servicerstoproperties
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
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Servicerstoproperties
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Servicerstoproperties
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
