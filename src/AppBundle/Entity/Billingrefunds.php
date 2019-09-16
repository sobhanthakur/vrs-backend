<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingrefunds
 *
 * @ORM\Table(name="BillingRefunds", indexes={@ORM\Index(name="IDX_C0D5BF4EED7AE224", columns={"BillingPaymentID"}), @ORM\Index(name="IDX_C0D5BF4EA5C729DB", columns={"BillingInvoiceID"})})
 * @ORM\Entity
 */
class Billingrefunds
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingRefundID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingrefundid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeID", type="string", length=200, nullable=true)
     */
    private $stripeid;

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Reason", type="text", length=-1, nullable=true)
     */
    private $reason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RefundDate", type="datetime", nullable=false)
     */
    private $refunddate;

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Billingpayments
     *
     * @ORM\ManyToOne(targetEntity="Billingpayments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BillingPaymentID", referencedColumnName="BillingPaymentID")
     * })
     */
    private $billingpaymentid;

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
     * Get billingrefundid.
     *
     * @return int
     */
    public function getBillingrefundid()
    {
        return $this->billingrefundid;
    }

    /**
     * Set stripeid.
     *
     * @param string|null $stripeid
     *
     * @return Billingrefunds
     */
    public function setStripeid($stripeid = null)
    {
        $this->stripeid = $stripeid;

        return $this;
    }

    /**
     * Get stripeid.
     *
     * @return string|null
     */
    public function getStripeid()
    {
        return $this->stripeid;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return Billingrefunds
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
     * Set reason.
     *
     * @param string|null $reason
     *
     * @return Billingrefunds
     */
    public function setReason($reason = null)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason.
     *
     * @return string|null
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set refunddate.
     *
     * @param \DateTime $refunddate
     *
     * @return Billingrefunds
     */
    public function setRefunddate($refunddate)
    {
        $this->refunddate = $refunddate;

        return $this;
    }

    /**
     * Get refunddate.
     *
     * @return \DateTime
     */
    public function getRefunddate()
    {
        return $this->refunddate;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Billingrefunds
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingrefunds
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
     * Set billingpaymentid.
     *
     * @param \AppBundle\Entity\Billingpayments|null $billingpaymentid
     *
     * @return Billingrefunds
     */
    public function setBillingpaymentid(\AppBundle\Entity\Billingpayments $billingpaymentid = null)
    {
        $this->billingpaymentid = $billingpaymentid;

        return $this;
    }

    /**
     * Get billingpaymentid.
     *
     * @return \AppBundle\Entity\Billingpayments|null
     */
    public function getBillingpaymentid()
    {
        return $this->billingpaymentid;
    }

    /**
     * Set billinginvoiceid.
     *
     * @param \AppBundle\Entity\Billinginvoices|null $billinginvoiceid
     *
     * @return Billingrefunds
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
}
