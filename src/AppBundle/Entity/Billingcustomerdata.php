<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingcustomerdata
 *
 * @ORM\Table(name="BillingCustomerData", indexes={@ORM\Index(name="IDX_E8D5C0FE854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Billingcustomerdata
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingCustomerDataID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingcustomerdataid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BillingStartDate", type="date", nullable=true)
     */
    private $billingstartdate;

    /**
     * @var float
     *
     * @ORM\Column(name="TaxRate", type="float", precision=53, scale=0, nullable=false)
     */
    private $taxrate = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="Discount", type="float", precision=53, scale=0, nullable=true)
     */
    private $discount;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CurrentCreditAmount", type="integer", nullable=true)
     */
    private $currentcreditamount;

    /**
     * @var int|null
     *
     * @ORM\Column(name="FlatRateNumProperties", type="integer", nullable=true)
     */
    private $flatratenumproperties = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Monthly", type="boolean", nullable=false, options={"default"="1"})
     */
    private $monthly = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="GoingToMonthly", type="boolean", nullable=false)
     */
    private $goingtomonthly = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HasAnnualCredit", type="boolean", nullable=true)
     */
    private $hasannualcredit = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="BillingPaymentSourceID", type="integer", nullable=true)
     */
    private $billingpaymentsourceid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CardLast4", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $cardlast4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BankLast4", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $banklast4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeID", type="string", length=200, nullable=true)
     */
    private $stripeid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeSubscriptionID", type="string", length=200, nullable=true)
     */
    private $stripesubscriptionid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripePlanID", type="string", length=200, nullable=true)
     */
    private $stripeplanid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeSourceID", type="string", length=200, nullable=true)
     */
    private $stripesourceid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AccountStatus", type="string", length=50, nullable=true, options={"comment"="Current,Free,Trial"})
     */
    private $accountstatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PaymentStatus", type="string", length=50, nullable=true, options={"comment"="CurrentCard,CurrentCheck,CurrentBank, CardError, Missing "})
     */
    private $paymentstatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CardErrorMessage", type="text", length=-1, nullable=true)
     */
    private $carderrormessage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TrialDays", type="integer", nullable=true)
     */
    private $trialdays;

    /**
     * @var bool
     *
     * @ORM\Column(name="AddOnICal", type="boolean", nullable=false)
     */
    private $addonical = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AddOnInternationalSMS", type="boolean", nullable=false)
     */
    private $addoninternationalsms = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="StripeErrorMessage", type="text", length=-1, nullable=true)
     */
    private $stripeerrormessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var int
     *
     * @ORM\Column(name="BasePricePerProperty", type="integer", nullable=false, options={"default"="2"})
     */
    private $basepriceperproperty = '2';

    /**
     * @var int
     *
     * @ORM\Column(name="MinimumMonthlyCharge", type="integer", nullable=false, options={"default"="40"})
     */
    private $minimummonthlycharge = '40';

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
     * Get billingcustomerdataid.
     *
     * @return int
     */
    public function getBillingcustomerdataid()
    {
        return $this->billingcustomerdataid;
    }

    /**
     * Set billingstartdate.
     *
     * @param \DateTime|null $billingstartdate
     *
     * @return Billingcustomerdata
     */
    public function setBillingstartdate($billingstartdate = null)
    {
        $this->billingstartdate = $billingstartdate;

        return $this;
    }

    /**
     * Get billingstartdate.
     *
     * @return \DateTime|null
     */
    public function getBillingstartdate()
    {
        return $this->billingstartdate;
    }

    /**
     * Set taxrate.
     *
     * @param float $taxrate
     *
     * @return Billingcustomerdata
     */
    public function setTaxrate($taxrate)
    {
        $this->taxrate = $taxrate;

        return $this;
    }

    /**
     * Get taxrate.
     *
     * @return float
     */
    public function getTaxrate()
    {
        return $this->taxrate;
    }

    /**
     * Set discount.
     *
     * @param float|null $discount
     *
     * @return Billingcustomerdata
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
     * Set currentcreditamount.
     *
     * @param int|null $currentcreditamount
     *
     * @return Billingcustomerdata
     */
    public function setCurrentcreditamount($currentcreditamount = null)
    {
        $this->currentcreditamount = $currentcreditamount;

        return $this;
    }

    /**
     * Get currentcreditamount.
     *
     * @return int|null
     */
    public function getCurrentcreditamount()
    {
        return $this->currentcreditamount;
    }

    /**
     * Set flatratenumproperties.
     *
     * @param int|null $flatratenumproperties
     *
     * @return Billingcustomerdata
     */
    public function setFlatratenumproperties($flatratenumproperties = null)
    {
        $this->flatratenumproperties = $flatratenumproperties;

        return $this;
    }

    /**
     * Get flatratenumproperties.
     *
     * @return int|null
     */
    public function getFlatratenumproperties()
    {
        return $this->flatratenumproperties;
    }

    /**
     * Set monthly.
     *
     * @param bool $monthly
     *
     * @return Billingcustomerdata
     */
    public function setMonthly($monthly)
    {
        $this->monthly = $monthly;

        return $this;
    }

    /**
     * Get monthly.
     *
     * @return bool
     */
    public function getMonthly()
    {
        return $this->monthly;
    }

    /**
     * Set goingtomonthly.
     *
     * @param bool $goingtomonthly
     *
     * @return Billingcustomerdata
     */
    public function setGoingtomonthly($goingtomonthly)
    {
        $this->goingtomonthly = $goingtomonthly;

        return $this;
    }

    /**
     * Get goingtomonthly.
     *
     * @return bool
     */
    public function getGoingtomonthly()
    {
        return $this->goingtomonthly;
    }

    /**
     * Set hasannualcredit.
     *
     * @param bool|null $hasannualcredit
     *
     * @return Billingcustomerdata
     */
    public function setHasannualcredit($hasannualcredit = null)
    {
        $this->hasannualcredit = $hasannualcredit;

        return $this;
    }

    /**
     * Get hasannualcredit.
     *
     * @return bool|null
     */
    public function getHasannualcredit()
    {
        return $this->hasannualcredit;
    }

    /**
     * Set billingpaymentsourceid.
     *
     * @param int|null $billingpaymentsourceid
     *
     * @return Billingcustomerdata
     */
    public function setBillingpaymentsourceid($billingpaymentsourceid = null)
    {
        $this->billingpaymentsourceid = $billingpaymentsourceid;

        return $this;
    }

    /**
     * Get billingpaymentsourceid.
     *
     * @return int|null
     */
    public function getBillingpaymentsourceid()
    {
        return $this->billingpaymentsourceid;
    }

    /**
     * Set cardlast4.
     *
     * @param string|null $cardlast4
     *
     * @return Billingcustomerdata
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
     * Set banklast4.
     *
     * @param string|null $banklast4
     *
     * @return Billingcustomerdata
     */
    public function setBanklast4($banklast4 = null)
    {
        $this->banklast4 = $banklast4;

        return $this;
    }

    /**
     * Get banklast4.
     *
     * @return string|null
     */
    public function getBanklast4()
    {
        return $this->banklast4;
    }

    /**
     * Set stripeid.
     *
     * @param string|null $stripeid
     *
     * @return Billingcustomerdata
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
     * Set stripesubscriptionid.
     *
     * @param string|null $stripesubscriptionid
     *
     * @return Billingcustomerdata
     */
    public function setStripesubscriptionid($stripesubscriptionid = null)
    {
        $this->stripesubscriptionid = $stripesubscriptionid;

        return $this;
    }

    /**
     * Get stripesubscriptionid.
     *
     * @return string|null
     */
    public function getStripesubscriptionid()
    {
        return $this->stripesubscriptionid;
    }

    /**
     * Set stripeplanid.
     *
     * @param string|null $stripeplanid
     *
     * @return Billingcustomerdata
     */
    public function setStripeplanid($stripeplanid = null)
    {
        $this->stripeplanid = $stripeplanid;

        return $this;
    }

    /**
     * Get stripeplanid.
     *
     * @return string|null
     */
    public function getStripeplanid()
    {
        return $this->stripeplanid;
    }

    /**
     * Set stripesourceid.
     *
     * @param string|null $stripesourceid
     *
     * @return Billingcustomerdata
     */
    public function setStripesourceid($stripesourceid = null)
    {
        $this->stripesourceid = $stripesourceid;

        return $this;
    }

    /**
     * Get stripesourceid.
     *
     * @return string|null
     */
    public function getStripesourceid()
    {
        return $this->stripesourceid;
    }

    /**
     * Set accountstatus.
     *
     * @param string|null $accountstatus
     *
     * @return Billingcustomerdata
     */
    public function setAccountstatus($accountstatus = null)
    {
        $this->accountstatus = $accountstatus;

        return $this;
    }

    /**
     * Get accountstatus.
     *
     * @return string|null
     */
    public function getAccountstatus()
    {
        return $this->accountstatus;
    }

    /**
     * Set paymentstatus.
     *
     * @param string|null $paymentstatus
     *
     * @return Billingcustomerdata
     */
    public function setPaymentstatus($paymentstatus = null)
    {
        $this->paymentstatus = $paymentstatus;

        return $this;
    }

    /**
     * Get paymentstatus.
     *
     * @return string|null
     */
    public function getPaymentstatus()
    {
        return $this->paymentstatus;
    }

    /**
     * Set carderrormessage.
     *
     * @param string|null $carderrormessage
     *
     * @return Billingcustomerdata
     */
    public function setCarderrormessage($carderrormessage = null)
    {
        $this->carderrormessage = $carderrormessage;

        return $this;
    }

    /**
     * Get carderrormessage.
     *
     * @return string|null
     */
    public function getCarderrormessage()
    {
        return $this->carderrormessage;
    }

    /**
     * Set trialdays.
     *
     * @param int|null $trialdays
     *
     * @return Billingcustomerdata
     */
    public function setTrialdays($trialdays = null)
    {
        $this->trialdays = $trialdays;

        return $this;
    }

    /**
     * Get trialdays.
     *
     * @return int|null
     */
    public function getTrialdays()
    {
        return $this->trialdays;
    }

    /**
     * Set addonical.
     *
     * @param bool $addonical
     *
     * @return Billingcustomerdata
     */
    public function setAddonical($addonical)
    {
        $this->addonical = $addonical;

        return $this;
    }

    /**
     * Get addonical.
     *
     * @return bool
     */
    public function getAddonical()
    {
        return $this->addonical;
    }

    /**
     * Set addoninternationalsms.
     *
     * @param bool $addoninternationalsms
     *
     * @return Billingcustomerdata
     */
    public function setAddoninternationalsms($addoninternationalsms)
    {
        $this->addoninternationalsms = $addoninternationalsms;

        return $this;
    }

    /**
     * Get addoninternationalsms.
     *
     * @return bool
     */
    public function getAddoninternationalsms()
    {
        return $this->addoninternationalsms;
    }

    /**
     * Set stripeerrormessage.
     *
     * @param string|null $stripeerrormessage
     *
     * @return Billingcustomerdata
     */
    public function setStripeerrormessage($stripeerrormessage = null)
    {
        $this->stripeerrormessage = $stripeerrormessage;

        return $this;
    }

    /**
     * Get stripeerrormessage.
     *
     * @return string|null
     */
    public function getStripeerrormessage()
    {
        return $this->stripeerrormessage;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingcustomerdata
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
     * Set basepriceperproperty.
     *
     * @param int $basepriceperproperty
     *
     * @return Billingcustomerdata
     */
    public function setBasepriceperproperty($basepriceperproperty)
    {
        $this->basepriceperproperty = $basepriceperproperty;

        return $this;
    }

    /**
     * Get basepriceperproperty.
     *
     * @return int
     */
    public function getBasepriceperproperty()
    {
        return $this->basepriceperproperty;
    }

    /**
     * Set minimummonthlycharge.
     *
     * @param int $minimummonthlycharge
     *
     * @return Billingcustomerdata
     */
    public function setMinimummonthlycharge($minimummonthlycharge)
    {
        $this->minimummonthlycharge = $minimummonthlycharge;

        return $this;
    }

    /**
     * Get minimummonthlycharge.
     *
     * @return int
     */
    public function getMinimummonthlycharge()
    {
        return $this->minimummonthlycharge;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Billingcustomerdata
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
