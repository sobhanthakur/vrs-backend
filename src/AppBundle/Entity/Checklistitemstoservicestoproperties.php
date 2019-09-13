<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Checklistitemstoservicestoproperties
 *
 * @ORM\Table(name="ChecklistItemsToServicesToProperties", indexes={@ORM\Index(name="IDX_419FB9FBB52FC753", columns={"ChecklistItemID"}), @ORM\Index(name="IDX_419FB9FB85991014", columns={"ServiceToPropertyID"})})
 * @ORM\Entity
 */
class Checklistitemstoservicestoproperties
{
    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistItemToServiceToPropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $checklistitemtoservicetopropertyid;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * @var \Servicestoproperties
     *
     * @ORM\ManyToOne(targetEntity="Servicestoproperties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceToPropertyID", referencedColumnName="ServiceToPropertyID")
     * })
     */
    private $servicetopropertyid;



    /**
     * Get checklistitemtoservicetopropertyid.
     *
     * @return int
     */
    public function getChecklistitemtoservicetopropertyid()
    {
        return $this->checklistitemtoservicetopropertyid;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Checklistitemstoservicestoproperties
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
     * @return Checklistitemstoservicestoproperties
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
     * Set checklistitemid.
     *
     * @param \AppBundle\Entity\Checklistitems|null $checklistitemid
     *
     * @return Checklistitemstoservicestoproperties
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
     * Set servicetopropertyid.
     *
     * @param \AppBundle\Entity\Servicestoproperties|null $servicetopropertyid
     *
     * @return Checklistitemstoservicestoproperties
     */
    public function setServicetopropertyid(\AppBundle\Entity\Servicestoproperties $servicetopropertyid = null)
    {
        $this->servicetopropertyid = $servicetopropertyid;

        return $this;
    }

    /**
     * Get servicetopropertyid.
     *
     * @return \AppBundle\Entity\Servicestoproperties|null
     */
    public function getServicetopropertyid()
    {
        return $this->servicetopropertyid;
    }
}
