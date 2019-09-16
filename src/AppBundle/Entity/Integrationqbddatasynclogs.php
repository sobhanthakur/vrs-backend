<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbddatasynclogs
 *
 * @ORM\Table(name="IntegrationQBDDataSyncLogs", indexes={@ORM\Index(name="IDX_87D6A9A8854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Integrationqbddatasynclogs
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDDataSyncLogID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbddatasynclogid;

    /**
     * @var bool
     *
     * @ORM\Column(name="PayrollOrBilling", type="boolean", nullable=false, options={"comment"="0=Payroll,1=Billing"})
     */
    private $payrollorbilling = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * Get integrationqbddatasynclogid.
     *
     * @return int
     */
    public function getIntegrationqbddatasynclogid()
    {
        return $this->integrationqbddatasynclogid;
    }

    /**
     * Set payrollorbilling.
     *
     * @param bool $payrollorbilling
     *
     * @return Integrationqbddatasynclogs
     */
    public function setPayrollorbilling($payrollorbilling)
    {
        $this->payrollorbilling = $payrollorbilling;

        return $this;
    }

    /**
     * Get payrollorbilling.
     *
     * @return bool
     */
    public function getPayrollorbilling()
    {
        return $this->payrollorbilling;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Integrationqbddatasynclogs
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
     * @return Integrationqbddatasynclogs
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
