<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertygroups
 *
 * @ORM\Table(name="PropertyGroups", indexes={@ORM\Index(name="IDX_768E690F854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyGroupsRepository")
 */
class Propertygroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertygroupid;

    /**
     * @var string
     *
     * @ORM\Column(name="PropertyGroup", type="string", length=150, nullable=false)
     */
    private $propertygroup;

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
     * Get propertygroupid.
     *
     * @return int
     */
    public function getPropertygroupid()
    {
        return $this->propertygroupid;
    }

    /**
     * Set propertygroup.
     *
     * @param string $propertygroup
     *
     * @return Propertygroups
     */
    public function setPropertygroup($propertygroup)
    {
        $this->propertygroup = $propertygroup;

        return $this;
    }

    /**
     * Get propertygroup.
     *
     * @return string
     */
    public function getPropertygroup()
    {
        return $this->propertygroup;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Propertygroups
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
     * @return Propertygroups
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
