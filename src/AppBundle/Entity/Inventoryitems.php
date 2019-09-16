<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventoryitems
 *
 * @ORM\Table(name="InventoryItems", indexes={@ORM\Index(name="IDX_FA3801A4854CF4BD", columns={"CustomerID"}), @ORM\Index(name="IDX_FA3801A44A01608", columns={"InventoryCategoryID"})})
 * @ORM\Entity
 */
class Inventoryitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="InventoryItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $inventoryitemid;

    /**
     * @var string
     *
     * @ORM\Column(name="InventoryItem", type="text", length=-1, nullable=false)
     */
    private $inventoryitem;

    /**
     * @var int
     *
     * @ORM\Column(name="TargetValueLow", type="integer", nullable=false)
     */
    private $targetvaluelow;

    /**
     * @var int
     *
     * @ORM\Column(name="TargetValueHigh", type="integer", nullable=false)
     */
    private $targetvaluehigh;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;

    /**
     * @var \Inventorycategories
     *
     * @ORM\ManyToOne(targetEntity="Inventorycategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="InventoryCategoryID", referencedColumnName="InventoryCategoryID")
     * })
     */
    private $inventorycategoryid;



    /**
     * Get inventoryitemid.
     *
     * @return int
     */
    public function getInventoryitemid()
    {
        return $this->inventoryitemid;
    }

    /**
     * Set inventoryitem.
     *
     * @param string $inventoryitem
     *
     * @return Inventoryitems
     */
    public function setInventoryitem($inventoryitem)
    {
        $this->inventoryitem = $inventoryitem;

        return $this;
    }

    /**
     * Get inventoryitem.
     *
     * @return string
     */
    public function getInventoryitem()
    {
        return $this->inventoryitem;
    }

    /**
     * Set targetvaluelow.
     *
     * @param int $targetvaluelow
     *
     * @return Inventoryitems
     */
    public function setTargetvaluelow($targetvaluelow)
    {
        $this->targetvaluelow = $targetvaluelow;

        return $this;
    }

    /**
     * Get targetvaluelow.
     *
     * @return int
     */
    public function getTargetvaluelow()
    {
        return $this->targetvaluelow;
    }

    /**
     * Set targetvaluehigh.
     *
     * @param int $targetvaluehigh
     *
     * @return Inventoryitems
     */
    public function setTargetvaluehigh($targetvaluehigh)
    {
        $this->targetvaluehigh = $targetvaluehigh;

        return $this;
    }

    /**
     * Get targetvaluehigh.
     *
     * @return int
     */
    public function getTargetvaluehigh()
    {
        return $this->targetvaluehigh;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Inventoryitems
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Inventoryitems
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
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Inventoryitems
     */
    public function setCustomerid(\AppBundle\Entity\Customers $customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return \AppBundle\Entity\Customers|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set inventorycategoryid.
     *
     * @param \AppBundle\Entity\Inventorycategories|null $inventorycategoryid
     *
     * @return Inventoryitems
     */
    public function setInventorycategoryid(\AppBundle\Entity\Inventorycategories $inventorycategoryid = null)
    {
        $this->inventorycategoryid = $inventorycategoryid;

        return $this;
    }

    /**
     * Get inventorycategoryid.
     *
     * @return \AppBundle\Entity\Inventorycategories|null
     */
    public function getInventorycategoryid()
    {
        return $this->inventorycategoryid;
    }
}
