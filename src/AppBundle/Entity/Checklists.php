<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Checklists
 *
 * @ORM\Table(name="Checklists", indexes={@ORM\Index(name="IDX_FFDE98EFBCFBD3DE", columns={"ParentChecklistID"}), @ORM\Index(name="IDX_FFDE98EF854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Checklists
{
    /**
     * @var int
     *
     * @ORM\Column(name="ChecklistID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $checklistid;

    /**
     * @var string
     *
     * @ORM\Column(name="ChecklistName", type="string", length=100, nullable=false)
     */
    private $checklistname;

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
     *   @ORM\JoinColumn(name="ParentChecklistID", referencedColumnName="ChecklistID")
     * })
     */
    private $parentchecklistid;

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
     * Get checklistid.
     *
     * @return int
     */
    public function getChecklistid()
    {
        return $this->checklistid;
    }

    /**
     * Set checklistname.
     *
     * @param string $checklistname
     *
     * @return Checklists
     */
    public function setChecklistname($checklistname)
    {
        $this->checklistname = $checklistname;

        return $this;
    }

    /**
     * Get checklistname.
     *
     * @return string
     */
    public function getChecklistname()
    {
        return $this->checklistname;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Checklists
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
     * Set parentchecklistid.
     *
     * @param \AppBundle\Entity\Checklists|null $parentchecklistid
     *
     * @return Checklists
     */
    public function setParentchecklistid(\AppBundle\Entity\Checklists $parentchecklistid = null)
    {
        $this->parentchecklistid = $parentchecklistid;

        return $this;
    }

    /**
     * Get parentchecklistid.
     *
     * @return \AppBundle\Entity\Checklists|null
     */
    public function getParentchecklistid()
    {
        return $this->parentchecklistid;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Checklists
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
