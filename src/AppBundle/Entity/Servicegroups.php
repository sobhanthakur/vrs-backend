<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicegroups
 *
 * @ORM\Table(name="ServiceGroups", indexes={@ORM\Index(name="customerid", columns={"CustomerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceGroupRepository")
 */
class Servicegroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServiceGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicegroupid;

    /**
     * @var string
     *
     * @ORM\Column(name="ServiceGroup", type="string", length=150, nullable=false)
     */
    private $servicegroup;

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
     * Get servicegroupid.
     *
     * @return int
     */
    public function getServicegroupid()
    {
        return $this->servicegroupid;
    }

    /**
     * Set servicegroup.
     *
     * @param string $servicegroup
     *
     * @return Servicegroups
     */
    public function setServicegroup($servicegroup)
    {
        $this->servicegroup = $servicegroup;

        return $this;
    }

    /**
     * Get servicegroup.
     *
     * @return string
     */
    public function getServicegroup()
    {
        return $this->servicegroup;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Servicegroups
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
     * @return Servicegroups
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
