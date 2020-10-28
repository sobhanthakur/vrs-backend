<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Services
 *
 * @ORM\Table(name="Services", indexes={@ORM\Index(name="active", columns={"Active"}), @ORM\Index(name="Billable", columns={"Billable"}), @ORM\Index(name="CustomerID", columns={"CustomerID"}), @ORM\Index(name="NonClusteredIndex_20180406_230717", columns={"Active"}), @ORM\Index(name="ParentServiceID", columns={"ParentServiceID"}), @ORM\Index(name="servicergroupid", columns={"ServiceGroupID"}), @ORM\Index(name="TaskType", columns={"TaskType"}), @ORM\Index(name="IDX_8A44833FB650950C", columns={"ChecklistID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServicesRepository")
 */
class Services
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServiceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serviceid;

    /**
     * @var string
     *
     * @ORM\Column(name="ServiceName", type="string", length=150, nullable=false)
     */
    private $servicename;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfServicers", type="integer", nullable=true)
     */
    private $numberofservicers = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="Beds24UnitStatusText", type="string", length=50, nullable=true)
     */
    private $beds24UnitStatusText;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Abbreviation", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $abbreviation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ShortDescription", type="string", length=0, nullable=true)
     */
    private $shortdescription;

    /**
     * @var int
     *
     * @ORM\Column(name="TaskType", type="integer", nullable=false, options={"comment"="0=CheckOut,1=CheckIn,2=Mid-Stay,3=Mid-Vacancy,4=ChangeOver"})
     */
    private $tasktype = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="AddIfPet", type="integer", nullable=false)
     */
    private $addifpet = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="AddIfOwner", type="integer", nullable=false)
     */
    private $addifowner = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="AddIfNextOwner", type="integer", nullable=false)
     */
    private $addifnextowner = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="AddIfBothOwner", type="integer", nullable=false)
     */
    private $addifbothowner = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Color", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $color;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AddTag", type="string", length=100, nullable=true, options={"fixed"=true})
     */
    private $addtag;

    /**
     * @var string|null
     *
     * @ORM\Column(name="RemoveTag", type="string", length=100, nullable=true, options={"fixed"=true})
     */
    private $removetag;

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowAllTagsOnDashboards", type="boolean", nullable=false)
     */
    private $showalltagsondashboards = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowPMSHousekeepingNoteOnDashboards", type="boolean", nullable=false)
     */
    private $showpmshousekeepingnoteondashboard = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ChangeOverDays", type="integer", nullable=true)
     */
    private $changeoverdays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MinChangeOverDays", type="integer", nullable=true)
     */
    private $minchangeoverdays;

    /**
     * @var int
     *
     * @ORM\Column(name="SkipOnMaxChangeDays", type="integer", nullable=false, options={"comment"="Only for Check In & Check Out tasks"})
     */
    private $skiponmaxchangedays = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="MaxDaysBeforeToComplete", type="integer", nullable=true)
     */
    private $maxdaysbeforetocomplete;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MaxDaysToComplete", type="integer", nullable=true)
     */
    private $maxdaystocomplete;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CompleteTime", type="integer", nullable=true)
     */
    private $completetime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidStayDays", type="integer", nullable=true)
     */
    private $midstaydays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidVacancyDays", type="integer", nullable=true)
     */
    private $midvacancydays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidStayDaysBeforeStart", type="integer", nullable=true)
     */
    private $midstaydaysbeforestart;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidDayOfWeekStart", type="integer", nullable=true)
     */
    private $middayofweekstart;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidStayDaysAfterEnd", type="integer", nullable=true)
     */
    private $midstaydaysafterend;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidStayDaysToComplete", type="integer", nullable=true)
     */
    private $midstaydaystocomplete;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidVacancyDaysBeforeStart", type="integer", nullable=true)
     */
    private $midvacancydaysbeforestart = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidVacancyDaysAfterEnd", type="integer", nullable=true)
     */
    private $midvacancydaysafterend = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="MidVacancyDaysToComplete", type="integer", nullable=true)
     */
    private $midvacancydaystocomplete = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ScheduleStartDate", type="date", nullable=true)
     */
    private $schedulestartdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ScheduleEndDate", type="date", nullable=true)
     */
    private $scheduleenddate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ScheduleRecurrance", type="integer", nullable=true, options={"comment"="1=every <type>, 2= every other <type> etc."})
     */
    private $schedulerecurrance;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ScheduleType", type="integer", nullable=true, options={"comment"="1=day,2=week,3=month"})
     */
    private $scheduletype;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ScheduleDay", type="integer", nullable=true)
     */
    private $scheduleday;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ScheduleStartTime", type="integer", nullable=true)
     */
    private $schedulestarttime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ScheduleNumDays", type="integer", nullable=true)
     */
    private $schedulenumdays;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ScheduleEndTime", type="integer", nullable=true, options={"comment"="1=every <type>, 2= every other <type> etc."})
     */
    private $scheduleendtime;

    /**
     * @var int
     *
     * @ORM\Column(name="ScheduleVacantOnly", type="integer", nullable=false)
     */
    private $schedulevacantonly = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="OneOffVacantOnly", type="boolean", nullable=true)
     */
    private $oneoffvacantonly = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ParentServiceID", type="integer", nullable=true)
     */
    private $parentserviceid = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="AlertOnParentCompletion", type="integer", nullable=true)
     */
    private $alertonparentcompletion = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AllowStartBeforeParentComplete", type="boolean", nullable=true)
     */
    private $allowstartbeforeparentcomplete = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="RestrictByBookings", type="boolean", nullable=true, options={"default"="1"})
     */
    private $restrictbybookings = '1';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AddPerBooking", type="boolean", nullable=true)
     */
    private $addperbooking = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="ScheduleFirstDay", type="integer", nullable=false, options={"default"="1"})
     */
    private $schedulefirstday = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="IncludeGuestName", type="boolean", nullable=false)
     */
    private $includeguestname = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="IncludeGuestNumbers", type="boolean", nullable=false)
     */
    private $includeguestnumbers = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="IncludeImageUpload", type="boolean", nullable=false, options={"default"="1"})
     */
    private $includeimageupload = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="AllowShareImagesWithOwners", type="boolean", nullable=false)
     */
    private $allowshareimageswithowners = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="CompletionRequired", type="boolean", nullable=true, options={"default"="1"})
     */
    private $completionrequired = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnCompletion", type="integer", nullable=true, options={"comment"="0=off,1=email only, 2=text only, 3= both text and email"})
     */
    private $notifycustomeroncompletion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnOverdue", type="integer", nullable=true, options={"comment"="0=off,1=test only, 2=email only, 3= both text and email"})
     */
    private $notifycustomeronoverdue;

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
     * @ORM\Column(name="NotifyCustomerOnServicerNote", type="integer", nullable=true)
     */
    private $notifycustomeronservicernote;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnSupplyFlag", type="integer", nullable=true)
     */
    private $notifycustomeronsupplyflag = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="IncludeOnIssueForm", type="boolean", nullable=false)
     */
    private $includeonissueform = '0';

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
     * @ORM\Column(name="IncludeServicerNote", type="boolean", nullable=true)
     */
    private $includeservicernote;

    /**
     * @var int
     *
     * @ORM\Column(name="NoDefaultServicerAssignedWithinDays", type="integer", nullable=false)
     */
    private $nodefaultservicerassignedwithindays = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyServicerOnOverdue", type="integer", nullable=true)
     */
    private $notifyserviceronoverdue;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnNotYetDone", type="integer", nullable=true)
     */
    private $notifycustomeronnotyetdone;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyServicerOnNotYetDone", type="integer", nullable=true)
     */
    private $notifyserviceronnotyetdone;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyServicerOnNotYetDoneHours", type="integer", nullable=true, options={"default"="2"})
     */
    private $notifyserviceronnotyetdonehours = '2';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyServicerOnCheckout", type="integer", nullable=true)
     */
    private $notifyserviceroncheckout;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyOwnerOnCompletion", type="integer", nullable=true)
     */
    private $notifyowneroncompletion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyOnAssignment", type="integer", nullable=true)
     */
    private $notifyonassignment = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="RequestAcceptanceOnAssignment", type="boolean", nullable=true)
     */
    private $requestacceptanceonassignment = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="CompletionRequest", type="integer", nullable=false)
     */
    private $completionrequest = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="DayBeforeReminder", type="integer", nullable=false)
     */
    private $daybeforereminder = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ReminderReceivedRequest", type="boolean", nullable=true)
     */
    private $reminderreceivedrequest;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyCustomerOnReminderReceived", type="integer", nullable=true)
     */
    private $notifycustomeronreminderreceived;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeToOwnerNote", type="boolean", nullable=true)
     */
    private $includetoownernote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DefaultToOwnerNote", type="string", length=0, nullable=true)
     */
    private $defaulttoownernote;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeToCustomerNote", type="boolean", nullable=true)
     */
    private $includetocustomernote = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="DefaultToCustomerNote", type="string", length=500, nullable=true)
     */
    private $defaulttocustomernote;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeDamageFlag", type="boolean", nullable=true, options={"default"="1"})
     */
    private $includedamageflag = '1';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeLostAndFoundFlag", type="boolean", nullable=true)
     */
    private $includelostandfoundflag;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IncludeUrgentFlag", type="boolean", nullable=true)
     */
    private $includeurgentflag = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ActiveForOwner", type="boolean", nullable=false, options={"default"="1"})
     */
    private $activeforowner = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Billable", type="boolean", nullable=true)
     */
    private $billable;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=true)
     */
    private $amount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="ExpenseAmount", type="float", precision=53, scale=0, nullable=true)
     */
    private $expenseamount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LaborAccount", type="string", length=250, nullable=true)
     */
    private $laboraccount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="MaterialsAccount", type="string", length=250, nullable=true)
     */
    private $materialsaccount;

    /**
     * @var int
     *
     * @ORM\Column(name="PayType", type="integer", nullable=false)
     */
    private $paytype = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="BH247CleaningState", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $bh247cleaningstate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BH247QAState", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $bh247qastate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BH247MaintenanceState", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $bh247maintenancestate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BH247Custom_1State", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $bh247custom1state;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BH247Custom_2State", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $bh247custom2state;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ESCAPIAHOUSEKEEPINGSTATUS", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $escapiahousekeepingstatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CloudbedsHousekeepingStatus", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $cloudbedshousekeepingstatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="WebRezProStatus", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $webRezProStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LMPMStatus", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $lmpmStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="GuestyStatus", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $guestyStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Beds24UnitStatusIndex", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $beds24UnitStatusIndex;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OpertoStatus", type="string", length=40, nullable=true, options={"fixed"=true})
     */
    private $opertostatus;

    /**
     * @var \Servicegroups
     *
     * @ORM\ManyToOne(targetEntity="Servicegroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceGroupID", referencedColumnName="ServiceGroupID")
     * })
     */
    private $servicegroupid;

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
     * @var \Checklists
     *
     * @ORM\ManyToOne(targetEntity="Checklists")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ChecklistID", referencedColumnName="ChecklistID")
     * })
     */
    private $checklistid;



    /**
     * Get serviceid.
     *
     * @return int
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set servicename.
     *
     * @param string $servicename
     *
     * @return Services
     */
    public function setServicename($servicename)
    {
        $this->servicename = $servicename;

        return $this;
    }

    /**
     * Get servicename.
     *
     * @return string
     */
    public function getServicename()
    {
        return $this->servicename;
    }

    /**
     * Set numberofservicers.
     *
     * @param int|null $numberofservicers
     *
     * @return Services
     */
    public function setNumberofservicers($numberofservicers = null)
    {
        $this->numberofservicers = $numberofservicers;

        return $this;
    }

    /**
     * Get numberofservicers.
     *
     * @return int|null
     */
    public function getNumberofservicers()
    {
        return $this->numberofservicers;
    }

    /**
     * Set abbreviation.
     *
     * @param string|null $abbreviation
     *
     * @return Services
     */
    public function setAbbreviation($abbreviation = null)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string|null
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set shortdescription.
     *
     * @param string|null $shortdescription
     *
     * @return Services
     */
    public function setShortdescription($shortdescription = null)
    {
        $this->shortdescription = $shortdescription;

        return $this;
    }

    /**
     * Get shortdescription.
     *
     * @return string|null
     */
    public function getShortdescription()
    {
        return $this->shortdescription;
    }

    /**
     * Set tasktype.
     *
     * @param int $tasktype
     *
     * @return Services
     */
    public function setTasktype($tasktype)
    {
        $this->tasktype = $tasktype;

        return $this;
    }

    /**
     * Get tasktype.
     *
     * @return int
     */
    public function getTasktype()
    {
        return $this->tasktype;
    }

    /**
     * Set addifpet.
     *
     * @param int $addifpet
     *
     * @return Services
     */
    public function setAddifpet($addifpet)
    {
        $this->addifpet = $addifpet;

        return $this;
    }

    /**
     * Get addifpet.
     *
     * @return int
     */
    public function getAddifpet()
    {
        return $this->addifpet;
    }

    /**
     * Set addifowner.
     *
     * @param int $addifowner
     *
     * @return Services
     */
    public function setAddifowner($addifowner)
    {
        $this->addifowner = $addifowner;

        return $this;
    }

    /**
     * Get addifowner.
     *
     * @return int
     */
    public function getAddifowner()
    {
        return $this->addifowner;
    }

    /**
     * Set addifnextowner.
     *
     * @param int $addifnextowner
     *
     * @return Services
     */
    public function setAddifnextowner($addifnextowner)
    {
        $this->addifnextowner = $addifnextowner;

        return $this;
    }

    /**
     * Get addifnextowner.
     *
     * @return int
     */
    public function getAddifnextowner()
    {
        return $this->addifnextowner;
    }

    /**
     * Set addifbothowner.
     *
     * @param int $addifbothowner
     *
     * @return Services
     */
    public function setAddifbothowner($addifbothowner)
    {
        $this->addifbothowner = $addifbothowner;

        return $this;
    }

    /**
     * Get addifbothowner.
     *
     * @return int
     */
    public function getAddifbothowner()
    {
        return $this->addifbothowner;
    }

    /**
     * Set color.
     *
     * @param string|null $color
     *
     * @return Services
     */
    public function setColor($color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set addtag.
     *
     * @param string|null $addtag
     *
     * @return Services
     */
    public function setAddtag($addtag = null)
    {
        $this->addtag = $addtag;

        return $this;
    }

    /**
     * Get addtag.
     *
     * @return string|null
     */
    public function getAddtag()
    {
        return $this->addtag;
    }

    /**
     * Set removetag.
     *
     * @param string|null $removetag
     *
     * @return Services
     */
    public function setRemovetag($removetag = null)
    {
        $this->removetag = $removetag;

        return $this;
    }

    /**
     * Get removetag.
     *
     * @return string|null
     */
    public function getRemovetag()
    {
        return $this->removetag;
    }

    /**
     * Set showalltagsondashboards.
     *
     * @param bool $showalltagsondashboards
     *
     * @return Services
     */
    public function setShowalltagsondashboards($showalltagsondashboards)
    {
        $this->showalltagsondashboards = $showalltagsondashboards;

        return $this;
    }

    /**
     * Get showalltagsondashboards.
     *
     * @return bool
     */
    public function getShowalltagsondashboards()
    {
        return $this->showalltagsondashboards;
    }

    /**
     * Set changeoverdays.
     *
     * @param int|null $changeoverdays
     *
     * @return Services
     */
    public function setChangeoverdays($changeoverdays = null)
    {
        $this->changeoverdays = $changeoverdays;

        return $this;
    }

    /**
     * Get changeoverdays.
     *
     * @return int|null
     */
    public function getChangeoverdays()
    {
        return $this->changeoverdays;
    }

    /**
     * Set minchangeoverdays.
     *
     * @param int|null $minchangeoverdays
     *
     * @return Services
     */
    public function setMinchangeoverdays($minchangeoverdays = null)
    {
        $this->minchangeoverdays = $minchangeoverdays;

        return $this;
    }

    /**
     * Get minchangeoverdays.
     *
     * @return int|null
     */
    public function getMinchangeoverdays()
    {
        return $this->minchangeoverdays;
    }

    /**
     * Set skiponmaxchangedays.
     *
     * @param int $skiponmaxchangedays
     *
     * @return Services
     */
    public function setSkiponmaxchangedays($skiponmaxchangedays)
    {
        $this->skiponmaxchangedays = $skiponmaxchangedays;

        return $this;
    }

    /**
     * Get skiponmaxchangedays.
     *
     * @return int
     */
    public function getSkiponmaxchangedays()
    {
        return $this->skiponmaxchangedays;
    }

    /**
     * Set maxdaysbeforetocomplete.
     *
     * @param int|null $maxdaysbeforetocomplete
     *
     * @return Services
     */
    public function setMaxdaysbeforetocomplete($maxdaysbeforetocomplete = null)
    {
        $this->maxdaysbeforetocomplete = $maxdaysbeforetocomplete;

        return $this;
    }

    /**
     * Get maxdaysbeforetocomplete.
     *
     * @return int|null
     */
    public function getMaxdaysbeforetocomplete()
    {
        return $this->maxdaysbeforetocomplete;
    }

    /**
     * Set maxdaystocomplete.
     *
     * @param int|null $maxdaystocomplete
     *
     * @return Services
     */
    public function setMaxdaystocomplete($maxdaystocomplete = null)
    {
        $this->maxdaystocomplete = $maxdaystocomplete;

        return $this;
    }

    /**
     * Get maxdaystocomplete.
     *
     * @return int|null
     */
    public function getMaxdaystocomplete()
    {
        return $this->maxdaystocomplete;
    }

    /**
     * Set completetime.
     *
     * @param int|null $completetime
     *
     * @return Services
     */
    public function setCompletetime($completetime = null)
    {
        $this->completetime = $completetime;

        return $this;
    }

    /**
     * Get completetime.
     *
     * @return int|null
     */
    public function getCompletetime()
    {
        return $this->completetime;
    }

    /**
     * Set midstaydays.
     *
     * @param int|null $midstaydays
     *
     * @return Services
     */
    public function setMidstaydays($midstaydays = null)
    {
        $this->midstaydays = $midstaydays;

        return $this;
    }

    /**
     * Get midstaydays.
     *
     * @return int|null
     */
    public function getMidstaydays()
    {
        return $this->midstaydays;
    }

    /**
     * Set midvacancydays.
     *
     * @param int|null $midvacancydays
     *
     * @return Services
     */
    public function setMidvacancydays($midvacancydays = null)
    {
        $this->midvacancydays = $midvacancydays;

        return $this;
    }

    /**
     * Get midvacancydays.
     *
     * @return int|null
     */
    public function getMidvacancydays()
    {
        return $this->midvacancydays;
    }

    /**
     * Set midstaydaysbeforestart.
     *
     * @param int|null $midstaydaysbeforestart
     *
     * @return Services
     */
    public function setMidstaydaysbeforestart($midstaydaysbeforestart = null)
    {
        $this->midstaydaysbeforestart = $midstaydaysbeforestart;

        return $this;
    }

    /**
     * Get midstaydaysbeforestart.
     *
     * @return int|null
     */
    public function getMidstaydaysbeforestart()
    {
        return $this->midstaydaysbeforestart;
    }

    /**
     * Set middayofweekstart.
     *
     * @param int|null $middayofweekstart
     *
     * @return Services
     */
    public function setMiddayofweekstart($middayofweekstart = null)
    {
        $this->middayofweekstart = $middayofweekstart;

        return $this;
    }

    /**
     * Get middayofweekstart.
     *
     * @return int|null
     */
    public function getMiddayofweekstart()
    {
        return $this->middayofweekstart;
    }

    /**
     * Set midstaydaysafterend.
     *
     * @param int|null $midstaydaysafterend
     *
     * @return Services
     */
    public function setMidstaydaysafterend($midstaydaysafterend = null)
    {
        $this->midstaydaysafterend = $midstaydaysafterend;

        return $this;
    }

    /**
     * Get midstaydaysafterend.
     *
     * @return int|null
     */
    public function getMidstaydaysafterend()
    {
        return $this->midstaydaysafterend;
    }

    /**
     * Set midstaydaystocomplete.
     *
     * @param int|null $midstaydaystocomplete
     *
     * @return Services
     */
    public function setMidstaydaystocomplete($midstaydaystocomplete = null)
    {
        $this->midstaydaystocomplete = $midstaydaystocomplete;

        return $this;
    }

    /**
     * Get midstaydaystocomplete.
     *
     * @return int|null
     */
    public function getMidstaydaystocomplete()
    {
        return $this->midstaydaystocomplete;
    }

    /**
     * Set midvacancydaysbeforestart.
     *
     * @param int|null $midvacancydaysbeforestart
     *
     * @return Services
     */
    public function setMidvacancydaysbeforestart($midvacancydaysbeforestart = null)
    {
        $this->midvacancydaysbeforestart = $midvacancydaysbeforestart;

        return $this;
    }

    /**
     * Get midvacancydaysbeforestart.
     *
     * @return int|null
     */
    public function getMidvacancydaysbeforestart()
    {
        return $this->midvacancydaysbeforestart;
    }

    /**
     * Set midvacancydaysafterend.
     *
     * @param int|null $midvacancydaysafterend
     *
     * @return Services
     */
    public function setMidvacancydaysafterend($midvacancydaysafterend = null)
    {
        $this->midvacancydaysafterend = $midvacancydaysafterend;

        return $this;
    }

    /**
     * Get midvacancydaysafterend.
     *
     * @return int|null
     */
    public function getMidvacancydaysafterend()
    {
        return $this->midvacancydaysafterend;
    }

    /**
     * Set midvacancydaystocomplete.
     *
     * @param int|null $midvacancydaystocomplete
     *
     * @return Services
     */
    public function setMidvacancydaystocomplete($midvacancydaystocomplete = null)
    {
        $this->midvacancydaystocomplete = $midvacancydaystocomplete;

        return $this;
    }

    /**
     * Get midvacancydaystocomplete.
     *
     * @return int|null
     */
    public function getMidvacancydaystocomplete()
    {
        return $this->midvacancydaystocomplete;
    }

    /**
     * Set schedulestartdate.
     *
     * @param \DateTime|null $schedulestartdate
     *
     * @return Services
     */
    public function setSchedulestartdate($schedulestartdate = null)
    {
        $this->schedulestartdate = $schedulestartdate;

        return $this;
    }

    /**
     * Get schedulestartdate.
     *
     * @return \DateTime|null
     */
    public function getSchedulestartdate()
    {
        return $this->schedulestartdate;
    }

    /**
     * Set scheduleenddate.
     *
     * @param \DateTime|null $scheduleenddate
     *
     * @return Services
     */
    public function setScheduleenddate($scheduleenddate = null)
    {
        $this->scheduleenddate = $scheduleenddate;

        return $this;
    }

    /**
     * Get scheduleenddate.
     *
     * @return \DateTime|null
     */
    public function getScheduleenddate()
    {
        return $this->scheduleenddate;
    }

    /**
     * Set schedulerecurrance.
     *
     * @param int|null $schedulerecurrance
     *
     * @return Services
     */
    public function setSchedulerecurrance($schedulerecurrance = null)
    {
        $this->schedulerecurrance = $schedulerecurrance;

        return $this;
    }

    /**
     * Get schedulerecurrance.
     *
     * @return int|null
     */
    public function getSchedulerecurrance()
    {
        return $this->schedulerecurrance;
    }

    /**
     * Set scheduletype.
     *
     * @param int|null $scheduletype
     *
     * @return Services
     */
    public function setScheduletype($scheduletype = null)
    {
        $this->scheduletype = $scheduletype;

        return $this;
    }

    /**
     * Get scheduletype.
     *
     * @return int|null
     */
    public function getScheduletype()
    {
        return $this->scheduletype;
    }

    /**
     * Set scheduleday.
     *
     * @param int|null $scheduleday
     *
     * @return Services
     */
    public function setScheduleday($scheduleday = null)
    {
        $this->scheduleday = $scheduleday;

        return $this;
    }

    /**
     * Get scheduleday.
     *
     * @return int|null
     */
    public function getScheduleday()
    {
        return $this->scheduleday;
    }

    /**
     * Set schedulestarttime.
     *
     * @param int|null $schedulestarttime
     *
     * @return Services
     */
    public function setSchedulestarttime($schedulestarttime = null)
    {
        $this->schedulestarttime = $schedulestarttime;

        return $this;
    }

    /**
     * Get schedulestarttime.
     *
     * @return int|null
     */
    public function getSchedulestarttime()
    {
        return $this->schedulestarttime;
    }

    /**
     * Set schedulenumdays.
     *
     * @param int|null $schedulenumdays
     *
     * @return Services
     */
    public function setSchedulenumdays($schedulenumdays = null)
    {
        $this->schedulenumdays = $schedulenumdays;

        return $this;
    }

    /**
     * Get schedulenumdays.
     *
     * @return int|null
     */
    public function getSchedulenumdays()
    {
        return $this->schedulenumdays;
    }

    /**
     * Set scheduleendtime.
     *
     * @param int|null $scheduleendtime
     *
     * @return Services
     */
    public function setScheduleendtime($scheduleendtime = null)
    {
        $this->scheduleendtime = $scheduleendtime;

        return $this;
    }

    /**
     * Get scheduleendtime.
     *
     * @return int|null
     */
    public function getScheduleendtime()
    {
        return $this->scheduleendtime;
    }

    /**
     * Set schedulevacantonly.
     *
     * @param int $schedulevacantonly
     *
     * @return Services
     */
    public function setSchedulevacantonly($schedulevacantonly)
    {
        $this->schedulevacantonly = $schedulevacantonly;

        return $this;
    }

    /**
     * Get schedulevacantonly.
     *
     * @return int
     */
    public function getSchedulevacantonly()
    {
        return $this->schedulevacantonly;
    }

    /**
     * Set oneoffvacantonly.
     *
     * @param bool|null $oneoffvacantonly
     *
     * @return Services
     */
    public function setOneoffvacantonly($oneoffvacantonly = null)
    {
        $this->oneoffvacantonly = $oneoffvacantonly;

        return $this;
    }

    /**
     * Get oneoffvacantonly.
     *
     * @return bool|null
     */
    public function getOneoffvacantonly()
    {
        return $this->oneoffvacantonly;
    }

    /**
     * Set parentserviceid.
     *
     * @param int|null $parentserviceid
     *
     * @return Services
     */
    public function setParentserviceid($parentserviceid = null)
    {
        $this->parentserviceid = $parentserviceid;

        return $this;
    }

    /**
     * Get parentserviceid.
     *
     * @return int|null
     */
    public function getParentserviceid()
    {
        return $this->parentserviceid;
    }

    /**
     * Set alertonparentcompletion.
     *
     * @param int|null $alertonparentcompletion
     *
     * @return Services
     */
    public function setAlertonparentcompletion($alertonparentcompletion = null)
    {
        $this->alertonparentcompletion = $alertonparentcompletion;

        return $this;
    }

    /**
     * Get alertonparentcompletion.
     *
     * @return int|null
     */
    public function getAlertonparentcompletion()
    {
        return $this->alertonparentcompletion;
    }

    /**
     * Set allowstartbeforeparentcomplete.
     *
     * @param bool|null $allowstartbeforeparentcomplete
     *
     * @return Services
     */
    public function setAllowstartbeforeparentcomplete($allowstartbeforeparentcomplete = null)
    {
        $this->allowstartbeforeparentcomplete = $allowstartbeforeparentcomplete;

        return $this;
    }

    /**
     * Get allowstartbeforeparentcomplete.
     *
     * @return bool|null
     */
    public function getAllowstartbeforeparentcomplete()
    {
        return $this->allowstartbeforeparentcomplete;
    }

    /**
     * Set restrictbybookings.
     *
     * @param bool|null $restrictbybookings
     *
     * @return Services
     */
    public function setRestrictbybookings($restrictbybookings = null)
    {
        $this->restrictbybookings = $restrictbybookings;

        return $this;
    }

    /**
     * Get restrictbybookings.
     *
     * @return bool|null
     */
    public function getRestrictbybookings()
    {
        return $this->restrictbybookings;
    }

    /**
     * Set addperbooking.
     *
     * @param bool|null $addperbooking
     *
     * @return Services
     */
    public function setAddperbooking($addperbooking = null)
    {
        $this->addperbooking = $addperbooking;

        return $this;
    }

    /**
     * Get addperbooking.
     *
     * @return bool|null
     */
    public function getAddperbooking()
    {
        return $this->addperbooking;
    }

    /**
     * Set schedulefirstday.
     *
     * @param int $schedulefirstday
     *
     * @return Services
     */
    public function setSchedulefirstday($schedulefirstday)
    {
        $this->schedulefirstday = $schedulefirstday;

        return $this;
    }

    /**
     * Get schedulefirstday.
     *
     * @return int
     */
    public function getSchedulefirstday()
    {
        return $this->schedulefirstday;
    }

    /**
     * Set includeguestname.
     *
     * @param bool $includeguestname
     *
     * @return Services
     */
    public function setIncludeguestname($includeguestname)
    {
        $this->includeguestname = $includeguestname;

        return $this;
    }

    /**
     * Get includeguestname.
     *
     * @return bool
     */
    public function getIncludeguestname()
    {
        return $this->includeguestname;
    }

    /**
     * Set includeguestnumbers.
     *
     * @param bool $includeguestnumbers
     *
     * @return Services
     */
    public function setIncludeguestnumbers($includeguestnumbers)
    {
        $this->includeguestnumbers = $includeguestnumbers;

        return $this;
    }

    /**
     * Get includeguestnumbers.
     *
     * @return bool
     */
    public function getIncludeguestnumbers()
    {
        return $this->includeguestnumbers;
    }

    /**
     * Set includeimageupload.
     *
     * @param bool $includeimageupload
     *
     * @return Services
     */
    public function setIncludeimageupload($includeimageupload)
    {
        $this->includeimageupload = $includeimageupload;

        return $this;
    }

    /**
     * Get includeimageupload.
     *
     * @return bool
     */
    public function getIncludeimageupload()
    {
        return $this->includeimageupload;
    }

    /**
     * Set allowshareimageswithowners.
     *
     * @param bool $allowshareimageswithowners
     *
     * @return Services
     */
    public function setAllowshareimageswithowners($allowshareimageswithowners)
    {
        $this->allowshareimageswithowners = $allowshareimageswithowners;

        return $this;
    }

    /**
     * Get allowshareimageswithowners.
     *
     * @return bool
     */
    public function getAllowshareimageswithowners()
    {
        return $this->allowshareimageswithowners;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Services
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
     * Set completionrequired.
     *
     * @param bool|null $completionrequired
     *
     * @return Services
     */
    public function setCompletionrequired($completionrequired = null)
    {
        $this->completionrequired = $completionrequired;

        return $this;
    }

    /**
     * Get completionrequired.
     *
     * @return bool|null
     */
    public function getCompletionrequired()
    {
        return $this->completionrequired;
    }

    /**
     * Set notifycustomeroncompletion.
     *
     * @param int|null $notifycustomeroncompletion
     *
     * @return Services
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
     * Set notifycustomeronoverdue.
     *
     * @param int|null $notifycustomeronoverdue
     *
     * @return Services
     */
    public function setNotifycustomeronoverdue($notifycustomeronoverdue = null)
    {
        $this->notifycustomeronoverdue = $notifycustomeronoverdue;

        return $this;
    }

    /**
     * Get notifycustomeronoverdue.
     *
     * @return int|null
     */
    public function getNotifycustomeronoverdue()
    {
        return $this->notifycustomeronoverdue;
    }

    /**
     * Set notifycustomerondamage.
     *
     * @param int|null $notifycustomerondamage
     *
     * @return Services
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
     * @return Services
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
     * @return Services
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
     * Set notifycustomeronservicernote.
     *
     * @param int|null $notifycustomeronservicernote
     *
     * @return Services
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
     * Set notifycustomeronsupplyflag.
     *
     * @param int|null $notifycustomeronsupplyflag
     *
     * @return Services
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
     * Set includeonissueform.
     *
     * @param bool $includeonissueform
     *
     * @return Services
     */
    public function setIncludeonissueform($includeonissueform)
    {
        $this->includeonissueform = $includeonissueform;

        return $this;
    }

    /**
     * Get includeonissueform.
     *
     * @return bool
     */
    public function getIncludeonissueform()
    {
        return $this->includeonissueform;
    }

    /**
     * Set includedamage.
     *
     * @param bool|null $includedamage
     *
     * @return Services
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
     * @return Services
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
     * @return Services
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
     * @return Services
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
     * Set includeservicernote.
     *
     * @param bool|null $includeservicernote
     *
     * @return Services
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
     * Set nodefaultservicerassignedwithindays.
     *
     * @param int $nodefaultservicerassignedwithindays
     *
     * @return Services
     */
    public function setNodefaultservicerassignedwithindays($nodefaultservicerassignedwithindays)
    {
        $this->nodefaultservicerassignedwithindays = $nodefaultservicerassignedwithindays;

        return $this;
    }

    /**
     * Get nodefaultservicerassignedwithindays.
     *
     * @return int
     */
    public function getNodefaultservicerassignedwithindays()
    {
        return $this->nodefaultservicerassignedwithindays;
    }

    /**
     * Set notifyserviceronoverdue.
     *
     * @param int|null $notifyserviceronoverdue
     *
     * @return Services
     */
    public function setNotifyserviceronoverdue($notifyserviceronoverdue = null)
    {
        $this->notifyserviceronoverdue = $notifyserviceronoverdue;

        return $this;
    }

    /**
     * Get notifyserviceronoverdue.
     *
     * @return int|null
     */
    public function getNotifyserviceronoverdue()
    {
        return $this->notifyserviceronoverdue;
    }

    /**
     * Set notifycustomeronnotyetdone.
     *
     * @param int|null $notifycustomeronnotyetdone
     *
     * @return Services
     */
    public function setNotifycustomeronnotyetdone($notifycustomeronnotyetdone = null)
    {
        $this->notifycustomeronnotyetdone = $notifycustomeronnotyetdone;

        return $this;
    }

    /**
     * Get notifycustomeronnotyetdone.
     *
     * @return int|null
     */
    public function getNotifycustomeronnotyetdone()
    {
        return $this->notifycustomeronnotyetdone;
    }

    /**
     * Set notifyserviceronnotyetdone.
     *
     * @param int|null $notifyserviceronnotyetdone
     *
     * @return Services
     */
    public function setNotifyserviceronnotyetdone($notifyserviceronnotyetdone = null)
    {
        $this->notifyserviceronnotyetdone = $notifyserviceronnotyetdone;

        return $this;
    }

    /**
     * Get notifyserviceronnotyetdone.
     *
     * @return int|null
     */
    public function getNotifyserviceronnotyetdone()
    {
        return $this->notifyserviceronnotyetdone;
    }

    /**
     * Set notifyserviceronnotyetdonehours.
     *
     * @param int|null $notifyserviceronnotyetdonehours
     *
     * @return Services
     */
    public function setNotifyserviceronnotyetdonehours($notifyserviceronnotyetdonehours = null)
    {
        $this->notifyserviceronnotyetdonehours = $notifyserviceronnotyetdonehours;

        return $this;
    }

    /**
     * Get notifyserviceronnotyetdonehours.
     *
     * @return int|null
     */
    public function getNotifyserviceronnotyetdonehours()
    {
        return $this->notifyserviceronnotyetdonehours;
    }

    /**
     * Set notifyserviceroncheckout.
     *
     * @param int|null $notifyserviceroncheckout
     *
     * @return Services
     */
    public function setNotifyserviceroncheckout($notifyserviceroncheckout = null)
    {
        $this->notifyserviceroncheckout = $notifyserviceroncheckout;

        return $this;
    }

    /**
     * Get notifyserviceroncheckout.
     *
     * @return int|null
     */
    public function getNotifyserviceroncheckout()
    {
        return $this->notifyserviceroncheckout;
    }

    /**
     * Set notifyowneroncompletion.
     *
     * @param int|null $notifyowneroncompletion
     *
     * @return Services
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
     * Set notifyonassignment.
     *
     * @param int|null $notifyonassignment
     *
     * @return Services
     */
    public function setNotifyonassignment($notifyonassignment = null)
    {
        $this->notifyonassignment = $notifyonassignment;

        return $this;
    }

    /**
     * Get notifyonassignment.
     *
     * @return int|null
     */
    public function getNotifyonassignment()
    {
        return $this->notifyonassignment;
    }

    /**
     * Set requestacceptanceonassignment.
     *
     * @param bool|null $requestacceptanceonassignment
     *
     * @return Services
     */
    public function setRequestacceptanceonassignment($requestacceptanceonassignment = null)
    {
        $this->requestacceptanceonassignment = $requestacceptanceonassignment;

        return $this;
    }

    /**
     * Get requestacceptanceonassignment.
     *
     * @return bool|null
     */
    public function getRequestacceptanceonassignment()
    {
        return $this->requestacceptanceonassignment;
    }

    /**
     * Set completionrequest.
     *
     * @param int $completionrequest
     *
     * @return Services
     */
    public function setCompletionrequest($completionrequest)
    {
        $this->completionrequest = $completionrequest;

        return $this;
    }

    /**
     * Get completionrequest.
     *
     * @return int
     */
    public function getCompletionrequest()
    {
        return $this->completionrequest;
    }

    /**
     * Set daybeforereminder.
     *
     * @param int $daybeforereminder
     *
     * @return Services
     */
    public function setDaybeforereminder($daybeforereminder)
    {
        $this->daybeforereminder = $daybeforereminder;

        return $this;
    }

    /**
     * Get daybeforereminder.
     *
     * @return int
     */
    public function getDaybeforereminder()
    {
        return $this->daybeforereminder;
    }

    /**
     * Set reminderreceivedrequest.
     *
     * @param bool|null $reminderreceivedrequest
     *
     * @return Services
     */
    public function setReminderreceivedrequest($reminderreceivedrequest = null)
    {
        $this->reminderreceivedrequest = $reminderreceivedrequest;

        return $this;
    }

    /**
     * Get reminderreceivedrequest.
     *
     * @return bool|null
     */
    public function getReminderreceivedrequest()
    {
        return $this->reminderreceivedrequest;
    }

    /**
     * Set notifycustomeronreminderreceived.
     *
     * @param int|null $notifycustomeronreminderreceived
     *
     * @return Services
     */
    public function setNotifycustomeronreminderreceived($notifycustomeronreminderreceived = null)
    {
        $this->notifycustomeronreminderreceived = $notifycustomeronreminderreceived;

        return $this;
    }

    /**
     * Get notifycustomeronreminderreceived.
     *
     * @return int|null
     */
    public function getNotifycustomeronreminderreceived()
    {
        return $this->notifycustomeronreminderreceived;
    }

    /**
     * Set includetoownernote.
     *
     * @param bool|null $includetoownernote
     *
     * @return Services
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
     * @return Services
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
     * Set includetocustomernote.
     *
     * @param bool|null $includetocustomernote
     *
     * @return Services
     */
    public function setIncludetocustomernote($includetocustomernote = null)
    {
        $this->includetocustomernote = $includetocustomernote;

        return $this;
    }

    /**
     * Get includetocustomernote.
     *
     * @return bool|null
     */
    public function getIncludetocustomernote()
    {
        return $this->includetocustomernote;
    }

    /**
     * Set defaulttocustomernote.
     *
     * @param string|null $defaulttocustomernote
     *
     * @return Services
     */
    public function setDefaulttocustomernote($defaulttocustomernote = null)
    {
        $this->defaulttocustomernote = $defaulttocustomernote;

        return $this;
    }

    /**
     * Get defaulttocustomernote.
     *
     * @return string|null
     */
    public function getDefaulttocustomernote()
    {
        return $this->defaulttocustomernote;
    }

    /**
     * Set includedamageflag.
     *
     * @param bool|null $includedamageflag
     *
     * @return Services
     */
    public function setIncludedamageflag($includedamageflag = null)
    {
        $this->includedamageflag = $includedamageflag;

        return $this;
    }

    /**
     * Get includedamageflag.
     *
     * @return bool|null
     */
    public function getIncludedamageflag()
    {
        return $this->includedamageflag;
    }

    /**
     * Set includelostandfoundflag.
     *
     * @param bool|null $includelostandfoundflag
     *
     * @return Services
     */
    public function setIncludelostandfoundflag($includelostandfoundflag = null)
    {
        $this->includelostandfoundflag = $includelostandfoundflag;

        return $this;
    }

    /**
     * Get includelostandfoundflag.
     *
     * @return bool|null
     */
    public function getIncludelostandfoundflag()
    {
        return $this->includelostandfoundflag;
    }

    /**
     * Set includeurgentflag.
     *
     * @param bool|null $includeurgentflag
     *
     * @return Services
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
     * Set activeforowner.
     *
     * @param bool $activeforowner
     *
     * @return Services
     */
    public function setActiveforowner($activeforowner)
    {
        $this->activeforowner = $activeforowner;

        return $this;
    }

    /**
     * Get activeforowner.
     *
     * @return bool
     */
    public function getActiveforowner()
    {
        return $this->activeforowner;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Services
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
     * Set billable.
     *
     * @param bool|null $billable
     *
     * @return Services
     */
    public function setBillable($billable = null)
    {
        $this->billable = $billable;

        return $this;
    }

    /**
     * Get billable.
     *
     * @return bool|null
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return Services
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set expenseamount.
     *
     * @param float|null $expenseamount
     *
     * @return Services
     */
    public function setExpenseamount($expenseamount = null)
    {
        $this->expenseamount = $expenseamount;

        return $this;
    }

    /**
     * Get expenseamount.
     *
     * @return float|null
     */
    public function getExpenseamount()
    {
        return $this->expenseamount;
    }

    /**
     * Set laboraccount.
     *
     * @param string|null $laboraccount
     *
     * @return Services
     */
    public function setLaboraccount($laboraccount = null)
    {
        $this->laboraccount = $laboraccount;

        return $this;
    }

    /**
     * Get laboraccount.
     *
     * @return string|null
     */
    public function getLaboraccount()
    {
        return $this->laboraccount;
    }

    /**
     * Set materialsaccount.
     *
     * @param string|null $materialsaccount
     *
     * @return Services
     */
    public function setMaterialsaccount($materialsaccount = null)
    {
        $this->materialsaccount = $materialsaccount;

        return $this;
    }

    /**
     * Get materialsaccount.
     *
     * @return string|null
     */
    public function getMaterialsaccount()
    {
        return $this->materialsaccount;
    }

    /**
     * Set paytype.
     *
     * @param int $paytype
     *
     * @return Services
     */
    public function setPaytype($paytype)
    {
        $this->paytype = $paytype;

        return $this;
    }

    /**
     * Get paytype.
     *
     * @return int
     */
    public function getPaytype()
    {
        return $this->paytype;
    }

    /**
     * Set bh247cleaningstate.
     *
     * @param string|null $bh247cleaningstate
     *
     * @return Services
     */
    public function setBh247cleaningstate($bh247cleaningstate = null)
    {
        $this->bh247cleaningstate = $bh247cleaningstate;

        return $this;
    }

    /**
     * Get bh247cleaningstate.
     *
     * @return string|null
     */
    public function getBh247cleaningstate()
    {
        return $this->bh247cleaningstate;
    }

    /**
     * Set bh247qastate.
     *
     * @param string|null $bh247qastate
     *
     * @return Services
     */
    public function setBh247qastate($bh247qastate = null)
    {
        $this->bh247qastate = $bh247qastate;

        return $this;
    }

    /**
     * Get bh247qastate.
     *
     * @return string|null
     */
    public function getBh247qastate()
    {
        return $this->bh247qastate;
    }

    /**
     * Set bh247maintenancestate.
     *
     * @param string|null $bh247maintenancestate
     *
     * @return Services
     */
    public function setBh247maintenancestate($bh247maintenancestate = null)
    {
        $this->bh247maintenancestate = $bh247maintenancestate;

        return $this;
    }

    /**
     * Get bh247maintenancestate.
     *
     * @return string|null
     */
    public function getBh247maintenancestate()
    {
        return $this->bh247maintenancestate;
    }

    /**
     * Set bh247custom1state.
     *
     * @param string|null $bh247custom1state
     *
     * @return Services
     */
    public function setBh247custom1state($bh247custom1state = null)
    {
        $this->bh247custom1state = $bh247custom1state;

        return $this;
    }

    /**
     * Get bh247custom1state.
     *
     * @return string|null
     */
    public function getBh247custom1state()
    {
        return $this->bh247custom1state;
    }

    /**
     * Set bh247custom2state.
     *
     * @param string|null $bh247custom2state
     *
     * @return Services
     */
    public function setBh247custom2state($bh247custom2state = null)
    {
        $this->bh247custom2state = $bh247custom2state;

        return $this;
    }

    /**
     * Get bh247custom2state.
     *
     * @return string|null
     */
    public function getBh247custom2state()
    {
        return $this->bh247custom2state;
    }

    /**
     * Set escapiahousekeepingstatus.
     *
     * @param string|null $escapiahousekeepingstatus
     *
     * @return Services
     */
    public function setEscapiahousekeepingstatus($escapiahousekeepingstatus = null)
    {
        $this->escapiahousekeepingstatus = $escapiahousekeepingstatus;

        return $this;
    }

    /**
     * Get escapiahousekeepingstatus.
     *
     * @return string|null
     */
    public function getEscapiahousekeepingstatus()
    {
        return $this->escapiahousekeepingstatus;
    }

    /**
     * Set cloudbedshousekeepingstatus.
     *
     * @param string|null $cloudbedshousekeepingstatus
     *
     * @return Services
     */
    public function setCloudbedshousekeepingstatus($cloudbedshousekeepingstatus = null)
    {
        $this->cloudbedshousekeepingstatus = $cloudbedshousekeepingstatus;

        return $this;
    }

    /**
     * Get cloudbedshousekeepingstatus.
     *
     * @return string|null
     */
    public function getCloudbedshousekeepingstatus()
    {
        return $this->cloudbedshousekeepingstatus;
    }

    /**
     * Set opertostatus.
     *
     * @param string|null $opertostatus
     *
     * @return Services
     */
    public function setOpertostatus($opertostatus = null)
    {
        $this->opertostatus = $opertostatus;

        return $this;
    }

    /**
     * Get opertostatus.
     *
     * @return string|null
     */
    public function getOpertostatus()
    {
        return $this->opertostatus;
    }

    /**
     * Set servicegroupid.
     *
     * @param \AppBundle\Entity\Servicegroups|null $servicegroupid
     *
     * @return Services
     */
    public function setServicegroupid(\AppBundle\Entity\Servicegroups $servicegroupid = null)
    {
        $this->servicegroupid = $servicegroupid;

        return $this;
    }

    /**
     * Get servicegroupid.
     *
     * @return \AppBundle\Entity\Servicegroups|null
     */
    public function getServicegroupid()
    {
        return $this->servicegroupid;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Services
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
     * Set checklistid.
     *
     * @param \AppBundle\Entity\Checklists|null $checklistid
     *
     * @return Services
     */
    public function setChecklistid(\AppBundle\Entity\Checklists $checklistid = null)
    {
        $this->checklistid = $checklistid;

        return $this;
    }

    /**
     * Get checklistid.
     *
     * @return \AppBundle\Entity\Checklists|null
     */
    public function getChecklistid()
    {
        return $this->checklistid;
    }

    /**
     * Set showpmshousekeepingnoteondashboard.
     *
     * @param bool $showpmshousekeepingnoteondashboard
     *
     * @return Services
     */
    public function setShowpmshousekeepingnoteondashboard($showpmshousekeepingnoteondashboard)
    {
        $this->showpmshousekeepingnoteondashboard = $showpmshousekeepingnoteondashboard;

        return $this;
    }

    /**
     * Get showpmshousekeepingnoteondashboard.
     *
     * @return bool
     */
    public function getShowpmshousekeepingnoteondashboard()
    {
        return $this->showpmshousekeepingnoteondashboard;
    }

    /**
     * Set webRezProStatus.
     *
     * @param string|null $webRezProStatus
     *
     * @return Services
     */
    public function setWebRezProStatus($webRezProStatus = null)
    {
        $this->webRezProStatus = $webRezProStatus;

        return $this;
    }

    /**
     * Get webRezProStatus.
     *
     * @return string|null
     */
    public function getWebRezProStatus()
    {
        return $this->webRezProStatus;
    }

    /**
     * Set lmpmStatus.
     *
     * @param string|null $lmpmStatus
     *
     * @return Services
     */
    public function setLmpmStatus($lmpmStatus = null)
    {
        $this->lmpmStatus = $lmpmStatus;

        return $this;
    }

    /**
     * Get lmpmStatus.
     *
     * @return string|null
     */
    public function getLmpmStatus()
    {
        return $this->lmpmStatus;
    }

    /**
     * Set guestyStatus.
     *
     * @param string|null $guestyStatus
     *
     * @return Services
     */
    public function setGuestyStatus($guestyStatus = null)
    {
        $this->guestyStatus = $guestyStatus;

        return $this;
    }

    /**
     * Get guestyStatus.
     *
     * @return string|null
     */
    public function getGuestyStatus()
    {
        return $this->guestyStatus;
    }

    /**
     * Set beds24UnitStatusIndex.
     *
     * @param string|null $beds24UnitStatusIndex
     *
     * @return Services
     */
    public function setBeds24UnitStatusIndex($beds24UnitStatusIndex = null)
    {
        $this->beds24UnitStatusIndex = $beds24UnitStatusIndex;

        return $this;
    }

    /**
     * Get beds24UnitStatusIndex.
     *
     * @return string|null
     */
    public function getBeds24UnitStatusIndex()
    {
        return $this->beds24UnitStatusIndex;
    }

    /**
     * Set beds24UnitStatusText.
     *
     * @param string|null $beds24UnitStatusText
     *
     * @return Services
     */
    public function setBeds24UnitStatusText($beds24UnitStatusText = null)
    {
        $this->beds24UnitStatusText = $beds24UnitStatusText;

        return $this;
    }

    /**
     * Get beds24UnitStatusText.
     *
     * @return string|null
     */
    public function getBeds24UnitStatusText()
    {
        return $this->beds24UnitStatusText;
    }
}
