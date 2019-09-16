<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Owners
 *
 * @ORM\Table(name="Owners", indexes={@ORM\Index(name="IDX_45DE97CC854CF4BD", columns={"CustomerID"}), @ORM\Index(name="IDX_45DE97CC423D04DF", columns={"CountryID"})})
 * @ORM\Entity
 */
class Owners
{
    /**
     * @var int
     *
     * @ORM\Column(name="OwnerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ownerid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IntegrationCompanyOwnerID", type="text", length=-1, nullable=true)
     */
    private $integrationcompanyownerid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OwnerName", type="string", length=50, nullable=true)
     */
    private $ownername;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OwnerEmail", type="string", length=255, nullable=true)
     */
    private $owneremail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OwnerPhone", type="string", length=255, nullable=true)
     */
    private $ownerphone;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SendEmails", type="boolean", nullable=true, options={"default"="1"})
     */
    private $sendemails = '1';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SendTexts", type="boolean", nullable=true)
     */
    private $sendtexts;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ReminderSchedule", type="integer", nullable=true, options={"comment"="0 = never, 1 = weekly, 2 = monthly"})
     */
    private $reminderschedule = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="LastMinuteBookingNotificationDays", type="integer", nullable=true)
     */
    private $lastminutebookingnotificationdays;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AccountName", type="string", length=200, nullable=true)
     */
    private $accountname;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowOwnerToEnterImportLinks", type="boolean", nullable=true, options={"default"="1"})
     */
    private $allowownertoenterimportlinks = '1';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowOwnerToEnterBookings", type="boolean", nullable=true)
     */
    private $allowownertoenterbookings;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowOwnerToChangeTimes", type="boolean", nullable=true)
     */
    private $allowownertochangetimes = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowOwnerToChangeNumbers", type="boolean", nullable=true)
     */
    private $allowownertochangenumbers = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowOwnerToEnterBookingNote", type="boolean", nullable=true)
     */
    private $allowownertoenterbookingnote = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowOwnerToViewFutureTasks", type="boolean", nullable=true)
     */
    private $allowownertoviewfuturetasks = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="GoLiveEmailSent", type="datetime", nullable=true)
     */
    private $goliveemailsent;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="WelcomeEmailSent", type="datetime", nullable=true)
     */
    private $welcomeemailsent;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingRequestEmailSent", type="datetime", nullable=true)
     */
    private $bookingrequestemailsent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Password", type="string", length=100, nullable=true, options={"fixed"=true})
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Password2", type="string", length=100, nullable=true, options={"fixed"=true})
     */
    private $password2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="UpdateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $updatedate = 'getutcdate()';

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
     * @var \Countries
     *
     * @ORM\ManyToOne(targetEntity="Countries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CountryID", referencedColumnName="CountryID")
     * })
     */
    private $countryid;



    /**
     * Get ownerid.
     *
     * @return int
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * Set integrationcompanyownerid.
     *
     * @param string|null $integrationcompanyownerid
     *
     * @return Owners
     */
    public function setIntegrationcompanyownerid($integrationcompanyownerid = null)
    {
        $this->integrationcompanyownerid = $integrationcompanyownerid;

        return $this;
    }

    /**
     * Get integrationcompanyownerid.
     *
     * @return string|null
     */
    public function getIntegrationcompanyownerid()
    {
        return $this->integrationcompanyownerid;
    }

    /**
     * Set ownername.
     *
     * @param string|null $ownername
     *
     * @return Owners
     */
    public function setOwnername($ownername = null)
    {
        $this->ownername = $ownername;

        return $this;
    }

    /**
     * Get ownername.
     *
     * @return string|null
     */
    public function getOwnername()
    {
        return $this->ownername;
    }

    /**
     * Set owneremail.
     *
     * @param string|null $owneremail
     *
     * @return Owners
     */
    public function setOwneremail($owneremail = null)
    {
        $this->owneremail = $owneremail;

        return $this;
    }

    /**
     * Get owneremail.
     *
     * @return string|null
     */
    public function getOwneremail()
    {
        return $this->owneremail;
    }

    /**
     * Set ownerphone.
     *
     * @param string|null $ownerphone
     *
     * @return Owners
     */
    public function setOwnerphone($ownerphone = null)
    {
        $this->ownerphone = $ownerphone;

        return $this;
    }

    /**
     * Get ownerphone.
     *
     * @return string|null
     */
    public function getOwnerphone()
    {
        return $this->ownerphone;
    }

    /**
     * Set sendemails.
     *
     * @param bool|null $sendemails
     *
     * @return Owners
     */
    public function setSendemails($sendemails = null)
    {
        $this->sendemails = $sendemails;

        return $this;
    }

    /**
     * Get sendemails.
     *
     * @return bool|null
     */
    public function getSendemails()
    {
        return $this->sendemails;
    }

    /**
     * Set sendtexts.
     *
     * @param bool|null $sendtexts
     *
     * @return Owners
     */
    public function setSendtexts($sendtexts = null)
    {
        $this->sendtexts = $sendtexts;

        return $this;
    }

    /**
     * Get sendtexts.
     *
     * @return bool|null
     */
    public function getSendtexts()
    {
        return $this->sendtexts;
    }

    /**
     * Set reminderschedule.
     *
     * @param int|null $reminderschedule
     *
     * @return Owners
     */
    public function setReminderschedule($reminderschedule = null)
    {
        $this->reminderschedule = $reminderschedule;

        return $this;
    }

    /**
     * Get reminderschedule.
     *
     * @return int|null
     */
    public function getReminderschedule()
    {
        return $this->reminderschedule;
    }

    /**
     * Set lastminutebookingnotificationdays.
     *
     * @param int|null $lastminutebookingnotificationdays
     *
     * @return Owners
     */
    public function setLastminutebookingnotificationdays($lastminutebookingnotificationdays = null)
    {
        $this->lastminutebookingnotificationdays = $lastminutebookingnotificationdays;

        return $this;
    }

    /**
     * Get lastminutebookingnotificationdays.
     *
     * @return int|null
     */
    public function getLastminutebookingnotificationdays()
    {
        return $this->lastminutebookingnotificationdays;
    }

    /**
     * Set accountname.
     *
     * @param string|null $accountname
     *
     * @return Owners
     */
    public function setAccountname($accountname = null)
    {
        $this->accountname = $accountname;

        return $this;
    }

    /**
     * Get accountname.
     *
     * @return string|null
     */
    public function getAccountname()
    {
        return $this->accountname;
    }

    /**
     * Set allowownertoenterimportlinks.
     *
     * @param bool|null $allowownertoenterimportlinks
     *
     * @return Owners
     */
    public function setAllowownertoenterimportlinks($allowownertoenterimportlinks = null)
    {
        $this->allowownertoenterimportlinks = $allowownertoenterimportlinks;

        return $this;
    }

    /**
     * Get allowownertoenterimportlinks.
     *
     * @return bool|null
     */
    public function getAllowownertoenterimportlinks()
    {
        return $this->allowownertoenterimportlinks;
    }

    /**
     * Set allowownertoenterbookings.
     *
     * @param bool|null $allowownertoenterbookings
     *
     * @return Owners
     */
    public function setAllowownertoenterbookings($allowownertoenterbookings = null)
    {
        $this->allowownertoenterbookings = $allowownertoenterbookings;

        return $this;
    }

    /**
     * Get allowownertoenterbookings.
     *
     * @return bool|null
     */
    public function getAllowownertoenterbookings()
    {
        return $this->allowownertoenterbookings;
    }

    /**
     * Set allowownertochangetimes.
     *
     * @param bool|null $allowownertochangetimes
     *
     * @return Owners
     */
    public function setAllowownertochangetimes($allowownertochangetimes = null)
    {
        $this->allowownertochangetimes = $allowownertochangetimes;

        return $this;
    }

    /**
     * Get allowownertochangetimes.
     *
     * @return bool|null
     */
    public function getAllowownertochangetimes()
    {
        return $this->allowownertochangetimes;
    }

    /**
     * Set allowownertochangenumbers.
     *
     * @param bool|null $allowownertochangenumbers
     *
     * @return Owners
     */
    public function setAllowownertochangenumbers($allowownertochangenumbers = null)
    {
        $this->allowownertochangenumbers = $allowownertochangenumbers;

        return $this;
    }

    /**
     * Get allowownertochangenumbers.
     *
     * @return bool|null
     */
    public function getAllowownertochangenumbers()
    {
        return $this->allowownertochangenumbers;
    }

    /**
     * Set allowownertoenterbookingnote.
     *
     * @param bool|null $allowownertoenterbookingnote
     *
     * @return Owners
     */
    public function setAllowownertoenterbookingnote($allowownertoenterbookingnote = null)
    {
        $this->allowownertoenterbookingnote = $allowownertoenterbookingnote;

        return $this;
    }

    /**
     * Get allowownertoenterbookingnote.
     *
     * @return bool|null
     */
    public function getAllowownertoenterbookingnote()
    {
        return $this->allowownertoenterbookingnote;
    }

    /**
     * Set allowownertoviewfuturetasks.
     *
     * @param bool|null $allowownertoviewfuturetasks
     *
     * @return Owners
     */
    public function setAllowownertoviewfuturetasks($allowownertoviewfuturetasks = null)
    {
        $this->allowownertoviewfuturetasks = $allowownertoviewfuturetasks;

        return $this;
    }

    /**
     * Get allowownertoviewfuturetasks.
     *
     * @return bool|null
     */
    public function getAllowownertoviewfuturetasks()
    {
        return $this->allowownertoviewfuturetasks;
    }

    /**
     * Set goliveemailsent.
     *
     * @param \DateTime|null $goliveemailsent
     *
     * @return Owners
     */
    public function setGoliveemailsent($goliveemailsent = null)
    {
        $this->goliveemailsent = $goliveemailsent;

        return $this;
    }

    /**
     * Get goliveemailsent.
     *
     * @return \DateTime|null
     */
    public function getGoliveemailsent()
    {
        return $this->goliveemailsent;
    }

    /**
     * Set welcomeemailsent.
     *
     * @param \DateTime|null $welcomeemailsent
     *
     * @return Owners
     */
    public function setWelcomeemailsent($welcomeemailsent = null)
    {
        $this->welcomeemailsent = $welcomeemailsent;

        return $this;
    }

    /**
     * Get welcomeemailsent.
     *
     * @return \DateTime|null
     */
    public function getWelcomeemailsent()
    {
        return $this->welcomeemailsent;
    }

    /**
     * Set bookingrequestemailsent.
     *
     * @param \DateTime|null $bookingrequestemailsent
     *
     * @return Owners
     */
    public function setBookingrequestemailsent($bookingrequestemailsent = null)
    {
        $this->bookingrequestemailsent = $bookingrequestemailsent;

        return $this;
    }

    /**
     * Get bookingrequestemailsent.
     *
     * @return \DateTime|null
     */
    public function getBookingrequestemailsent()
    {
        return $this->bookingrequestemailsent;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return Owners
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password2.
     *
     * @param string|null $password2
     *
     * @return Owners
     */
    public function setPassword2($password2 = null)
    {
        $this->password2 = $password2;

        return $this;
    }

    /**
     * Get password2.
     *
     * @return string|null
     */
    public function getPassword2()
    {
        return $this->password2;
    }

    /**
     * Set updatedate.
     *
     * @param \DateTime $updatedate
     *
     * @return Owners
     */
    public function setUpdatedate($updatedate)
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    /**
     * Get updatedate.
     *
     * @return \DateTime
     */
    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Owners
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
     * @return Owners
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

    /**
     * Set countryid.
     *
     * @param \AppBundle\Entity\Countries|null $countryid
     *
     * @return Owners
     */
    public function setCountryid(\AppBundle\Entity\Countries $countryid = null)
    {
        $this->countryid = $countryid;

        return $this;
    }

    /**
     * Get countryid.
     *
     * @return \AppBundle\Entity\Countries|null
     */
    public function getCountryid()
    {
        return $this->countryid;
    }
}
