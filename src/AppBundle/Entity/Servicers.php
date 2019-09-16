<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicers
 *
 * @ORM\Table(name="Servicers", indexes={@ORM\Index(name="active", columns={"Active"}), @ORM\Index(name="customerid", columns={"CustomerID"}), @ORM\Index(name="LinkedCustomerid", columns={"LinkedCustomerID"}), @ORM\Index(name="password", columns={"Password"}), @ORM\Index(name="servicertype", columns={"ServicerType"}), @ORM\Index(name="sortorder", columns={"SortOrder"}), @ORM\Index(name="TIMETRACKING", columns={"TimeTracking"}), @ORM\Index(name="IDX_B4F997F4424D9CA0", columns={"TimeZoneID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServicersRepository")
 */
class Servicers
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServicerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="LinkedCustomerID", type="integer", nullable=true)
     */
    private $linkedcustomerid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LinkedCustomerPin", type="string", length=50, nullable=true)
     */
    private $linkedcustomerpin;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=0, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ServicerAbbreviation", type="string", length=50, nullable=true)
     */
    private $servicerabbreviation;

    /**
     * @var int
     *
     * @ORM\Column(name="CountryID", type="integer", nullable=false, options={"default"="225"})
     */
    private $countryid = '225';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Email", type="text", length=-1, nullable=true)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="SendEmails", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sendemails = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Phone", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(name="SendTexts", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sendtexts = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="AlertOnDamage", type="boolean", nullable=false)
     */
    private $alertondamage = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AlertOnMaintenance", type="boolean", nullable=false)
     */
    private $alertonmaintenance = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyIfUrgent", type="integer", nullable=false)
     */
    private $notifyifurgent = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnCompletion", type="integer", nullable=false)
     */
    private $notifyoncompletion = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnDamage", type="integer", nullable=false)
     */
    private $notifyondamage = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnMaintenance", type="integer", nullable=false)
     */
    private $notifyonmaintenance = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnLostAndFound", type="integer", nullable=false)
     */
    private $notifyonlostandfound = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnSupplyFlag", type="integer", nullable=false)
     */
    private $notifyonsupplyflag = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnServicerNote", type="integer", nullable=false)
     */
    private $notifyonservicernote = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnNotYetDone", type="integer", nullable=false)
     */
    private $notifyonnotyetdone = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnNotYetDoneHours", type="integer", nullable=false, options={"default"="2"})
     */
    private $notifyonnotyetdonehours = '2';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnOverdue", type="integer", nullable=false)
     */
    private $notifyonoverdue = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnAccepted", type="integer", nullable=false)
     */
    private $notifyonaccepted = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnDeclined", type="integer", nullable=false)
     */
    private $notifyondeclined = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="NotifyOnCheckout", type="integer", nullable=false)
     */
    private $notifyoncheckout = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="SendOwnerBookingNotes", type="boolean", nullable=false)
     */
    private $sendownerbookingnotes = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="SendTaskLists", type="integer", nullable=false)
     */
    private $sendtasklists = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SendTaskListNightBefore", type="boolean", nullable=true)
     */
    private $sendtasklistnightbefore = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SendTaskListDayOf", type="boolean", nullable=true)
     */
    private $sendtasklistdayof = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SendTaskListWeekly", type="boolean", nullable=true)
     */
    private $sendtasklistweekly = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="SendTaskListWeeklyDay", type="integer", nullable=true)
     */
    private $sendtasklistweeklyday;

    /**
     * @var int
     *
     * @ORM\Column(name="FullBookingListSendSchedule", type="integer", nullable=false, options={"default"="-1","comment"="0=no send, 1 = one week, 2 = one month, 3 = two months"})
     */
    private $fullbookinglistsendschedule = '-1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="FullBookingListSendDaysBefore", type="integer", nullable=true)
     */
    private $fullbookinglistsenddaysbefore;

    /**
     * @var int|null
     *
     * @ORM\Column(name="LastMinuteBookingNotificationDays", type="integer", nullable=true)
     */
    private $lastminutebookingnotificationdays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ViewTasksWithinDays", type="integer", nullable=true)
     */
    private $viewtaskswithindays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ViewBookingsWithinDays", type="integer", nullable=true)
     */
    private $viewbookingswithindays;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeGuestName", type="boolean", nullable=true, options={"comment"="ForVendors, other employees should use settings from services"})
     */
    private $includeguestname;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeGuestNumbers", type="boolean", nullable=true, options={"comment"="For VENDORS"})
     */
    private $includeguestnumbers;

    /**
     * @var bool
     *
     * @ORM\Column(name="IncludeGuestEmailPhone", type="boolean", nullable=false)
     */
    private $includeguestemailphone = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowChangeTaskDate", type="boolean", nullable=false)
     */
    private $allowchangetaskdate = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowCreateOneOffTask", type="boolean", nullable=false)
     */
    private $allowcreateoneofftask = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowImageUpload", type="boolean", nullable=false, options={"default"="1"})
     */
    private $allowimageupload = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowIssuesLog", type="boolean", nullable=false)
     */
    private $showissueslog = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowAddStandardTask", type="boolean", nullable=false)
     */
    private $allowaddstandardtask = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ServicerType", type="integer", nullable=true, options={"comment"="0=employee,1=vendor?,2=owner cleaner"})
     */
    private $servicertype = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowCreateCompletedTask", type="boolean", nullable=true)
     */
    private $allowcreatecompletedtask = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="TaskName", type="string", length=50, nullable=true)
     */
    private $taskname;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeDamage", type="boolean", nullable=true)
     */
    private $includedamage;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeMaintenance", type="boolean", nullable=true)
     */
    private $includemaintenance;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeLostAndFound", type="boolean", nullable=true)
     */
    private $includelostandfound;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeSupplyFlag", type="boolean", nullable=true)
     */
    private $includesupplyflag;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeUrgentFlag", type="boolean", nullable=true)
     */
    private $includeurgentflag = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeServicerNote", type="boolean", nullable=true)
     */
    private $includeservicernote;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnCompletion", type="integer", nullable=true, options={"comment"="0=off,1=email only, 2=text only, 3= both text and email"})
     */
    private $notifycustomeroncompletion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnDamage", type="integer", nullable=true)
     */
    private $notifycustomerondamage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnMaintenance", type="integer", nullable=true)
     */
    private $notifycustomeronmaintenance;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnLostAndFound", type="integer", nullable=true)
     */
    private $notifycustomeronlostandfound;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnSupplyFlag", type="integer", nullable=true)
     */
    private $notifycustomeronsupplyflag;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnServicerNote", type="integer", nullable=true)
     */
    private $notifycustomeronservicernote;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeToOwnerNote", type="boolean", nullable=true)
     */
    private $includetoownernote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DefaultToOwnerNote", type="string", length=5000, nullable=true)
     */
    private $defaulttoownernote;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyOwnerOnCompletion", type="integer", nullable=true)
     */
    private $notifyowneroncompletion;

    /**
     * @var int
     *
     * @ORM\Column(name="AllowShareImagesWithOwners", type="integer", nullable=false)
     */
    private $allowshareimageswithowners = '0';

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
     * @var string|null
     *
     * @ORM\Column(name="AdminPassword", type="string", length=50, nullable=true)
     */
    private $adminpassword;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="WelcomeEmailSent", type="datetime", nullable=true)
     */
    private $welcomeemailsent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="WorkDays", type="string", length=50, nullable=true)
     */
    private $workdays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID1", type="integer", nullable=true)
     */
    private $backupservicerid1;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID2", type="integer", nullable=true)
     */
    private $backupservicerid2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID3", type="integer", nullable=true)
     */
    private $backupservicerid3;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID4", type="integer", nullable=true)
     */
    private $backupservicerid4;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID5", type="integer", nullable=true)
     */
    private $backupservicerid5;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID6", type="integer", nullable=true)
     */
    private $backupservicerid6;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BackupServicerID7", type="integer", nullable=true)
     */
    private $backupservicerid7;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote1", type="string", length=0, nullable=true)
     */
    private $schedulenote1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote2", type="string", length=0, nullable=true)
     */
    private $schedulenote2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote3", type="string", length=0, nullable=true)
     */
    private $schedulenote3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote4", type="string", length=0, nullable=true)
     */
    private $schedulenote4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote5", type="string", length=0, nullable=true)
     */
    private $schedulenote5;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote7", type="string", length=0, nullable=true)
     */
    private $schedulenote7;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ScheduleNote6", type="string", length=0, nullable=true)
     */
    private $schedulenote6;

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote1Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote1show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote2Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote2show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote3Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote3show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote4Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote4show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote5Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote5show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote6Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote6show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="ScheduleNote7Show", type="boolean", nullable=false, options={"default"="1"})
     */
    private $schedulenote7show = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowAdminAccess", type="boolean", nullable=false)
     */
    private $allowadminaccess = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowSetupAccess", type="boolean", nullable=false)
     */
    private $allowsetupaccess = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowServiceAssignmentAccess", type="boolean", nullable=false)
     */
    private $allowserviceassignmentaccess = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowAccountAccess", type="boolean", nullable=false)
     */
    private $allowaccountaccess = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowMasterCalendar", type="boolean", nullable=false)
     */
    private $allowmastercalendar = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowIssuesAccess", type="boolean", nullable=false)
     */
    private $allowissuesaccess = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowIssuesEdit", type="boolean", nullable=false)
     */
    private $allowissuesedit = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowScheduleAccess", type="boolean", nullable=false)
     */
    private $allowscheduleaccess = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowEditBookings", type="boolean", nullable=false)
     */
    private $alloweditbookings = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowEditTasks", type="boolean", nullable=false)
     */
    private $allowedittasks = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowEditTaskPiecePay", type="boolean", nullable=false)
     */
    private $allowedittaskpiecepay = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowDragandDrop", type="boolean", nullable=false)
     */
    private $allowdraganddrop = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowEditNotes", type="boolean", nullable=false)
     */
    private $alloweditnotes = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowTracking", type="boolean", nullable=false)
     */
    private $allowtracking = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowReports", type="boolean", nullable=false)
     */
    private $allowreports = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowManage", type="boolean", nullable=false)
     */
    private $allowmanage = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowSetupEmployees", type="boolean", nullable=false)
     */
    private $allowsetupemployees = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowQuickReports", type="boolean", nullable=false)
     */
    private $allowquickreports = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowViewRentDeposit", type="boolean", nullable=false)
     */
    private $allowviewrentdeposit = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowEditRentDeposit", type="boolean", nullable=false)
     */
    private $alloweditrentdeposit = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowTaskTimeEstimates", type="boolean", nullable=false, options={"default"="1"})
     */
    private $showtasktimeestimates = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="RequestAcceptTasks", type="boolean", nullable=false)
     */
    private $requestaccepttasks = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="TimeTracking", type="boolean", nullable=false)
     */
    private $timetracking = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="TimeTrackingMileage", type="boolean", nullable=true)
     */
    private $timetrackingmileage = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="TimeTrackingGPS", type="boolean", nullable=true)
     */
    private $timetrackinggps;

    /**
     * @var float|null
     *
     * @ORM\Column(name="PayRate", type="float", precision=53, scale=0, nullable=true)
     */
    private $payrate;

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowStartEarly", type="boolean", nullable=false)
     */
    private $allowstartearly = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="LanguageID", type="integer", nullable=false, options={"comment"="0=english,1=spanish,2=portugese,3=french,4=japanese?"})
     */
    private $languageid = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="VRSCookie1", type="string", length=50, nullable=true)
     */
    private $vrscookie1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="VRSCookie2", type="string", length=50, nullable=true)
     */
    private $vrscookie2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Rand", type="string", length=50, nullable=true)
     */
    private $rand;

    /**
     * @var bool
     *
     * @ORM\Column(name="PermissionsUpdated", type="boolean", nullable=false)
     */
    private $permissionsupdated = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()","comment"="0=english,1=spanish,2=portugese,3=french,4=japanese?"})
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
     * @var \Timezones
     *
     * @ORM\ManyToOne(targetEntity="Timezones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TimeZoneID", referencedColumnName="TimeZoneID")
     * })
     */
    private $timezoneid;



    /**
     * Get servicerid.
     *
     * @return int
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }

    /**
     * Set linkedcustomerid.
     *
     * @param int|null $linkedcustomerid
     *
     * @return Servicers
     */
    public function setLinkedcustomerid($linkedcustomerid = null)
    {
        $this->linkedcustomerid = $linkedcustomerid;

        return $this;
    }

    /**
     * Get linkedcustomerid.
     *
     * @return int|null
     */
    public function getLinkedcustomerid()
    {
        return $this->linkedcustomerid;
    }

    /**
     * Set linkedcustomerpin.
     *
     * @param string|null $linkedcustomerpin
     *
     * @return Servicers
     */
    public function setLinkedcustomerpin($linkedcustomerpin = null)
    {
        $this->linkedcustomerpin = $linkedcustomerpin;

        return $this;
    }

    /**
     * Get linkedcustomerpin.
     *
     * @return string|null
     */
    public function getLinkedcustomerpin()
    {
        return $this->linkedcustomerpin;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Servicers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set servicerabbreviation.
     *
     * @param string|null $servicerabbreviation
     *
     * @return Servicers
     */
    public function setServicerabbreviation($servicerabbreviation = null)
    {
        $this->servicerabbreviation = $servicerabbreviation;

        return $this;
    }

    /**
     * Get servicerabbreviation.
     *
     * @return string|null
     */
    public function getServicerabbreviation()
    {
        return $this->servicerabbreviation;
    }

    /**
     * Set countryid.
     *
     * @param int $countryid
     *
     * @return Servicers
     */
    public function setCountryid($countryid)
    {
        $this->countryid = $countryid;

        return $this;
    }

    /**
     * Get countryid.
     *
     * @return int
     */
    public function getCountryid()
    {
        return $this->countryid;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Servicers
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
     * Set sendemails.
     *
     * @param bool $sendemails
     *
     * @return Servicers
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
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Servicers
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
     * Set sendtexts.
     *
     * @param bool $sendtexts
     *
     * @return Servicers
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
     * Set alertondamage.
     *
     * @param bool $alertondamage
     *
     * @return Servicers
     */
    public function setAlertondamage($alertondamage)
    {
        $this->alertondamage = $alertondamage;

        return $this;
    }

    /**
     * Get alertondamage.
     *
     * @return bool
     */
    public function getAlertondamage()
    {
        return $this->alertondamage;
    }

    /**
     * Set alertonmaintenance.
     *
     * @param bool $alertonmaintenance
     *
     * @return Servicers
     */
    public function setAlertonmaintenance($alertonmaintenance)
    {
        $this->alertonmaintenance = $alertonmaintenance;

        return $this;
    }

    /**
     * Get alertonmaintenance.
     *
     * @return bool
     */
    public function getAlertonmaintenance()
    {
        return $this->alertonmaintenance;
    }

    /**
     * Set notifyifurgent.
     *
     * @param int $notifyifurgent
     *
     * @return Servicers
     */
    public function setNotifyifurgent($notifyifurgent)
    {
        $this->notifyifurgent = $notifyifurgent;

        return $this;
    }

    /**
     * Get notifyifurgent.
     *
     * @return int
     */
    public function getNotifyifurgent()
    {
        return $this->notifyifurgent;
    }

    /**
     * Set notifyoncompletion.
     *
     * @param int $notifyoncompletion
     *
     * @return Servicers
     */
    public function setNotifyoncompletion($notifyoncompletion)
    {
        $this->notifyoncompletion = $notifyoncompletion;

        return $this;
    }

    /**
     * Get notifyoncompletion.
     *
     * @return int
     */
    public function getNotifyoncompletion()
    {
        return $this->notifyoncompletion;
    }

    /**
     * Set notifyondamage.
     *
     * @param int $notifyondamage
     *
     * @return Servicers
     */
    public function setNotifyondamage($notifyondamage)
    {
        $this->notifyondamage = $notifyondamage;

        return $this;
    }

    /**
     * Get notifyondamage.
     *
     * @return int
     */
    public function getNotifyondamage()
    {
        return $this->notifyondamage;
    }

    /**
     * Set notifyonmaintenance.
     *
     * @param int $notifyonmaintenance
     *
     * @return Servicers
     */
    public function setNotifyonmaintenance($notifyonmaintenance)
    {
        $this->notifyonmaintenance = $notifyonmaintenance;

        return $this;
    }

    /**
     * Get notifyonmaintenance.
     *
     * @return int
     */
    public function getNotifyonmaintenance()
    {
        return $this->notifyonmaintenance;
    }

    /**
     * Set notifyonlostandfound.
     *
     * @param int $notifyonlostandfound
     *
     * @return Servicers
     */
    public function setNotifyonlostandfound($notifyonlostandfound)
    {
        $this->notifyonlostandfound = $notifyonlostandfound;

        return $this;
    }

    /**
     * Get notifyonlostandfound.
     *
     * @return int
     */
    public function getNotifyonlostandfound()
    {
        return $this->notifyonlostandfound;
    }

    /**
     * Set notifyonsupplyflag.
     *
     * @param int $notifyonsupplyflag
     *
     * @return Servicers
     */
    public function setNotifyonsupplyflag($notifyonsupplyflag)
    {
        $this->notifyonsupplyflag = $notifyonsupplyflag;

        return $this;
    }

    /**
     * Get notifyonsupplyflag.
     *
     * @return int
     */
    public function getNotifyonsupplyflag()
    {
        return $this->notifyonsupplyflag;
    }

    /**
     * Set notifyonservicernote.
     *
     * @param int $notifyonservicernote
     *
     * @return Servicers
     */
    public function setNotifyonservicernote($notifyonservicernote)
    {
        $this->notifyonservicernote = $notifyonservicernote;

        return $this;
    }

    /**
     * Get notifyonservicernote.
     *
     * @return int
     */
    public function getNotifyonservicernote()
    {
        return $this->notifyonservicernote;
    }

    /**
     * Set notifyonnotyetdone.
     *
     * @param int $notifyonnotyetdone
     *
     * @return Servicers
     */
    public function setNotifyonnotyetdone($notifyonnotyetdone)
    {
        $this->notifyonnotyetdone = $notifyonnotyetdone;

        return $this;
    }

    /**
     * Get notifyonnotyetdone.
     *
     * @return int
     */
    public function getNotifyonnotyetdone()
    {
        return $this->notifyonnotyetdone;
    }

    /**
     * Set notifyonnotyetdonehours.
     *
     * @param int $notifyonnotyetdonehours
     *
     * @return Servicers
     */
    public function setNotifyonnotyetdonehours($notifyonnotyetdonehours)
    {
        $this->notifyonnotyetdonehours = $notifyonnotyetdonehours;

        return $this;
    }

    /**
     * Get notifyonnotyetdonehours.
     *
     * @return int
     */
    public function getNotifyonnotyetdonehours()
    {
        return $this->notifyonnotyetdonehours;
    }

    /**
     * Set notifyonoverdue.
     *
     * @param int $notifyonoverdue
     *
     * @return Servicers
     */
    public function setNotifyonoverdue($notifyonoverdue)
    {
        $this->notifyonoverdue = $notifyonoverdue;

        return $this;
    }

    /**
     * Get notifyonoverdue.
     *
     * @return int
     */
    public function getNotifyonoverdue()
    {
        return $this->notifyonoverdue;
    }

    /**
     * Set notifyonaccepted.
     *
     * @param int $notifyonaccepted
     *
     * @return Servicers
     */
    public function setNotifyonaccepted($notifyonaccepted)
    {
        $this->notifyonaccepted = $notifyonaccepted;

        return $this;
    }

    /**
     * Get notifyonaccepted.
     *
     * @return int
     */
    public function getNotifyonaccepted()
    {
        return $this->notifyonaccepted;
    }

    /**
     * Set notifyondeclined.
     *
     * @param int $notifyondeclined
     *
     * @return Servicers
     */
    public function setNotifyondeclined($notifyondeclined)
    {
        $this->notifyondeclined = $notifyondeclined;

        return $this;
    }

    /**
     * Get notifyondeclined.
     *
     * @return int
     */
    public function getNotifyondeclined()
    {
        return $this->notifyondeclined;
    }

    /**
     * Set notifyoncheckout.
     *
     * @param int $notifyoncheckout
     *
     * @return Servicers
     */
    public function setNotifyoncheckout($notifyoncheckout)
    {
        $this->notifyoncheckout = $notifyoncheckout;

        return $this;
    }

    /**
     * Get notifyoncheckout.
     *
     * @return int
     */
    public function getNotifyoncheckout()
    {
        return $this->notifyoncheckout;
    }

    /**
     * Set sendownerbookingnotes.
     *
     * @param bool $sendownerbookingnotes
     *
     * @return Servicers
     */
    public function setSendownerbookingnotes($sendownerbookingnotes)
    {
        $this->sendownerbookingnotes = $sendownerbookingnotes;

        return $this;
    }

    /**
     * Get sendownerbookingnotes.
     *
     * @return bool
     */
    public function getSendownerbookingnotes()
    {
        return $this->sendownerbookingnotes;
    }

    /**
     * Set sendtasklists.
     *
     * @param int $sendtasklists
     *
     * @return Servicers
     */
    public function setSendtasklists($sendtasklists)
    {
        $this->sendtasklists = $sendtasklists;

        return $this;
    }

    /**
     * Get sendtasklists.
     *
     * @return int
     */
    public function getSendtasklists()
    {
        return $this->sendtasklists;
    }

    /**
     * Set sendtasklistnightbefore.
     *
     * @param bool|null $sendtasklistnightbefore
     *
     * @return Servicers
     */
    public function setSendtasklistnightbefore($sendtasklistnightbefore = null)
    {
        $this->sendtasklistnightbefore = $sendtasklistnightbefore;

        return $this;
    }

    /**
     * Get sendtasklistnightbefore.
     *
     * @return bool|null
     */
    public function getSendtasklistnightbefore()
    {
        return $this->sendtasklistnightbefore;
    }

    /**
     * Set sendtasklistdayof.
     *
     * @param bool|null $sendtasklistdayof
     *
     * @return Servicers
     */
    public function setSendtasklistdayof($sendtasklistdayof = null)
    {
        $this->sendtasklistdayof = $sendtasklistdayof;

        return $this;
    }

    /**
     * Get sendtasklistdayof.
     *
     * @return bool|null
     */
    public function getSendtasklistdayof()
    {
        return $this->sendtasklistdayof;
    }

    /**
     * Set sendtasklistweekly.
     *
     * @param bool|null $sendtasklistweekly
     *
     * @return Servicers
     */
    public function setSendtasklistweekly($sendtasklistweekly = null)
    {
        $this->sendtasklistweekly = $sendtasklistweekly;

        return $this;
    }

    /**
     * Get sendtasklistweekly.
     *
     * @return bool|null
     */
    public function getSendtasklistweekly()
    {
        return $this->sendtasklistweekly;
    }

    /**
     * Set sendtasklistweeklyday.
     *
     * @param int|null $sendtasklistweeklyday
     *
     * @return Servicers
     */
    public function setSendtasklistweeklyday($sendtasklistweeklyday = null)
    {
        $this->sendtasklistweeklyday = $sendtasklistweeklyday;

        return $this;
    }

    /**
     * Get sendtasklistweeklyday.
     *
     * @return int|null
     */
    public function getSendtasklistweeklyday()
    {
        return $this->sendtasklistweeklyday;
    }

    /**
     * Set fullbookinglistsendschedule.
     *
     * @param int $fullbookinglistsendschedule
     *
     * @return Servicers
     */
    public function setFullbookinglistsendschedule($fullbookinglistsendschedule)
    {
        $this->fullbookinglistsendschedule = $fullbookinglistsendschedule;

        return $this;
    }

    /**
     * Get fullbookinglistsendschedule.
     *
     * @return int
     */
    public function getFullbookinglistsendschedule()
    {
        return $this->fullbookinglistsendschedule;
    }

    /**
     * Set fullbookinglistsenddaysbefore.
     *
     * @param int|null $fullbookinglistsenddaysbefore
     *
     * @return Servicers
     */
    public function setFullbookinglistsenddaysbefore($fullbookinglistsenddaysbefore = null)
    {
        $this->fullbookinglistsenddaysbefore = $fullbookinglistsenddaysbefore;

        return $this;
    }

    /**
     * Get fullbookinglistsenddaysbefore.
     *
     * @return int|null
     */
    public function getFullbookinglistsenddaysbefore()
    {
        return $this->fullbookinglistsenddaysbefore;
    }

    /**
     * Set lastminutebookingnotificationdays.
     *
     * @param int|null $lastminutebookingnotificationdays
     *
     * @return Servicers
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
     * Set viewtaskswithindays.
     *
     * @param int|null $viewtaskswithindays
     *
     * @return Servicers
     */
    public function setViewtaskswithindays($viewtaskswithindays = null)
    {
        $this->viewtaskswithindays = $viewtaskswithindays;

        return $this;
    }

    /**
     * Get viewtaskswithindays.
     *
     * @return int|null
     */
    public function getViewtaskswithindays()
    {
        return $this->viewtaskswithindays;
    }

    /**
     * Set viewbookingswithindays.
     *
     * @param int|null $viewbookingswithindays
     *
     * @return Servicers
     */
    public function setViewbookingswithindays($viewbookingswithindays = null)
    {
        $this->viewbookingswithindays = $viewbookingswithindays;

        return $this;
    }

    /**
     * Get viewbookingswithindays.
     *
     * @return int|null
     */
    public function getViewbookingswithindays()
    {
        return $this->viewbookingswithindays;
    }

    /**
     * Set includeguestname.
     *
     * @param bool|null $includeguestname
     *
     * @return Servicers
     */
    public function setIncludeguestname($includeguestname = null)
    {
        $this->includeguestname = $includeguestname;

        return $this;
    }

    /**
     * Get includeguestname.
     *
     * @return bool|null
     */
    public function getIncludeguestname()
    {
        return $this->includeguestname;
    }

    /**
     * Set includeguestnumbers.
     *
     * @param bool|null $includeguestnumbers
     *
     * @return Servicers
     */
    public function setIncludeguestnumbers($includeguestnumbers = null)
    {
        $this->includeguestnumbers = $includeguestnumbers;

        return $this;
    }

    /**
     * Get includeguestnumbers.
     *
     * @return bool|null
     */
    public function getIncludeguestnumbers()
    {
        return $this->includeguestnumbers;
    }

    /**
     * Set includeguestemailphone.
     *
     * @param bool $includeguestemailphone
     *
     * @return Servicers
     */
    public function setIncludeguestemailphone($includeguestemailphone)
    {
        $this->includeguestemailphone = $includeguestemailphone;

        return $this;
    }

    /**
     * Get includeguestemailphone.
     *
     * @return bool
     */
    public function getIncludeguestemailphone()
    {
        return $this->includeguestemailphone;
    }

    /**
     * Set allowchangetaskdate.
     *
     * @param bool $allowchangetaskdate
     *
     * @return Servicers
     */
    public function setAllowchangetaskdate($allowchangetaskdate)
    {
        $this->allowchangetaskdate = $allowchangetaskdate;

        return $this;
    }

    /**
     * Get allowchangetaskdate.
     *
     * @return bool
     */
    public function getAllowchangetaskdate()
    {
        return $this->allowchangetaskdate;
    }

    /**
     * Set allowcreateoneofftask.
     *
     * @param bool $allowcreateoneofftask
     *
     * @return Servicers
     */
    public function setAllowcreateoneofftask($allowcreateoneofftask)
    {
        $this->allowcreateoneofftask = $allowcreateoneofftask;

        return $this;
    }

    /**
     * Get allowcreateoneofftask.
     *
     * @return bool
     */
    public function getAllowcreateoneofftask()
    {
        return $this->allowcreateoneofftask;
    }

    /**
     * Set allowimageupload.
     *
     * @param bool $allowimageupload
     *
     * @return Servicers
     */
    public function setAllowimageupload($allowimageupload)
    {
        $this->allowimageupload = $allowimageupload;

        return $this;
    }

    /**
     * Get allowimageupload.
     *
     * @return bool
     */
    public function getAllowimageupload()
    {
        return $this->allowimageupload;
    }

    /**
     * Set showissueslog.
     *
     * @param bool $showissueslog
     *
     * @return Servicers
     */
    public function setShowissueslog($showissueslog)
    {
        $this->showissueslog = $showissueslog;

        return $this;
    }

    /**
     * Get showissueslog.
     *
     * @return bool
     */
    public function getShowissueslog()
    {
        return $this->showissueslog;
    }

    /**
     * Set allowaddstandardtask.
     *
     * @param bool $allowaddstandardtask
     *
     * @return Servicers
     */
    public function setAllowaddstandardtask($allowaddstandardtask)
    {
        $this->allowaddstandardtask = $allowaddstandardtask;

        return $this;
    }

    /**
     * Get allowaddstandardtask.
     *
     * @return bool
     */
    public function getAllowaddstandardtask()
    {
        return $this->allowaddstandardtask;
    }

    /**
     * Set servicertype.
     *
     * @param int|null $servicertype
     *
     * @return Servicers
     */
    public function setServicertype($servicertype = null)
    {
        $this->servicertype = $servicertype;

        return $this;
    }

    /**
     * Get servicertype.
     *
     * @return int|null
     */
    public function getServicertype()
    {
        return $this->servicertype;
    }

    /**
     * Set allowcreatecompletedtask.
     *
     * @param bool|null $allowcreatecompletedtask
     *
     * @return Servicers
     */
    public function setAllowcreatecompletedtask($allowcreatecompletedtask = null)
    {
        $this->allowcreatecompletedtask = $allowcreatecompletedtask;

        return $this;
    }

    /**
     * Get allowcreatecompletedtask.
     *
     * @return bool|null
     */
    public function getAllowcreatecompletedtask()
    {
        return $this->allowcreatecompletedtask;
    }

    /**
     * Set taskname.
     *
     * @param string|null $taskname
     *
     * @return Servicers
     */
    public function setTaskname($taskname = null)
    {
        $this->taskname = $taskname;

        return $this;
    }

    /**
     * Get taskname.
     *
     * @return string|null
     */
    public function getTaskname()
    {
        return $this->taskname;
    }

    /**
     * Set includedamage.
     *
     * @param bool|null $includedamage
     *
     * @return Servicers
     */
    public function setIncludedamage($includedamage = null)
    {
        $this->includedamage = $includedamage;

        return $this;
    }

    /**
     * Get includedamage.
     *
     * @return bool|null
     */
    public function getIncludedamage()
    {
        return $this->includedamage;
    }

    /**
     * Set includemaintenance.
     *
     * @param bool|null $includemaintenance
     *
     * @return Servicers
     */
    public function setIncludemaintenance($includemaintenance = null)
    {
        $this->includemaintenance = $includemaintenance;

        return $this;
    }

    /**
     * Get includemaintenance.
     *
     * @return bool|null
     */
    public function getIncludemaintenance()
    {
        return $this->includemaintenance;
    }

    /**
     * Set includelostandfound.
     *
     * @param bool|null $includelostandfound
     *
     * @return Servicers
     */
    public function setIncludelostandfound($includelostandfound = null)
    {
        $this->includelostandfound = $includelostandfound;

        return $this;
    }

    /**
     * Get includelostandfound.
     *
     * @return bool|null
     */
    public function getIncludelostandfound()
    {
        return $this->includelostandfound;
    }

    /**
     * Set includesupplyflag.
     *
     * @param bool|null $includesupplyflag
     *
     * @return Servicers
     */
    public function setIncludesupplyflag($includesupplyflag = null)
    {
        $this->includesupplyflag = $includesupplyflag;

        return $this;
    }

    /**
     * Get includesupplyflag.
     *
     * @return bool|null
     */
    public function getIncludesupplyflag()
    {
        return $this->includesupplyflag;
    }

    /**
     * Set includeurgentflag.
     *
     * @param bool|null $includeurgentflag
     *
     * @return Servicers
     */
    public function setIncludeurgentflag($includeurgentflag = null)
    {
        $this->includeurgentflag = $includeurgentflag;

        return $this;
    }

    /**
     * Get includeurgentflag.
     *
     * @return bool|null
     */
    public function getIncludeurgentflag()
    {
        return $this->includeurgentflag;
    }

    /**
     * Set includeservicernote.
     *
     * @param bool|null $includeservicernote
     *
     * @return Servicers
     */
    public function setIncludeservicernote($includeservicernote = null)
    {
        $this->includeservicernote = $includeservicernote;

        return $this;
    }

    /**
     * Get includeservicernote.
     *
     * @return bool|null
     */
    public function getIncludeservicernote()
    {
        return $this->includeservicernote;
    }

    /**
     * Set notifycustomeroncompletion.
     *
     * @param int|null $notifycustomeroncompletion
     *
     * @return Servicers
     */
    public function setNotifycustomeroncompletion($notifycustomeroncompletion = null)
    {
        $this->notifycustomeroncompletion = $notifycustomeroncompletion;

        return $this;
    }

    /**
     * Get notifycustomeroncompletion.
     *
     * @return int|null
     */
    public function getNotifycustomeroncompletion()
    {
        return $this->notifycustomeroncompletion;
    }

    /**
     * Set notifycustomerondamage.
     *
     * @param int|null $notifycustomerondamage
     *
     * @return Servicers
     */
    public function setNotifycustomerondamage($notifycustomerondamage = null)
    {
        $this->notifycustomerondamage = $notifycustomerondamage;

        return $this;
    }

    /**
     * Get notifycustomerondamage.
     *
     * @return int|null
     */
    public function getNotifycustomerondamage()
    {
        return $this->notifycustomerondamage;
    }

    /**
     * Set notifycustomeronmaintenance.
     *
     * @param int|null $notifycustomeronmaintenance
     *
     * @return Servicers
     */
    public function setNotifycustomeronmaintenance($notifycustomeronmaintenance = null)
    {
        $this->notifycustomeronmaintenance = $notifycustomeronmaintenance;

        return $this;
    }

    /**
     * Get notifycustomeronmaintenance.
     *
     * @return int|null
     */
    public function getNotifycustomeronmaintenance()
    {
        return $this->notifycustomeronmaintenance;
    }

    /**
     * Set notifycustomeronlostandfound.
     *
     * @param int|null $notifycustomeronlostandfound
     *
     * @return Servicers
     */
    public function setNotifycustomeronlostandfound($notifycustomeronlostandfound = null)
    {
        $this->notifycustomeronlostandfound = $notifycustomeronlostandfound;

        return $this;
    }

    /**
     * Get notifycustomeronlostandfound.
     *
     * @return int|null
     */
    public function getNotifycustomeronlostandfound()
    {
        return $this->notifycustomeronlostandfound;
    }

    /**
     * Set notifycustomeronsupplyflag.
     *
     * @param int|null $notifycustomeronsupplyflag
     *
     * @return Servicers
     */
    public function setNotifycustomeronsupplyflag($notifycustomeronsupplyflag = null)
    {
        $this->notifycustomeronsupplyflag = $notifycustomeronsupplyflag;

        return $this;
    }

    /**
     * Get notifycustomeronsupplyflag.
     *
     * @return int|null
     */
    public function getNotifycustomeronsupplyflag()
    {
        return $this->notifycustomeronsupplyflag;
    }

    /**
     * Set notifycustomeronservicernote.
     *
     * @param int|null $notifycustomeronservicernote
     *
     * @return Servicers
     */
    public function setNotifycustomeronservicernote($notifycustomeronservicernote = null)
    {
        $this->notifycustomeronservicernote = $notifycustomeronservicernote;

        return $this;
    }

    /**
     * Get notifycustomeronservicernote.
     *
     * @return int|null
     */
    public function getNotifycustomeronservicernote()
    {
        return $this->notifycustomeronservicernote;
    }

    /**
     * Set includetoownernote.
     *
     * @param bool|null $includetoownernote
     *
     * @return Servicers
     */
    public function setIncludetoownernote($includetoownernote = null)
    {
        $this->includetoownernote = $includetoownernote;

        return $this;
    }

    /**
     * Get includetoownernote.
     *
     * @return bool|null
     */
    public function getIncludetoownernote()
    {
        return $this->includetoownernote;
    }

    /**
     * Set defaulttoownernote.
     *
     * @param string|null $defaulttoownernote
     *
     * @return Servicers
     */
    public function setDefaulttoownernote($defaulttoownernote = null)
    {
        $this->defaulttoownernote = $defaulttoownernote;

        return $this;
    }

    /**
     * Get defaulttoownernote.
     *
     * @return string|null
     */
    public function getDefaulttoownernote()
    {
        return $this->defaulttoownernote;
    }

    /**
     * Set notifyowneroncompletion.
     *
     * @param int|null $notifyowneroncompletion
     *
     * @return Servicers
     */
    public function setNotifyowneroncompletion($notifyowneroncompletion = null)
    {
        $this->notifyowneroncompletion = $notifyowneroncompletion;

        return $this;
    }

    /**
     * Get notifyowneroncompletion.
     *
     * @return int|null
     */
    public function getNotifyowneroncompletion()
    {
        return $this->notifyowneroncompletion;
    }

    /**
     * Set allowshareimageswithowners.
     *
     * @param int $allowshareimageswithowners
     *
     * @return Servicers
     */
    public function setAllowshareimageswithowners($allowshareimageswithowners)
    {
        $this->allowshareimageswithowners = $allowshareimageswithowners;

        return $this;
    }

    /**
     * Get allowshareimageswithowners.
     *
     * @return int
     */
    public function getAllowshareimageswithowners()
    {
        return $this->allowshareimageswithowners;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return Servicers
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
     * @return Servicers
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
     * Set adminpassword.
     *
     * @param string|null $adminpassword
     *
     * @return Servicers
     */
    public function setAdminpassword($adminpassword = null)
    {
        $this->adminpassword = $adminpassword;

        return $this;
    }

    /**
     * Get adminpassword.
     *
     * @return string|null
     */
    public function getAdminpassword()
    {
        return $this->adminpassword;
    }

    /**
     * Set welcomeemailsent.
     *
     * @param \DateTime|null $welcomeemailsent
     *
     * @return Servicers
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
     * Set workdays.
     *
     * @param string|null $workdays
     *
     * @return Servicers
     */
    public function setWorkdays($workdays = null)
    {
        $this->workdays = $workdays;

        return $this;
    }

    /**
     * Get workdays.
     *
     * @return string|null
     */
    public function getWorkdays()
    {
        return $this->workdays;
    }

    /**
     * Set backupservicerid1.
     *
     * @param int|null $backupservicerid1
     *
     * @return Servicers
     */
    public function setBackupservicerid1($backupservicerid1 = null)
    {
        $this->backupservicerid1 = $backupservicerid1;

        return $this;
    }

    /**
     * Get backupservicerid1.
     *
     * @return int|null
     */
    public function getBackupservicerid1()
    {
        return $this->backupservicerid1;
    }

    /**
     * Set backupservicerid2.
     *
     * @param int|null $backupservicerid2
     *
     * @return Servicers
     */
    public function setBackupservicerid2($backupservicerid2 = null)
    {
        $this->backupservicerid2 = $backupservicerid2;

        return $this;
    }

    /**
     * Get backupservicerid2.
     *
     * @return int|null
     */
    public function getBackupservicerid2()
    {
        return $this->backupservicerid2;
    }

    /**
     * Set backupservicerid3.
     *
     * @param int|null $backupservicerid3
     *
     * @return Servicers
     */
    public function setBackupservicerid3($backupservicerid3 = null)
    {
        $this->backupservicerid3 = $backupservicerid3;

        return $this;
    }

    /**
     * Get backupservicerid3.
     *
     * @return int|null
     */
    public function getBackupservicerid3()
    {
        return $this->backupservicerid3;
    }

    /**
     * Set backupservicerid4.
     *
     * @param int|null $backupservicerid4
     *
     * @return Servicers
     */
    public function setBackupservicerid4($backupservicerid4 = null)
    {
        $this->backupservicerid4 = $backupservicerid4;

        return $this;
    }

    /**
     * Get backupservicerid4.
     *
     * @return int|null
     */
    public function getBackupservicerid4()
    {
        return $this->backupservicerid4;
    }

    /**
     * Set backupservicerid5.
     *
     * @param int|null $backupservicerid5
     *
     * @return Servicers
     */
    public function setBackupservicerid5($backupservicerid5 = null)
    {
        $this->backupservicerid5 = $backupservicerid5;

        return $this;
    }

    /**
     * Get backupservicerid5.
     *
     * @return int|null
     */
    public function getBackupservicerid5()
    {
        return $this->backupservicerid5;
    }

    /**
     * Set backupservicerid6.
     *
     * @param int|null $backupservicerid6
     *
     * @return Servicers
     */
    public function setBackupservicerid6($backupservicerid6 = null)
    {
        $this->backupservicerid6 = $backupservicerid6;

        return $this;
    }

    /**
     * Get backupservicerid6.
     *
     * @return int|null
     */
    public function getBackupservicerid6()
    {
        return $this->backupservicerid6;
    }

    /**
     * Set backupservicerid7.
     *
     * @param int|null $backupservicerid7
     *
     * @return Servicers
     */
    public function setBackupservicerid7($backupservicerid7 = null)
    {
        $this->backupservicerid7 = $backupservicerid7;

        return $this;
    }

    /**
     * Get backupservicerid7.
     *
     * @return int|null
     */
    public function getBackupservicerid7()
    {
        return $this->backupservicerid7;
    }

    /**
     * Set schedulenote1.
     *
     * @param string|null $schedulenote1
     *
     * @return Servicers
     */
    public function setSchedulenote1($schedulenote1 = null)
    {
        $this->schedulenote1 = $schedulenote1;

        return $this;
    }

    /**
     * Get schedulenote1.
     *
     * @return string|null
     */
    public function getSchedulenote1()
    {
        return $this->schedulenote1;
    }

    /**
     * Set schedulenote2.
     *
     * @param string|null $schedulenote2
     *
     * @return Servicers
     */
    public function setSchedulenote2($schedulenote2 = null)
    {
        $this->schedulenote2 = $schedulenote2;

        return $this;
    }

    /**
     * Get schedulenote2.
     *
     * @return string|null
     */
    public function getSchedulenote2()
    {
        return $this->schedulenote2;
    }

    /**
     * Set schedulenote3.
     *
     * @param string|null $schedulenote3
     *
     * @return Servicers
     */
    public function setSchedulenote3($schedulenote3 = null)
    {
        $this->schedulenote3 = $schedulenote3;

        return $this;
    }

    /**
     * Get schedulenote3.
     *
     * @return string|null
     */
    public function getSchedulenote3()
    {
        return $this->schedulenote3;
    }

    /**
     * Set schedulenote4.
     *
     * @param string|null $schedulenote4
     *
     * @return Servicers
     */
    public function setSchedulenote4($schedulenote4 = null)
    {
        $this->schedulenote4 = $schedulenote4;

        return $this;
    }

    /**
     * Get schedulenote4.
     *
     * @return string|null
     */
    public function getSchedulenote4()
    {
        return $this->schedulenote4;
    }

    /**
     * Set schedulenote5.
     *
     * @param string|null $schedulenote5
     *
     * @return Servicers
     */
    public function setSchedulenote5($schedulenote5 = null)
    {
        $this->schedulenote5 = $schedulenote5;

        return $this;
    }

    /**
     * Get schedulenote5.
     *
     * @return string|null
     */
    public function getSchedulenote5()
    {
        return $this->schedulenote5;
    }

    /**
     * Set schedulenote7.
     *
     * @param string|null $schedulenote7
     *
     * @return Servicers
     */
    public function setSchedulenote7($schedulenote7 = null)
    {
        $this->schedulenote7 = $schedulenote7;

        return $this;
    }

    /**
     * Get schedulenote7.
     *
     * @return string|null
     */
    public function getSchedulenote7()
    {
        return $this->schedulenote7;
    }

    /**
     * Set schedulenote6.
     *
     * @param string|null $schedulenote6
     *
     * @return Servicers
     */
    public function setSchedulenote6($schedulenote6 = null)
    {
        $this->schedulenote6 = $schedulenote6;

        return $this;
    }

    /**
     * Get schedulenote6.
     *
     * @return string|null
     */
    public function getSchedulenote6()
    {
        return $this->schedulenote6;
    }

    /**
     * Set schedulenote1show.
     *
     * @param bool $schedulenote1show
     *
     * @return Servicers
     */
    public function setSchedulenote1show($schedulenote1show)
    {
        $this->schedulenote1show = $schedulenote1show;

        return $this;
    }

    /**
     * Get schedulenote1show.
     *
     * @return bool
     */
    public function getSchedulenote1show()
    {
        return $this->schedulenote1show;
    }

    /**
     * Set schedulenote2show.
     *
     * @param bool $schedulenote2show
     *
     * @return Servicers
     */
    public function setSchedulenote2show($schedulenote2show)
    {
        $this->schedulenote2show = $schedulenote2show;

        return $this;
    }

    /**
     * Get schedulenote2show.
     *
     * @return bool
     */
    public function getSchedulenote2show()
    {
        return $this->schedulenote2show;
    }

    /**
     * Set schedulenote3show.
     *
     * @param bool $schedulenote3show
     *
     * @return Servicers
     */
    public function setSchedulenote3show($schedulenote3show)
    {
        $this->schedulenote3show = $schedulenote3show;

        return $this;
    }

    /**
     * Get schedulenote3show.
     *
     * @return bool
     */
    public function getSchedulenote3show()
    {
        return $this->schedulenote3show;
    }

    /**
     * Set schedulenote4show.
     *
     * @param bool $schedulenote4show
     *
     * @return Servicers
     */
    public function setSchedulenote4show($schedulenote4show)
    {
        $this->schedulenote4show = $schedulenote4show;

        return $this;
    }

    /**
     * Get schedulenote4show.
     *
     * @return bool
     */
    public function getSchedulenote4show()
    {
        return $this->schedulenote4show;
    }

    /**
     * Set schedulenote5show.
     *
     * @param bool $schedulenote5show
     *
     * @return Servicers
     */
    public function setSchedulenote5show($schedulenote5show)
    {
        $this->schedulenote5show = $schedulenote5show;

        return $this;
    }

    /**
     * Get schedulenote5show.
     *
     * @return bool
     */
    public function getSchedulenote5show()
    {
        return $this->schedulenote5show;
    }

    /**
     * Set schedulenote6show.
     *
     * @param bool $schedulenote6show
     *
     * @return Servicers
     */
    public function setSchedulenote6show($schedulenote6show)
    {
        $this->schedulenote6show = $schedulenote6show;

        return $this;
    }

    /**
     * Get schedulenote6show.
     *
     * @return bool
     */
    public function getSchedulenote6show()
    {
        return $this->schedulenote6show;
    }

    /**
     * Set schedulenote7show.
     *
     * @param bool $schedulenote7show
     *
     * @return Servicers
     */
    public function setSchedulenote7show($schedulenote7show)
    {
        $this->schedulenote7show = $schedulenote7show;

        return $this;
    }

    /**
     * Get schedulenote7show.
     *
     * @return bool
     */
    public function getSchedulenote7show()
    {
        return $this->schedulenote7show;
    }

    /**
     * Set allowadminaccess.
     *
     * @param bool $allowadminaccess
     *
     * @return Servicers
     */
    public function setAllowadminaccess($allowadminaccess)
    {
        $this->allowadminaccess = $allowadminaccess;

        return $this;
    }

    /**
     * Get allowadminaccess.
     *
     * @return bool
     */
    public function getAllowadminaccess()
    {
        return $this->allowadminaccess;
    }

    /**
     * Set allowsetupaccess.
     *
     * @param bool $allowsetupaccess
     *
     * @return Servicers
     */
    public function setAllowsetupaccess($allowsetupaccess)
    {
        $this->allowsetupaccess = $allowsetupaccess;

        return $this;
    }

    /**
     * Get allowsetupaccess.
     *
     * @return bool
     */
    public function getAllowsetupaccess()
    {
        return $this->allowsetupaccess;
    }

    /**
     * Set allowserviceassignmentaccess.
     *
     * @param bool $allowserviceassignmentaccess
     *
     * @return Servicers
     */
    public function setAllowserviceassignmentaccess($allowserviceassignmentaccess)
    {
        $this->allowserviceassignmentaccess = $allowserviceassignmentaccess;

        return $this;
    }

    /**
     * Get allowserviceassignmentaccess.
     *
     * @return bool
     */
    public function getAllowserviceassignmentaccess()
    {
        return $this->allowserviceassignmentaccess;
    }

    /**
     * Set allowaccountaccess.
     *
     * @param bool $allowaccountaccess
     *
     * @return Servicers
     */
    public function setAllowaccountaccess($allowaccountaccess)
    {
        $this->allowaccountaccess = $allowaccountaccess;

        return $this;
    }

    /**
     * Get allowaccountaccess.
     *
     * @return bool
     */
    public function getAllowaccountaccess()
    {
        return $this->allowaccountaccess;
    }

    /**
     * Set allowmastercalendar.
     *
     * @param bool $allowmastercalendar
     *
     * @return Servicers
     */
    public function setAllowmastercalendar($allowmastercalendar)
    {
        $this->allowmastercalendar = $allowmastercalendar;

        return $this;
    }

    /**
     * Get allowmastercalendar.
     *
     * @return bool
     */
    public function getAllowmastercalendar()
    {
        return $this->allowmastercalendar;
    }

    /**
     * Set allowissuesaccess.
     *
     * @param bool $allowissuesaccess
     *
     * @return Servicers
     */
    public function setAllowissuesaccess($allowissuesaccess)
    {
        $this->allowissuesaccess = $allowissuesaccess;

        return $this;
    }

    /**
     * Get allowissuesaccess.
     *
     * @return bool
     */
    public function getAllowissuesaccess()
    {
        return $this->allowissuesaccess;
    }

    /**
     * Set allowissuesedit.
     *
     * @param bool $allowissuesedit
     *
     * @return Servicers
     */
    public function setAllowissuesedit($allowissuesedit)
    {
        $this->allowissuesedit = $allowissuesedit;

        return $this;
    }

    /**
     * Get allowissuesedit.
     *
     * @return bool
     */
    public function getAllowissuesedit()
    {
        return $this->allowissuesedit;
    }

    /**
     * Set allowscheduleaccess.
     *
     * @param bool $allowscheduleaccess
     *
     * @return Servicers
     */
    public function setAllowscheduleaccess($allowscheduleaccess)
    {
        $this->allowscheduleaccess = $allowscheduleaccess;

        return $this;
    }

    /**
     * Get allowscheduleaccess.
     *
     * @return bool
     */
    public function getAllowscheduleaccess()
    {
        return $this->allowscheduleaccess;
    }

    /**
     * Set alloweditbookings.
     *
     * @param bool $alloweditbookings
     *
     * @return Servicers
     */
    public function setAlloweditbookings($alloweditbookings)
    {
        $this->alloweditbookings = $alloweditbookings;

        return $this;
    }

    /**
     * Get alloweditbookings.
     *
     * @return bool
     */
    public function getAlloweditbookings()
    {
        return $this->alloweditbookings;
    }

    /**
     * Set allowedittasks.
     *
     * @param bool $allowedittasks
     *
     * @return Servicers
     */
    public function setAllowedittasks($allowedittasks)
    {
        $this->allowedittasks = $allowedittasks;

        return $this;
    }

    /**
     * Get allowedittasks.
     *
     * @return bool
     */
    public function getAllowedittasks()
    {
        return $this->allowedittasks;
    }

    /**
     * Set allowedittaskpiecepay.
     *
     * @param bool $allowedittaskpiecepay
     *
     * @return Servicers
     */
    public function setAllowedittaskpiecepay($allowedittaskpiecepay)
    {
        $this->allowedittaskpiecepay = $allowedittaskpiecepay;

        return $this;
    }

    /**
     * Get allowedittaskpiecepay.
     *
     * @return bool
     */
    public function getAllowedittaskpiecepay()
    {
        return $this->allowedittaskpiecepay;
    }

    /**
     * Set allowdraganddrop.
     *
     * @param bool $allowdraganddrop
     *
     * @return Servicers
     */
    public function setAllowdraganddrop($allowdraganddrop)
    {
        $this->allowdraganddrop = $allowdraganddrop;

        return $this;
    }

    /**
     * Get allowdraganddrop.
     *
     * @return bool
     */
    public function getAllowdraganddrop()
    {
        return $this->allowdraganddrop;
    }

    /**
     * Set alloweditnotes.
     *
     * @param bool $alloweditnotes
     *
     * @return Servicers
     */
    public function setAlloweditnotes($alloweditnotes)
    {
        $this->alloweditnotes = $alloweditnotes;

        return $this;
    }

    /**
     * Get alloweditnotes.
     *
     * @return bool
     */
    public function getAlloweditnotes()
    {
        return $this->alloweditnotes;
    }

    /**
     * Set allowtracking.
     *
     * @param bool $allowtracking
     *
     * @return Servicers
     */
    public function setAllowtracking($allowtracking)
    {
        $this->allowtracking = $allowtracking;

        return $this;
    }

    /**
     * Get allowtracking.
     *
     * @return bool
     */
    public function getAllowtracking()
    {
        return $this->allowtracking;
    }

    /**
     * Set allowreports.
     *
     * @param bool $allowreports
     *
     * @return Servicers
     */
    public function setAllowreports($allowreports)
    {
        $this->allowreports = $allowreports;

        return $this;
    }

    /**
     * Get allowreports.
     *
     * @return bool
     */
    public function getAllowreports()
    {
        return $this->allowreports;
    }

    /**
     * Set allowmanage.
     *
     * @param bool $allowmanage
     *
     * @return Servicers
     */
    public function setAllowmanage($allowmanage)
    {
        $this->allowmanage = $allowmanage;

        return $this;
    }

    /**
     * Get allowmanage.
     *
     * @return bool
     */
    public function getAllowmanage()
    {
        return $this->allowmanage;
    }

    /**
     * Set allowsetupemployees.
     *
     * @param bool $allowsetupemployees
     *
     * @return Servicers
     */
    public function setAllowsetupemployees($allowsetupemployees)
    {
        $this->allowsetupemployees = $allowsetupemployees;

        return $this;
    }

    /**
     * Get allowsetupemployees.
     *
     * @return bool
     */
    public function getAllowsetupemployees()
    {
        return $this->allowsetupemployees;
    }

    /**
     * Set allowquickreports.
     *
     * @param bool $allowquickreports
     *
     * @return Servicers
     */
    public function setAllowquickreports($allowquickreports)
    {
        $this->allowquickreports = $allowquickreports;

        return $this;
    }

    /**
     * Get allowquickreports.
     *
     * @return bool
     */
    public function getAllowquickreports()
    {
        return $this->allowquickreports;
    }

    /**
     * Set allowviewrentdeposit.
     *
     * @param bool $allowviewrentdeposit
     *
     * @return Servicers
     */
    public function setAllowviewrentdeposit($allowviewrentdeposit)
    {
        $this->allowviewrentdeposit = $allowviewrentdeposit;

        return $this;
    }

    /**
     * Get allowviewrentdeposit.
     *
     * @return bool
     */
    public function getAllowviewrentdeposit()
    {
        return $this->allowviewrentdeposit;
    }

    /**
     * Set alloweditrentdeposit.
     *
     * @param bool $alloweditrentdeposit
     *
     * @return Servicers
     */
    public function setAlloweditrentdeposit($alloweditrentdeposit)
    {
        $this->alloweditrentdeposit = $alloweditrentdeposit;

        return $this;
    }

    /**
     * Get alloweditrentdeposit.
     *
     * @return bool
     */
    public function getAlloweditrentdeposit()
    {
        return $this->alloweditrentdeposit;
    }

    /**
     * Set showtasktimeestimates.
     *
     * @param bool $showtasktimeestimates
     *
     * @return Servicers
     */
    public function setShowtasktimeestimates($showtasktimeestimates)
    {
        $this->showtasktimeestimates = $showtasktimeestimates;

        return $this;
    }

    /**
     * Get showtasktimeestimates.
     *
     * @return bool
     */
    public function getShowtasktimeestimates()
    {
        return $this->showtasktimeestimates;
    }

    /**
     * Set requestaccepttasks.
     *
     * @param bool $requestaccepttasks
     *
     * @return Servicers
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
     * Set timetracking.
     *
     * @param bool $timetracking
     *
     * @return Servicers
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
     * Set timetrackingmileage.
     *
     * @param bool|null $timetrackingmileage
     *
     * @return Servicers
     */
    public function setTimetrackingmileage($timetrackingmileage = null)
    {
        $this->timetrackingmileage = $timetrackingmileage;

        return $this;
    }

    /**
     * Get timetrackingmileage.
     *
     * @return bool|null
     */
    public function getTimetrackingmileage()
    {
        return $this->timetrackingmileage;
    }

    /**
     * Set timetrackinggps.
     *
     * @param bool|null $timetrackinggps
     *
     * @return Servicers
     */
    public function setTimetrackinggps($timetrackinggps = null)
    {
        $this->timetrackinggps = $timetrackinggps;

        return $this;
    }

    /**
     * Get timetrackinggps.
     *
     * @return bool|null
     */
    public function getTimetrackinggps()
    {
        return $this->timetrackinggps;
    }

    /**
     * Set payrate.
     *
     * @param float|null $payrate
     *
     * @return Servicers
     */
    public function setPayrate($payrate = null)
    {
        $this->payrate = $payrate;

        return $this;
    }

    /**
     * Get payrate.
     *
     * @return float|null
     */
    public function getPayrate()
    {
        return $this->payrate;
    }

    /**
     * Set allowstartearly.
     *
     * @param bool $allowstartearly
     *
     * @return Servicers
     */
    public function setAllowstartearly($allowstartearly)
    {
        $this->allowstartearly = $allowstartearly;

        return $this;
    }

    /**
     * Get allowstartearly.
     *
     * @return bool
     */
    public function getAllowstartearly()
    {
        return $this->allowstartearly;
    }

    /**
     * Set languageid.
     *
     * @param int $languageid
     *
     * @return Servicers
     */
    public function setLanguageid($languageid)
    {
        $this->languageid = $languageid;

        return $this;
    }

    /**
     * Get languageid.
     *
     * @return int
     */
    public function getLanguageid()
    {
        return $this->languageid;
    }

    /**
     * Set vrscookie1.
     *
     * @param string|null $vrscookie1
     *
     * @return Servicers
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
     * @return Servicers
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
     * @return Servicers
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
     * Set permissionsupdated.
     *
     * @param bool $permissionsupdated
     *
     * @return Servicers
     */
    public function setPermissionsupdated($permissionsupdated)
    {
        $this->permissionsupdated = $permissionsupdated;

        return $this;
    }

    /**
     * Get permissionsupdated.
     *
     * @return bool
     */
    public function getPermissionsupdated()
    {
        return $this->permissionsupdated;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Servicers
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Servicers
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
     * @return Servicers
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
     * @return Servicers
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
     * Set timezoneid.
     *
     * @param \AppBundle\Entity\Timezones|null $timezoneid
     *
     * @return Servicers
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
}
