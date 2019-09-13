<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingpayments
 *
 * @ORM\Table(name="BillingPayments", indexes={@ORM\Index(name="IDX_B07AD50A5C729DB", columns={"BillingInvoiceID"}), @ORM\Index(name="IDX_B07AD50854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Billingpayments
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingPaymentID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingpaymentid;

    /**
     * @var int
     *
     * @ORM\Column(name="BillingPaymentSourceID", type="integer", nullable=false)
     */
    private $billingpaymentsourceid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeID", type="string", length=50, nullable=true)
     */
    private $stripeid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CardLast4", type="string", length=4, nullable=true, options={"fixed"=true})
     */
    private $cardlast4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CheckNumber", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $checknumber;

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="AmountRefunded", type="float", precision=53, scale=0, nullable=true)
     */
    private $amountrefunded;

    /**
     * @var bool
     *
     * @ORM\Column(name="Paid", type="boolean", nullable=false)
     */
    private $paid = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Message", type="text", length=-1, nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="PaymentDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $paymentdate = 'getutcdate()';

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
     * Get billingpaymentid.
     *
     * @return int
     */
    public function getBillingpaymentid()
    {
        return $this->billingpaymentid;
    }

    /**
     * Set billingpaymentsourceid.
     *
     * @param int $billingpaymentsourceid
     *
     * @return Billingpayments
     */
    public function setBillingpaymentsourceid($billingpaymentsourceid)
    {
        $this->billingpaymentsourceid = $billingpaymentsourceid;

        return $this;
    }

    /**
     * Get billingpaymentsourceid.
     *
     * @return int
     */
    public function getBillingpaymentsourceid()
    {
        return $this->billingpaymentsourceid;
    }

    /**
     * Set stripeid.
     *
     * @param string|null $stripeid
     *
     * @return Billingpayments
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
     * Set cardlast4.
     *
     * @param string|null $cardlast4
     *
     * @return Billingpayments
     */
    public function setCardlast4($cardlast4 = null)
    {
        $this->cardlast4 = $cardlast4;

        return $this;
    }

    /**
     * Get cardlast4.
     *
     * @return string|null
     */
    public function getCardlast4()
    {
        return $this->cardlast4;
    }

    /**
     * Set checknumber.
     *
     * @param string|null $checknumber
     *
     * @return Billingpayments
     */
    public function setChecknumber($checknumber = null)
    {
        $this->checknumber = $checknumber;

        return $this;
    }

    /**
     * Get checknumber.
     *
     * @return string|null
     */
    public function getChecknumber()
    {
        return $this->checknumber;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return Billingpayments
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
     * Set amountrefunded.
     *
     * @param float|null $amountrefunded
     *
     * @return Billingpayments
     */
    public function setAmountrefunded($amountrefunded = null)
    {
        $this->amountrefunded = $amountrefunded;

        return $this;
    }

    /**
     * Get amountrefunded.
     *
     * @return float|null
     */
    public function getAmountrefunded()
    {
        return $this->amountrefunded;
    }

    /**
     * Set paid.
     *
     * @param bool $paid
     *
     * @return Billingpayments
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid.
     *
     * @return bool
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set message.
     *
     * @param string|null $message
     *
     * @return Billingpayments
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set paymentdate.
     *
     * @param \DateTime $paymentdate
     *
     * @return Billingpayments
     */
    public function setPaymentdate($paymentdate)
    {
        $this->paymentdate = $paymentdate;

        return $this;
    }

    /**
     * Get paymentdate.
     *
     * @return \DateTime
     */
    public function getPaymentdate()
    {
        return $this->paymentdate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingpayments
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
     * @return Billingpayments
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
     * @return Billingpayments
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
