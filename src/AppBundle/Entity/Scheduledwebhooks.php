<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scheduledwebhooks
 *
 * @ORM\Table(name="ScheduledWebHooks")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Scheduledwebhooks
{
    /**
     * @var int
     *
     * @ORM\Column(name="ScheduledWebHookID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $scheduledwebhookid;

    /**
     * @var int
     *
     * @ORM\Column(name="CustomerID", type="integer", nullable=false)
     */
    private $customerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TaskID", type="integer", nullable=true)
     */
    private $taskid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ServicerID", type="integer", nullable=true)
     */
    private $servicerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PropertyID", type="integer", nullable=true)
     */
    private $propertyid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PropertyBookingID", type="integer", nullable=true)
     */
    private $propertybookingid;

    /**
     * @var int
     *
     * @ORM\Column(name="PartnerID", type="integer", nullable=false, options={"comment"="1=BeHome247"})
     */
    private $partnerid;

    /**
     * @var int
     *
     * @ORM\Column(name="EventID", type="integer", nullable=false)
     */
    private $eventid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Value", type="string", length=0, nullable=true)
     */
    private $value;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Response", type="string", length=0, nullable=true)
     */
    private $response;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Notes", type="string", length=0, nullable=true)
     */
    private $notes;

    /**
     * @var int
     *
     * @ORM\Column(name="AttemptCount", type="integer", nullable=false)
     */
    private $attemptcount = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Success", type="boolean", nullable=false)
     */
    private $success = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CompleteDate", type="datetime", nullable=true)
     */
    private $completedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="AttemptDate", type="datetime", nullable=true)
     */
    private $attemptdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;



    /**
     * Get scheduledwebhookid.
     *
     * @return int
     */
    public function getScheduledwebhookid()
    {
        return $this->scheduledwebhookid;
    }

    /**
     * Set customerid.
     *
     * @param int $customerid
     *
     * @return Scheduledwebhooks
     */
    public function setCustomerid($customerid)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return int
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set taskid.
     *
     * @param int|null $taskid
     *
     * @return Scheduledwebhooks
     */
    public function setTaskid($taskid = null)
    {
        $this->taskid = $taskid;

        return $this;
    }

    /**
     * Get taskid.
     *
     * @return int|null
     */
    public function getTaskid()
    {
        return $this->taskid;
    }

    /**
     * Set servicerid.
     *
     * @param int|null $servicerid
     *
     * @return Scheduledwebhooks
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
     * Set propertyid.
     *
     * @param int|null $propertyid
     *
     * @return Scheduledwebhooks
     */
    public function setPropertyid($propertyid = null)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid.
     *
     * @return int|null
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set propertybookingid.
     *
     * @param int|null $propertybookingid
     *
     * @return Scheduledwebhooks
     */
    public function setPropertybookingid($propertybookingid = null)
    {
        $this->propertybookingid = $propertybookingid;

        return $this;
    }

    /**
     * Get propertybookingid.
     *
     * @return int|null
     */
    public function getPropertybookingid()
    {
        return $this->propertybookingid;
    }

    /**
     * Set partnerid.
     *
     * @param int $partnerid
     *
     * @return Scheduledwebhooks
     */
    public function setPartnerid($partnerid)
    {
        $this->partnerid = $partnerid;

        return $this;
    }

    /**
     * Get partnerid.
     *
     * @return int
     */
    public function getPartnerid()
    {
        return $this->partnerid;
    }

    /**
     * Set eventid.
     *
     * @param int $eventid
     *
     * @return Scheduledwebhooks
     */
    public function setEventid($eventid)
    {
        $this->eventid = $eventid;

        return $this;
    }

    /**
     * Get eventid.
     *
     * @return int
     */
    public function getEventid()
    {
        return $this->eventid;
    }

    /**
     * Set value.
     *
     * @param string|null $value
     *
     * @return Scheduledwebhooks
     */
    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set response.
     *
     * @param string|null $response
     *
     * @return Scheduledwebhooks
     */
    public function setResponse($response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response.
     *
     * @return string|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set notes.
     *
     * @param string|null $notes
     *
     * @return Scheduledwebhooks
     */
    public function setNotes($notes = null)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string|null
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set attemptcount.
     *
     * @param int $attemptcount
     *
     * @return Scheduledwebhooks
     */
    public function setAttemptcount($attemptcount)
    {
        $this->attemptcount = $attemptcount;

        return $this;
    }

    /**
     * Get attemptcount.
     *
     * @return int
     */
    public function getAttemptcount()
    {
        return $this->attemptcount;
    }

    /**
     * Set success.
     *
     * @param bool $success
     *
     * @return Scheduledwebhooks
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Get success.
     *
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Set completedate.
     *
     * @param \DateTime|null $completedate
     *
     * @return Scheduledwebhooks
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
     * Set attemptdate.
     *
     * @param \DateTime|null $attemptdate
     *
     * @return Scheduledwebhooks
     */
    public function setAttemptdate($attemptdate = null)
    {
        $this->attemptdate = $attemptdate;

        return $this;
    }

    /**
     * Get attemptdate.
     *
     * @return \DateTime|null
     */
    public function getAttemptdate()
    {
        return $this->attemptdate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Scheduledwebhooks
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
