<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scheduledtasks
 *
 * @ORM\Table(name="ScheduledTasks", indexes={@ORM\Index(name="CompleteDate", columns={"CompleteDate"}), @ORM\Index(name="FromAdmin", columns={"FromAdmin"}), @ORM\Index(name="performingtask", columns={"PerformingTask"}), @ORM\Index(name="propertyid", columns={"PropertyID"}), @ORM\Index(name="skipbookingimport", columns={"SkipBookingImport"})})
 * @ORM\Entity
 */
class Scheduledtasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="ScheduledTaskID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $scheduledtaskid;

    /**
     * @var int
     *
     * @ORM\Column(name="SkipBookingImport", type="integer", nullable=false)
     */
    private $skipbookingimport = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="PerformingTask", type="boolean", nullable=false)
     */
    private $performingtask = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="PerformingTaskDate", type="datetime", nullable=true)
     */
    private $performingtaskdate;

    /**
     * @var bool
     *
     * @ORM\Column(name="SendMessages", type="boolean", nullable=false, options={"default"="1"})
     */
    private $sendmessages = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="Track", type="integer", nullable=true)
     */
    private $track;

    /**
     * @var bool
     *
     * @ORM\Column(name="FromAdmin", type="boolean", nullable=false)
     */
    private $fromadmin = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CompleteDate", type="datetime", nullable=true)
     */
    private $completedate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * Get scheduledtaskid.
     *
     * @return int
     */
    public function getScheduledtaskid()
    {
        return $this->scheduledtaskid;
    }

    /**
     * Set skipbookingimport.
     *
     * @param int $skipbookingimport
     *
     * @return Scheduledtasks
     */
    public function setSkipbookingimport($skipbookingimport)
    {
        $this->skipbookingimport = $skipbookingimport;

        return $this;
    }

    /**
     * Get skipbookingimport.
     *
     * @return int
     */
    public function getSkipbookingimport()
    {
        return $this->skipbookingimport;
    }

    /**
     * Set performingtask.
     *
     * @param bool $performingtask
     *
     * @return Scheduledtasks
     */
    public function setPerformingtask($performingtask)
    {
        $this->performingtask = $performingtask;

        return $this;
    }

    /**
     * Get performingtask.
     *
     * @return bool
     */
    public function getPerformingtask()
    {
        return $this->performingtask;
    }

    /**
     * Set performingtaskdate.
     *
     * @param \DateTime|null $performingtaskdate
     *
     * @return Scheduledtasks
     */
    public function setPerformingtaskdate($performingtaskdate = null)
    {
        $this->performingtaskdate = $performingtaskdate;

        return $this;
    }

    /**
     * Get performingtaskdate.
     *
     * @return \DateTime|null
     */
    public function getPerformingtaskdate()
    {
        return $this->performingtaskdate;
    }

    /**
     * Set sendmessages.
     *
     * @param bool $sendmessages
     *
     * @return Scheduledtasks
     */
    public function setSendmessages($sendmessages)
    {
        $this->sendmessages = $sendmessages;

        return $this;
    }

    /**
     * Get sendmessages.
     *
     * @return bool
     */
    public function getSendmessages()
    {
        return $this->sendmessages;
    }

    /**
     * Set track.
     *
     * @param int|null $track
     *
     * @return Scheduledtasks
     */
    public function setTrack($track = null)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track.
     *
     * @return int|null
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set fromadmin.
     *
     * @param bool $fromadmin
     *
     * @return Scheduledtasks
     */
    public function setFromadmin($fromadmin)
    {
        $this->fromadmin = $fromadmin;

        return $this;
    }

    /**
     * Get fromadmin.
     *
     * @return bool
     */
    public function getFromadmin()
    {
        return $this->fromadmin;
    }

    /**
     * Set completedate.
     *
     * @param \DateTime|null $completedate
     *
     * @return Scheduledtasks
     */
    public function setCompletedate($completedate = null)
    {
        $this->completedate = $completedate;

        return $this;
    }

    /**
     * Get completedate.
     *
     * @return \DateTime|null
     */
    public function getCompletedate()
    {
        return $this->completedate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Scheduledtasks
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
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Scheduledtasks
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
}
