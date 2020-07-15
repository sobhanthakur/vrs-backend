<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbditems
 *
 * @ORM\Table(name="IntegrationQBDItems")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbditemsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Integrationqbditems
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbditemid;

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
     * @var string|null
     *
     * @ORM\Column(name="QBDItemListID", type="string", length=50, nullable=true)
     */
    private $qbditemlistid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="QBDItemFullName", type="string", length=100, nullable=true)
     */
    private $qbditemfullname;

    /**
     * @var float|null
     *
     * @ORM\Column(name="UnitPrice", type="float",precision=53, scale=0, nullable=true)
     */
    private $unitprice;

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;



    /**
     * Get integrationqbditemid.
     *
     * @return int
     */
    public function getIntegrationqbditemid()
    {
        return $this->integrationqbditemid;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Integrationqbdcustomers
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
     * Set qbditemlistid.
     *
     * @param string|null $qbditemlistid
     *
     * @return Integrationqbditems
     */
    public function setQbditemlistid($qbditemlistid = null)
    {
        $this->qbditemlistid = $qbditemlistid;

        return $this;
    }

    /**
     * Get qbditemlistid.
     *
     * @return string|null
     */
    public function getQbditemlistid()
    {
        return $this->qbditemlistid;
    }

    /**
     * Set qbditemfullname.
     *
     * @param string|null $qbditemfullname
     *
     * @return Integrationqbditems
     */
    public function setQbditemfullname($qbditemfullname = null)
    {
        $this->qbditemfullname = $qbditemfullname;

        return $this;
    }

    /**
     * Get qbditemfullname.
     *
     * @return string|null
     */
    public function getQbditemfullname()
    {
        return $this->qbditemfullname;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Integrationqbditems
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbditems
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
     * Set unitprice.
     *
     * @param float|null $unitprice
     *
     * @return Integrationqbditems
     */
    public function setUnitprice($unitprice = null)
    {
        $this->unitprice = $unitprice;

        return $this;
    }

    /**
     * Get unitprice.
     *
     * @return float|null
     */
    public function getUnitprice()
    {
        return $this->unitprice;
    }
}
