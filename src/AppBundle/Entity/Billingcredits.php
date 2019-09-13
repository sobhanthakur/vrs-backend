<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingcredits
 *
 * @ORM\Table(name="BillingCredits", indexes={@ORM\Index(name="IDX_FF2754E9A5C729DB", columns={"BillingInvoiceID"}), @ORM\Index(name="IDX_FF2754E9854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Billingcredits
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingCreditID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingcreditid;

    /**
     * @var float
     *
     * @ORM\Column(name="CreditAmount", type="float", precision=53, scale=0, nullable=false)
     */
    private $creditamount;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreditDate", type="date", nullable=true)
     */
    private $creditdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Billinginvoices
     *
     * @ORM\ManyToOne(targetEntity="Billinginvoices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BillingInvoiceID", referencedColumnName="BillingInvoiceID")
     * })
     */
    private $billinginvoiceid;

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
     * Get billingcreditid.
     *
     * @return int
     */
    public function getBillingcreditid()
    {
        return $this->billingcreditid;
    }

    /**
     * Set creditamount.
     *
     * @param float $creditamount
     *
     * @return Billingcredits
     */
    public function setCreditamount($creditamount)
    {
        $this->creditamount = $creditamount;

        return $this;
    }

    /**
     * Get creditamount.
     *
     * @return float
     */
    public function getCreditamount()
    {
        return $this->creditamount;
    }

    /**
     * Set creditdate.
     *
     * @param \DateTime|null $creditdate
     *
     * @return Billingcredits
     */
    public function setCreditdate($creditdate = null)
    {
        $this->creditdate = $creditdate;

        return $this;
    }

    /**
     * Get creditdate.
     *
     * @return \DateTime|null
     */
    public function getCreditdate()
    {
        return $this->creditdate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingcredits
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
     * Set billinginvoiceid.
     *
     * @param \AppBundle\Entity\Billinginvoices|null $billinginvoiceid
     *
     * @return Billingcredits
     */
    public function setBillinginvoiceid(\AppBundle\Entity\Billinginvoices $billinginvoiceid = null)
    {
        $this->billinginvoiceid = $billinginvoiceid;

        return $this;
    }

    /**
     * Get billinginvoiceid.
     *
     * @return \AppBundle\Entity\Billinginvoices|null
     */
    public function getBillinginvoiceid()
    {
        return $this->billinginvoiceid;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Billingcredits
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
