<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employeegroups
 *
 * @ORM\Table(name="EmployeeGroups", indexes={@ORM\Index(name="CustomerID", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Employeegroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="EmployeeGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $employeegroupid;

    /**
     * @var string
     *
     * @ORM\Column(name="EmployeeGroup", type="string", length=150, nullable=false)
     */
    private $employeegroup;

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
     * Get employeegroupid.
     *
     * @return int
     */
    public function getEmployeegroupid()
    {
        return $this->employeegroupid;
    }

    /**
     * Set employeegroup.
     *
     * @param string $employeegroup
     *
     * @return Employeegroups
     */
    public function setEmployeegroup($employeegroup)
    {
        $this->employeegroup = $employeegroup;

        return $this;
    }

    /**
     * Get employeegroup.
     *
     * @return string
     */
    public function getEmployeegroup()
    {
        return $this->employeegroup;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Employeegroups
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
     * @return Employeegroups
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
