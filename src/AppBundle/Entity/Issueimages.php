<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issueimages
 *
 * @ORM\Table(name="IssueImages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IssueimagesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Issueimages
{
    /**
     * @var int
     *
     * @ORM\Column(name="IssueImageID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $issueImageid;

    /**
     * @var \Issues
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Issues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IssueID", referencedColumnName="IssueID")
     * })
     */
    private $issueID;

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOwner", type="boolean")
     */
    private $showOwner;

    /**
     * @var string
     *
     * @ORM\Column(name="ImageName", type="string", length=255)
     */
    private $imageName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime")
     */
    private $createDate;


    /**
     * Get issueImageid.
     *
     * @return int
     */
    public function getIssueImageid()
    {
        return $this->issueImageid;
    }

    /**
     * Set showOwner.
     *
     * @param bool $showOwner
     *
     * @return Issueimages
     */
    public function setShowOwner($showOwner)
    {
        $this->showOwner = $showOwner;

        return $this;
    }

    /**
     * Get showOwner.
     *
     * @return bool
     */
    public function getShowOwner()
    {
        return $this->showOwner;
    }

    /**
     * Set imageName.
     *
     * @param string $imageName
     *
     * @return Issueimages
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName.
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Issueimages
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set issueID.
     *
     * @param \AppBundle\Entity\Issues|null $issueID
     *
     * @return Issueimages
     */
    public function setIssueID(\AppBundle\Entity\Issues $issueID = null)
    {
        $this->issueID = $issueID;

        return $this;
    }

    /**
     * Get issueID.
     *
     * @return \AppBundle\Entity\Issues|null
     */
    public function getIssueID()
    {
        return $this->issueID;
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
