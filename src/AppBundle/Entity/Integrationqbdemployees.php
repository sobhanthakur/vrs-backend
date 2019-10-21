<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbdemployees
 *
 * @ORM\Table(name="IntegrationQBDEmployees", indexes={@ORM\Index(name="IDX_33A27136854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegrationqbdemployeesRepository")
 */
class Integrationqbdemployees
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDEmployeeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbdemployeeid;

    /**
     * @var string
     *
     * @ORM\Column(name="QBDEmployeeListID", type="string", length=50, nullable=false)
     */
    private $qbdemployeelistid;

    /**
     * @var string
     *
     * @ORM\Column(name="QBDEmployeeFullName", type="string", length=100, nullable=false)
     */
    private $qbdemployeefullname;

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
     * Get integrationqbdemployeeid.
     *
     * @return int
     */
    public function getIntegrationqbdemployeeid()
    {
        return $this->integrationqbdemployeeid;
    }

    /**
     * Set qbdemployeelistid.
     *
     * @param string $qbdemployeelistid
     *
     * @return Integrationqbdemployees
     */
    public function setQbdemployeelistid($qbdemployeelistid)
    {
        $this->qbdemployeelistid = $qbdemployeelistid;

        return $this;
    }

    /**
     * Get qbdemployeelistid.
     *
     * @return string
     */
    public function getQbdemployeelistid()
    {
        return $this->qbdemployeelistid;
    }

    /**
     * Set qbdemployeefullname.
     *
     * @param string $qbdemployeefullname
     *
     * @return Integrationqbdemployees
     */
    public function setQbdemployeefullname($qbdemployeefullname)
    {
        $this->qbdemployeefullname = $qbdemployeefullname;

        return $this;
    }

    /**
     * Get qbdemployeefullname.
     *
     * @return string
     */
    public function getQbdemployeefullname()
    {
        return $this->qbdemployeefullname;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Integrationqbdemployees
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
     * @return Integrationqbdemployees
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
     * @return Integrationqbdemployees
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
