<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taskoffers
 *
 * @ORM\Table(name="TaskOffers", indexes={@ORM\Index(name="IDX_DF2BF57D3C7E7BEF", columns={"ServicerID"}), @ORM\Index(name="IDX_DF2BF57DEF8DEFC9", columns={"TaskID"})})
 * @ORM\Entity
 */
class Taskoffers
{
    /**
     * @var int
     *
     * @ORM\Column(name="TaskOfferID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $taskofferid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="AcceptDate", type="datetime", nullable=true)
     */
    private $acceptdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeclineDate", type="datetime", nullable=true)
     */
    private $declinedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ReminderConfirmedDate", type="datetime", nullable=true)
     */
    private $reminderconfirmeddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ServicerNotes", type="string", length=5000, nullable=true)
     */
    private $servicernotes;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * Get taskofferid.
     *
     * @return int
     */
    public function getTaskofferid()
    {
        return $this->taskofferid;
    }

    /**
     * Set acceptdate.
     *
     * @param \DateTime|null $acceptdate
     *
     * @return Taskoffers
     */
    public function setAcceptdate($acceptdate = null)
    {
        $this->acceptdate = $acceptdate;

        return $this;
    }

    /**
     * Get acceptdate.
     *
     * @return \DateTime|null
     */
    public function getAcceptdate()
    {
        return $this->acceptdate;
    }

    /**
     * Set declinedate.
     *
     * @param \DateTime|null $declinedate
     *
     * @return Taskoffers
     */
    public function setDeclinedate($declinedate = null)
    {
        $this->declinedate = $declinedate;

        return $this;
    }

    /**
     * Get declinedate.
     *
     * @return \DateTime|null
     */
    public function getDeclinedate()
    {
        return $this->declinedate;
    }

    /**
     * Set reminderconfirmeddate.
     *
     * @param \DateTime|null $reminderconfirmeddate
     *
     * @return Taskoffers
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
     * Set servicernotes.
     *
     * @param string|null $servicernotes
     *
     * @return Taskoffers
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
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Taskoffers
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
     * @return Taskoffers
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
     * @return Taskoffers
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
}
