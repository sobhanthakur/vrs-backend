<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issues
 *
 * @ORM\Table(name="Issues", indexes={@ORM\Index(name="Billable", columns={"Billable"}), @ORM\Index(name="ClosedDate", columns={"CreateDate"}), @ORM\Index(name="CreateDAte", columns={"CreateDate"}), @ORM\Index(name="fromtaskID", columns={"FromTaskID"}), @ORM\Index(name="issuetype", columns={"IssueType"}), @ORM\Index(name="propertyid", columns={"PropertyID"}), @ORM\Index(name="PropertyITemID", columns={"PropertyItemID"}), @ORM\Index(name="ServicerID", columns={"ServicerID"}), @ORM\Index(name="SubmittedByServicerID", columns={"SubmittedByServicerID"}), @ORM\Index(name="taskid", columns={"TaskID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IssueRepository")
 */
class Issues
{
    /**
     * @var int
     *
     * @ORM\Column(name="IssueID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $issueid;

    /**
     * @var int
     *
     * @ORM\Column(name="StatusID", type="integer", nullable=false)
     */
    private $statusid = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="IssueType", type="integer", nullable=false, options={"comment"="0=Damage,1=Maintenance,2=Lost and Found"})
     */
    private $issuetype;

    /**
     * @var bool
     *
     * @ORM\Column(name="Urgent", type="boolean", nullable=false)
     */
    private $urgent = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Issue", type="string", length=200, nullable=true)
     */
    private $issue;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PropertyItemID", type="integer", nullable=true)
     */
    private $propertyitemid;

    /**
     * @var string
     *
     * @ORM\Column(name="Notes", type="string", length=0, nullable=false)
     */
    private $notes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ServicerNotes", type="string", length=0, nullable=true)
     */
    private $servicernotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="InternalNotes", type="string", length=0, nullable=true)
     */
    private $internalnotes;

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
     * @ORM\Column(name="Billable", type="boolean", nullable=false)
     */
    private $billable = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=true)
     */
    private $amount = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOnOwnerDashboard", type="boolean", nullable=false)
     */
    private $showonownerdashboard = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowOwnerImage1", type="boolean", nullable=true)
     */
    private $showownerimage1 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowOwnerImage2", type="boolean", nullable=true)
     */
    private $showownerimage2 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowOwnerImage3", type="boolean", nullable=true)
     */
    private $showownerimage3 = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOnVendorDashboard", type="boolean", nullable=false)
     */
    private $showonvendordashboard = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowVendorImage1", type="boolean", nullable=true)
     */
    private $showvendorimage1 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowVendorImage2", type="boolean", nullable=true)
     */
    private $showvendorimage2 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowVendorImage3", type="boolean", nullable=true)
     */
    private $showvendorimage3 = '0';

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
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

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
    private $workordersentdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="WorkOrderIntegrationCompanyID", type="integer", nullable=true)
     */
    private $workorderintegrationcompanyid;

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
     * @var \Tasks
     *
     * @ORM\ManyToOne(targetEntity="Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FromTaskID", referencedColumnName="TaskID")
     * })
     */
    private $fromtaskid;

    /**
     * @var \Tasks
     *
     * @ORM\ManyToOne(targetEntity="Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TaskID", referencedColumnName="TaskID")
     * })
     */
    private $taskid;

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
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SubmittedByServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $submittedbyservicerid;



    /**
     * Get issueid.
     *
     * @return int
     */
    public function getIssueid()
    {
        return $this->issueid;
    }

    /**
     * Set statusid.
     *
     * @param int $statusid
     *
     * @return Issues
     */
    public function setStatusid($statusid)
    {
        $this->statusid = $statusid;

        return $this;
    }

    /**
     * Get statusid.
     *
     * @return int
     */
    public function getStatusid()
    {
        return $this->statusid;
    }

    /**
     * Set issuetype.
     *
     * @param int $issuetype
     *
     * @return Issues
     */
    public function setIssuetype($issuetype)
    {
        $this->issuetype = $issuetype;

        return $this;
    }

    /**
     * Get issuetype.
     *
     * @return int
     */
    public function getIssuetype()
    {
        return $this->issuetype;
    }

    /**
     * Set urgent.
     *
     * @param bool $urgent
     *
     * @return Issues
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;

        return $this;
    }

    /**
     * Get urgent.
     *
     * @return bool
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Set issue.
     *
     * @param string|null $issue
     *
     * @return Issues
     */
    public function setIssue($issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue.
     *
     * @return string|null
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set propertyitemid.
     *
     * @param int|null $propertyitemid
     *
     * @return Issues
     */
    public function setPropertyitemid($propertyitemid = null)
    {
        $this->propertyitemid = $propertyitemid;

        return $this;
    }

    /**
     * Get propertyitemid.
     *
     * @return int|null
     */
    public function getPropertyitemid()
    {
        return $this->propertyitemid;
    }

    /**
     * Set notes.
     *
     * @param string $notes
     *
     * @return Issues
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set servicernotes.
     *
     * @param string|null $servicernotes
     *
     * @return Issues
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
     * Set internalnotes.
     *
     * @param string|null $internalnotes
     *
     * @return Issues
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
     * Set image1.
     *
     * @param string|null $image1
     *
     * @return Issues
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
     * @return Issues
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
     * @return Issues
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
     * Set billable.
     *
     * @param bool $billable
     *
     * @return Issues
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
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return Issues
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
     * Set showonownerdashboard.
     *
     * @param bool $showonownerdashboard
     *
     * @return Issues
     */
    public function setShowonownerdashboard($showonownerdashboard)
    {
        $this->showonownerdashboard = $showonownerdashboard;

        return $this;
    }

    /**
     * Get showonownerdashboard.
     *
     * @return bool
     */
    public function getShowonownerdashboard()
    {
        return $this->showonownerdashboard;
    }

    /**
     * Set showownerimage1.
     *
     * @param bool|null $showownerimage1
     *
     * @return Issues
     */
    public function setShowownerimage1($showownerimage1 = null)
    {
        $this->showownerimage1 = $showownerimage1;

        return $this;
    }

    /**
     * Get showownerimage1.
     *
     * @return bool|null
     */
    public function getShowownerimage1()
    {
        return $this->showownerimage1;
    }

    /**
     * Set showownerimage2.
     *
     * @param bool|null $showownerimage2
     *
     * @return Issues
     */
    public function setShowownerimage2($showownerimage2 = null)
    {
        $this->showownerimage2 = $showownerimage2;

        return $this;
    }

    /**
     * Get showownerimage2.
     *
     * @return bool|null
     */
    public function getShowownerimage2()
    {
        return $this->showownerimage2;
    }

    /**
     * Set showownerimage3.
     *
     * @param bool|null $showownerimage3
     *
     * @return Issues
     */
    public function setShowownerimage3($showownerimage3 = null)
    {
        $this->showownerimage3 = $showownerimage3;

        return $this;
    }

    /**
     * Get showownerimage3.
     *
     * @return bool|null
     */
    public function getShowownerimage3()
    {
        return $this->showownerimage3;
    }

    /**
     * Set showonvendordashboard.
     *
     * @param bool $showonvendordashboard
     *
     * @return Issues
     */
    public function setShowonvendordashboard($showonvendordashboard)
    {
        $this->showonvendordashboard = $showonvendordashboard;

        return $this;
    }

    /**
     * Get showonvendordashboard.
     *
     * @return bool
     */
    public function getShowonvendordashboard()
    {
        return $this->showonvendordashboard;
    }

    /**
     * Set showvendorimage1.
     *
     * @param bool|null $showvendorimage1
     *
     * @return Issues
     */
    public function setShowvendorimage1($showvendorimage1 = null)
    {
        $this->showvendorimage1 = $showvendorimage1;

        return $this;
    }

    /**
     * Get showvendorimage1.
     *
     * @return bool|null
     */
    public function getShowvendorimage1()
    {
        return $this->showvendorimage1;
    }

    /**
     * Set showvendorimage2.
     *
     * @param bool|null $showvendorimage2
     *
     * @return Issues
     */
    public function setShowvendorimage2($showvendorimage2 = null)
    {
        $this->showvendorimage2 = $showvendorimage2;

        return $this;
    }

    /**
     * Get showvendorimage2.
     *
     * @return bool|null
     */
    public function getShowvendorimage2()
    {
        return $this->showvendorimage2;
    }

    /**
     * Set showvendorimage3.
     *
     * @param bool|null $showvendorimage3
     *
     * @return Issues
     */
    public function setShowvendorimage3($showvendorimage3 = null)
    {
        $this->showvendorimage3 = $showvendorimage3;

        return $this;
    }

    /**
     * Get showvendorimage3.
     *
     * @return bool|null
     */
    public function getShowvendorimage3()
    {
        return $this->showvendorimage3;
    }

    /**
     * Set closeddate.
     *
     * @param \DateTime|null $closeddate
     *
     * @return Issues
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
     * @return Issues
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
     * @return Issues
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
     * Set active.
     *
     * @param bool $active
     *
     * @return Issues
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
     * Set sendworkorder.
     *
     * @param bool|null $sendworkorder
     *
     * @return Issues
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
     * @return Issues
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
     * Set workorderintegrationcompanyid.
     *
     * @param int|null $workorderintegrationcompanyid
     *
     * @return Issues
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
     * Set workorderid.
     *
     * @param string|null $workorderid
     *
     * @return Issues
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
     * @return Issues
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
     * Set fromtaskid.
     *
     * @param \AppBundle\Entity\Tasks|null $fromtaskid
     *
     * @return Issues
     */
    public function setFromtaskid(\AppBundle\Entity\Tasks $fromtaskid = null)
    {
        $this->fromtaskid = $fromtaskid;

        return $this;
    }

    /**
     * Get fromtaskid.
     *
     * @return \AppBundle\Entity\Tasks|null
     */
    public function getFromtaskid()
    {
        return $this->fromtaskid;
    }

    /**
     * Set taskid.
     *
     * @param \AppBundle\Entity\Tasks|null $taskid
     *
     * @return Issues
     */
    public function setTaskid(\AppBundle\Entity\Tasks $taskid = null)
    {
        $this->taskid = $taskid;

        return $this;
    }

    /**
     * Get taskid.
     *
     * @return \AppBundle\Entity\Tasks|null
     */
    public function getTaskid()
    {
        return $this->taskid;
    }

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Issues
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Issues
     */
    public function setServicerid(\AppBundle\Entity\Servicers $servicerid = null)
    {
        $this->servicerid = $servicerid;

        return $this;
    }

    /**
     * Get servicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }

    /**
     * Set submittedbyservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $submittedbyservicerid
     *
     * @return Issues
     */
    public function setSubmittedbyservicerid(\AppBundle\Entity\Servicers $submittedbyservicerid = null)
    {
        $this->submittedbyservicerid = $submittedbyservicerid;

        return $this;
    }

    /**
     * Get submittedbyservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getSubmittedbyservicerid()
    {
        return $this->submittedbyservicerid;
    }
}
