<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChecklistItemImages
 *
 * @ORM\Table(name="ChecklistItemImages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChecklistItemImagesRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="TaskToChecklistItemID", type="integer", nullable=false)
     */
    private $tasktochecklistitemid;

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

    /**
     * Set tasktochecklistitemid.
     *
     * @param int $tasktochecklistitemid
     *
     * @return ChecklistItemImages
     */
    public function setTasktochecklistitemid($tasktochecklistitemid)
    {
        $this->tasktochecklistitemid = $tasktochecklistitemid;

        return $this;
    }

    /**
     * Get tasktochecklistitemid.
     *
     * @return int
     */
    public function getTasktochecklistitemid()
    {
        return $this->tasktochecklistitemid;
    }
}
