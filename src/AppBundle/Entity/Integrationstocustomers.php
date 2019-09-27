<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationstocustomers
 *
 * @ORM\Table(name="IntegrationsToCustomers", indexes={@ORM\Index(name="IDX_EE874FDF1389864B", columns={"IntegrationQBDHourWageTypeID"}), @ORM\Index(name="IDX_EE874FDF4182F12B", columns={"IntegrationQBDRateWageTypeID"}), @ORM\Index(name="IDX_EE874FDF854CF4BD", columns={"CustomerID"}), @ORM\Index(name="IDX_EE874FDF31B9B12", columns={"IntegrationID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationsToCustomersRepository")
 */
class Integrationstocustomers
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationToCustomerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationtocustomerid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Username", type="string", length=50, nullable=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Password", type="string", length=50, nullable=true)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="QBDSyncBilling", type="boolean", nullable=false)
     */
    private $qbdsyncbilling = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="QBDSyncPayroll", type="boolean", nullable=false)
     */
    private $qbdsyncpayroll = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="StartDate", type="datetime", nullable=true)
     */
    private $startdate;

    /**
     * @var \Integrationqbdpayrollitemwages
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbdpayrollitemwages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBDHourWageTypeID", referencedColumnName="IntegrationQBDPayrollItemWageID")
     * })
     */
    private $integrationqbdhourwagetypeid;

    /**
     * @var \Integrationqbdpayrollitemwages
     *
     * @ORM\ManyToOne(targetEntity="Integrationqbdpayrollitemwages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationQBDRateWageTypeID", referencedColumnName="IntegrationQBDPayrollItemWageID")
     * })
     */
    private $integrationqbdratewagetypeid;

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
     * @var \Integrations
     *
     * @ORM\ManyToOne(targetEntity="Integrations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IntegrationID", referencedColumnName="IntegrationID")
     * })
     */
    private $integrationid;



    /**
     * Get integrationtocustomerid.
     *
     * @return int
     */
    public function getIntegrationtocustomerid()
    {
        return $this->integrationtocustomerid;
    }

    /**
     * Set username.
     *
     * @param string|null $username
     *
     * @return Integrationstocustomers
     */
    public function setUsername($username = null)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return Integrationstocustomers
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set qbdsyncbilling.
     *
     * @param bool $qbdsyncbilling
     *
     * @return Integrationstocustomers
     */
    public function setQbdsyncbilling($qbdsyncbilling)
    {
        $this->qbdsyncbilling = $qbdsyncbilling;

        return $this;
    }

    /**
     * Get qbdsyncbilling.
     *
     * @return bool
     */
    public function getQbdsyncbilling()
    {
        return $this->qbdsyncbilling;
    }

    /**
     * Set qbdsyncpayroll.
     *
     * @param bool $qbdsyncpayroll
     *
     * @return Integrationstocustomers
     */
    public function setQbdsyncpayroll($qbdsyncpayroll)
    {
        $this->qbdsyncpayroll = $qbdsyncpayroll;

        return $this;
    }

    /**
     * Get qbdsyncpayroll.
     *
     * @return bool
     */
    public function getQbdsyncpayroll()
    {
        return $this->qbdsyncpayroll;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Integrationstocustomers
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
     * @return Integrationstocustomers
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
     * Set startdate.
     *
     * @param \DateTime|null $startdate
     *
     * @return Integrationstocustomers
     */
    public function setStartdate($startdate = null)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate.
     *
     * @return \DateTime|null
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set integrationqbdhourwagetypeid.
     *
     * @param \AppBundle\Entity\Integrationqbdpayrollitemwages|null $integrationqbdhourwagetypeid
     *
     * @return Integrationstocustomers
     */
    public function setIntegrationqbdhourwagetypeid(\AppBundle\Entity\Integrationqbdpayrollitemwages $integrationqbdhourwagetypeid = null)
    {
        $this->integrationqbdhourwagetypeid = $integrationqbdhourwagetypeid;

        return $this;
    }

    /**
     * Get integrationqbdhourwagetypeid.
     *
     * @return \AppBundle\Entity\Integrationqbdpayrollitemwages|null
     */
    public function getIntegrationqbdhourwagetypeid()
    {
        return $this->integrationqbdhourwagetypeid;
    }

    /**
     * Set integrationqbdratewagetypeid.
     *
     * @param \AppBundle\Entity\Integrationqbdpayrollitemwages|null $integrationqbdratewagetypeid
     *
     * @return Integrationstocustomers
     */
    public function setIntegrationqbdratewagetypeid(\AppBundle\Entity\Integrationqbdpayrollitemwages $integrationqbdratewagetypeid = null)
    {
        $this->integrationqbdratewagetypeid = $integrationqbdratewagetypeid;

        return $this;
    }

    /**
     * Get integrationqbdratewagetypeid.
     *
     * @return \AppBundle\Entity\Integrationqbdpayrollitemwages|null
     */
    public function getIntegrationqbdratewagetypeid()
    {
        return $this->integrationqbdratewagetypeid;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Integrationstocustomers
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
     * Set integrationid.
     *
     * @param \AppBundle\Entity\Integrations|null $integrationid
     *
     * @return Integrationstocustomers
     */
    public function setIntegrationid(\AppBundle\Entity\Integrations $integrationid = null)
    {
        $this->integrationid = $integrationid;

        return $this;
    }

    /**
     * Get integrationid.
     *
     * @return \AppBundle\Entity\Integrations|null
     */
    public function getIntegrationid()
    {
        return $this->integrationid;
    }
}
