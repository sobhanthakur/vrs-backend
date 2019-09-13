<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 *
 * @ORM\Table(name="Tasks", indexes={@ORM\Index(name="Active", columns={"Active"}), @ORM\Index(name="backtobakck", columns={"BackToBack"}), @ORM\Index(name="Billable", columns={"Billable"}), @ORM\Index(name="completeconfirmeddate", columns={"CompleteConfirmedDate"}), @ORM\Index(name="CompleteConfirmedDAte-Active", columns={"CompleteConfirmedDate", "Active"}), @ORM\Index(name="CompletedByServicerID", columns={"CompletedByServicerID"}), @ORM\Index(name="IssueID", columns={"IssueID"}), @ORM\Index(name="NextPropertyBOokingID", columns={"NextPropertyBookingID"}), @ORM\Index(name="ParentTaskID", columns={"ParentTaskID"}), @ORM\Index(name="propertyBOokingID", columns={"PropertyBookingID"}), @ORM\Index(name="PropertyID", columns={"PropertyID"}), @ORM\Index(name="PropertyItemID", columns={"PropertyItemID"}), @ORM\Index(name="ServiceID", columns={"ServiceID"}), @ORM\Index(name="ServicerID", columns={"ServicerID"}), @ORM\Index(name="TaskDate", columns={"TaskDate"}), @ORM\Index(name="TaskTime", columns={"TaskTime"}), @ORM\Index(name="TaskTimeMinutes", columns={"TaskTimeMinutes"}), @ORM\Index(name="TaskType", columns={"TaskType"}), @ORM\Index(name="IDX_91994A93245F4372", columns={"CreatedByServicerID"}), @ORM\Index(name="IDX_91994A9314202D84", columns={"DeactivatedByServicerID"}), @ORM\Index(name="IDX_91994A93854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Tasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="TaskID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $taskid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Abbreviation", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $abbreviation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TaskName", type="string", length=200, nullable=true)
     */
    private $taskname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TaskDescription", type="string", length=0, nullable=true)
     */
    private $taskdescription;

    /**
     * @var int
     *
     * @ORM\Column(name="TaskType", type="integer", nullable=false, options={"comment"="0-check out, 1-check in, 2- mid stay, 3- mid vacancy, 4- change over, 5= on a  schedule, 6 - servicer created on complet, 7 - on the fly task"})
     */
    private $tasktype = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="BackToBack", type="boolean", nullable=false)
     */
    private $backtoback = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TaskDate", type="date", nullable=false)
     */
    private $taskdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TaskTime", type="integer", nullable=true)
     */
    private $tasktime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TaskTimeMinutes", type="integer", nullable=true)
     */
    private $tasktimeminutes;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="TaskStartDate", type="date", nullable=true)
     */
    private $taskstartdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TaskStartTime", type="integer", nullable=true)
     */
    private $taskstarttime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="TaskDateTime", type="datetime", nullable=true)
     */
    private $taskdatetime;

    /**
     * @var int
     *
     * @ORM\Column(name="TaskStartTimeMinutes", type="integer", nullable=false)
     */
    private $taskstarttimeminutes = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfServicers", type="integer", nullable=true)
     */
    private $numberofservicers;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="TaskCompleteByDate", type="date", nullable=true)
     */
    private $taskcompletebydate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TaskCompleteByTime", type="integer", nullable=true)
     */
    private $taskcompletebytime;

    /**
     * @var int
     *
     * @ORM\Column(name="TaskCompleteByTimeMinutes", type="integer", nullable=false)
     */
    private $taskcompletebytimeminutes = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ServicerID", type="integer", nullable=true)
     */
    private $servicerid;

    /**
     * @var float|null
     *
     * @ORM\Column(name="MinTimeToComplete", type="float", precision=53, scale=0, nullable=true)
     */
    private $mintimetocomplete;

    /**
     * @var float|null
     *
     * @ORM\Column(name="MaxTimeToComplete", type="float", precision=53, scale=0, nullable=true)
     */
    private $maxtimetocomplete;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="NeedsMaintenance", type="boolean", nullable=true)
     */
    private $needsmaintenance;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HasDamage", type="boolean", nullable=true)
     */
    private $hasdamage = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="HasLostAndFound", type="boolean", nullable=true)
     */
    private $haslostandfound = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SupplyFlag", type="boolean", nullable=true)
     */
    private $supplyflag;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Urgent", type="boolean", nullable=true)
     */
    private $urgent;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CompleteConfirmedDate", type="datetime", nullable=true)
     */
    private $completeconfirmeddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ToOwnerNote", type="string", length=0, nullable=true)
     */
    private $toownernote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ToCustomerNote", type="string", length=0, nullable=true)
     */
    private $tocustomernote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ServicerNotes", type="string", length=0, nullable=true)
     */
    private $servicernotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PropertyManagerNotes", type="string", length=0, nullable=true)
     */
    private $propertymanagernotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="InternalNotes", type="string", length=0, nullable=true)
     */
    private $internalnotes;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Marked", type="boolean", nullable=true)
     */
    private $marked = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Edited", type="boolean", nullable=false)
     */
    private $edited = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="Priority", type="integer", nullable=true)
     */
    private $priority = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ParentTaskID", type="integer", nullable=true)
     */
    private $parenttaskid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ClosedDate", type="datetime", nullable=true)
     */
    private $closeddate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="UpdateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $updatedate = 'getutcdate()';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeletedDate", type="datetime", nullable=true)
     */
    private $deleteddate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ManagerServicerID", type="integer", nullable=true)
     */
    private $managerservicerid;

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
     * @var bool|null
     *
     * @ORM\Column(name="AllowShareImagesWithOwners", type="boolean", nullable=true)
     */
    private $allowshareimageswithowners = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyServicerOnOverdue", type="integer", nullable=true)
     */
    private $notifyserviceronoverdue;

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
     * @ORM\Column(name="NotifyOwnerOnCompletion", type="integer", nullable=true)
     */
    private $notifyowneroncompletion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NotifyServicerOnCheckout", type="integer", nullable=true)
     */
    private $notifyserviceroncheckout = '0';

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
     * @ORM\Column(name="IncludeToOwnerNoteOnOwnerDashboard", type="boolean", nullable=true, options={"default"="1"})
     */
    private $includetoownernoteonownerdashboard = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="OwnerReportNote", type="string", length=0, nullable=true)
     */
    private $ownerreportnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TaskDescriptionImage1", type="string", length=200, nullable=true)
     */
    private $taskdescriptionimage1;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowTaskImage1OnOwnerReport", type="boolean", nullable=true)
     */
    private $showtaskimage1onownerreport = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="TaskDescriptionImage2", type="string", length=200, nullable=true)
     */
    private $taskdescriptionimage2;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowTaskImage2OnOwnerReport", type="boolean", nullable=true)
     */
    private $showtaskimage2onownerreport = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="TaskDescriptionImage3", type="string", length=200, nullable=true)
     */
    private $taskdescriptionimage3;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowTaskImage3OnOwnerReport", type="boolean", nullable=true)
     */
    private $showtaskimage3onownerreport = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ActiveForOwner", type="boolean", nullable=true, options={"default"="1"})
     */
    private $activeforowner = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image1", type="string", length=250, nullable=true)
     */
    private $image1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image2", type="string", length=250, nullable=true)
     */
    private $image2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image3", type="string", length=250, nullable=true)
     */
    private $image3;

    /**
     * @var bool
     *
     * @ORM\Column(name="Image1ShowOwner", type="boolean", nullable=false)
     */
    private $image1showowner = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Image2ShowOwner", type="boolean", nullable=false)
     */
    private $image2showowner = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Image3ShowOwner", type="boolean", nullable=false)
     */
    private $image3showowner = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Billable", type="boolean", nullable=false, options={"default"="1"})
     */
    private $billable = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="ExpenseAmount", type="float", precision=53, scale=0, nullable=false)
     */
    private $expenseamount = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=false)
     */
    private $amount = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="BillingDescription", type="string", length=200, nullable=true)
     */
    private $billingdescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ScheduleChangeDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $schedulechangedate = 'getutcdate()';

    /**
     * @var int
     *
     * @ORM\Column(name="AutosaveCount", type="integer", nullable=false, options={"comment"="used in employee dashboard to ensure ajax submit does not overright due to latency = <<VRAUTOSAVE>>"})
     */
    private $autosavecount = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SendWorkOrder", type="boolean", nullable=true)
     */
    private $sendworkorder;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="WorkOrderSentDate", type="datetime", nullable=true)
     */
    private $workordersentdate = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="WorkOrderIntegrationCompany", type="integer", nullable=true)
     */
    private $workorderintegrationcompany;

    /**
     * @var string|null
     *
     * @ORM\Column(name="WorkOrderID", type="string", length=50, nullable=true)
     */
    private $workorderid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="WorkOrderError", type="text", length=-1, nullable=true)
     */
    private $workordererror;

    /**
     * @var bool
     *
     * @ORM\Column(name="Locked", type="boolean", nullable=false, options={"comment"="If the task is locked, the record can no longer be edited. Locked with QB batches."})
     */
    private $locked = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="LockedDate", type="datetime", nullable=true)
     */
    private $lockeddate;

    /**
     * @var \Issues
     *
     * @ORM\ManyToOne(targetEntity="Issues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IssueID", referencedColumnName="IssueID")
     * })
     */
    private $issueid;

    /**
     * @var \Properties
     *
     * @ORM\ManyToOne(targetEntity="Properties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyID", referencedColumnName="PropertyID")
     * })
     */
    private $propertyid;

    /**
     * @var \Propertybookings
     *
     * @ORM\ManyToOne(targetEntity="Propertybookings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyBookingID", referencedColumnName="PropertyBookingID")
     * })
     */
    private $propertybookingid;

    /**
     * @var \Propertybookings
     *
     * @ORM\ManyToOne(targetEntity="Propertybookings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NextPropertyBookingID", referencedColumnName="PropertyBookingID")
     * })
     */
    private $nextpropertybookingid;

    /**
     * @var \Propertyitems
     *
     * @ORM\ManyToOne(targetEntity="Propertyitems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyItemID", referencedColumnName="PropertyItemID")
     * })
     */
    private $propertyitemid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CompletedByServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $completedbyservicerid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CreatedByServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $createdbyservicerid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="DeactivatedByServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $deactivatedbyservicerid;

    /**
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceID", referencedColumnName="ServiceID")
     * })
     */
    private $serviceid;

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
     * Get taskid.
     *
     * @return int
     */
    public function getTaskid()
    {
        return $this->taskid;
    }

    /**
     * Set abbreviation.
     *
     * @param string|null $abbreviation
     *
     * @return Tasks
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
     * Set taskname.
     *
     * @param string|null $taskname
     *
     * @return Tasks
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
     * Set taskdescription.
     *
     * @param string|null $taskdescription
     *
     * @return Tasks
     */
    public function setTaskdescription($taskdescription = null)
    {
        $this->taskdescription = $taskdescription;

        return $this;
    }

    /**
     * Get taskdescription.
     *
     * @return string|null
     */
    public function getTaskdescription()
    {
        return $this->taskdescription;
    }

    /**
     * Set tasktype.
     *
     * @param int $tasktype
     *
     * @return Tasks
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
     * Set backtoback.
     *
     * @param bool $backtoback
     *
     * @return Tasks
     */
    public function setBacktoback($backtoback)
    {
        $this->backtoback = $backtoback;

        return $this;
    }

    /**
     * Get backtoback.
     *
     * @return bool
     */
    public function getBacktoback()
    {
        return $this->backtoback;
    }

    /**
     * Set taskdate.
     *
     * @param \DateTime $taskdate
     *
     * @return Tasks
     */
    public function setTaskdate($taskdate)
    {
        $this->taskdate = $taskdate;

        return $this;
    }

    /**
     * Get taskdate.
     *
     * @return \DateTime
     */
    public function getTaskdate()
    {
        return $this->taskdate;
    }

    /**
     * Set tasktime.
     *
     * @param int|null $tasktime
     *
     * @return Tasks
     */
    public function setTasktime($tasktime = null)
    {
        $this->tasktime = $tasktime;

        return $this;
    }

    /**
     * Get tasktime.
     *
     * @return int|null
     */
    public function getTasktime()
    {
        return $this->tasktime;
    }

    /**
     * Set tasktimeminutes.
     *
     * @param int|null $tasktimeminutes
     *
     * @return Tasks
     */
    public function setTasktimeminutes($tasktimeminutes = null)
    {
        $this->tasktimeminutes = $tasktimeminutes;

        return $this;
    }

    /**
     * Get tasktimeminutes.
     *
     * @return int|null
     */
    public function getTasktimeminutes()
    {
        return $this->tasktimeminutes;
    }

    /**
     * Set taskstartdate.
     *
     * @param \DateTime|null $taskstartdate
     *
     * @return Tasks
     */
    public function setTaskstartdate($taskstartdate = null)
    {
        $this->taskstartdate = $taskstartdate;

        return $this;
    }

    /**
     * Get taskstartdate.
     *
     * @return \DateTime|null
     */
    public function getTaskstartdate()
    {
        return $this->taskstartdate;
    }

    /**
     * Set taskstarttime.
     *
     * @param int|null $taskstarttime
     *
     * @return Tasks
     */
    public function setTaskstarttime($taskstarttime = null)
    {
        $this->taskstarttime = $taskstarttime;

        return $this;
    }

    /**
     * Get taskstarttime.
     *
     * @return int|null
     */
    public function getTaskstarttime()
    {
        return $this->taskstarttime;
    }

    /**
     * Set taskdatetime.
     *
     * @param \DateTime|null $taskdatetime
     *
     * @return Tasks
     */
    public function setTaskdatetime($taskdatetime = null)
    {
        $this->taskdatetime = $taskdatetime;

        return $this;
    }

    /**
     * Get taskdatetime.
     *
     * @return \DateTime|null
     */
    public function getTaskdatetime()
    {
        return $this->taskdatetime;
    }

    /**
     * Set taskstarttimeminutes.
     *
     * @param int $taskstarttimeminutes
     *
     * @return Tasks
     */
    public function setTaskstarttimeminutes($taskstarttimeminutes)
    {
        $this->taskstarttimeminutes = $taskstarttimeminutes;

        return $this;
    }

    /**
     * Get taskstarttimeminutes.
     *
     * @return int
     */
    public function getTaskstarttimeminutes()
    {
        return $this->taskstarttimeminutes;
    }

    /**
     * Set numberofservicers.
     *
     * @param int|null $numberofservicers
     *
     * @return Tasks
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
     * Set taskcompletebydate.
     *
     * @param \DateTime|null $taskcompletebydate
     *
     * @return Tasks
     */
    public function setTaskcompletebydate($taskcompletebydate = null)
    {
        $this->taskcompletebydate = $taskcompletebydate;

        return $this;
    }

    /**
     * Get taskcompletebydate.
     *
     * @return \DateTime|null
     */
    public function getTaskcompletebydate()
    {
        return $this->taskcompletebydate;
    }

    /**
     * Set taskcompletebytime.
     *
     * @param int|null $taskcompletebytime
     *
     * @return Tasks
     */
    public function setTaskcompletebytime($taskcompletebytime = null)
    {
        $this->taskcompletebytime = $taskcompletebytime;

        return $this;
    }

    /**
     * Get taskcompletebytime.
     *
     * @return int|null
     */
    public function getTaskcompletebytime()
    {
        return $this->taskcompletebytime;
    }

    /**
     * Set taskcompletebytimeminutes.
     *
     * @param int $taskcompletebytimeminutes
     *
     * @return Tasks
     */
    public function setTaskcompletebytimeminutes($taskcompletebytimeminutes)
    {
        $this->taskcompletebytimeminutes = $taskcompletebytimeminutes;

        return $this;
    }

    /**
     * Get taskcompletebytimeminutes.
     *
     * @return int
     */
    public function getTaskcompletebytimeminutes()
    {
        return $this->taskcompletebytimeminutes;
    }

    /**
     * Set servicerid.
     *
     * @param int|null $servicerid
     *
     * @return Tasks
     */
    public function setServicerid($servicerid = null)
    {
        $this->servicerid = $servicerid;

        return $this;
    }

    /**
     * Get servicerid.
     *
     * @return int|null
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }

    /**
     * Set mintimetocomplete.
     *
     * @param float|null $mintimetocomplete
     *
     * @return Tasks
     */
    public function setMintimetocomplete($mintimetocomplete = null)
    {
        $this->mintimetocomplete = $mintimetocomplete;

        return $this;
    }

    /**
     * Get mintimetocomplete.
     *
     * @return float|null
     */
    public function getMintimetocomplete()
    {
        return $this->mintimetocomplete;
    }

    /**
     * Set maxtimetocomplete.
     *
     * @param float|null $maxtimetocomplete
     *
     * @return Tasks
     */
    public function setMaxtimetocomplete($maxtimetocomplete = null)
    {
        $this->maxtimetocomplete = $maxtimetocomplete;

        return $this;
    }

    /**
     * Get maxtimetocomplete.
     *
     * @return float|null
     */
    public function getMaxtimetocomplete()
    {
        return $this->maxtimetocomplete;
    }

    /**
     * Set needsmaintenance.
     *
     * @param bool|null $needsmaintenance
     *
     * @return Tasks
     */
    public function setNeedsmaintenance($needsmaintenance = null)
    {
        $this->needsmaintenance = $needsmaintenance;

        return $this;
    }

    /**
     * Get needsmaintenance.
     *
     * @return bool|null
     */
    public function getNeedsmaintenance()
    {
        return $this->needsmaintenance;
    }

    /**
     * Set hasdamage.
     *
     * @param bool|null $hasdamage
     *
     * @return Tasks
     */
    public function setHasdamage($hasdamage = null)
    {
        $this->hasdamage = $hasdamage;

        return $this;
    }

    /**
     * Get hasdamage.
     *
     * @return bool|null
     */
    public function getHasdamage()
    {
        return $this->hasdamage;
    }

    /**
     * Set haslostandfound.
     *
     * @param bool|null $haslostandfound
     *
     * @return Tasks
     */
    public function setHaslostandfound($haslostandfound = null)
    {
        $this->haslostandfound = $haslostandfound;

        return $this;
    }

    /**
     * Get haslostandfound.
     *
     * @return bool|null
     */
    public function getHaslostandfound()
    {
        return $this->haslostandfound;
    }

    /**
     * Set supplyflag.
     *
     * @param bool|null $supplyflag
     *
     * @return Tasks
     */
    public function setSupplyflag($supplyflag = null)
    {
        $this->supplyflag = $supplyflag;

        return $this;
    }

    /**
     * Get supplyflag.
     *
     * @return bool|null
     */
    public function getSupplyflag()
    {
        return $this->supplyflag;
    }

    /**
     * Set urgent.
     *
     * @param bool|null $urgent
     *
     * @return Tasks
     */
    public function setUrgent($urgent = null)
    {
        $this->urgent = $urgent;

        return $this;
    }

    /**
     * Get urgent.
     *
     * @return bool|null
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Tasks
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
     * Set completeconfirmeddate.
     *
     * @param \DateTime|null $completeconfirmeddate
     *
     * @return Tasks
     */
    public function setCompleteconfirmeddate($completeconfirmeddate = null)
    {
        $this->completeconfirmeddate = $completeconfirmeddate;

        return $this;
    }

    /**
     * Get completeconfirmeddate.
     *
     * @return \DateTime|null
     */
    public function getCompleteconfirmeddate()
    {
        return $this->completeconfirmeddate;
    }

    /**
     * Set toownernote.
     *
     * @param string|null $toownernote
     *
     * @return Tasks
     */
    public function setToownernote($toownernote = null)
    {
        $this->toownernote = $toownernote;

        return $this;
    }

    /**
     * Get toownernote.
     *
     * @return string|null
     */
    public function getToownernote()
    {
        return $this->toownernote;
    }

    /**
     * Set tocustomernote.
     *
     * @param string|null $tocustomernote
     *
     * @return Tasks
     */
    public function setTocustomernote($tocustomernote = null)
    {
        $this->tocustomernote = $tocustomernote;

        return $this;
    }

    /**
     * Get tocustomernote.
     *
     * @return string|null
     */
    public function getTocustomernote()
    {
        return $this->tocustomernote;
    }

    /**
     * Set servicernotes.
     *
     * @param string|null $servicernotes
     *
     * @return Tasks
     */
    public function setServicernotes($servicernotes = null)
    {
        $this->servicernotes = $servicernotes;

        return $this;
    }

    /**
     * Get servicernotes.
     *
     * @return string|null
     */
    public function getServicernotes()
    {
        return $this->servicernotes;
    }

    /**
     * Set propertymanagernotes.
     *
     * @param string|null $propertymanagernotes
     *
     * @return Tasks
     */
    public function setPropertymanagernotes($propertymanagernotes = null)
    {
        $this->propertymanagernotes = $propertymanagernotes;

        return $this;
    }

    /**
     * Get propertymanagernotes.
     *
     * @return string|null
     */
    public function getPropertymanagernotes()
    {
        return $this->propertymanagernotes;
    }

    /**
     * Set internalnotes.
     *
     * @param string|null $internalnotes
     *
     * @return Tasks
     */
    public function setInternalnotes($internalnotes = null)
    {
        $this->internalnotes = $internalnotes;

        return $this;
    }

    /**
     * Get internalnotes.
     *
     * @return string|null
     */
    public function getInternalnotes()
    {
        return $this->internalnotes;
    }

    /**
     * Set marked.
     *
     * @param bool|null $marked
     *
     * @return Tasks
     */
    public function setMarked($marked = null)
    {
        $this->marked = $marked;

        return $this;
    }

    /**
     * Get marked.
     *
     * @return bool|null
     */
    public function getMarked()
    {
        return $this->marked;
    }

    /**
     * Set edited.
     *
     * @param bool $edited
     *
     * @return Tasks
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get edited.
     *
     * @return bool
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Set priority.
     *
     * @param int|null $priority
     *
     * @return Tasks
     */
    public function setPriority($priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return int|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Tasks
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
     * Set parenttaskid.
     *
     * @param int|null $parenttaskid
     *
     * @return Tasks
     */
    public function setParenttaskid($parenttaskid = null)
    {
        $this->parenttaskid = $parenttaskid;

        return $this;
    }

    /**
     * Get parenttaskid.
     *
     * @return int|null
     */
    public function getParenttaskid()
    {
        return $this->parenttaskid;
    }

    /**
     * Set closeddate.
     *
     * @param \DateTime|null $closeddate
     *
     * @return Tasks
     */
    public function setCloseddate($closeddate = null)
    {
        $this->closeddate = $closeddate;

        return $this;
    }

    /**
     * Get closeddate.
     *
     * @return \DateTime|null
     */
    public function getCloseddate()
    {
        return $this->closeddate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Tasks
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
     * Set updatedate.
     *
     * @param \DateTime $updatedate
     *
     * @return Tasks
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
     * Set deleteddate.
     *
     * @param \DateTime|null $deleteddate
     *
     * @return Tasks
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
     * Set managerservicerid.
     *
     * @param int|null $managerservicerid
     *
     * @return Tasks
     */
    public function setManagerservicerid($managerservicerid = null)
    {
        $this->managerservicerid = $managerservicerid;

        return $this;
    }

    /**
     * Get managerservicerid.
     *
     * @return int|null
     */
    public function getManagerservicerid()
    {
        return $this->managerservicerid;
    }

    /**
     * Set includedamage.
     *
     * @param bool|null $includedamage
     *
     * @return Tasks
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
     * @return Tasks
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
     * @return Tasks
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
     * @return Tasks
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
     * @return Tasks
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
     * @return Tasks
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
     * Set allowshareimageswithowners.
     *
     * @param bool|null $allowshareimageswithowners
     *
     * @return Tasks
     */
    public function setAllowshareimageswithowners($allowshareimageswithowners = null)
    {
        $this->allowshareimageswithowners = $allowshareimageswithowners;

        return $this;
    }

    /**
     * Get allowshareimageswithowners.
     *
     * @return bool|null
     */
    public function getAllowshareimageswithowners()
    {
        return $this->allowshareimageswithowners;
    }

    /**
     * Set notifyserviceronoverdue.
     *
     * @param int|null $notifyserviceronoverdue
     *
     * @return Tasks
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
     * Set notifyserviceronnotyetdone.
     *
     * @param int|null $notifyserviceronnotyetdone
     *
     * @return Tasks
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
     * @return Tasks
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
     * Set notifyowneroncompletion.
     *
     * @param int|null $notifyowneroncompletion
     *
     * @return Tasks
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
     * Set notifyserviceroncheckout.
     *
     * @param int|null $notifyserviceroncheckout
     *
     * @return Tasks
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
     * Set includetoownernote.
     *
     * @param bool|null $includetoownernote
     *
     * @return Tasks
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
     * @return Tasks
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
     * Set includetoownernoteonownerdashboard.
     *
     * @param bool|null $includetoownernoteonownerdashboard
     *
     * @return Tasks
     */
    public function setIncludetoownernoteonownerdashboard($includetoownernoteonownerdashboard = null)
    {
        $this->includetoownernoteonownerdashboard = $includetoownernoteonownerdashboard;

        return $this;
    }

    /**
     * Get includetoownernoteonownerdashboard.
     *
     * @return bool|null
     */
    public function getIncludetoownernoteonownerdashboard()
    {
        return $this->includetoownernoteonownerdashboard;
    }

    /**
     * Set ownerreportnote.
     *
     * @param string|null $ownerreportnote
     *
     * @return Tasks
     */
    public function setOwnerreportnote($ownerreportnote = null)
    {
        $this->ownerreportnote = $ownerreportnote;

        return $this;
    }

    /**
     * Get ownerreportnote.
     *
     * @return string|null
     */
    public function getOwnerreportnote()
    {
        return $this->ownerreportnote;
    }

    /**
     * Set taskdescriptionimage1.
     *
     * @param string|null $taskdescriptionimage1
     *
     * @return Tasks
     */
    public function setTaskdescriptionimage1($taskdescriptionimage1 = null)
    {
        $this->taskdescriptionimage1 = $taskdescriptionimage1;

        return $this;
    }

    /**
     * Get taskdescriptionimage1.
     *
     * @return string|null
     */
    public function getTaskdescriptionimage1()
    {
        return $this->taskdescriptionimage1;
    }

    /**
     * Set showtaskimage1onownerreport.
     *
     * @param bool|null $showtaskimage1onownerreport
     *
     * @return Tasks
     */
    public function setShowtaskimage1onownerreport($showtaskimage1onownerreport = null)
    {
        $this->showtaskimage1onownerreport = $showtaskimage1onownerreport;

        return $this;
    }

    /**
     * Get showtaskimage1onownerreport.
     *
     * @return bool|null
     */
    public function getShowtaskimage1onownerreport()
    {
        return $this->showtaskimage1onownerreport;
    }

    /**
     * Set taskdescriptionimage2.
     *
     * @param string|null $taskdescriptionimage2
     *
     * @return Tasks
     */
    public function setTaskdescriptionimage2($taskdescriptionimage2 = null)
    {
        $this->taskdescriptionimage2 = $taskdescriptionimage2;

        return $this;
    }

    /**
     * Get taskdescriptionimage2.
     *
     * @return string|null
     */
    public function getTaskdescriptionimage2()
    {
        return $this->taskdescriptionimage2;
    }

    /**
     * Set showtaskimage2onownerreport.
     *
     * @param bool|null $showtaskimage2onownerreport
     *
     * @return Tasks
     */
    public function setShowtaskimage2onownerreport($showtaskimage2onownerreport = null)
    {
        $this->showtaskimage2onownerreport = $showtaskimage2onownerreport;

        return $this;
    }

    /**
     * Get showtaskimage2onownerreport.
     *
     * @return bool|null
     */
    public function getShowtaskimage2onownerreport()
    {
        return $this->showtaskimage2onownerreport;
    }

    /**
     * Set taskdescriptionimage3.
     *
     * @param string|null $taskdescriptionimage3
     *
     * @return Tasks
     */
    public function setTaskdescriptionimage3($taskdescriptionimage3 = null)
    {
        $this->taskdescriptionimage3 = $taskdescriptionimage3;

        return $this;
    }

    /**
     * Get taskdescriptionimage3.
     *
     * @return string|null
     */
    public function getTaskdescriptionimage3()
    {
        return $this->taskdescriptionimage3;
    }

    /**
     * Set showtaskimage3onownerreport.
     *
     * @param bool|null $showtaskimage3onownerreport
     *
     * @return Tasks
     */
    public function setShowtaskimage3onownerreport($showtaskimage3onownerreport = null)
    {
        $this->showtaskimage3onownerreport = $showtaskimage3onownerreport;

        return $this;
    }

    /**
     * Get showtaskimage3onownerreport.
     *
     * @return bool|null
     */
    public function getShowtaskimage3onownerreport()
    {
        return $this->showtaskimage3onownerreport;
    }

    /**
     * Set activeforowner.
     *
     * @param bool|null $activeforowner
     *
     * @return Tasks
     */
    public function setActiveforowner($activeforowner = null)
    {
        $this->activeforowner = $activeforowner;

        return $this;
    }

    /**
     * Get activeforowner.
     *
     * @return bool|null
     */
    public function getActiveforowner()
    {
        return $this->activeforowner;
    }

    /**
     * Set image1.
     *
     * @param string|null $image1
     *
     * @return Tasks
     */
    public function setImage1($image1 = null)
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * Get image1.
     *
     * @return string|null
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * Set image2.
     *
     * @param string|null $image2
     *
     * @return Tasks
     */
    public function setImage2($image2 = null)
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Get image2.
     *
     * @return string|null
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * Set image3.
     *
     * @param string|null $image3
     *
     * @return Tasks
     */
    public function setImage3($image3 = null)
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Get image3.
     *
     * @return string|null
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Set image1showowner.
     *
     * @param bool $image1showowner
     *
     * @return Tasks
     */
    public function setImage1showowner($image1showowner)
    {
        $this->image1showowner = $image1showowner;

        return $this;
    }

    /**
     * Get image1showowner.
     *
     * @return bool
     */
    public function getImage1showowner()
    {
        return $this->image1showowner;
    }

    /**
     * Set image2showowner.
     *
     * @param bool $image2showowner
     *
     * @return Tasks
     */
    public function setImage2showowner($image2showowner)
    {
        $this->image2showowner = $image2showowner;

        return $this;
    }

    /**
     * Get image2showowner.
     *
     * @return bool
     */
    public function getImage2showowner()
    {
        return $this->image2showowner;
    }

    /**
     * Set image3showowner.
     *
     * @param bool $image3showowner
     *
     * @return Tasks
     */
    public function setImage3showowner($image3showowner)
    {
        $this->image3showowner = $image3showowner;

        return $this;
    }

    /**
     * Get image3showowner.
     *
     * @return bool
     */
    public function getImage3showowner()
    {
        return $this->image3showowner;
    }

    /**
     * Set billable.
     *
     * @param bool $billable
     *
     * @return Tasks
     */
    public function setBillable($billable)
    {
        $this->billable = $billable;

        return $this;
    }

    /**
     * Get billable.
     *
     * @return bool
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * Set expenseamount.
     *
     * @param float $expenseamount
     *
     * @return Tasks
     */
    public function setExpenseamount($expenseamount)
    {
        $this->expenseamount = $expenseamount;

        return $this;
    }

    /**
     * Get expenseamount.
     *
     * @return float
     */
    public function getExpenseamount()
    {
        return $this->expenseamount;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return Tasks
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
     * Set billingdescription.
     *
     * @param string|null $billingdescription
     *
     * @return Tasks
     */
    public function setBillingdescription($billingdescription = null)
    {
        $this->billingdescription = $billingdescription;

        return $this;
    }

    /**
     * Get billingdescription.
     *
     * @return string|null
     */
    public function getBillingdescription()
    {
        return $this->billingdescription;
    }

    /**
     * Set schedulechangedate.
     *
     * @param \DateTime $schedulechangedate
     *
     * @return Tasks
     */
    public function setSchedulechangedate($schedulechangedate)
    {
        $this->schedulechangedate = $schedulechangedate;

        return $this;
    }

    /**
     * Get schedulechangedate.
     *
     * @return \DateTime
     */
    public function getSchedulechangedate()
    {
        return $this->schedulechangedate;
    }

    /**
     * Set autosavecount.
     *
     * @param int $autosavecount
     *
     * @return Tasks
     */
    public function setAutosavecount($autosavecount)
    {
        $this->autosavecount = $autosavecount;

        return $this;
    }

    /**
     * Get autosavecount.
     *
     * @return int
     */
    public function getAutosavecount()
    {
        return $this->autosavecount;
    }

    /**
     * Set sendworkorder.
     *
     * @param bool|null $sendworkorder
     *
     * @return Tasks
     */
    public function setSendworkorder($sendworkorder = null)
    {
        $this->sendworkorder = $sendworkorder;

        return $this;
    }

    /**
     * Get sendworkorder.
     *
     * @return bool|null
     */
    public function getSendworkorder()
    {
        return $this->sendworkorder;
    }

    /**
     * Set workordersentdate.
     *
     * @param \DateTime|null $workordersentdate
     *
     * @return Tasks
     */
    public function setWorkordersentdate($workordersentdate = null)
    {
        $this->workordersentdate = $workordersentdate;

        return $this;
    }

    /**
     * Get workordersentdate.
     *
     * @return \DateTime|null
     */
    public function getWorkordersentdate()
    {
        return $this->workordersentdate;
    }

    /**
     * Set workorderintegrationcompany.
     *
     * @param int|null $workorderintegrationcompany
     *
     * @return Tasks
     */
    public function setWorkorderintegrationcompany($workorderintegrationcompany = null)
    {
        $this->workorderintegrationcompany = $workorderintegrationcompany;

        return $this;
    }

    /**
     * Get workorderintegrationcompany.
     *
     * @return int|null
     */
    public function getWorkorderintegrationcompany()
    {
        return $this->workorderintegrationcompany;
    }

    /**
     * Set workorderid.
     *
     * @param string|null $workorderid
     *
     * @return Tasks
     */
    public function setWorkorderid($workorderid = null)
    {
        $this->workorderid = $workorderid;

        return $this;
    }

    /**
     * Get workorderid.
     *
     * @return string|null
     */
    public function getWorkorderid()
    {
        return $this->workorderid;
    }

    /**
     * Set workordererror.
     *
     * @param string|null $workordererror
     *
     * @return Tasks
     */
    public function setWorkordererror($workordererror = null)
    {
        $this->workordererror = $workordererror;

        return $this;
    }

    /**
     * Get workordererror.
     *
     * @return string|null
     */
    public function getWorkordererror()
    {
        return $this->workordererror;
    }

    /**
     * Set locked.
     *
     * @param bool $locked
     *
     * @return Tasks
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked.
     *
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set lockeddate.
     *
     * @param \DateTime|null $lockeddate
     *
     * @return Tasks
     */
    public function setLockeddate($lockeddate = null)
    {
        $this->lockeddate = $lockeddate;

        return $this;
    }

    /**
     * Get lockeddate.
     *
     * @return \DateTime|null
     */
    public function getLockeddate()
    {
        return $this->lockeddate;
    }

    /**
     * Set issueid.
     *
     * @param \AppBundle\Entity\Issues|null $issueid
     *
     * @return Tasks
     */
    public function setIssueid(\AppBundle\Entity\Issues $issueid = null)
    {
        $this->issueid = $issueid;

        return $this;
    }

    /**
     * Get issueid.
     *
     * @return \AppBundle\Entity\Issues|null
     */
    public function getIssueid()
    {
        return $this->issueid;
    }

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Tasks
     */
    public function setPropertyid(\AppBundle\Entity\Properties $propertyid = null)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid.
     *
     * @return \AppBundle\Entity\Properties|null
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set propertybookingid.
     *
     * @param \AppBundle\Entity\Propertybookings|null $propertybookingid
     *
     * @return Tasks
     */
    public function setPropertybookingid(\AppBundle\Entity\Propertybookings $propertybookingid = null)
    {
        $this->propertybookingid = $propertybookingid;

        return $this;
    }

    /**
     * Get propertybookingid.
     *
     * @return \AppBundle\Entity\Propertybookings|null
     */
    public function getPropertybookingid()
    {
        return $this->propertybookingid;
    }

    /**
     * Set nextpropertybookingid.
     *
     * @param \AppBundle\Entity\Propertybookings|null $nextpropertybookingid
     *
     * @return Tasks
     */
    public function setNextpropertybookingid(\AppBundle\Entity\Propertybookings $nextpropertybookingid = null)
    {
        $this->nextpropertybookingid = $nextpropertybookingid;

        return $this;
    }

    /**
     * Get nextpropertybookingid.
     *
     * @return \AppBundle\Entity\Propertybookings|null
     */
    public function getNextpropertybookingid()
    {
        return $this->nextpropertybookingid;
    }

    /**
     * Set propertyitemid.
     *
     * @param \AppBundle\Entity\Propertyitems|null $propertyitemid
     *
     * @return Tasks
     */
    public function setPropertyitemid(\AppBundle\Entity\Propertyitems $propertyitemid = null)
    {
        $this->propertyitemid = $propertyitemid;

        return $this;
    }

    /**
     * Get propertyitemid.
     *
     * @return \AppBundle\Entity\Propertyitems|null
     */
    public function getPropertyitemid()
    {
        return $this->propertyitemid;
    }

    /**
     * Set completedbyservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $completedbyservicerid
     *
     * @return Tasks
     */
    public function setCompletedbyservicerid(\AppBundle\Entity\Servicers $completedbyservicerid = null)
    {
        $this->completedbyservicerid = $completedbyservicerid;

        return $this;
    }

    /**
     * Get completedbyservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getCompletedbyservicerid()
    {
        return $this->completedbyservicerid;
    }

    /**
     * Set createdbyservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $createdbyservicerid
     *
     * @return Tasks
     */
    public function setCreatedbyservicerid(\AppBundle\Entity\Servicers $createdbyservicerid = null)
    {
        $this->createdbyservicerid = $createdbyservicerid;

        return $this;
    }

    /**
     * Get createdbyservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getCreatedbyservicerid()
    {
        return $this->createdbyservicerid;
    }

    /**
     * Set deactivatedbyservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $deactivatedbyservicerid
     *
     * @return Tasks
     */
    public function setDeactivatedbyservicerid(\AppBundle\Entity\Servicers $deactivatedbyservicerid = null)
    {
        $this->deactivatedbyservicerid = $deactivatedbyservicerid;

        return $this;
    }

    /**
     * Get deactivatedbyservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getDeactivatedbyservicerid()
    {
        return $this->deactivatedbyservicerid;
    }

    /**
     * Set serviceid.
     *
     * @param \AppBundle\Entity\Services|null $serviceid
     *
     * @return Tasks
     */
    public function setServiceid(\AppBundle\Entity\Services $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid.
     *
     * @return \AppBundle\Entity\Services|null
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Tasks
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
