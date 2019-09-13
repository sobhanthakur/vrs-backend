<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taskacceptdeclines
 *
 * @ORM\Table(name="TaskAcceptDeclines", indexes={@ORM\Index(name="IDX_5F66D6763C7E7BEF", columns={"ServicerID"}), @ORM\Index(name="IDX_5F66D676EF8DEFC9", columns={"TaskID"})})
 * @ORM\Entity
 */
class Taskacceptdeclines
{
    /**
     * @var int
     *
     * @ORM\Column(name="TaskAcceptDeclineID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $taskacceptdeclineid;

    /**
     * @var bool
     *
     * @ORM\Column(name="AcceptOrDecline", type="boolean", nullable=false)
     */
    private $acceptordecline = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Note", type="text", length=-1, nullable=true)
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDAte", type="datetime", nullable=false, options={"default"="getutcdate()"})
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
     * Get taskacceptdeclineid.
     *
     * @return int
     */
    public function getTaskacceptdeclineid()
    {
        return $this->taskacceptdeclineid;
    }

    /**
     * Set acceptordecline.
     *
     * @param bool $acceptordecline
     *
     * @return Taskacceptdeclines
     */
    public function setAcceptordecline($acceptordecline)
    {
        $this->acceptordecline = $acceptordecline;

        return $this;
    }

    /**
     * Get acceptordecline.
     *
     * @return bool
     */
    public function getAcceptordecline()
    {
        return $this->acceptordecline;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Taskacceptdeclines
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
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Taskacceptdeclines
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Taskacceptdeclines
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
     * @return Taskacceptdeclines
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
