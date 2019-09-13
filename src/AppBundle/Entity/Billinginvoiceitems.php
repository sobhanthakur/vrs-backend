<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billinginvoiceitems
 *
 * @ORM\Table(name="BillingInvoiceItems", indexes={@ORM\Index(name="IDX_98B30F98A5C729DB", columns={"BillingInvoiceID"})})
 * @ORM\Entity
 */
class Billinginvoiceitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingInvoiceItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billinginvoiceitemid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeID", type="string", length=200, nullable=true)
     */
    private $stripeid;

    /**
     * @var string
     *
     * @ORM\Column(name="Item", type="string", length=100, nullable=false)
     */
    private $item;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="text", length=-1, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="Qty", type="integer", nullable=false)
     */
    private $qty;

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=false)
     */
    private $amount = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="CreditPurchased", type="float", precision=53, scale=0, nullable=false)
     */
    private $creditpurchased = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="ItemTotal", type="float", precision=53, scale=0, nullable=false)
     */
    private $itemtotal;

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
     * @var \Billinginvoices
     *
     * @ORM\ManyToOne(targetEntity="Billinginvoices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BillingInvoiceID", referencedColumnName="BillingInvoiceID")
     * })
     */
    private $billinginvoiceid;



    /**
     * Get billinginvoiceitemid.
     *
     * @return int
     */
    public function getBillinginvoiceitemid()
    {
        return $this->billinginvoiceitemid;
    }

    /**
     * Set stripeid.
     *
     * @param string|null $stripeid
     *
     * @return Billinginvoiceitems
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
     * Set item.
     *
     * @param string $item
     *
     * @return Billinginvoiceitems
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item.
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Billinginvoiceitems
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set qty.
     *
     * @param int $qty
     *
     * @return Billinginvoiceitems
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty.
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return Billinginvoiceitems
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
     * Set creditpurchased.
     *
     * @param float $creditpurchased
     *
     * @return Billinginvoiceitems
     */
    public function setCreditpurchased($creditpurchased)
    {
        $this->creditpurchased = $creditpurchased;

        return $this;
    }

    /**
     * Get creditpurchased.
     *
     * @return float
     */
    public function getCreditpurchased()
    {
        return $this->creditpurchased;
    }

    /**
     * Set itemtotal.
     *
     * @param float $itemtotal
     *
     * @return Billinginvoiceitems
     */
    public function setItemtotal($itemtotal)
    {
        $this->itemtotal = $itemtotal;

        return $this;
    }

    /**
     * Get itemtotal.
     *
     * @return float
     */
    public function getItemtotal()
    {
        return $this->itemtotal;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Billinginvoiceitems
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
     * @return Billinginvoiceitems
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
     * @return Billinginvoiceitems
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
