<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taskstoservicers
 *
 * @ORM\Table(name="TasksToServicers", indexes={@ORM\Index(name="IsLead", columns={"IsLead"}), @ORM\Index(name="ServicerID", columns={"ServicerID"}), @ORM\Index(name="ServicerIDwithTaskID", columns={"TaskID", "ServicerID"}), @ORM\Index(name="TaskID", columns={"TaskID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StaffTasksRepository")
 */
class Taskstoservicers
{
    /**
     * @var int
     *
     * @ORM\Column(name="TaskToServicerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tasktoservicerid;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsLead", type="boolean", nullable=false)
     */
    private $islead = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="PayType", type="integer", nullable=false)
     */
    private $paytype = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="PayRate", type="float", precision=53, scale=0, nullable=false)
     */
    private $payrate = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="PiecePay", type="float", precision=53, scale=0, nullable=true)
     */
    private $piecepay;

    /**
     * @var int
     *
     * @ORM\Column(name="PiecePayStatus", type="integer", nullable=false)
     */
    private $piecepaystatus = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ApprovedServicerID", type="integer", nullable=true)
     */
    private $approvedservicerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PaidServicerID", type="integer", nullable=true)
     */
    private $paidservicerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="FlaggedServicerID", type="integer", nullable=true)
     */
    private $flaggedservicerid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Instructions", type="text", length=-1, nullable=true)
     */
    private $instructions;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="AcceptedDate", type="datetime", nullable=true)
     */
    private $accepteddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeclinedDate", type="datetime", nullable=true)
     */
    private $declineddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="SentInTaskListDate", type="datetime", nullable=true)
     */
    private $sentintasklistdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingNotifiedDate", type="datetime", nullable=true)
     */
    private $bookingnotifieddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingConfirmedDate", type="datetime", nullable=true)
     */
    private $bookingconfirmeddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingDeclinedDate", type="datetime", nullable=true)
     */
    private $bookingdeclineddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ReminderNotifiedDate", type="datetime", nullable=true)
     */
    private $remindernotifieddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ReminderConfirmedDate", type="datetime", nullable=true)
     */
    private $reminderconfirmeddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CompleteConfirmationRequestedDate", type="datetime", nullable=true)
     */
    private $completeconfirmationrequesteddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CompleteConfirmedDate", type="datetime", nullable=true)
     */
    private $completeconfirmeddate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var string|null
     *
     * @ORM\Column(name="TroubleshootingNote", type="text", length=-1, nullable=true)
     */
    private $troubleshootingnote;

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
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;



    /**
     * Get tasktoservicerid.
     *
     * @return int
     */
    public function getTasktoservicerid()
    {
        return $this->tasktoservicerid;
    }

    /**
     * Set islead.
     *
     * @param bool $islead
     *
     * @return Taskstoservicers
     */
    public function setIslead($islead)
    {
        $this->islead = $islead;

        return $this;
    }

    /**
     * Get islead.
     *
     * @return bool
     */
    public function getIslead()
    {
        return $this->islead;
    }

    /**
     * Set paytype.
     *
     * @param int $paytype
     *
     * @return Taskstoservicers
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
     * Set payrate.
     *
     * @param float $payrate
     *
     * @return Taskstoservicers
     */
    public function setPayrate($payrate)
    {
        $this->payrate = $payrate;

        return $this;
    }

    /**
     * Get payrate.
     *
     * @return float
     */
    public function getPayrate()
    {
        return $this->payrate;
    }

    /**
     * Set piecepay.
     *
     * @param float|null $piecepay
     *
     * @return Taskstoservicers
     */
    public function setPiecepay($piecepay = null)
    {
        $this->piecepay = $piecepay;

        return $this;
    }

    /**
     * Get piecepay.
     *
     * @return float|null
     */
    public function getPiecepay()
    {
        return $this->piecepay;
    }

    /**
     * Set piecepaystatus.
     *
     * @param int $piecepaystatus
     *
     * @return Taskstoservicers
     */
    public function setPiecepaystatus($piecepaystatus)
    {
        $this->piecepaystatus = $piecepaystatus;

        return $this;
    }

    /**
     * Get piecepaystatus.
     *
     * @return int
     */
    public function getPiecepaystatus()
    {
        return $this->piecepaystatus;
    }

    /**
     * Set approvedservicerid.
     *
     * @param int|null $approvedservicerid
     *
     * @return Taskstoservicers
     */
    public function setApprovedservicerid($approvedservicerid = null)
    {
        $this->approvedservicerid = $approvedservicerid;

        return $this;
    }

    /**
     * Get approvedservicerid.
     *
     * @return int|null
     */
    public function getApprovedservicerid()
    {
        return $this->approvedservicerid;
    }

    /**
     * Set paidservicerid.
     *
     * @param int|null $paidservicerid
     *
     * @return Taskstoservicers
     */
    public function setPaidservicerid($paidservicerid = null)
    {
        $this->paidservicerid = $paidservicerid;

        return $this;
    }

    /**
     * Get paidservicerid.
     *
     * @return int|null
     */
    public function getPaidservicerid()
    {
        return $this->paidservicerid;
    }

    /**
     * Set flaggedservicerid.
     *
     * @param int|null $flaggedservicerid
     *
     * @return Taskstoservicers
     */
    public function setFlaggedservicerid($flaggedservicerid = null)
    {
        $this->flaggedservicerid = $flaggedservicerid;

        return $this;
    }

    /**
     * Get flaggedservicerid.
     *
     * @return int|null
     */
    public function getFlaggedservicerid()
    {
        return $this->flaggedservicerid;
    }

    /**
     * Set instructions.
     *
     * @param string|null $instructions
     *
     * @return Taskstoservicers
     */
    public function setInstructions($instructions = null)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions.
     *
     * @return string|null
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set accepteddate.
     *
     * @param \DateTime|null $accepteddate
     *
     * @return Taskstoservicers
     */
    public function setAccepteddate($accepteddate = null)
    {
        $this->accepteddate = $accepteddate;

        return $this;
    }

    /**
     * Get accepteddate.
     *
     * @return \DateTime|null
     */
    public function getAccepteddate()
    {
        return $this->accepteddate;
    }

    /**
     * Set declineddate.
     *
     * @param \DateTime|null $declineddate
     *
     * @return Taskstoservicers
     */
    public function setDeclineddate($declineddate = null)
    {
        $this->declineddate = $declineddate;

        return $this;
    }

    /**
     * Get declineddate.
     *
     * @return \DateTime|null
     */
    public function getDeclineddate()
    {
        return $this->declineddate;
    }

    /**
     * Set sentintasklistdate.
     *
     * @param \DateTime|null $sentintasklistdate
     *
     * @return Taskstoservicers
     */
    public function setSentintasklistdate($sentintasklistdate = null)
    {
        $this->sentintasklistdate = $sentintasklistdate;

        return $this;
    }

    /**
     * Get sentintasklistdate.
     *
     * @return \DateTime|null
     */
    public function getSentintasklistdate()
    {
        return $this->sentintasklistdate;
    }

    /**
     * Set bookingnotifieddate.
     *
     * @param \DateTime|null $bookingnotifieddate
     *
     * @return Taskstoservicers
     */
    public function setBookingnotifieddate($bookingnotifieddate = null)
    {
        $this->bookingnotifieddate = $bookingnotifieddate;

        return $this;
    }

    /**
     * Get bookingnotifieddate.
     *
     * @return \DateTime|null
     */
    public function getBookingnotifieddate()
    {
        return $this->bookingnotifieddate;
    }

    /**
     * Set bookingconfirmeddate.
     *
     * @param \DateTime|null $bookingconfirmeddate
     *
     * @return Taskstoservicers
     */
    public function setBookingconfirmeddate($bookingconfirmeddate = null)
    {
        $this->bookingconfirmeddate = $bookingconfirmeddate;

        return $this;
    }

    /**
     * Get bookingconfirmeddate.
     *
     * @return \DateTime|null
     */
    public function getBookingconfirmeddate()
    {
        return $this->bookingconfirmeddate;
    }

    /**
     * Set bookingdeclineddate.
     *
     * @param \DateTime|null $bookingdeclineddate
     *
     * @return Taskstoservicers
     */
    public function setBookingdeclineddate($bookingdeclineddate = null)
    {
        $this->bookingdeclineddate = $bookingdeclineddate;

        return $this;
    }

    /**
     * Get bookingdeclineddate.
     *
     * @return \DateTime|null
     */
    public function getBookingdeclineddate()
    {
        return $this->bookingdeclineddate;
    }

    /**
     * Set remindernotifieddate.
     *
     * @param \DateTime|null $remindernotifieddate
     *
     * @return Taskstoservicers
     */
    public function setRemindernotifieddate($remindernotifieddate = null)
    {
        $this->remindernotifieddate = $remindernotifieddate;

        return $this;
    }

    /**
     * Get remindernotifieddate.
     *
     * @return \DateTime|null
     */
    public function getRemindernotifieddate()
    {
        return $this->remindernotifieddate;
    }

    /**
     * Set reminderconfirmeddate.
     *
     * @param \DateTime|null $reminderconfirmeddate
     *
     * @return Taskstoservicers
     */
    public function setReminderconfirmeddate($reminderconfirmeddate = null)
    {
        $this->reminderconfirmeddate = $reminderconfirmeddate;

        return $this;
    }

    /**
     * Get reminderconfirmeddate.
     *
     * @return \DateTime|null
     */
    public function getReminderconfirmeddate()
    {
        return $this->reminderconfirmeddate;
    }

    /**
     * Set completeconfirmationrequesteddate.
     *
     * @param \DateTime|null $completeconfirmationrequesteddate
     *
     * @return Taskstoservicers
     */
    public function setCompleteconfirmationrequesteddate($completeconfirmationrequesteddate = null)
    {
        $this->completeconfirmationrequesteddate = $completeconfirmationrequesteddate;

        return $this;
    }

    /**
     * Get completeconfirmationrequesteddate.
     *
     * @return \DateTime|null
     */
    public function getCompleteconfirmationrequesteddate()
    {
        return $this->completeconfirmationrequesteddate;
    }

    /**
     * Set completeconfirmeddate.
     *
     * @param \DateTime|null $completeconfirmeddate
     *
     * @return Taskstoservicers
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
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Taskstoservicers
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
     * Set troubleshootingnote.
     *
     * @param string|null $troubleshootingnote
     *
     * @return Taskstoservicers
     */
    public function setTroubleshootingnote($troubleshootingnote = null)
    {
        $this->troubleshootingnote = $troubleshootingnote;

        return $this;
    }

    /**
     * Get troubleshootingnote.
     *
     * @return string|null
     */
    public function getTroubleshootingnote()
    {
        return $this->troubleshootingnote;
    }

    /**
     * Set taskid.
     *
     * @param \AppBundle\Entity\Tasks|null $taskid
     *
     * @return Taskstoservicers
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Taskstoservicers
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
}
