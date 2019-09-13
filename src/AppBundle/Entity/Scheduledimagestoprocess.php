<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scheduledimagestoprocess
 *
 * @ORM\Table(name="ScheduledImagesToProcess")
 * @ORM\Entity
 */
class Scheduledimagestoprocess
{
    /**
     * @var int
     *
     * @ORM\Column(name="ScheduledImagesToProcessID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $scheduledimagestoprocessid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImagePath", type="string", length=300, nullable=true)
     */
    private $imagepath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NewImagePath", type="string", length=300, nullable=true)
     */
    private $newimagepath;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="TodayWithTIme", type="datetime", nullable=true)
     */
    private $todaywithtime;

    /**
     * @var bool
     *
     * @ORM\Column(name="SkipTimestamp", type="boolean", nullable=false)
     */
    private $skiptimestamp = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DoneDate", type="datetime", nullable=true)
     */
    private $donedate;



    /**
     * Get scheduledimagestoprocessid.
     *
     * @return int
     */
    public function getScheduledimagestoprocessid()
    {
        return $this->scheduledimagestoprocessid;
    }

    /**
     * Set imagepath.
     *
     * @param string|null $imagepath
     *
     * @return Scheduledimagestoprocess
     */
    public function setImagepath($imagepath = null)
    {
        $this->imagepath = $imagepath;

        return $this;
    }

    /**
     * Get imagepath.
     *
     * @return string|null
     */
    public function getImagepath()
    {
        return $this->imagepath;
    }

    /**
     * Set newimagepath.
     *
     * @param string|null $newimagepath
     *
     * @return Scheduledimagestoprocess
     */
    public function setNewimagepath($newimagepath = null)
    {
        $this->newimagepath = $newimagepath;

        return $this;
    }

    /**
     * Get newimagepath.
     *
     * @return string|null
     */
    public function getNewimagepath()
    {
        return $this->newimagepath;
    }

    /**
     * Set todaywithtime.
     *
     * @param \DateTime|null $todaywithtime
     *
     * @return Scheduledimagestoprocess
     */
    public function setTodaywithtime($todaywithtime = null)
    {
        $this->todaywithtime = $todaywithtime;

        return $this;
    }

    /**
     * Get todaywithtime.
     *
     * @return \DateTime|null
     */
    public function getTodaywithtime()
    {
        return $this->todaywithtime;
    }

    /**
     * Set skiptimestamp.
     *
     * @param bool $skiptimestamp
     *
     * @return Scheduledimagestoprocess
     */
    public function setSkiptimestamp($skiptimestamp)
    {
        $this->skiptimestamp = $skiptimestamp;

        return $this;
    }

    /**
     * Get skiptimestamp.
     *
     * @return bool
     */
    public function getSkiptimestamp()
    {
        return $this->skiptimestamp;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Scheduledimagestoprocess
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
     * Set donedate.
     *
     * @param \DateTime|null $donedate
     *
     * @return Scheduledimagestoprocess
     */
    public function setDonedate($donedate = null)
    {
        $this->donedate = $donedate;

        return $this;
    }

    /**
     * Get donedate.
     *
     * @return \DateTime|null
     */
    public function getDonedate()
    {
        return $this->donedate;
    }
}
