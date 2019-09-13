<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerstoemployeegroups
 *
 * @ORM\Table(name="ServicersToEmployeeGroups", indexes={@ORM\Index(name="employeegroupid", columns={"EmployeeGroupID"}), @ORM\Index(name="servicerid", columns={"ServicerID"})})
 * @ORM\Entity
 */
class Servicerstoemployeegroups
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServicerToEmployeeGroupID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicertoemployeegroupid;

    /**
     * @var \Employeegroups
     *
     * @ORM\ManyToOne(targetEntity="Employeegroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EmployeeGroupID", referencedColumnName="EmployeeGroupID")
     * })
     */
    private $employeegroupid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;



    /**
     * Get servicertoemployeegroupid.
     *
     * @return int
     */
    public function getServicertoemployeegroupid()
    {
        return $this->servicertoemployeegroupid;
    }

    /**
     * Set employeegroupid.
     *
     * @param \AppBundle\Entity\Employeegroups|null $employeegroupid
     *
     * @return Servicerstoemployeegroups
     */
    public function setEmployeegroupid(\AppBundle\Entity\Employeegroups $employeegroupid = null)
    {
        $this->employeegroupid = $employeegroupid;

        return $this;
    }

    /**
     * Get employeegroupid.
     *
     * @return \AppBundle\Entity\Employeegroups|null
     */
    public function getEmployeegroupid()
    {
        return $this->employeegroupid;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Servicerstoemployeegroups
     */
    public function setServicerid(\AppBundle\Entity\Servicers $servicerid = null)
    {
        $this->servicerid = $servicerid;

        return $this;
    }

    /**
     * Get servicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }
}
