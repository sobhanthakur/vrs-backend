<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taskchanges
 *
 * @ORM\Table(name="TaskChanges", indexes={@ORM\Index(name="IDX_AB9B6D26EF8DEFC9", columns={"TaskID"})})
 * @ORM\Entity
 */
class Taskchanges
{
    /**
     * @var int
     *
     * @ORM\Column(name="TaskChangeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $taskchangeid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ToDate", type="datetime", nullable=true)
     */
    private $todate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ToServicer", type="integer", nullable=true)
     */
    private $toservicer;

    /**
     * @var int
     *
     * @ORM\Column(name="ByServicer", type="integer", nullable=false)
     */
    private $byservicer = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="ByCustomer", type="integer", nullable=false)
     */
    private $bycustomer = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * Get taskchangeid.
     *
     * @return int
     */
    public function getTaskchangeid()
    {
        return $this->taskchangeid;
    }

    /**
     * Set todate.
     *
     * @param \DateTime|null $todate
     *
     * @return Taskchanges
     */
    public function setTodate($todate = null)
    {
        $this->todate = $todate;

        return $this;
    }

    /**
     * Get todate.
     *
     * @return \DateTime|null
     */
    public function getTodate()
    {
        return $this->todate;
    }

    /**
     * Set toservicer.
     *
     * @param int|null $toservicer
     *
     * @return Taskchanges
     */
    public function setToservicer($toservicer = null)
    {
        $this->toservicer = $toservicer;

        return $this;
    }

    /**
     * Get toservicer.
     *
     * @return int|null
     */
    public function getToservicer()
    {
        return $this->toservicer;
    }

    /**
     * Set byservicer.
     *
     * @param int $byservicer
     *
     * @return Taskchanges
     */
    public function setByservicer($byservicer)
    {
        $this->byservicer = $byservicer;

        return $this;
    }

    /**
     * Get byservicer.
     *
     * @return int
     */
    public function getByservicer()
    {
        return $this->byservicer;
    }

    /**
     * Set bycustomer.
     *
     * @param int $bycustomer
     *
     * @return Taskchanges
     */
    public function setBycustomer($bycustomer)
    {
        $this->bycustomer = $bycustomer;

        return $this;
    }

    /**
     * Get bycustomer.
     *
     * @return int
     */
    public function getBycustomer()
    {
        return $this->bycustomer;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Taskchanges
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Taskchanges
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
     * Set taskid.
     *
     * @param \AppBundle\Entity\Tasks|null $taskid
     *
     * @return Taskchanges
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
