<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdcustomers
 *
 * @ORM\Table(name="IntegrationQBDCustomers", indexes={@ORM\Index(name="IDX_EB73FC17854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbdcustomersRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Integrationqbdcustomers
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDCustomerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdcustomerid;

    /**
     * @var string
     *
     * @ORM\Column(name="QBDCustomerListID", type="string", length=50, nullable=false)
     */
    private $qbdcustomerlistid;

    /**
     * @var string
     *
     * @ORM\Column(name="QBDCustomerFullName", type="string", length=100, nullable=false)
     */
    private $qbdcustomerfullname;

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
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;



    /**
     * Get integrationqbdcustomerid.
     *
     * @return int
     */
    public function getIntegrationqbdcustomerid()
    {
        return $this->integrationqbdcustomerid;
    }

    /**
     * Set qbdcustomerlistid.
     *
     * @param string $qbdcustomerlistid
     *
     * @return Integrationqbdcustomers
     */
    public function setQbdcustomerlistid($qbdcustomerlistid)
    {
        $this->qbdcustomerlistid = $qbdcustomerlistid;

        return $this;
    }

    /**
     * Get qbdcustomerlistid.
     *
     * @return string
     */
    public function getQbdcustomerlistid()
    {
        return $this->qbdcustomerlistid;
    }

    /**
     * Set qbdcustomerfullname.
     *
     * @param string $qbdcustomerfullname
     *
     * @return Integrationqbdcustomers
     */
    public function setQbdcustomerfullname($qbdcustomerfullname)
    {
        $this->qbdcustomerfullname = $qbdcustomerfullname;

        return $this;
    }

    /**
     * Get qbdcustomerfullname.
     *
     * @return string
     */
    public function getQbdcustomerfullname()
    {
        return $this->qbdcustomerfullname;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Integrationqbdcustomers
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
     * @return Integrationqbdcustomers
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
