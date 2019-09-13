<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertyitemtypes
 *
 * @ORM\Table(name="PropertyItemTypes", indexes={@ORM\Index(name="CustomerID", columns={"CustomerID"}), @ORM\Index(name="sortorder", columns={"SortOrder"})})
 * @ORM\Entity
 */
class Propertyitemtypes
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyItemTypeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertyitemtypeid;

    /**
     * @var string
     *
     * @ORM\Column(name="PropertyItemType", type="string", length=50, nullable=false)
     */
    private $propertyitemtype;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false, options={"default"="1"})
     */
    private $sortorder = '1';

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
     * Get propertyitemtypeid.
     *
     * @return int
     */
    public function getPropertyitemtypeid()
    {
        return $this->propertyitemtypeid;
    }

    /**
     * Set propertyitemtype.
     *
     * @param string $propertyitemtype
     *
     * @return Propertyitemtypes
     */
    public function setPropertyitemtype($propertyitemtype)
    {
        $this->propertyitemtype = $propertyitemtype;

        return $this;
    }

    /**
     * Get propertyitemtype.
     *
     * @return string
     */
    public function getPropertyitemtype()
    {
        return $this->propertyitemtype;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Propertyitemtypes
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
     * @return Propertyitemtypes
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
     * @return Propertyitemtypes
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
