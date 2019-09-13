<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingdebits
 *
 * @ORM\Table(name="BillingDebits", indexes={@ORM\Index(name="IDX_1C96C0F3854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Billingdebits
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingDebitID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingdebitid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PropertyCount", type="integer", nullable=true)
     */
    private $propertycount;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HasIcal", type="boolean", nullable=true)
     */
    private $hasical;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HasInternationalSMS", type="boolean", nullable=true)
     */
    private $hasinternationalsms;

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DebitDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $debitdate = 'getutcdate()';

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
     * Get billingdebitid.
     *
     * @return int
     */
    public function getBillingdebitid()
    {
        return $this->billingdebitid;
    }

    /**
     * Set propertycount.
     *
     * @param int|null $propertycount
     *
     * @return Billingdebits
     */
    public function setPropertycount($propertycount = null)
    {
        $this->propertycount = $propertycount;

        return $this;
    }

    /**
     * Get propertycount.
     *
     * @return int|null
     */
    public function getPropertycount()
    {
        return $this->propertycount;
    }

    /**
     * Set hasical.
     *
     * @param bool|null $hasical
     *
     * @return Billingdebits
     */
    public function setHasical($hasical = null)
    {
        $this->hasical = $hasical;

        return $this;
    }

    /**
     * Get hasical.
     *
     * @return bool|null
     */
    public function getHasical()
    {
        return $this->hasical;
    }

    /**
     * Set hasinternationalsms.
     *
     * @param bool|null $hasinternationalsms
     *
     * @return Billingdebits
     */
    public function setHasinternationalsms($hasinternationalsms = null)
    {
        $this->hasinternationalsms = $hasinternationalsms;

        return $this;
    }

    /**
     * Get hasinternationalsms.
     *
     * @return bool|null
     */
    public function getHasinternationalsms()
    {
        return $this->hasinternationalsms;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return Billingdebits
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set debitdate.
     *
     * @param \DateTime $debitdate
     *
     * @return Billingdebits
     */
    public function setDebitdate($debitdate)
    {
        $this->debitdate = $debitdate;

        return $this;
    }

    /**
     * Get debitdate.
     *
     * @return \DateTime
     */
    public function getDebitdate()
    {
        return $this->debitdate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingdebits
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
     * @return Billingdebits
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
