<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChecklistItemImages
 *
 * @ORM\Table(name="ChecklistItemImages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChecklistItemImagesRepository")
 */
class ChecklistItemImages
{
    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistItemImageID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $checklistitemimageid;

    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistItemID", type="integer", nullable=false)
     */
    private $checklistitemid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="UploadedImage", type="string", length=200, nullable=true)
     */
    private $uploadedimage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedate() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }

    /**
     * Get checklistitemimageid.
     *
     * @return int
     */
    public function getChecklistitemimageid()
    {
        return $this->checklistitemimageid;
    }

    /**
     * Set checklistitemid.
     *
     * @param int $checklistitemid
     *
     * @return ChecklistItemImages
     */
    public function setChecklistitemid($checklistitemid)
    {
        $this->checklistitemid = $checklistitemid;

        return $this;
    }

    /**
     * Get checklistitemid.
     *
     * @return int
     */
    public function getChecklistitemid()
    {
        return $this->checklistitemid;
    }

    /**
     * Set uploadedimage.
     *
     * @param string|null $uploadedimage
     *
     * @return ChecklistItemImages
     */
    public function setUploadedimage($uploadedimage = null)
    {
        $this->uploadedimage = $uploadedimage;

        return $this;
    }

    /**
     * Get uploadedimage.
     *
     * @return string|null
     */
    public function getUploadedimage()
    {
        return $this->uploadedimage;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return ChecklistItemImages
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
}
