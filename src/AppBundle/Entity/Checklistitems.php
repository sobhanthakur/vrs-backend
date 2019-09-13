<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Checklistitems
 *
 * @ORM\Table(name="ChecklistItems", indexes={@ORM\Index(name="IDX_366ECB6D854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Checklistitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $checklistitemid;

    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistTypeID", type="integer", nullable=false)
     */
    private $checklisttypeid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="ChecklistItem", type="string", length=400, nullable=false)
     */
    private $checklistitem;

    /**
     * @var string|null
     *
     * @ORM\Column(name="InternalName", type="string", length=400, nullable=true)
     */
    private $internalname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=0, nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowDescription", type="boolean", nullable=false)
     */
    private $showdescription = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image", type="string", length=200, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Options", type="text", length=-1, nullable=true)
     */
    private $options;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ColumnCount", type="integer", nullable=true)
     */
    private $columncount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder;

    /**
     * @var bool
     *
     * @ORM\Column(name="Required", type="boolean", nullable=false)
     */
    private $required = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOnOwnerReport", type="boolean", nullable=false)
     */
    private $showonownerreport = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ShowImageOnOwnerReport", type="boolean", nullable=true)
     */
    private $showimageonownerreport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;



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
     * Set checklisttypeid.
     *
     * @param int $checklisttypeid
     *
     * @return Checklistitems
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
     * Set checklistitem.
     *
     * @param string $checklistitem
     *
     * @return Checklistitems
     */
    public function setChecklistitem($checklistitem)
    {
        $this->checklistitem = $checklistitem;

        return $this;
    }

    /**
     * Get checklistitem.
     *
     * @return string
     */
    public function getChecklistitem()
    {
        return $this->checklistitem;
    }

    /**
     * Set internalname.
     *
     * @param string|null $internalname
     *
     * @return Checklistitems
     */
    public function setInternalname($internalname = null)
    {
        $this->internalname = $internalname;

        return $this;
    }

    /**
     * Get internalname.
     *
     * @return string|null
     */
    public function getInternalname()
    {
        return $this->internalname;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Checklistitems
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
     * Set showdescription.
     *
     * @param bool $showdescription
     *
     * @return Checklistitems
     */
    public function setShowdescription($showdescription)
    {
        $this->showdescription = $showdescription;

        return $this;
    }

    /**
     * Get showdescription.
     *
     * @return bool
     */
    public function getShowdescription()
    {
        return $this->showdescription;
    }

    /**
     * Set image.
     *
     * @param string|null $image
     *
     * @return Checklistitems
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
     * Set options.
     *
     * @param string|null $options
     *
     * @return Checklistitems
     */
    public function setOptions($options = null)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options.
     *
     * @return string|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set columncount.
     *
     * @param int|null $columncount
     *
     * @return Checklistitems
     */
    public function setColumncount($columncount = null)
    {
        $this->columncount = $columncount;

        return $this;
    }

    /**
     * Get columncount.
     *
     * @return int|null
     */
    public function getColumncount()
    {
        return $this->columncount;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Checklistitems
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
     * Set required.
     *
     * @param bool $required
     *
     * @return Checklistitems
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required.
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set showonownerreport.
     *
     * @param bool $showonownerreport
     *
     * @return Checklistitems
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
     * @param bool|null $showimageonownerreport
     *
     * @return Checklistitems
     */
    public function setShowimageonownerreport($showimageonownerreport = null)
    {
        $this->showimageonownerreport = $showimageonownerreport;

        return $this;
    }

    /**
     * Get showimageonownerreport.
     *
     * @return bool|null
     */
    public function getShowimageonownerreport()
    {
        return $this->showimageonownerreport;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Checklistitems
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
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Checklistitems
     */
    public function setCustomerid(\AppBundle\Entity\Customers $customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return \AppBundle\Entity\Customers|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }
}
