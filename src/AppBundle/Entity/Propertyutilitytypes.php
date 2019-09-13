<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertyutilitytypes
 *
 * @ORM\Table(name="PropertyUtilityTypes", indexes={@ORM\Index(name="customerid", columns={"CustomerID"}), @ORM\Index(name="sortorder", columns={"SortOrder"})})
 * @ORM\Entity
 */
class Propertyutilitytypes
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyUtilityTypeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertyutilitytypeid;

    /**
     * @var string
     *
     * @ORM\Column(name="PropertyUtilityType", type="string", length=50, nullable=false)
     */
    private $propertyutilitytype;

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
     * Get propertyutilitytypeid.
     *
     * @return int
     */
    public function getPropertyutilitytypeid()
    {
        return $this->propertyutilitytypeid;
    }

    /**
     * Set propertyutilitytype.
     *
     * @param string $propertyutilitytype
     *
     * @return Propertyutilitytypes
     */
    public function setPropertyutilitytype($propertyutilitytype)
    {
        $this->propertyutilitytype = $propertyutilitytype;

        return $this;
    }

    /**
     * Get propertyutilitytype.
     *
     * @return string
     */
    public function getPropertyutilitytype()
    {
        return $this->propertyutilitytype;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Propertyutilitytypes
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
     * @return Propertyutilitytypes
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
     * @return Propertyutilitytypes
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
