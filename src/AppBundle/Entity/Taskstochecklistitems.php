<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taskstochecklistitems
 *
 * @ORM\Table(name="TasksToChecklistItems", indexes={@ORM\Index(name="ChecklistItemID", columns={"ChecklistItemID"}), @ORM\Index(name="ChecklistTypeID", columns={"ChecklistTypeID"}), @ORM\Index(name="dedup", columns={"deduped"}), @ORM\Index(name="optionid", columns={"OptionID"}), @ORM\Index(name="TaskID", columns={"TaskID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TasksToChecklistItemsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Taskstochecklistitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="TaskToChecklistItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tasktochecklistitemid;

    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistTypeID", type="integer", nullable=false)
     */
    private $checklisttypeid = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="OptionID", type="integer", nullable=true)
     */
    private $optionid = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Checked", type="boolean", nullable=false)
     */
    private $checked = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ChecklistItem", type="string", length=0, nullable=true)
     */
    private $checklistitem;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=0, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image", type="string", length=0, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="EnteredValue", type="string", length=0, nullable=true)
     */
    private $enteredvalue;

    /**
     * @var float|null
     *
     * @ORM\Column(name="EnteredValueAmount", type="float", precision=53, scale=0, nullable=true)
     */
    private $enteredvalueamount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImageUploaded", type="string", length=0, nullable=true)
     */
    private $imageuploaded;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OptionSelected", type="string", length=0, nullable=true)
     */
    private $optionselected;

    /**
     * @var int
     *
     * @ORM\Column(name="ColumnValue", type="integer", nullable=false)
     */
    private $columnvalue = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOnOwnerReport", type="boolean", nullable=false)
     */
    private $showonownerreport = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowImageOnOwnerReport", type="boolean", nullable=false)
     */
    private $showimageonownerreport = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="deduped", type="boolean", nullable=false)
     */
    private $deduped = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Createdate", type="datetime", nullable=false)
     */
    private $createdate;

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
     * @var \Checklistitems
     *
     * @ORM\ManyToOne(targetEntity="Checklistitems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ChecklistItemID", referencedColumnName="ChecklistItemID")
     * })
     */
    private $checklistitemid;



    /**
     * Get tasktochecklistitemid.
     *
     * @return int
     */
    public function getTasktochecklistitemid()
    {
        return $this->tasktochecklistitemid;
    }

    /**
     * Set checklisttypeid.
     *
     * @param int $checklisttypeid
     *
     * @return Taskstochecklistitems
     */
    public function setChecklisttypeid($checklisttypeid)
    {
        $this->checklisttypeid = $checklisttypeid;

        return $this;
    }

    /**
     * Get checklisttypeid.
     *
     * @return int
     */
    public function getChecklisttypeid()
    {
        return $this->checklisttypeid;
    }

    /**
     * Set optionid.
     *
     * @param int|null $optionid
     *
     * @return Taskstochecklistitems
     */
    public function setOptionid($optionid = null)
    {
        $this->optionid = $optionid;

        return $this;
    }

    /**
     * Get optionid.
     *
     * @return int|null
     */
    public function getOptionid()
    {
        return $this->optionid;
    }

    /**
     * Set checked.
     *
     * @param bool $checked
     *
     * @return Taskstochecklistitems
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked.
     *
     * @return bool
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set checklistitem.
     *
     * @param string|null $checklistitem
     *
     * @return Taskstochecklistitems
     */
    public function setChecklistitem($checklistitem = null)
    {
        $this->checklistitem = $checklistitem;

        return $this;
    }

    /**
     * Get checklistitem.
     *
     * @return string|null
     */
    public function getChecklistitem()
    {
        return $this->checklistitem;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Taskstochecklistitems
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
     * Set image.
     *
     * @param string|null $image
     *
     * @return Taskstochecklistitems
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set enteredvalue.
     *
     * @param string|null $enteredvalue
     *
     * @return Taskstochecklistitems
     */
    public function setEnteredvalue($enteredvalue = null)
    {
        $this->enteredvalue = $enteredvalue;

        return $this;
    }

    /**
     * Get enteredvalue.
     *
     * @return string|null
     */
    public function getEnteredvalue()
    {
        return $this->enteredvalue;
    }

    /**
     * Set imageuploaded.
     *
     * @param string|null $imageuploaded
     *
     * @return Taskstochecklistitems
     */
    public function setImageuploaded($imageuploaded = null)
    {
        $this->imageuploaded = $imageuploaded;

        return $this;
    }

    /**
     * Get imageuploaded.
     *
     * @return string|null
     */
    public function getImageuploaded()
    {
        return $this->imageuploaded;
    }

    /**
     * Set optionselected.
     *
     * @param string|null $optionselected
     *
     * @return Taskstochecklistitems
     */
    public function setOptionselected($optionselected = null)
    {
        $this->optionselected = $optionselected;

        return $this;
    }

    /**
     * Get optionselected.
     *
     * @return string|null
     */
    public function getOptionselected()
    {
        return $this->optionselected;
    }

    /**
     * Set columnvalue.
     *
     * @param int $columnvalue
     *
     * @return Taskstochecklistitems
     */
    public function setColumnvalue($columnvalue)
    {
        $this->columnvalue = $columnvalue;

        return $this;
    }

    /**
     * Get columnvalue.
     *
     * @return int
     */
    public function getColumnvalue()
    {
        return $this->columnvalue;
    }

    /**
     * Set showonownerreport.
     *
     * @param bool $showonownerreport
     *
     * @return Taskstochecklistitems
     */
    public function setShowonownerreport($showonownerreport)
    {
        $this->showonownerreport = $showonownerreport;

        return $this;
    }

    /**
     * Get showonownerreport.
     *
     * @return bool
     */
    public function getShowonownerreport()
    {
        return $this->showonownerreport;
    }

    /**
     * Set showimageonownerreport.
     *
     * @param bool $showimageonownerreport
     *
     * @return Taskstochecklistitems
     */
    public function setShowimageonownerreport($showimageonownerreport)
    {
        $this->showimageonownerreport = $showimageonownerreport;

        return $this;
    }

    /**
     * Get showimageonownerreport.
     *
     * @return bool
     */
    public function getShowimageonownerreport()
    {
        return $this->showimageonownerreport;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Taskstochecklistitems
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set deduped.
     *
     * @param bool $deduped
     *
     * @return Taskstochecklistitems
     */
    public function setDeduped($deduped)
    {
        $this->deduped = $deduped;

        return $this;
    }

    /**
     * Get deduped.
     *
     * @return bool
     */
    public function getDeduped()
    {
        return $this->deduped;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Taskstochecklistitems
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
     * @return Taskstochecklistitems
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

    /**
     * Set checklistitemid.
     *
     * @param \AppBundle\Entity\Checklistitems|null $checklistitemid
     *
     * @return Taskstochecklistitems
     */
    public function setChecklistitemid(\AppBundle\Entity\Checklistitems $checklistitemid = null)
    {
        $this->checklistitemid = $checklistitemid;

        return $this;
    }

    /**
     * Get checklistitemid.
     *
     * @return \AppBundle\Entity\Checklistitems|null
     */
    public function getChecklistitemid()
    {
        return $this->checklistitemid;
    }

    /**
     * Set enteredvalueamount.
     *
     * @param float|null $enteredvalueamount
     *
     * @return Taskstochecklistitems
     */
    public function setEnteredvalueamount($enteredvalueamount = null)
    {
        $this->enteredvalueamount = $enteredvalueamount;

        return $this;
    }

    /**
     * Get enteredvalueamount.
     *
     * @return float|null
     */
    public function getEnteredvalueamount()
    {
        return $this->enteredvalueamount;
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
