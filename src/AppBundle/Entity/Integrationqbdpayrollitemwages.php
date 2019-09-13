<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdpayrollitemwages
 *
 * @ORM\Table(name="IntegrationQBDPayrollItemWages", indexes={@ORM\Index(name="IDX_F203B0DA854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Integrationqbdpayrollitemwages
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDPayrollItemWageID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdpayrollitemwageid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="QBDPayrollItemWageListID", type="string", length=50, nullable=true)
     */
    private $qbdpayrollitemwagelistid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="QBDPayrollItemWageName", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $qbdpayrollitemwagename;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true)
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
     * Get integrationqbdpayrollitemwageid.
     *
     * @return int
     */
    public function getIntegrationqbdpayrollitemwageid()
    {
        return $this->integrationqbdpayrollitemwageid;
    }

    /**
     * Set qbdpayrollitemwagelistid.
     *
     * @param string|null $qbdpayrollitemwagelistid
     *
     * @return Integrationqbdpayrollitemwages
     */
    public function setQbdpayrollitemwagelistid($qbdpayrollitemwagelistid = null)
    {
        $this->qbdpayrollitemwagelistid = $qbdpayrollitemwagelistid;

        return $this;
    }

    /**
     * Get qbdpayrollitemwagelistid.
     *
     * @return string|null
     */
    public function getQbdpayrollitemwagelistid()
    {
        return $this->qbdpayrollitemwagelistid;
    }

    /**
     * Set qbdpayrollitemwagename.
     *
     * @param string|null $qbdpayrollitemwagename
     *
     * @return Integrationqbdpayrollitemwages
     */
    public function setQbdpayrollitemwagename($qbdpayrollitemwagename = null)
    {
        $this->qbdpayrollitemwagename = $qbdpayrollitemwagename;

        return $this;
    }

    /**
     * Get qbdpayrollitemwagename.
     *
     * @return string|null
     */
    public function getQbdpayrollitemwagename()
    {
        return $this->qbdpayrollitemwagename;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return Integrationqbdpayrollitemwages
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Integrationqbdpayrollitemwages
     */
    public function setCreatedate($createdate = null)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime|null
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
     * @return Integrationqbdpayrollitemwages
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
