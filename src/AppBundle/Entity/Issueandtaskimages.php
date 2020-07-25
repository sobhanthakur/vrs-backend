<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issueandtaskimages
 *
 * @ORM\Table(name="IssueAndTaskImages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IssueandtaskimagesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Issueandtaskimages
{
    /**
     * @var int
     *
     * @ORM\Column(name="IssueAndTaskImageID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $issueAndtaskimageid;

    /**
     * @var Issues
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Issues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IssueID", referencedColumnName="IssueID")
     * })
     */
    private $issueID;

    /**
     * @var Properties
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Properties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyID", referencedColumnName="PropertyID")
     * })
     */
    private $propertyID;

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOwner", type="boolean")
     */
    private $showOwner = false;

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
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedate() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }

    /**
     * Get issueAndtaskimageid.
     *
     * @return int
     */
    public function getIssueAndtaskimageid()
    {
        return $this->issueAndtaskimageid;
    }

    /**
     * Set showOwner.
     *
     * @param bool $showOwner
     *
     * @return Issueandtaskimages
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
     * @return Issueandtaskimages
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
     * @return Issueandtaskimages
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
     * @return Issueandtaskimages
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
     * Set propertyID.
     *
     * @param \AppBundle\Entity\Properties|null $propertyID
     *
     * @return Issueandtaskimages
     */
    public function setPropertyID(\AppBundle\Entity\Properties $propertyID = null)
    {
        $this->propertyID = $propertyID;

        return $this;
    }

    /**
     * Get propertyID.
     *
     * @return \AppBundle\Entity\Properties|null
     */
    public function getPropertyID()
    {
        return $this->propertyID;
    }
}
