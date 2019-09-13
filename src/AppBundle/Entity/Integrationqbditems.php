<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integrationqbditems
 *
 * @ORM\Table(name="IntegrationQBDItems")
 * @ORM\Entity
 */
class Integrationqbditems
{
    /**
     * @var int
     *
     * @ORM\Column(name="IntegrationQBDItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $integrationqbditemid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CustomerID", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $customerid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="QBDItemListID", type="string", length=50, nullable=true)
     */
    private $qbditemlistid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="QBDItemFullName", type="string", length=100, nullable=true)
     */
    private $qbditemfullname;

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
     * Get integrationqbditemid.
     *
     * @return int
     */
    public function getIntegrationqbditemid()
    {
        return $this->integrationqbditemid;
    }

    /**
     * Set customerid.
     *
     * @param string|null $customerid
     *
     * @return Integrationqbditems
     */
    public function setCustomerid($customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return string|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set qbditemlistid.
     *
     * @param string|null $qbditemlistid
     *
     * @return Integrationqbditems
     */
    public function setQbditemlistid($qbditemlistid = null)
    {
        $this->qbditemlistid = $qbditemlistid;

        return $this;
    }

    /**
     * Get qbditemlistid.
     *
     * @return string|null
     */
    public function getQbditemlistid()
    {
        return $this->qbditemlistid;
    }

    /**
     * Set qbditemfullname.
     *
     * @param string|null $qbditemfullname
     *
     * @return Integrationqbditems
     */
    public function setQbditemfullname($qbditemfullname = null)
    {
        $this->qbditemfullname = $qbditemfullname;

        return $this;
    }

    /**
     * Get qbditemfullname.
     *
     * @return string|null
     */
    public function getQbditemfullname()
    {
        return $this->qbditemfullname;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Integrationqbditems
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
     * @return Integrationqbditems
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
}
