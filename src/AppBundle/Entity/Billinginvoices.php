<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billinginvoices
 *
 * @ORM\Table(name="BillingInvoices", indexes={@ORM\Index(name="IDX_4FA19F7854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Billinginvoices
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingInvoiceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billinginvoiceid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeID", type="string", length=50, nullable=true)
     */
    private $stripeid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="InvoiceDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $invoicedate = 'getutcdate()';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DueDate", type="datetime", nullable=true)
     */
    private $duedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="MonthPeriodStart", type="date", nullable=true)
     */
    private $monthperiodstart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="MonthPeriodEnd", type="date", nullable=true)
     */
    private $monthperiodend;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Discount", type="float", precision=53, scale=0, nullable=true)
     */
    private $discount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="BillingStatusID", type="integer", nullable=false, options={"comment"="1=Pending, 2=Due, 3=Paid, 4=Refunded,5=Partial Refund"})
     */
    private $billingstatusid = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfProperties", type="integer", nullable=true)
     */
    private $numberofproperties;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CreditPurchased", type="integer", nullable=true)
     */
    private $creditpurchased;

    /**
     * @var int|null
     *
     * @ORM\Column(name="AmountDue", type="integer", nullable=true)
     */
    private $amountdue;

    /**
     * @var int|null
     *
     * @ORM\Column(name="AmountPaid", type="integer", nullable=true)
     */
    private $amountpaid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Subtotal", type="integer", nullable=true)
     */
    private $subtotal;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Tax", type="integer", nullable=true)
     */
    private $tax;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TaxPercent", type="integer", nullable=true)
     */
    private $taxpercent;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Total", type="integer", nullable=true)
     */
    private $total;

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';

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
     * Get billinginvoiceid.
     *
     * @return int
     */
    public function getBillinginvoiceid()
    {
        return $this->billinginvoiceid;
    }

    /**
     * Set stripeid.
     *
     * @param string|null $stripeid
     *
     * @return Billinginvoices
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
     * Set invoicedate.
     *
     * @param \DateTime $invoicedate
     *
     * @return Billinginvoices
     */
    public function setInvoicedate($invoicedate)
    {
        $this->invoicedate = $invoicedate;

        return $this;
    }

    /**
     * Get invoicedate.
     *
     * @return \DateTime
     */
    public function getInvoicedate()
    {
        return $this->invoicedate;
    }

    /**
     * Set duedate.
     *
     * @param \DateTime|null $duedate
     *
     * @return Billinginvoices
     */
    public function setDuedate($duedate = null)
    {
        $this->duedate = $duedate;

        return $this;
    }

    /**
     * Get duedate.
     *
     * @return \DateTime|null
     */
    public function getDuedate()
    {
        return $this->duedate;
    }

    /**
     * Set monthperiodstart.
     *
     * @param \DateTime|null $monthperiodstart
     *
     * @return Billinginvoices
     */
    public function setMonthperiodstart($monthperiodstart = null)
    {
        $this->monthperiodstart = $monthperiodstart;

        return $this;
    }

    /**
     * Get monthperiodstart.
     *
     * @return \DateTime|null
     */
    public function getMonthperiodstart()
    {
        return $this->monthperiodstart;
    }

    /**
     * Set monthperiodend.
     *
     * @param \DateTime|null $monthperiodend
     *
     * @return Billinginvoices
     */
    public function setMonthperiodend($monthperiodend = null)
    {
        $this->monthperiodend = $monthperiodend;

        return $this;
    }

    /**
     * Get monthperiodend.
     *
     * @return \DateTime|null
     */
    public function getMonthperiodend()
    {
        return $this->monthperiodend;
    }

    /**
     * Set discount.
     *
     * @param float|null $discount
     *
     * @return Billinginvoices
     */
    public function setDiscount($discount = null)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount.
     *
     * @return float|null
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set billingstatusid.
     *
     * @param int $billingstatusid
     *
     * @return Billinginvoices
     */
    public function setBillingstatusid($billingstatusid)
    {
        $this->billingstatusid = $billingstatusid;

        return $this;
    }

    /**
     * Get billingstatusid.
     *
     * @return int
     */
    public function getBillingstatusid()
    {
        return $this->billingstatusid;
    }

    /**
     * Set numberofproperties.
     *
     * @param int|null $numberofproperties
     *
     * @return Billinginvoices
     */
    public function setNumberofproperties($numberofproperties = null)
    {
        $this->numberofproperties = $numberofproperties;

        return $this;
    }

    /**
     * Get numberofproperties.
     *
     * @return int|null
     */
    public function getNumberofproperties()
    {
        return $this->numberofproperties;
    }

    /**
     * Set creditpurchased.
     *
     * @param int|null $creditpurchased
     *
     * @return Billinginvoices
     */
    public function setCreditpurchased($creditpurchased = null)
    {
        $this->creditpurchased = $creditpurchased;

        return $this;
    }

    /**
     * Get creditpurchased.
     *
     * @return int|null
     */
    public function getCreditpurchased()
    {
        return $this->creditpurchased;
    }

    /**
     * Set amountdue.
     *
     * @param int|null $amountdue
     *
     * @return Billinginvoices
     */
    public function setAmountdue($amountdue = null)
    {
        $this->amountdue = $amountdue;

        return $this;
    }

    /**
     * Get amountdue.
     *
     * @return int|null
     */
    public function getAmountdue()
    {
        return $this->amountdue;
    }

    /**
     * Set amountpaid.
     *
     * @param int|null $amountpaid
     *
     * @return Billinginvoices
     */
    public function setAmountpaid($amountpaid = null)
    {
        $this->amountpaid = $amountpaid;

        return $this;
    }

    /**
     * Get amountpaid.
     *
     * @return int|null
     */
    public function getAmountpaid()
    {
        return $this->amountpaid;
    }

    /**
     * Set subtotal.
     *
     * @param int|null $subtotal
     *
     * @return Billinginvoices
     */
    public function setSubtotal($subtotal = null)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal.
     *
     * @return int|null
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set tax.
     *
     * @param int|null $tax
     *
     * @return Billinginvoices
     */
    public function setTax($tax = null)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax.
     *
     * @return int|null
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set taxpercent.
     *
     * @param int|null $taxpercent
     *
     * @return Billinginvoices
     */
    public function setTaxpercent($taxpercent = null)
    {
        $this->taxpercent = $taxpercent;

        return $this;
    }

    /**
     * Get taxpercent.
     *
     * @return int|null
     */
    public function getTaxpercent()
    {
        return $this->taxpercent;
    }

    /**
     * Set total.
     *
     * @param int|null $total
     *
     * @return Billinginvoices
     */
    public function setTotal($total = null)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return int|null
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Billinginvoices
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
     * @return Billinginvoices
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
     * @return Billinginvoices
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
