<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timeclockdays
 *
 * @ORM\Table(name="TimeClockDays", indexes={@ORM\Index(name="ClockIn", columns={"ClockIn"}), @ORM\Index(name="ServicerID", columns={"ServicerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimeclockdaysRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Timeclockdays
{
    /**
     * @var int
     *
     * @ORM\Column(name="TimeClockDayID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $timeclockdayid;

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
     * @var bool
     *
     * @ORM\Column(name="autoLogOutFlag", type="boolean", nullable=false)
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
     * @var int|null
     *
     * @ORM\Column(name="MileageIn", type="integer", nullable=true)
     */
    private $mileagein;

    /**
     * @var int|null
     *
     * @ORM\Column(name="MileageOut", type="integer", nullable=true)
     */
    private $mileageout;

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
     * Get timeclockdayid.
     *
     * @return int
     */
    public function getTimeclockdayid()
    {
        return $this->timeclockdayid;
    }

    /**
     * Set clockin.
     *
     * @param \DateTime $clockin
     *
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * Set autologoutflag.
     *
     * @param bool $autologoutflag
     *
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * Set mileagein.
     *
     * @param int|null $mileagein
     *
     * @return Timeclockdays
     */
    public function setMileagein($mileagein = null)
    {
        $this->mileagein = $mileagein;

        return $this;
    }

    /**
     * Get mileagein.
     *
     * @return int|null
     */
    public function getMileagein()
    {
        return $this->mileagein;
    }

    /**
     * Set mileageout.
     *
     * @param int|null $mileageout
     *
     * @return Timeclockdays
     */
    public function setMileageout($mileageout = null)
    {
        $this->mileageout = $mileageout;

        return $this;
    }

    /**
     * Get mileageout.
     *
     * @return int|null
     */
    public function getMileageout()
    {
        return $this->mileageout;
    }

    /**
     * Set inismobile.
     *
     * @param bool|null $inismobile
     *
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @return Timeclockdays
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
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedate(new \DateTime('now', new \DateTimeZone('UTC')));
        if ($this->getCreatedate() == null && $this->getClockin() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
            $this->setClockin($datetime);
        }
    }
}
