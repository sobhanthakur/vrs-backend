<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertybookingstoservices
 *
 * @ORM\Table(name="PropertyBookingsToServices", indexes={@ORM\Index(name="CustomerID", columns={"CustomerID"}), @ORM\Index(name="ServiceID", columns={"ServiceID"}), @ORM\Index(name="IDX_CD2F2E10CC6341F", columns={"PropertyBookingID"})})
 * @ORM\Entity
 */
class Propertybookingstoservices
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyBookingToServiceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertybookingtoserviceid;

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
     * @var \Propertybookings
     *
     * @ORM\ManyToOne(targetEntity="Propertybookings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyBookingID", referencedColumnName="PropertyBookingID")
     * })
     */
    private $propertybookingid;

    /**
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceID", referencedColumnName="ServiceID")
     * })
     */
    private $serviceid;



    /**
     * Get propertybookingtoserviceid.
     *
     * @return int
     */
    public function getPropertybookingtoserviceid()
    {
        return $this->propertybookingtoserviceid;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Propertybookingstoservices
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
     * @return Propertybookingstoservices
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
     * Set propertybookingid.
     *
     * @param \AppBundle\Entity\Propertybookings|null $propertybookingid
     *
     * @return Propertybookingstoservices
     */
    public function setPropertybookingid(\AppBundle\Entity\Propertybookings $propertybookingid = null)
    {
        $this->propertybookingid = $propertybookingid;

        return $this;
    }

    /**
     * Get propertybookingid.
     *
     * @return \AppBundle\Entity\Propertybookings|null
     */
    public function getPropertybookingid()
    {
        return $this->propertybookingid;
    }

    /**
     * Set serviceid.
     *
     * @param \AppBundle\Entity\Services|null $serviceid
     *
     * @return Propertybookingstoservices
     */
    public function setServiceid(\AppBundle\Entity\Services $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid.
     *
     * @return \AppBundle\Entity\Services|null
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
}
