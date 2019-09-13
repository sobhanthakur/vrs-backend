<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingcustomerpropertycounts
 *
 * @ORM\Table(name="BillingCustomerPropertyCounts", indexes={@ORM\Index(name="IDX_F973F0DD854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Billingcustomerpropertycounts
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingCustomerPropertyCountID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingcustomerpropertycountid;

    /**
     * @var int
     *
     * @ORM\Column(name="Quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PropertyIDs", type="text", length=-1, nullable=true)
     */
    private $propertyids;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="RemoveDate", type="datetime", nullable=true)
     */
    private $removedate;

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
     * Get billingcustomerpropertycountid.
     *
     * @return int
     */
    public function getBillingcustomerpropertycountid()
    {
        return $this->billingcustomerpropertycountid;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Billingcustomerpropertycounts
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set propertyids.
     *
     * @param string|null $propertyids
     *
     * @return Billingcustomerpropertycounts
     */
    public function setPropertyids($propertyids = null)
    {
        $this->propertyids = $propertyids;

        return $this;
    }

    /**
     * Get propertyids.
     *
     * @return string|null
     */
    public function getPropertyids()
    {
        return $this->propertyids;
    }

    /**
     * Set removedate.
     *
     * @param \DateTime|null $removedate
     *
     * @return Billingcustomerpropertycounts
     */
    public function setRemovedate($removedate = null)
    {
        $this->removedate = $removedate;

        return $this;
    }

    /**
     * Get removedate.
     *
     * @return \DateTime|null
     */
    public function getRemovedate()
    {
        return $this->removedate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingcustomerpropertycounts
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
     * @return Billingcustomerpropertycounts
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
