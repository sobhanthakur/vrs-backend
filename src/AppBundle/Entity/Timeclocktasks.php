<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timeclocktasks
 *
 * @ORM\Table(name="TimeClockTasks", indexes={@ORM\Index(name="AutoLogOutFlag", columns={"AutoLogOutFlag"}), @ORM\Index(name="ClockIn", columns={"ClockIn"}), @ORM\Index(name="Clockout", columns={"ClockOut"}), @ORM\Index(name="ServicerID", columns={"ServicerID"}), @ORM\Index(name="TaskID", columns={"TaskID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimeClockTasksRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Timeclocktasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="TimeClockTaskID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $timeclocktaskid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ClockIn", type="datetime", nullable=false)
     */
    private $clockin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ClockOut", type="datetime", nullable=true)
     */
    private $clockout;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Note", type="string", length=2000, nullable=true)
     */
    private $note;

    /**
     * @var bool
     *
     * @ORM\Column(name="AutoLogOutFlag", type="boolean", nullable=false)
     */
    private $autologoutflag = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="InLat", type="float", precision=53, scale=0, nullable=true)
     */
    private $inlat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="InLon", type="float", precision=53, scale=0, nullable=true)
     */
    private $inlon;

    /**
     * @var float|null
     *
     * @ORM\Column(name="OutLat", type="float", precision=53, scale=0, nullable=true)
     */
    private $outlat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="OutLon", type="float", precision=53, scale=0, nullable=true)
     */
    private $outlon;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lat", type="float", precision=53, scale=0, nullable=true)
     */
    private $lat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lon", type="float", precision=53, scale=0, nullable=true)
     */
    private $lon;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="InIsMobile", type="boolean", nullable=true)
     */
    private $inismobile = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="InAccuracy", type="float", precision=53, scale=0, nullable=true)
     */
    private $inaccuracy;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="OutIsMobile", type="boolean", nullable=true)
     */
    private $outismobile = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="OutAccuracy", type="float", precision=53, scale=0, nullable=true)
     */
    private $outaccuracy;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="UpdateDate", type="datetime", nullable=true)
     */
    private $updatedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDAte", type="datetime", nullable=true)
     */
    private $createdate;

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
     * @var \Tasks
     *
     * @ORM\ManyToOne(targetEntity="Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TaskID", referencedColumnName="TaskID")
     * })
     */
    private $taskid;



    /**
     * Get timeclocktaskid.
     *
     * @return int
     */
    public function getTimeclocktaskid()
    {
        return $this->timeclocktaskid;
    }

    /**
     * Set clockin.
     *
     * @param \DateTime $clockin
     *
     * @return Timeclocktasks
     */
    public function setClockin($clockin)
    {
        $this->clockin = $clockin;

        return $this;
    }

    /**
     * Get clockin.
     *
     * @return \DateTime
     */
    public function getClockin()
    {
        return $this->clockin;
    }

    /**
     * Set clockout.
     *
     * @param \DateTime|null $clockout
     *
     * @return Timeclocktasks
     */
    public function setClockout($clockout = null)
    {
        $this->clockout = $clockout;

        return $this;
    }

    /**
     * Get clockout.
     *
     * @return \DateTime|null
     */
    public function getClockout()
    {
        return $this->clockout;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Timeclocktasks
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set autologoutflag.
     *
     * @param bool $autologoutflag
     *
     * @return Timeclocktasks
     */
    public function setAutologoutflag($autologoutflag)
    {
        $this->autologoutflag = $autologoutflag;

        return $this;
    }

    /**
     * Get autologoutflag.
     *
     * @return bool
     */
    public function getAutologoutflag()
    {
        return $this->autologoutflag;
    }

    /**
     * Set inlat.
     *
     * @param float|null $inlat
     *
     * @return Timeclocktasks
     */
    public function setInlat($inlat = null)
    {
        $this->inlat = $inlat;

        return $this;
    }

    /**
     * Get inlat.
     *
     * @return float|null
     */
    public function getInlat()
    {
        return $this->inlat;
    }

    /**
     * Set inlon.
     *
     * @param float|null $inlon
     *
     * @return Timeclocktasks
     */
    public function setInlon($inlon = null)
    {
        $this->inlon = $inlon;

        return $this;
    }

    /**
     * Get inlon.
     *
     * @return float|null
     */
    public function getInlon()
    {
        return $this->inlon;
    }

    /**
     * Set outlat.
     *
     * @param float|null $outlat
     *
     * @return Timeclocktasks
     */
    public function setOutlat($outlat = null)
    {
        $this->outlat = $outlat;

        return $this;
    }

    /**
     * Get outlat.
     *
     * @return float|null
     */
    public function getOutlat()
    {
        return $this->outlat;
    }

    /**
     * Set outlon.
     *
     * @param float|null $outlon
     *
     * @return Timeclocktasks
     */
    public function setOutlon($outlon = null)
    {
        $this->outlon = $outlon;

        return $this;
    }

    /**
     * Get outlon.
     *
     * @return float|null
     */
    public function getOutlon()
    {
        return $this->outlon;
    }

    /**
     * Set lat.
     *
     * @param float|null $lat
     *
     * @return Timeclocktasks
     */
    public function setLat($lat = null)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat.
     *
     * @return float|null
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon.
     *
     * @param float|null $lon
     *
     * @return Timeclocktasks
     */
    public function setLon($lon = null)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon.
     *
     * @return float|null
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set inismobile.
     *
     * @param bool|null $inismobile
     *
     * @return Timeclocktasks
     */
    public function setInismobile($inismobile = null)
    {
        $this->inismobile = $inismobile;

        return $this;
    }

    /**
     * Get inismobile.
     *
     * @return bool|null
     */
    public function getInismobile()
    {
        return $this->inismobile;
    }

    /**
     * Set inaccuracy.
     *
     * @param float|null $inaccuracy
     *
     * @return Timeclocktasks
     */
    public function setInaccuracy($inaccuracy = null)
    {
        $this->inaccuracy = $inaccuracy;

        return $this;
    }

    /**
     * Get inaccuracy.
     *
     * @return float|null
     */
    public function getInaccuracy()
    {
        return $this->inaccuracy;
    }

    /**
     * Set outismobile.
     *
     * @param bool|null $outismobile
     *
     * @return Timeclocktasks
     */
    public function setOutismobile($outismobile = null)
    {
        $this->outismobile = $outismobile;

        return $this;
    }

    /**
     * Get outismobile.
     *
     * @return bool|null
     */
    public function getOutismobile()
    {
        return $this->outismobile;
    }

    /**
     * Set outaccuracy.
     *
     * @param float|null $outaccuracy
     *
     * @return Timeclocktasks
     */
    public function setOutaccuracy($outaccuracy = null)
    {
        $this->outaccuracy = $outaccuracy;

        return $this;
    }

    /**
     * Get outaccuracy.
     *
     * @return float|null
     */
    public function getOutaccuracy()
    {
        return $this->outaccuracy;
    }

    /**
     * Set updatedate.
     *
     * @param \DateTime|null $updatedate
     *
     * @return Timeclocktasks
     */
    public function setUpdatedate($updatedate = null)
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    /**
     * Get updatedate.
     *
     * @return \DateTime|null
     */
    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Timeclocktasks
     */
    public function setCreatedate($createdate = null)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime|null
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Timeclocktasks
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
     * Set taskid.
     *
     * @param \AppBundle\Entity\Tasks|null $taskid
     *
     * @return Timeclocktasks
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
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedate(new \DateTime('now', new \DateTimeZone('UTC')));
        if ($this->getCreatedate() == null && $this->getClockin() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }
}
