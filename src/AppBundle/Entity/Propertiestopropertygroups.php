<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertiestopropertygroups
 *
 * @ORM\Table(name="PropertiesToPropertyGroups", indexes={@ORM\Index(name="PropertyGroupID", columns={"PropertyGroupID"}), @ORM\Index(name="Propertyid", columns={"PropertyID"})})
 * @ORM\Entity
 */
class Propertiestopropertygroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyToPropertyGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertytopropertygroupid;

    /**
     * @var \Propertygroups
     *
     * @ORM\ManyToOne(targetEntity="Propertygroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyGroupID", referencedColumnName="PropertyGroupID")
     * })
     */
    private $propertygroupid;

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
     * Get propertytopropertygroupid.
     *
     * @return int
     */
    public function getPropertytopropertygroupid()
    {
        return $this->propertytopropertygroupid;
    }

    /**
     * Set propertygroupid.
     *
     * @param \AppBundle\Entity\Propertygroups|null $propertygroupid
     *
     * @return Propertiestopropertygroups
     */
    public function setPropertygroupid(\AppBundle\Entity\Propertygroups $propertygroupid = null)
    {
        $this->propertygroupid = $propertygroupid;

        return $this;
    }

    /**
     * Get propertygroupid.
     *
     * @return \AppBundle\Entity\Propertygroups|null
     */
    public function getPropertygroupid()
    {
        return $this->propertygroupid;
    }

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Propertiestopropertygroups
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
