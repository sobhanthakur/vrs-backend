<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PropertyStatuses
 *
 * @ORM\Table(name="PropertyStatuses")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyStatusesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PropertyStatuses
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyStatusID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $propertystatusid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CustomerID", type="integer", nullable=false)
     */
    private $customerid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="PropertyStatus", type="string", length=200, nullable=false)
     */
    private $propertystatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;

    /**
     * @var bool
     *
     * @ORM\Column(name="SetOnCheckIn", type="boolean", nullable=false)
     */
    private $setoncheckin = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="SetOnCheckOut", type="boolean", nullable=false)
     */
    private $setoncheckout = false;

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
     * Get propertystatusid.
     *
     * @return int
     */
    public function getPropertystatusid()
    {
        return $this->propertystatusid;
    }

    /**
     * Set customerid.
     *
     * @param int $customerid
     *
     * @return PropertyStatuses
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
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return PropertyStatuses
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
     * Set propertystatus.
     *
     * @param string $propertystatus
     *
     * @return PropertyStatuses
     */
    public function setPropertystatus($propertystatus)
    {
        $this->propertystatus = $propertystatus;

        return $this;
    }

    /**
     * Get propertystatus.
     *
     * @return string
     */
    public function getPropertystatus()
    {
        return $this->propertystatus;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return PropertyStatuses
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
     * Set setoncheckin.
     *
     * @param bool $setoncheckin
     *
     * @return PropertyStatuses
     */
    public function setSetoncheckin($setoncheckin)
    {
        $this->setoncheckin = $setoncheckin;

        return $this;
    }

    /**
     * Get setoncheckin.
     *
     * @return bool
     */
    public function getSetoncheckin()
    {
        return $this->setoncheckin;
    }

    /**
     * Set setoncheckout.
     *
     * @param bool $setoncheckout
     *
     * @return PropertyStatuses
     */
    public function setSetoncheckout($setoncheckout)
    {
        $this->setoncheckout = $setoncheckout;

        return $this;
    }

    /**
     * Get setoncheckout.
     *
     * @return bool
     */
    public function getSetoncheckout()
    {
        return $this->setoncheckout;
    }
}
