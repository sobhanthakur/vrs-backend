<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Checkliststochecklistitems
 *
 * @ORM\Table(name="ChecklistsToChecklistItems", indexes={@ORM\Index(name="IDX_7D87A201B650950C", columns={"ChecklistID"}), @ORM\Index(name="IDX_7D87A201B52FC753", columns={"ChecklistItemID"})})
 * @ORM\Entity
 */
class Checkliststochecklistitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistToChecklistItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $checklisttochecklistitemid;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Checklists
     *
     * @ORM\ManyToOne(targetEntity="Checklists")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ChecklistID", referencedColumnName="ChecklistID")
     * })
     */
    private $checklistid;

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
     * Get checklisttochecklistitemid.
     *
     * @return int
     */
    public function getChecklisttochecklistitemid()
    {
        return $this->checklisttochecklistitemid;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Checkliststochecklistitems
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
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Checkliststochecklistitems
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
     * Set checklistid.
     *
     * @param \AppBundle\Entity\Checklists|null $checklistid
     *
     * @return Checkliststochecklistitems
     */
    public function setChecklistid(\AppBundle\Entity\Checklists $checklistid = null)
    {
        $this->checklistid = $checklistid;

        return $this;
    }

    /**
     * Get checklistid.
     *
     * @return \AppBundle\Entity\Checklists|null
     */
    public function getChecklistid()
    {
        return $this->checklistid;
    }

    /**
     * Set checklistitemid.
     *
     * @param \AppBundle\Entity\Checklistitems|null $checklistitemid
     *
     * @return Checkliststochecklistitems
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
}
