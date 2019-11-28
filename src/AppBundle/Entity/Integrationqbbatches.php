<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbbatches
 *
 * @ORM\Table(name="IntegrationQBBatches", indexes={@ORM\Index(name="IDX_6CC1D73B854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Integrationqbbatches
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBBatchID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbbatchid;

    /**
     * @var bool
     *
     * @ORM\Column(name="BatchType", type="boolean", nullable=false, options={"comment"="0=Blling & 1=TimeTracking"})
     */
    private $batchtype;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;

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
     * Get integrationqbbatchid.
     *
     * @return int
     */
    public function getIntegrationqbbatchid()
    {
        return $this->integrationqbbatchid;
    }

    /**
     * Set batchtype.
     *
     * @param bool $batchtype
     *
     * @return Integrationqbbatches
     */
    public function setBatchtype($batchtype)
    {
        $this->batchtype = $batchtype;

        return $this;
    }

    /**
     * Get batchtype.
     *
     * @return bool
     */
    public function getBatchtype()
    {
        return $this->batchtype;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbbatches
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
     * @return Integrationqbbatches
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
