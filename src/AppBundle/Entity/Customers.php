<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customers
 *
 * @ORM\Table(name="Customers", indexes={@ORM\Index(name="Createdate", columns={"CreateDate"}), @ORM\Index(name="golivedate", columns={"GoLiveDate"}), @ORM\Index(name="LiveMode", columns={"LiveMode"}), @ORM\Index(name="PlanID", columns={"PlanID"}), @ORM\Index(name="TimeZoneID", columns={"TimeZoneID"}), @ORM\Index(name="IDX_E0A2CC82423D04DF", columns={"CountryID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomersRepository")
 */
class Customers
{
    /**
     * @var int
     *
     * @ORM\Column(name="CustomerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PipedriveOrganizationID", type="integer", nullable=true)
     */
    private $pipedriveorganizationid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PipedrivePersonID", type="integer", nullable=true)
     */
    private $pipedrivepersonid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PipedriveDealID", type="integer", nullable=true)
     */
    private $pipedrivedealid;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="UseBeHome247", type="boolean", nullable=true)
     */
    private $usebehome247;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BeHome247Key", type="string", length=200, nullable=true)
     */
    private $behome247key;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BeHome247Secret", type="string", length=200, nullable=true)
     */
    private $behome247secret;

    /**
     * @var bool
     *
     * @ORM\Column(name="UsePointCentral", type="boolean", nullable=false)
     */
    private $usepointcentral = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="PointCentralCustomerID", type="string", length=200, nullable=true)
     */
    private $pointcentralcustomerid;

    /**
     * @var bool
     *
     * @ORM\Column(name="UseOperto", type="boolean", nullable=false)
     */
    private $useoperto = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="OpertoPerPropertyCharge", type="float", precision=53, scale=0, nullable=false)
     */
    private $opertoperpropertycharge = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="TagsToUse", type="text", length=-1, nullable=true)
     */
    private $tagstouse;

    /**
     * @var int|null
     *
     * @ORM\Column(name="FacebookID", type="bigint", nullable=true)
     */
    private $facebookid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="UUID", type="string", length=244, nullable=true)
     */
    private $uuid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCALUUID", type="guid", nullable=true, options={"default"="newid()"})
     */
    private $icaluuid = 'newid()';

    /**
     * @var string|null
     *
     * @ORM\Column(name="PMSystem", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $pmsystem;

    /**
     * @var bool
     *
     * @ORM\Column(name="UseOwnerOnboarding", type="boolean", nullable=false)
     */
    private $useowneronboarding = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="CurrentPlan", type="string", length=50, nullable=true)
     */
    private $currentplan;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PlanID", type="integer", nullable=true)
     */
    private $planid;

    /**
     * @var bool
     *
     * @ORM\Column(name="InternationalMessaging", type="boolean", nullable=false)
     */
    private $internationalmessaging = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="DayEndTime", type="float", precision=53, scale=0, nullable=true)
     */
    private $dayendtime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="DefaultCheckInTime", type="integer", nullable=true, options={"default"="16"})
     */
    private $defaultcheckintime = '16';

    /**
     * @var int
     *
     * @ORM\Column(name="DefaultCheckInTimeMinutes", type="integer", nullable=false)
     */
    private $defaultcheckintimeminutes = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="DefaultCheckOutTime", type="integer", nullable=true, options={"default"="11"})
     */
    private $defaultcheckouttime = '11';

    /**
     * @var int
     *
     * @ORM\Column(name="DefaultCheckOutTimeMinutes", type="integer", nullable=false)
     */
    private $defaultcheckouttimeminutes = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="CancelPeriodEnd", type="string", length=50, nullable=true)
     */
    private $cancelperiodend;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CustomerName", type="string", length=250, nullable=true)
     */
    private $customername;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BusinessName", type="string", length=250, nullable=true)
     */
    private $businessname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BusinessLogo", type="string", length=100, nullable=true)
     */
    private $businesslogo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BusinessInfo", type="text", length=-1, nullable=true)
     */
    private $businessinfo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="QuickChangeAbbreviation", type="string", length=8, nullable=true, options={"fixed"=true})
     */
    private $quickchangeabbreviation;

    /**
     * @var int
     *
     * @ORM\Column(name="SortQuickChangeToTop", type="integer", nullable=false, options={"default"="2"})
     */
    private $sortquickchangetotop = '2';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Email", type="string", length=300, nullable=true)
     */
    private $email;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="EmailConfirmed", type="boolean", nullable=true)
     */
    private $emailconfirmed = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="BillingEmail", type="string", length=200, nullable=true)
     */
    private $billingemail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LinenFields", type="string", length=2000, nullable=true)
     */
    private $linenfields;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Password", type="string", length=50, nullable=true, options={"fixed"=true})
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="VRSCookie1", type="string", length=500, nullable=true)
     */
    private $vrscookie1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="VRSCookie2", type="string", length=500, nullable=true)
     */
    private $vrscookie2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Rand", type="string", length=50, nullable=true)
     */
    private $rand;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Phone", type="string", length=200, nullable=true, options={"fixed"=true})
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(name="SendEmails", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sendemails = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="SendTexts", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sendtexts = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="TimeTracking", type="boolean", nullable=false)
     */
    private $timetracking = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="PiecePay", type="boolean", nullable=false)
     */
    private $piecepay = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowPiecePayAmountsOnEmployeeDashboards", type="boolean", nullable=false)
     */
    private $showpiecepayamountsonemployeedashboards = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="TrackLaborMaterials", type="boolean", nullable=false)
     */
    private $tracklabormaterials = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="DateFormat", type="integer", nullable=false)
     */
    private $dateformat = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowSendIssuesToOwners", type="boolean", nullable=false)
     */
    private $allowsendissuestoowners = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ImportOwners", type="boolean", nullable=false, options={"default"="1"})
     */
    private $importowners = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowStartTimeOnDashboard", type="boolean", nullable=false)
     */
    private $showstarttimeondashboard = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowTaskEndTimeOnSchedulingCalendar", type="boolean", nullable=false)
     */
    private $showtaskendtimeonschedulingcalendar = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ICALAddOn", type="boolean", nullable=false)
     */
    private $icaladdon = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ICALandAPI", type="boolean", nullable=false)
     */
    private $icalandapi = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="RequestAcceptTAsks", type="boolean", nullable=false)
     */
    private $requestaccepttasks = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AutoLogBackIn", type="boolean", nullable=false)
     */
    private $autologbackin = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="LastMinuteBookingNotificationDays", type="integer", nullable=true)
     */
    private $lastminutebookingnotificationdays;

    /**
     * @var bool
     *
     * @ORM\Column(name="NotifyOnServicerIssueNote", type="boolean", nullable=false)
     */
    private $notifyonservicerissuenote = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="SendTaskListOnDays", type="string", length=50, nullable=true)
     */
    private $sendtasklistondays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SendTaskListTime", type="integer", nullable=true)
     */
    private $sendtasklisttime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SendTaskListMaxDays", type="integer", nullable=true)
     */
    private $sendtasklistmaxdays;

    /**
     * @var float|null
     *
     * @ORM\Column(name="StartNotificationTime", type="float", precision=53, scale=0, nullable=true, options={"default"="8"})
     */
    private $startnotificationtime = '8';

    /**
     * @var float|null
     *
     * @ORM\Column(name="EndNotificationTime", type="float", precision=53, scale=0, nullable=true, options={"default"="20"})
     */
    private $endnotificationtime = '20';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="LiveMode", type="boolean", nullable=true)
     */
    private $livemode = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="SetupStep", type="string", length=255, nullable=true)
     */
    private $setupstep;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OwnerWelcomeMessage", type="string", length=500, nullable=true)
     */
    private $ownerwelcomemessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="VRNotes", type="string", length=255, nullable=true)
     */
    private $vrnotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OfficePassword", type="string", length=50, nullable=true)
     */
    private $officepassword;

    /**
     * @var bool
     *
     * @ORM\Column(name="ImportGuestEmailPhone", type="boolean", nullable=false)
     */
    private $importguestemailphone = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ImportBookingRentDeposit", type="boolean", nullable=false)
     */
    private $importbookingrentdeposit = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ImportBookingTimes", type="boolean", nullable=false, options={"default"="1"})
     */
    private $importbookingtimes = '1';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Active", type="boolean", nullable=true, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="GoLiveDate", type="datetime", nullable=true)
     */
    private $golivedate;

    /**
     * @var int
     *
     * @ORM\Column(name="TrialDays", type="integer", nullable=false, options={"default"="30"})
     */
    private $trialdays = '30';

    /**
     * @var bool
     *
     * @ORM\Column(name="UpdateAlertFlag", type="boolean", nullable=false)
     */
    private $updatealertflag = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="LastLoginDate", type="datetime", nullable=true)
     */
    private $lastlogindate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IPAddress", type="string", length=100, nullable=true)
     */
    private $ipaddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeletedDate", type="datetime", nullable=true)
     */
    private $deleteddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Source", type="string", length=50, nullable=true)
     */
    private $source;

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowSendIssuesAsWorkOrders", type="boolean", nullable=false)
     */
    private $allowsendissuesasworkorders = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowSendTasksAsWorkOrders", type="boolean", nullable=false)
     */
    private $allowsendtasksasworkorders = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="WorkOrderIntegrationCompanyStaffID", type="string", length=50, nullable=true)
     */
    private $workorderintegrationcompanystaffid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="WorkOrderIntegrationCompanyID", type="integer", nullable=true)
     */
    private $workorderintegrationcompanyid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="WorkOrderIntegrationCompanyCategoryID", type="string", length=50, nullable=true)
     */
    private $workorderintegrationcompanycategoryid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="WorkOrderIntegrationCompanySubCategoryID", type="string", length=50, nullable=true)
     */
    private $workorderintegrationcompanysubcategoryid;

    /**
     * @var bool
     *
     * @ORM\Column(name="UseIntegrationPortal", type="boolean", nullable=false)
     */
    private $useintegrationportal = '0';

    /**
     * @var \Timezones
     *
     * @ORM\ManyToOne(targetEntity="Timezones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TimeZoneID", referencedColumnName="TimeZoneID")
     * })
     */
    private $timezoneid;

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
     * @var string|null
     *
     * @ORM\Column(name="SlackTeamID", type="string", length=10, nullable=true)
     */
    private $slackteamid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueDamageAlt", type="string", length=20, nullable=true)
     */
    private $issueDamageAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueMaintenanceAlt", type="string", length=20, nullable=true)
     */
    private $issueMaintenanceAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueLostAndFoundAlt", type="string", length=20, nullable=true)
     */
    private $issueLostAndFoundAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueSupplyAlt", type="string", length=20, nullable=true)
     */
    private $issueSupplyAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueHousekeepingAlt", type="string", length=20, nullable=true)
     */
    private $issueHousekeepingAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueDamageAbbrAlt", type="string", length=5, nullable=true)
     */
    private $issueDamageAbbrAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueMaintenanceAbbrAlt", type="string", length=5, nullable=true)
     */
    private $issueMaintenanceAbbrAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueLostAndFoundAbbrAlt", type="string", length=5, nullable=true)
     */
    private $issueLostAndFoundAbbrAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueSupplyAbbrAlt", type="string", length=5, nullable=true)
     */
    private $issueSupplyAbbrAlt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="IssueHousekeepingAbbrAlt", type="string", length=5, nullable=true)
     */
    private $issueHousekeepingAbbrAlt;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IssueAllowVideoUpload", type="boolean", nullable=false)
     */
    private $issueAllowVideoUpload = false;

    /**
     * @var int|null
     *
     * @ORM\Column(name="LocaleID", type="integer", nullable=false)
     */
    private $localeid = 53;



    /**
     * Get customerid.
     *
     * @return int
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set pipedriveorganizationid.
     *
     * @param int|null $pipedriveorganizationid
     *
     * @return Customers
     */
    public function setPipedriveorganizationid($pipedriveorganizationid = null)
    {
        $this->pipedriveorganizationid = $pipedriveorganizationid;

        return $this;
    }

    /**
     * Get pipedriveorganizationid.
     *
     * @return int|null
     */
    public function getPipedriveorganizationid()
    {
        return $this->pipedriveorganizationid;
    }

    /**
     * Set pipedrivepersonid.
     *
     * @param int|null $pipedrivepersonid
     *
     * @return Customers
     */
    public function setPipedrivepersonid($pipedrivepersonid = null)
    {
        $this->pipedrivepersonid = $pipedrivepersonid;

        return $this;
    }

    /**
     * Get pipedrivepersonid.
     *
     * @return int|null
     */
    public function getPipedrivepersonid()
    {
        return $this->pipedrivepersonid;
    }

    /**
     * Set pipedrivedealid.
     *
     * @param int|null $pipedrivedealid
     *
     * @return Customers
     */
    public function setPipedrivedealid($pipedrivedealid = null)
    {
        $this->pipedrivedealid = $pipedrivedealid;

        return $this;
    }

    /**
     * Get pipedrivedealid.
     *
     * @return int|null
     */
    public function getPipedrivedealid()
    {
        return $this->pipedrivedealid;
    }

    /**
     * Set usebehome247.
     *
     * @param bool|null $usebehome247
     *
     * @return Customers
     */
    public function setUsebehome247($usebehome247 = null)
    {
        $this->usebehome247 = $usebehome247;

        return $this;
    }

    /**
     * Get usebehome247.
     *
     * @return bool|null
     */
    public function getUsebehome247()
    {
        return $this->usebehome247;
    }

    /**
     * Set behome247key.
     *
     * @param string|null $behome247key
     *
     * @return Customers
     */
    public function setBehome247key($behome247key = null)
    {
        $this->behome247key = $behome247key;

        return $this;
    }

    /**
     * Get behome247key.
     *
     * @return string|null
     */
    public function getBehome247key()
    {
        return $this->behome247key;
    }

    /**
     * Set behome247secret.
     *
     * @param string|null $behome247secret
     *
     * @return Customers
     */
    public function setBehome247secret($behome247secret = null)
    {
        $this->behome247secret = $behome247secret;

        return $this;
    }

    /**
     * Get behome247secret.
     *
     * @return string|null
     */
    public function getBehome247secret()
    {
        return $this->behome247secret;
    }

    /**
     * Set usepointcentral.
     *
     * @param bool $usepointcentral
     *
     * @return Customers
     */
    public function setUsepointcentral($usepointcentral)
    {
        $this->usepointcentral = $usepointcentral;

        return $this;
    }

    /**
     * Get usepointcentral.
     *
     * @return bool
     */
    public function getUsepointcentral()
    {
        return $this->usepointcentral;
    }

    /**
     * Set pointcentralcustomerid.
     *
     * @param string|null $pointcentralcustomerid
     *
     * @return Customers
     */
    public function setPointcentralcustomerid($pointcentralcustomerid = null)
    {
        $this->pointcentralcustomerid = $pointcentralcustomerid;

        return $this;
    }

    /**
     * Get pointcentralcustomerid.
     *
     * @return string|null
     */
    public function getPointcentralcustomerid()
    {
        return $this->pointcentralcustomerid;
    }

    /**
     * Set useoperto.
     *
     * @param bool $useoperto
     *
     * @return Customers
     */
    public function setUseoperto($useoperto)
    {
        $this->useoperto = $useoperto;

        return $this;
    }

    /**
     * Get useoperto.
     *
     * @return bool
     */
    public function getUseoperto()
    {
        return $this->useoperto;
    }

    /**
     * Set opertoperpropertycharge.
     *
     * @param float $opertoperpropertycharge
     *
     * @return Customers
     */
    public function setOpertoperpropertycharge($opertoperpropertycharge)
    {
        $this->opertoperpropertycharge = $opertoperpropertycharge;

        return $this;
    }

    /**
     * Get opertoperpropertycharge.
     *
     * @return float
     */
    public function getOpertoperpropertycharge()
    {
        return $this->opertoperpropertycharge;
    }

    /**
     * Set tagstouse.
     *
     * @param string|null $tagstouse
     *
     * @return Customers
     */
    public function setTagstouse($tagstouse = null)
    {
        $this->tagstouse = $tagstouse;

        return $this;
    }

    /**
     * Get tagstouse.
     *
     * @return string|null
     */
    public function getTagstouse()
    {
        return $this->tagstouse;
    }

    /**
     * Set facebookid.
     *
     * @param int|null $facebookid
     *
     * @return Customers
     */
    public function setFacebookid($facebookid = null)
    {
        $this->facebookid = $facebookid;

        return $this;
    }

    /**
     * Get facebookid.
     *
     * @return int|null
     */
    public function getFacebookid()
    {
        return $this->facebookid;
    }

    /**
     * Set uuid.
     *
     * @param string|null $uuid
     *
     * @return Customers
     */
    public function setUuid($uuid = null)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid.
     *
     * @return string|null
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set icaluuid.
     *
     * @param string|null $icaluuid
     *
     * @return Customers
     */
    public function setIcaluuid($icaluuid = null)
    {
        $this->icaluuid = $icaluuid;

        return $this;
    }

    /**
     * Get icaluuid.
     *
     * @return string|null
     */
    public function getIcaluuid()
    {
        return $this->icaluuid;
    }

    /**
     * Set pmsystem.
     *
     * @param string|null $pmsystem
     *
     * @return Customers
     */
    public function setPmsystem($pmsystem = null)
    {
        $this->pmsystem = $pmsystem;

        return $this;
    }

    /**
     * Get pmsystem.
     *
     * @return string|null
     */
    public function getPmsystem()
    {
        return $this->pmsystem;
    }

    /**
     * Set useowneronboarding.
     *
     * @param bool $useowneronboarding
     *
     * @return Customers
     */
    public function setUseowneronboarding($useowneronboarding)
    {
        $this->useowneronboarding = $useowneronboarding;

        return $this;
    }

    /**
     * Get useowneronboarding.
     *
     * @return bool
     */
    public function getUseowneronboarding()
    {
        return $this->useowneronboarding;
    }

    /**
     * Set currentplan.
     *
     * @param string|null $currentplan
     *
     * @return Customers
     */
    public function setCurrentplan($currentplan = null)
    {
        $this->currentplan = $currentplan;

        return $this;
    }

    /**
     * Get currentplan.
     *
     * @return string|null
     */
    public function getCurrentplan()
    {
        return $this->currentplan;
    }

    /**
     * Set planid.
     *
     * @param int|null $planid
     *
     * @return Customers
     */
    public function setPlanid($planid = null)
    {
        $this->planid = $planid;

        return $this;
    }

    /**
     * Get planid.
     *
     * @return int|null
     */
    public function getPlanid()
    {
        return $this->planid;
    }

    /**
     * Set internationalmessaging.
     *
     * @param bool $internationalmessaging
     *
     * @return Customers
     */
    public function setInternationalmessaging($internationalmessaging)
    {
        $this->internationalmessaging = $internationalmessaging;

        return $this;
    }

    /**
     * Get internationalmessaging.
     *
     * @return bool
     */
    public function getInternationalmessaging()
    {
        return $this->internationalmessaging;
    }

    /**
     * Set dayendtime.
     *
     * @param float|null $dayendtime
     *
     * @return Customers
     */
    public function setDayendtime($dayendtime = null)
    {
        $this->dayendtime = $dayendtime;

        return $this;
    }

    /**
     * Get dayendtime.
     *
     * @return float|null
     */
    public function getDayendtime()
    {
        return $this->dayendtime;
    }

    /**
     * Set defaultcheckintime.
     *
     * @param int|null $defaultcheckintime
     *
     * @return Customers
     */
    public function setDefaultcheckintime($defaultcheckintime = null)
    {
        $this->defaultcheckintime = $defaultcheckintime;

        return $this;
    }

    /**
     * Get defaultcheckintime.
     *
     * @return int|null
     */
    public function getDefaultcheckintime()
    {
        return $this->defaultcheckintime;
    }

    /**
     * Set defaultcheckintimeminutes.
     *
     * @param int $defaultcheckintimeminutes
     *
     * @return Customers
     */
    public function setDefaultcheckintimeminutes($defaultcheckintimeminutes)
    {
        $this->defaultcheckintimeminutes = $defaultcheckintimeminutes;

        return $this;
    }

    /**
     * Get defaultcheckintimeminutes.
     *
     * @return int
     */
    public function getDefaultcheckintimeminutes()
    {
        return $this->defaultcheckintimeminutes;
    }

    /**
     * Set defaultcheckouttime.
     *
     * @param int|null $defaultcheckouttime
     *
     * @return Customers
     */
    public function setDefaultcheckouttime($defaultcheckouttime = null)
    {
        $this->defaultcheckouttime = $defaultcheckouttime;

        return $this;
    }

    /**
     * Get defaultcheckouttime.
     *
     * @return int|null
     */
    public function getDefaultcheckouttime()
    {
        return $this->defaultcheckouttime;
    }

    /**
     * Set defaultcheckouttimeminutes.
     *
     * @param int $defaultcheckouttimeminutes
     *
     * @return Customers
     */
    public function setDefaultcheckouttimeminutes($defaultcheckouttimeminutes)
    {
        $this->defaultcheckouttimeminutes = $defaultcheckouttimeminutes;

        return $this;
    }

    /**
     * Get defaultcheckouttimeminutes.
     *
     * @return int
     */
    public function getDefaultcheckouttimeminutes()
    {
        return $this->defaultcheckouttimeminutes;
    }

    /**
     * Set cancelperiodend.
     *
     * @param string|null $cancelperiodend
     *
     * @return Customers
     */
    public function setCancelperiodend($cancelperiodend = null)
    {
        $this->cancelperiodend = $cancelperiodend;

        return $this;
    }

    /**
     * Get cancelperiodend.
     *
     * @return string|null
     */
    public function getCancelperiodend()
    {
        return $this->cancelperiodend;
    }

    /**
     * Set customername.
     *
     * @param string|null $customername
     *
     * @return Customers
     */
    public function setCustomername($customername = null)
    {
        $this->customername = $customername;

        return $this;
    }

    /**
     * Get customername.
     *
     * @return string|null
     */
    public function getCustomername()
    {
        return $this->customername;
    }

    /**
     * Set businessname.
     *
     * @param string|null $businessname
     *
     * @return Customers
     */
    public function setBusinessname($businessname = null)
    {
        $this->businessname = $businessname;

        return $this;
    }

    /**
     * Get businessname.
     *
     * @return string|null
     */
    public function getBusinessname()
    {
        return $this->businessname;
    }

    /**
     * Set businesslogo.
     *
     * @param string|null $businesslogo
     *
     * @return Customers
     */
    public function setBusinesslogo($businesslogo = null)
    {
        $this->businesslogo = $businesslogo;

        return $this;
    }

    /**
     * Get businesslogo.
     *
     * @return string|null
     */
    public function getBusinesslogo()
    {
        return $this->businesslogo;
    }

    /**
     * Set businessinfo.
     *
     * @param string|null $businessinfo
     *
     * @return Customers
     */
    public function setBusinessinfo($businessinfo = null)
    {
        $this->businessinfo = $businessinfo;

        return $this;
    }

    /**
     * Get businessinfo.
     *
     * @return string|null
     */
    public function getBusinessinfo()
    {
        return $this->businessinfo;
    }

    /**
     * Set quickchangeabbreviation.
     *
     * @param string|null $quickchangeabbreviation
     *
     * @return Customers
     */
    public function setQuickchangeabbreviation($quickchangeabbreviation = null)
    {
        $this->quickchangeabbreviation = $quickchangeabbreviation;

        return $this;
    }

    /**
     * Get quickchangeabbreviation.
     *
     * @return string|null
     */
    public function getQuickchangeabbreviation()
    {
        return $this->quickchangeabbreviation;
    }

    /**
     * Set sortquickchangetotop.
     *
     * @param int $sortquickchangetotop
     *
     * @return Customers
     */
    public function setSortquickchangetotop($sortquickchangetotop)
    {
        $this->sortquickchangetotop = $sortquickchangetotop;

        return $this;
    }

    /**
     * Get sortquickchangetotop.
     *
     * @return int
     */
    public function getSortquickchangetotop()
    {
        return $this->sortquickchangetotop;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Customers
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailconfirmed.
     *
     * @param bool|null $emailconfirmed
     *
     * @return Customers
     */
    public function setEmailconfirmed($emailconfirmed = null)
    {
        $this->emailconfirmed = $emailconfirmed;

        return $this;
    }

    /**
     * Get emailconfirmed.
     *
     * @return bool|null
     */
    public function getEmailconfirmed()
    {
        return $this->emailconfirmed;
    }

    /**
     * Set billingemail.
     *
     * @param string|null $billingemail
     *
     * @return Customers
     */
    public function setBillingemail($billingemail = null)
    {
        $this->billingemail = $billingemail;

        return $this;
    }

    /**
     * Get billingemail.
     *
     * @return string|null
     */
    public function getBillingemail()
    {
        return $this->billingemail;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return Customers
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
     * Set vrscookie1.
     *
     * @param string|null $vrscookie1
     *
     * @return Customers
     */
    public function setVrscookie1($vrscookie1 = null)
    {
        $this->vrscookie1 = $vrscookie1;

        return $this;
    }

    /**
     * Get vrscookie1.
     *
     * @return string|null
     */
    public function getVrscookie1()
    {
        return $this->vrscookie1;
    }

    /**
     * Set vrscookie2.
     *
     * @param string|null $vrscookie2
     *
     * @return Customers
     */
    public function setVrscookie2($vrscookie2 = null)
    {
        $this->vrscookie2 = $vrscookie2;

        return $this;
    }

    /**
     * Get vrscookie2.
     *
     * @return string|null
     */
    public function getVrscookie2()
    {
        return $this->vrscookie2;
    }

    /**
     * Set rand.
     *
     * @param string|null $rand
     *
     * @return Customers
     */
    public function setRand($rand = null)
    {
        $this->rand = $rand;

        return $this;
    }

    /**
     * Get rand.
     *
     * @return string|null
     */
    public function getRand()
    {
        return $this->rand;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Customers
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set sendemails.
     *
     * @param bool $sendemails
     *
     * @return Customers
     */
    public function setSendemails($sendemails)
    {
        $this->sendemails = $sendemails;

        return $this;
    }

    /**
     * Get sendemails.
     *
     * @return bool
     */
    public function getSendemails()
    {
        return $this->sendemails;
    }

    /**
     * Set sendtexts.
     *
     * @param bool $sendtexts
     *
     * @return Customers
     */
    public function setSendtexts($sendtexts)
    {
        $this->sendtexts = $sendtexts;

        return $this;
    }

    /**
     * Get sendtexts.
     *
     * @return bool
     */
    public function getSendtexts()
    {
        return $this->sendtexts;
    }

    /**
     * Set timetracking.
     *
     * @param bool $timetracking
     *
     * @return Customers
     */
    public function setTimetracking($timetracking)
    {
        $this->timetracking = $timetracking;

        return $this;
    }

    /**
     * Get timetracking.
     *
     * @return bool
     */
    public function getTimetracking()
    {
        return $this->timetracking;
    }

    /**
     * Set piecepay.
     *
     * @param bool $piecepay
     *
     * @return Customers
     */
    public function setPiecepay($piecepay)
    {
        $this->piecepay = $piecepay;

        return $this;
    }

    /**
     * Get piecepay.
     *
     * @return bool
     */
    public function getPiecepay()
    {
        return $this->piecepay;
    }

    /**
     * Set showpiecepayamountsonemployeedashboards.
     *
     * @param bool $showpiecepayamountsonemployeedashboards
     *
     * @return Customers
     */
    public function setShowpiecepayamountsonemployeedashboards($showpiecepayamountsonemployeedashboards)
    {
        $this->showpiecepayamountsonemployeedashboards = $showpiecepayamountsonemployeedashboards;

        return $this;
    }

    /**
     * Get showpiecepayamountsonemployeedashboards.
     *
     * @return bool
     */
    public function getShowpiecepayamountsonemployeedashboards()
    {
        return $this->showpiecepayamountsonemployeedashboards;
    }

    /**
     * Set tracklabormaterials.
     *
     * @param bool $tracklabormaterials
     *
     * @return Customers
     */
    public function setTracklabormaterials($tracklabormaterials)
    {
        $this->tracklabormaterials = $tracklabormaterials;

        return $this;
    }

    /**
     * Get tracklabormaterials.
     *
     * @return bool
     */
    public function getTracklabormaterials()
    {
        return $this->tracklabormaterials;
    }

    /**
     * Set dateformat.
     *
     * @param int $dateformat
     *
     * @return Customers
     */
    public function setDateformat($dateformat)
    {
        $this->dateformat = $dateformat;

        return $this;
    }

    /**
     * Get dateformat.
     *
     * @return int
     */
    public function getDateformat()
    {
        return $this->dateformat;
    }

    /**
     * Set allowsendissuestoowners.
     *
     * @param bool $allowsendissuestoowners
     *
     * @return Customers
     */
    public function setAllowsendissuestoowners($allowsendissuestoowners)
    {
        $this->allowsendissuestoowners = $allowsendissuestoowners;

        return $this;
    }

    /**
     * Get allowsendissuestoowners.
     *
     * @return bool
     */
    public function getAllowsendissuestoowners()
    {
        return $this->allowsendissuestoowners;
    }

    /**
     * Set importowners.
     *
     * @param bool $importowners
     *
     * @return Customers
     */
    public function setImportowners($importowners)
    {
        $this->importowners = $importowners;

        return $this;
    }

    /**
     * Get importowners.
     *
     * @return bool
     */
    public function getImportowners()
    {
        return $this->importowners;
    }

    /**
     * Set showstarttimeondashboard.
     *
     * @param bool $showstarttimeondashboard
     *
     * @return Customers
     */
    public function setShowstarttimeondashboard($showstarttimeondashboard)
    {
        $this->showstarttimeondashboard = $showstarttimeondashboard;

        return $this;
    }

    /**
     * Get showstarttimeondashboard.
     *
     * @return bool
     */
    public function getShowstarttimeondashboard()
    {
        return $this->showstarttimeondashboard;
    }

    /**
     * Set showtaskendtimeonschedulingcalendar.
     *
     * @param bool $showtaskendtimeonschedulingcalendar
     *
     * @return Customers
     */
    public function setShowtaskendtimeonschedulingcalendar($showtaskendtimeonschedulingcalendar)
    {
        $this->showtaskendtimeonschedulingcalendar = $showtaskendtimeonschedulingcalendar;

        return $this;
    }

    /**
     * Get showtaskendtimeonschedulingcalendar.
     *
     * @return bool
     */
    public function getShowtaskendtimeonschedulingcalendar()
    {
        return $this->showtaskendtimeonschedulingcalendar;
    }

    /**
     * Set icaladdon.
     *
     * @param bool $icaladdon
     *
     * @return Customers
     */
    public function setIcaladdon($icaladdon)
    {
        $this->icaladdon = $icaladdon;

        return $this;
    }

    /**
     * Get icaladdon.
     *
     * @return bool
     */
    public function getIcaladdon()
    {
        return $this->icaladdon;
    }

    /**
     * Set icalandapi.
     *
     * @param bool $icalandapi
     *
     * @return Customers
     */
    public function setIcalandapi($icalandapi)
    {
        $this->icalandapi = $icalandapi;

        return $this;
    }

    /**
     * Get icalandapi.
     *
     * @return bool
     */
    public function getIcalandapi()
    {
        return $this->icalandapi;
    }

    /**
     * Set requestaccepttasks.
     *
     * @param bool $requestaccepttasks
     *
     * @return Customers
     */
    public function setRequestaccepttasks($requestaccepttasks)
    {
        $this->requestaccepttasks = $requestaccepttasks;

        return $this;
    }

    /**
     * Get requestaccepttasks.
     *
     * @return bool
     */
    public function getRequestaccepttasks()
    {
        return $this->requestaccepttasks;
    }

    /**
     * Set autologbackin.
     *
     * @param bool $autologbackin
     *
     * @return Customers
     */
    public function setAutologbackin($autologbackin)
    {
        $this->autologbackin = $autologbackin;

        return $this;
    }

    /**
     * Get autologbackin.
     *
     * @return bool
     */
    public function getAutologbackin()
    {
        return $this->autologbackin;
    }

    /**
     * Set lastminutebookingnotificationdays.
     *
     * @param int|null $lastminutebookingnotificationdays
     *
     * @return Customers
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
     * Set notifyonservicerissuenote.
     *
     * @param bool $notifyonservicerissuenote
     *
     * @return Customers
     */
    public function setNotifyonservicerissuenote($notifyonservicerissuenote)
    {
        $this->notifyonservicerissuenote = $notifyonservicerissuenote;

        return $this;
    }

    /**
     * Get notifyonservicerissuenote.
     *
     * @return bool
     */
    public function getNotifyonservicerissuenote()
    {
        return $this->notifyonservicerissuenote;
    }

    /**
     * Set sendtasklistondays.
     *
     * @param string|null $sendtasklistondays
     *
     * @return Customers
     */
    public function setSendtasklistondays($sendtasklistondays = null)
    {
        $this->sendtasklistondays = $sendtasklistondays;

        return $this;
    }

    /**
     * Get sendtasklistondays.
     *
     * @return string|null
     */
    public function getSendtasklistondays()
    {
        return $this->sendtasklistondays;
    }

    /**
     * Set sendtasklisttime.
     *
     * @param int|null $sendtasklisttime
     *
     * @return Customers
     */
    public function setSendtasklisttime($sendtasklisttime = null)
    {
        $this->sendtasklisttime = $sendtasklisttime;

        return $this;
    }

    /**
     * Get sendtasklisttime.
     *
     * @return int|null
     */
    public function getSendtasklisttime()
    {
        return $this->sendtasklisttime;
    }

    /**
     * Set sendtasklistmaxdays.
     *
     * @param int|null $sendtasklistmaxdays
     *
     * @return Customers
     */
    public function setSendtasklistmaxdays($sendtasklistmaxdays = null)
    {
        $this->sendtasklistmaxdays = $sendtasklistmaxdays;

        return $this;
    }

    /**
     * Get sendtasklistmaxdays.
     *
     * @return int|null
     */
    public function getSendtasklistmaxdays()
    {
        return $this->sendtasklistmaxdays;
    }

    /**
     * Set startnotificationtime.
     *
     * @param float|null $startnotificationtime
     *
     * @return Customers
     */
    public function setStartnotificationtime($startnotificationtime = null)
    {
        $this->startnotificationtime = $startnotificationtime;

        return $this;
    }

    /**
     * Get startnotificationtime.
     *
     * @return float|null
     */
    public function getStartnotificationtime()
    {
        return $this->startnotificationtime;
    }

    /**
     * Set endnotificationtime.
     *
     * @param float|null $endnotificationtime
     *
     * @return Customers
     */
    public function setEndnotificationtime($endnotificationtime = null)
    {
        $this->endnotificationtime = $endnotificationtime;

        return $this;
    }

    /**
     * Get endnotificationtime.
     *
     * @return float|null
     */
    public function getEndnotificationtime()
    {
        return $this->endnotificationtime;
    }

    /**
     * Set livemode.
     *
     * @param bool|null $livemode
     *
     * @return Customers
     */
    public function setLivemode($livemode = null)
    {
        $this->livemode = $livemode;

        return $this;
    }

    /**
     * Get livemode.
     *
     * @return bool|null
     */
    public function getLivemode()
    {
        return $this->livemode;
    }

    /**
     * Set setupstep.
     *
     * @param string|null $setupstep
     *
     * @return Customers
     */
    public function setSetupstep($setupstep = null)
    {
        $this->setupstep = $setupstep;

        return $this;
    }

    /**
     * Get setupstep.
     *
     * @return string|null
     */
    public function getSetupstep()
    {
        return $this->setupstep;
    }

    /**
     * Set ownerwelcomemessage.
     *
     * @param string|null $ownerwelcomemessage
     *
     * @return Customers
     */
    public function setOwnerwelcomemessage($ownerwelcomemessage = null)
    {
        $this->ownerwelcomemessage = $ownerwelcomemessage;

        return $this;
    }

    /**
     * Get ownerwelcomemessage.
     *
     * @return string|null
     */
    public function getOwnerwelcomemessage()
    {
        return $this->ownerwelcomemessage;
    }

    /**
     * Set vrnotes.
     *
     * @param string|null $vrnotes
     *
     * @return Customers
     */
    public function setVrnotes($vrnotes = null)
    {
        $this->vrnotes = $vrnotes;

        return $this;
    }

    /**
     * Get vrnotes.
     *
     * @return string|null
     */
    public function getVrnotes()
    {
        return $this->vrnotes;
    }

    /**
     * Set officepassword.
     *
     * @param string|null $officepassword
     *
     * @return Customers
     */
    public function setOfficepassword($officepassword = null)
    {
        $this->officepassword = $officepassword;

        return $this;
    }

    /**
     * Get officepassword.
     *
     * @return string|null
     */
    public function getOfficepassword()
    {
        return $this->officepassword;
    }

    /**
     * Set importguestemailphone.
     *
     * @param bool $importguestemailphone
     *
     * @return Customers
     */
    public function setImportguestemailphone($importguestemailphone)
    {
        $this->importguestemailphone = $importguestemailphone;

        return $this;
    }

    /**
     * Get importguestemailphone.
     *
     * @return bool
     */
    public function getImportguestemailphone()
    {
        return $this->importguestemailphone;
    }

    /**
     * Set importbookingrentdeposit.
     *
     * @param bool $importbookingrentdeposit
     *
     * @return Customers
     */
    public function setImportbookingrentdeposit($importbookingrentdeposit)
    {
        $this->importbookingrentdeposit = $importbookingrentdeposit;

        return $this;
    }

    /**
     * Get importbookingrentdeposit.
     *
     * @return bool
     */
    public function getImportbookingrentdeposit()
    {
        return $this->importbookingrentdeposit;
    }

    /**
     * Set importbookingtimes.
     *
     * @param bool $importbookingtimes
     *
     * @return Customers
     */
    public function setImportbookingtimes($importbookingtimes)
    {
        $this->importbookingtimes = $importbookingtimes;

        return $this;
    }

    /**
     * Get importbookingtimes.
     *
     * @return bool
     */
    public function getImportbookingtimes()
    {
        return $this->importbookingtimes;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Customers
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set golivedate.
     *
     * @param \DateTime|null $golivedate
     *
     * @return Customers
     */
    public function setGolivedate($golivedate = null)
    {
        $this->golivedate = $golivedate;

        return $this;
    }

    /**
     * Get golivedate.
     *
     * @return \DateTime|null
     */
    public function getGolivedate()
    {
        return $this->golivedate;
    }

    /**
     * Set trialdays.
     *
     * @param int $trialdays
     *
     * @return Customers
     */
    public function setTrialdays($trialdays)
    {
        $this->trialdays = $trialdays;

        return $this;
    }

    /**
     * Get trialdays.
     *
     * @return int
     */
    public function getTrialdays()
    {
        return $this->trialdays;
    }

    /**
     * Set updatealertflag.
     *
     * @param bool $updatealertflag
     *
     * @return Customers
     */
    public function setUpdatealertflag($updatealertflag)
    {
        $this->updatealertflag = $updatealertflag;

        return $this;
    }

    /**
     * Get updatealertflag.
     *
     * @return bool
     */
    public function getUpdatealertflag()
    {
        return $this->updatealertflag;
    }

    /**
     * Set lastlogindate.
     *
     * @param \DateTime|null $lastlogindate
     *
     * @return Customers
     */
    public function setLastlogindate($lastlogindate = null)
    {
        $this->lastlogindate = $lastlogindate;

        return $this;
    }

    /**
     * Get lastlogindate.
     *
     * @return \DateTime|null
     */
    public function getLastlogindate()
    {
        return $this->lastlogindate;
    }

    /**
     * Set ipaddress.
     *
     * @param string|null $ipaddress
     *
     * @return Customers
     */
    public function setIpaddress($ipaddress = null)
    {
        $this->ipaddress = $ipaddress;

        return $this;
    }

    /**
     * Get ipaddress.
     *
     * @return string|null
     */
    public function getIpaddress()
    {
        return $this->ipaddress;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Customers
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
     * Set deleteddate.
     *
     * @param \DateTime|null $deleteddate
     *
     * @return Customers
     */
    public function setDeleteddate($deleteddate = null)
    {
        $this->deleteddate = $deleteddate;

        return $this;
    }

    /**
     * Get deleteddate.
     *
     * @return \DateTime|null
     */
    public function getDeleteddate()
    {
        return $this->deleteddate;
    }

    /**
     * Set source.
     *
     * @param string|null $source
     *
     * @return Customers
     */
    public function setSource($source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return string|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set allowsendissuesasworkorders.
     *
     * @param bool $allowsendissuesasworkorders
     *
     * @return Customers
     */
    public function setAllowsendissuesasworkorders($allowsendissuesasworkorders)
    {
        $this->allowsendissuesasworkorders = $allowsendissuesasworkorders;

        return $this;
    }

    /**
     * Get allowsendissuesasworkorders.
     *
     * @return bool
     */
    public function getAllowsendissuesasworkorders()
    {
        return $this->allowsendissuesasworkorders;
    }

    /**
     * Set allowsendtasksasworkorders.
     *
     * @param bool $allowsendtasksasworkorders
     *
     * @return Customers
     */
    public function setAllowsendtasksasworkorders($allowsendtasksasworkorders)
    {
        $this->allowsendtasksasworkorders = $allowsendtasksasworkorders;

        return $this;
    }

    /**
     * Get allowsendtasksasworkorders.
     *
     * @return bool
     */
    public function getAllowsendtasksasworkorders()
    {
        return $this->allowsendtasksasworkorders;
    }

    /**
     * Set workorderintegrationcompanystaffid.
     *
     * @param string|null $workorderintegrationcompanystaffid
     *
     * @return Customers
     */
    public function setWorkorderintegrationcompanystaffid($workorderintegrationcompanystaffid = null)
    {
        $this->workorderintegrationcompanystaffid = $workorderintegrationcompanystaffid;

        return $this;
    }

    /**
     * Get workorderintegrationcompanystaffid.
     *
     * @return string|null
     */
    public function getWorkorderintegrationcompanystaffid()
    {
        return $this->workorderintegrationcompanystaffid;
    }

    /**
     * Set workorderintegrationcompanyid.
     *
     * @param int|null $workorderintegrationcompanyid
     *
     * @return Customers
     */
    public function setWorkorderintegrationcompanyid($workorderintegrationcompanyid = null)
    {
        $this->workorderintegrationcompanyid = $workorderintegrationcompanyid;

        return $this;
    }

    /**
     * Get workorderintegrationcompanyid.
     *
     * @return int|null
     */
    public function getWorkorderintegrationcompanyid()
    {
        return $this->workorderintegrationcompanyid;
    }

    /**
     * Set workorderintegrationcompanycategoryid.
     *
     * @param string|null $workorderintegrationcompanycategoryid
     *
     * @return Customers
     */
    public function setWorkorderintegrationcompanycategoryid($workorderintegrationcompanycategoryid = null)
    {
        $this->workorderintegrationcompanycategoryid = $workorderintegrationcompanycategoryid;

        return $this;
    }

    /**
     * Get workorderintegrationcompanycategoryid.
     *
     * @return string|null
     */
    public function getWorkorderintegrationcompanycategoryid()
    {
        return $this->workorderintegrationcompanycategoryid;
    }

    /**
     * Set workorderintegrationcompanysubcategoryid.
     *
     * @param string|null $workorderintegrationcompanysubcategoryid
     *
     * @return Customers
     */
    public function setWorkorderintegrationcompanysubcategoryid($workorderintegrationcompanysubcategoryid = null)
    {
        $this->workorderintegrationcompanysubcategoryid = $workorderintegrationcompanysubcategoryid;

        return $this;
    }

    /**
     * Get workorderintegrationcompanysubcategoryid.
     *
     * @return string|null
     */
    public function getWorkorderintegrationcompanysubcategoryid()
    {
        return $this->workorderintegrationcompanysubcategoryid;
    }

    /**
     * Set useintegrationportal.
     *
     * @param bool $useintegrationportal
     *
     * @return Customers
     */
    public function setUseintegrationportal($useintegrationportal)
    {
        $this->useintegrationportal = $useintegrationportal;

        return $this;
    }

    /**
     * Get useintegrationportal.
     *
     * @return bool
     */
    public function getUseintegrationportal()
    {
        return $this->useintegrationportal;
    }

    /**
     * Set timezoneid.
     *
     * @param \AppBundle\Entity\Timezones|null $timezoneid
     *
     * @return Customers
     */
    public function setTimezoneid(\AppBundle\Entity\Timezones $timezoneid = null)
    {
        $this->timezoneid = $timezoneid;

        return $this;
    }

    /**
     * Get timezoneid.
     *
     * @return \AppBundle\Entity\Timezones|null
     */
    public function getTimezoneid()
    {
        return $this->timezoneid;
    }

    /**
     * Set countryid.
     *
     * @param \AppBundle\Entity\Countries|null $countryid
     *
     * @return Customers
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

    /**
     * Set linenfields.
     *
     * @param string|null $linenfields
     *
     * @return Customers
     */
    public function setLinenfields($linenfields = null)
    {
        $this->linenfields = $linenfields;

        return $this;
    }

    /**
     * Get linenfields.
     *
     * @return string|null
     */
    public function getLinenfields()
    {
        return $this->linenfields;
    }

    /**
     * Set slackteamid.
     *
     * @param string|null $slackteamid
     *
     * @return Customers
     */
    public function setSlackteamid($slackteamid = null)
    {
        $this->slackteamid = $slackteamid;

        return $this;
    }

    /**
     * Get slackteamid.
     *
     * @return string|null
     */
    public function getSlackteamid()
    {
        return $this->slackteamid;
    }

    /**
     * Set issueDamageAlt.
     *
     * @param string|null $issueDamageAlt
     *
     * @return Customers
     */
    public function setIssueDamageAlt($issueDamageAlt = null)
    {
        $this->issueDamageAlt = $issueDamageAlt;

        return $this;
    }

    /**
     * Get issueDamageAlt.
     *
     * @return string|null
     */
    public function getIssueDamageAlt()
    {
        return $this->issueDamageAlt;
    }

    /**
     * Set issueMaintenanceAlt.
     *
     * @param string|null $issueMaintenanceAlt
     *
     * @return Customers
     */
    public function setIssueMaintenanceAlt($issueMaintenanceAlt = null)
    {
        $this->issueMaintenanceAlt = $issueMaintenanceAlt;

        return $this;
    }

    /**
     * Get issueMaintenanceAlt.
     *
     * @return string|null
     */
    public function getIssueMaintenanceAlt()
    {
        return $this->issueMaintenanceAlt;
    }

    /**
     * Set issueLostAndFoundAlt.
     *
     * @param string|null $issueLostAndFoundAlt
     *
     * @return Customers
     */
    public function setIssueLostAndFoundAlt($issueLostAndFoundAlt = null)
    {
        $this->issueLostAndFoundAlt = $issueLostAndFoundAlt;

        return $this;
    }

    /**
     * Get issueLostAndFoundAlt.
     *
     * @return string|null
     */
    public function getIssueLostAndFoundAlt()
    {
        return $this->issueLostAndFoundAlt;
    }

    /**
     * Set issueSupplyAlt.
     *
     * @param string|null $issueSupplyAlt
     *
     * @return Customers
     */
    public function setIssueSupplyAlt($issueSupplyAlt = null)
    {
        $this->issueSupplyAlt = $issueSupplyAlt;

        return $this;
    }

    /**
     * Get issueSupplyAlt.
     *
     * @return string|null
     */
    public function getIssueSupplyAlt()
    {
        return $this->issueSupplyAlt;
    }

    /**
     * Set issueHousekeepingAlt.
     *
     * @param string|null $issueHousekeepingAlt
     *
     * @return Customers
     */
    public function setIssueHousekeepingAlt($issueHousekeepingAlt = null)
    {
        $this->issueHousekeepingAlt = $issueHousekeepingAlt;

        return $this;
    }

    /**
     * Get issueHousekeepingAlt.
     *
     * @return string|null
     */
    public function getIssueHousekeepingAlt()
    {
        return $this->issueHousekeepingAlt;
    }

    /**
     * Set issueDamageAbbrAlt.
     *
     * @param string|null $issueDamageAbbrAlt
     *
     * @return Customers
     */
    public function setIssueDamageAbbrAlt($issueDamageAbbrAlt = null)
    {
        $this->issueDamageAbbrAlt = $issueDamageAbbrAlt;

        return $this;
    }

    /**
     * Get issueDamageAbbrAlt.
     *
     * @return string|null
     */
    public function getIssueDamageAbbrAlt()
    {
        return $this->issueDamageAbbrAlt;
    }

    /**
     * Set issueMaintenanceAbbrAlt.
     *
     * @param string|null $issueMaintenanceAbbrAlt
     *
     * @return Customers
     */
    public function setIssueMaintenanceAbbrAlt($issueMaintenanceAbbrAlt = null)
    {
        $this->issueMaintenanceAbbrAlt = $issueMaintenanceAbbrAlt;

        return $this;
    }

    /**
     * Get issueMaintenanceAbbrAlt.
     *
     * @return string|null
     */
    public function getIssueMaintenanceAbbrAlt()
    {
        return $this->issueMaintenanceAbbrAlt;
    }

    /**
     * Set issueLostAndFoundAbbrAlt.
     *
     * @param string|null $issueLostAndFoundAbbrAlt
     *
     * @return Customers
     */
    public function setIssueLostAndFoundAbbrAlt($issueLostAndFoundAbbrAlt = null)
    {
        $this->issueLostAndFoundAbbrAlt = $issueLostAndFoundAbbrAlt;

        return $this;
    }

    /**
     * Get issueLostAndFoundAbbrAlt.
     *
     * @return string|null
     */
    public function getIssueLostAndFoundAbbrAlt()
    {
        return $this->issueLostAndFoundAbbrAlt;
    }

    /**
     * Set issueSupplyAbbrAlt.
     *
     * @param string|null $issueSupplyAbbrAlt
     *
     * @return Customers
     */
    public function setIssueSupplyAbbrAlt($issueSupplyAbbrAlt = null)
    {
        $this->issueSupplyAbbrAlt = $issueSupplyAbbrAlt;

        return $this;
    }

    /**
     * Get issueSupplyAbbrAlt.
     *
     * @return string|null
     */
    public function getIssueSupplyAbbrAlt()
    {
        return $this->issueSupplyAbbrAlt;
    }

    /**
     * Set issueHousekeepingAbbrAlt.
     *
     * @param string|null $issueHousekeepingAbbrAlt
     *
     * @return Customers
     */
    public function setIssueHousekeepingAbbrAlt($issueHousekeepingAbbrAlt = null)
    {
        $this->issueHousekeepingAbbrAlt = $issueHousekeepingAbbrAlt;

        return $this;
    }

    /**
     * Get issueHousekeepingAbbrAlt.
     *
     * @return string|null
     */
    public function getIssueHousekeepingAbbrAlt()
    {
        return $this->issueHousekeepingAbbrAlt;
    }

    /**
     * Set issueAllowVideoUpload.
     *
     * @param bool $issueAllowVideoUpload
     *
     * @return Customers
     */
    public function setIssueAllowVideoUpload($issueAllowVideoUpload)
    {
        $this->issueAllowVideoUpload = $issueAllowVideoUpload;

        return $this;
    }

    /**
     * Get issueAllowVideoUpload.
     *
     * @return bool
     */
    public function getIssueAllowVideoUpload()
    {
        return $this->issueAllowVideoUpload;
    }

    /**
     * Set localeid.
     *
     * @param int $localeid
     *
     * @return Customers
     */
    public function setLocaleid($localeid)
    {
        $this->localeid = $localeid;

        return $this;
    }

    /**
     * Get localeid.
     *
     * @return int
     */
    public function getLocaleid()
    {
        return $this->localeid;
    }
}
