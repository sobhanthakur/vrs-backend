<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apikeys
 *
 * @ORM\Table(name="APIKeys", indexes={@ORM\Index(name="IDX_1385A7B2854CF4BD", columns={"CustomerID"})})
 * @ORM\Entity
 */
class Apikeys
{
    /**
     * @var int
     *
     * @ORM\Column(name="APIKeyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $apikeyid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="APIKeyName", type="string", length=100, nullable=true)
     */
    private $apikeyname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="APIKey", type="text", length=-1, nullable=true)
     */
    private $apikey;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Value", type="text", length=-1, nullable=true)
     */
    private $value;

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
     * Get apikeyid.
     *
     * @return int
     */
    public function getApikeyid()
    {
        return $this->apikeyid;
    }

    /**
     * Set apikeyname.
     *
     * @param string|null $apikeyname
     *
     * @return Apikeys
     */
    public function setApikeyname($apikeyname = null)
    {
        $this->apikeyname = $apikeyname;

        return $this;
    }

    /**
     * Get apikeyname.
     *
     * @return string|null
     */
    public function getApikeyname()
    {
        return $this->apikeyname;
    }

    /**
     * Set apikey.
     *
     * @param string|null $apikey
     *
     * @return Apikeys
     */
    public function setApikey($apikey = null)
    {
        $this->apikey = $apikey;

        return $this;
    }

    /**
     * Get apikey.
     *
     * @return string|null
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * Set value.
     *
     * @param string|null $value
     *
     * @return Apikeys
     */
    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Apikeys
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
     * @return Apikeys
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
