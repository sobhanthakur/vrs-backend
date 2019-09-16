<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventoryitemstoproperties
 *
 * @ORM\Table(name="InventoryItemsToProperties", indexes={@ORM\Index(name="IDX_6E1244EC55345FC6", columns={"PropertyID"}), @ORM\Index(name="IDX_6E1244EC575BEB8D", columns={"InventoryItemID"})})
 * @ORM\Entity
 */
class Inventoryitemstoproperties
{
    /**
     * @var int
     *
     * @ORM\Column(name="InventoryItemToPropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $inventoryitemtopropertyid;

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
     * @var \Inventoryitems
     *
     * @ORM\ManyToOne(targetEntity="Inventoryitems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="InventoryItemID", referencedColumnName="InventoryItemID")
     * })
     */
    private $inventoryitemid;



    /**
     * Get inventoryitemtopropertyid.
     *
     * @return int
     */
    public function getInventoryitemtopropertyid()
    {
        return $this->inventoryitemtopropertyid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Inventoryitemstoproperties
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
     * @return Inventoryitemstoproperties
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
     * Set inventoryitemid.
     *
     * @param \AppBundle\Entity\Inventoryitems|null $inventoryitemid
     *
     * @return Inventoryitemstoproperties
     */
    public function setInventoryitemid(\AppBundle\Entity\Inventoryitems $inventoryitemid = null)
    {
        $this->inventoryitemid = $inventoryitemid;

        return $this;
    }

    /**
     * Get inventoryitemid.
     *
     * @return \AppBundle\Entity\Inventoryitems|null
     */
    public function getInventoryitemid()
    {
        return $this->inventoryitemid;
    }
}
