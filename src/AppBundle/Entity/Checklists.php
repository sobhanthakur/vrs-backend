<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Checklists
 *
 * @ORM\Table(name="Checklists")
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
     * @var int|null
     *
     * @ORM\Column(name="ParentChecklistID", type="integer", nullable=true)
     */
    private $parentchecklistid;

    /**
     * @var int
     *
     * @ORM\Column(name="CustomerID", type="integer", nullable=false)
     */
    private $customerid;

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
     * Get checklistid.
     *
     * @return int
     */
    public function getChecklistid()
    {
        return $this->checklistid;
    }

    /**
     * Set parentchecklistid.
     *
     * @param int|null $parentchecklistid
     *
     * @return Checklists
     */
    public function setParentchecklistid($parentchecklistid = null)
    {
        $this->parentchecklistid = $parentchecklistid;

        return $this;
    }

    /**
     * Get parentchecklistid.
     *
     * @return int|null
     */
    public function getParentchecklistid()
    {
        return $this->parentchecklistid;
    }

    /**
     * Set customerid.
     *
     * @param int $customerid
     *
     * @return Checklists
     */
    public function setCustomerid($customerid)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return int
     */
    public function getCustomerid()
    {
        return $this->customerid;
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
}
