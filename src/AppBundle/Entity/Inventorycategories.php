<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventorycategories
 *
 * @ORM\Table(name="InventoryCategories", indexes={@ORM\Index(name="IDX_990A7BD9854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Inventorycategories
{
    /**
     * @var int
     *
     * @ORM\Column(name="InventoryCategoryID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $inventorycategoryid;

    /**
     * @var string
     *
     * @ORM\Column(name="InventoryCategory", type="string", length=200, nullable=false)
     */
    private $inventorycategory;

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
     * Get inventorycategoryid.
     *
     * @return int
     */
    public function getInventorycategoryid()
    {
        return $this->inventorycategoryid;
    }

    /**
     * Set inventorycategory.
     *
     * @param string $inventorycategory
     *
     * @return Inventorycategories
     */
    public function setInventorycategory($inventorycategory)
    {
        $this->inventorycategory = $inventorycategory;

        return $this;
    }

    /**
     * Get inventorycategory.
     *
     * @return string
     */
    public function getInventorycategory()
    {
        return $this->inventorycategory;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Inventorycategories
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
     * @return Inventorycategories
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
     * @return Inventorycategories
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
}
