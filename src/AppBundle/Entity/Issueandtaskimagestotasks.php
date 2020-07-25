<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issueandtaskimagestotasks
 *
 * @ORM\Table(name="IssueAndTaskImagesToTasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IssueandtaskimagestotasksRepository")
 */
class Issueandtaskimagestotasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="IssueAndTaskImageToTaskID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $issueAndtaskimagetotaskid;

    /**
     * @var Issueandtaskimages
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Issueandtaskimages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IssueAndTaskImageID", referencedColumnName="IssueAndTaskImageID")
     * })
     */
    private $issueAndtaskimageid;

    /**
     * @var Tasks
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TaskID", referencedColumnName="TaskID")
     * })
     */
    private $taskId;

    /**
     * Get issueAndtaskimagetotaskid.
     *
     * @return int
     */
    public function getIssueAndtaskimagetotaskid()
    {
        return $this->issueAndtaskimagetotaskid;
    }

    /**
     * Set issueAndtaskimageid.
     *
     * @param \AppBundle\Entity\Issueandtaskimages|null $issueAndtaskimageid
     *
     * @return Issueandtaskimagestotasks
     */
    public function setIssueAndtaskimageid(\AppBundle\Entity\Issueandtaskimages $issueAndtaskimageid = null)
    {
        $this->issueAndtaskimageid = $issueAndtaskimageid;

        return $this;
    }

    /**
     * Get issueAndtaskimageid.
     *
     * @return \AppBundle\Entity\Issueandtaskimages|null
     */
    public function getIssueAndtaskimageid()
    {
        return $this->issueAndtaskimageid;
    }

    /**
     * Set taskId.
     *
     * @param \AppBundle\Entity\Tasks|null $taskId
     *
     * @return Issueandtaskimagestotasks
     */
    public function setTaskId(\AppBundle\Entity\Tasks $taskId = null)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Get taskId.
     *
     * @return \AppBundle\Entity\Tasks|null
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
}
