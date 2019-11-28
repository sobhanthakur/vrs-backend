<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdtimetrackingrecords
 *
 * @ORM\Table(name="IntegrationQBDTimeTrackingRecords", indexes={@ORM\Index(name="IDX_CC2BFEF4ED4D199A", columns={"IntegrationQBBatchID"}), @ORM\Index(name="IDX_CC2BFEF4E0EE8C07", columns={"IntegrationQBDPayrollItemWageID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbdtimetrackingrecordsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Integrationqbdtimetrackingrecords
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDTimeTrackingRecords", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdtimetrackingrecords;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Day", type="date", nullable=true)
     */
    private $day;

    /**
     * @var string|null
     *
     * @ORM\Column(name="TxnID", type="string", length=50, nullable=true)
     */
    private $txnid;

    /**
     * @var int
     *
     * @ORM\Column(name="Status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="SentStatus", type="boolean", nullable=true)
     */
    private $sentstatus;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TimeTrackedSeconds", type="integer", nullable=true)
     */
    private $timetrackedseconds;

    /**
     * @var \Integrationqbbatches
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbbatches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBBatchID", referencedColumnName="IntegrationQBBatchID")
     * })
     */
    private $integrationqbbatchid;

    /**
     * @var \Timeclockdays
     *
     * @ORM\ManyToOne(targetEntity="Timeclockdays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TimeClockDaysID", referencedColumnName="TimeClockDayID")
     * })
     */
    private $timeclockdaysid;

    /**
     * Get integrationqbdtimetrackingrecords.
     *
     * @return int
     */
    public function getIntegrationqbdtimetrackingrecords()
    {
        return $this->integrationqbdtimetrackingrecords;
    }

    /**
     * Set day.
     *
     * @param \DateTime|null $day
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setDay($day = null)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day.
     *
     * @return \DateTime|null
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set txnid.
     *
     * @param string|null $txnid
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setTxnid($txnid = null)
    {
        $this->txnid = $txnid;

        return $this;
    }

    /**
     * Get txnid.
     *
     * @return string|null
     */
    public function getTxnid()
    {
        return $this->txnid;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbdtimetrackingrecords
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
     * Set sentstatus.
     *
     * @param bool|null $sentstatus
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setSentstatus($sentstatus = null)
    {
        $this->sentstatus = $sentstatus;

        return $this;
    }

    /**
     * Get sentstatus.
     *
     * @return bool|null
     */
    public function getSentstatus()
    {
        return $this->sentstatus;
    }

    /**
     * Set timetrackedseconds.
     *
     * @param int|null $timetrackedseconds
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setTimetrackedseconds($timetrackedseconds = null)
    {
        $this->timetrackedseconds = $timetrackedseconds;

        return $this;
    }

    /**
     * Get timetrackedseconds.
     *
     * @return int|null
     */
    public function getTimetrackedseconds()
    {
        return $this->timetrackedseconds;
    }

    /**
     * Set integrationqbbatchid.
     *
     * @param \AppBundle\Entity\Integrationqbbatches|null $integrationqbbatchid
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setIntegrationqbbatchid(\AppBundle\Entity\Integrationqbbatches $integrationqbbatchid = null)
    {
        $this->integrationqbbatchid = $integrationqbbatchid;

        return $this;
    }

    /**
     * Get integrationqbbatchid.
     *
     * @return \AppBundle\Entity\Integrationqbbatches|null
     */
    public function getIntegrationqbbatchid()
    {
        return $this->integrationqbbatchid;
    }

    /**
     * Set timeclockdaysid.
     *
     * @param \AppBundle\Entity\Integrationqbbatches|null $integrationqbbatchid
     *
     * @return Integrationqbdtimetrackingrecords
     */
    public function setTimeclockdaysid(\AppBundle\Entity\Timeclockdays $timeclockdaysid = null)
    {
        $this->timeclockdaysid = $timeclockdaysid;

        return $this;
    }


    /**
     * @return \Timeclockdays
     */
    public function getTimeclockdaysid()
    {
        return $this->timeclockdaysid;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedate() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }
}
