<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apikeystoapipublicresources
 *
 * @ORM\Table(name="APIKeysToAPIPublicResources", indexes={@ORM\Index(name="IDX_CA5905CCA366E37A", columns={"APIKeyID"}), @ORM\Index(name="IDX_CA5905CC8BC57D53", columns={"APIPublicResourceID"})})
 *  @ORM\Entity(repositoryClass="AppBundle\Repository\ApikeystoapipublicresourcesRepository")
 */
class Apikeystoapipublicresources
{
    /**
     * @var int
     *
     * @ORM\Column(name="APIKeyToAPIPublicResourceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $apikeytoapipublicresourceid;

    /**
     * @var int
     *
     * @ORM\Column(name="AccessLevel", type="integer", nullable=false, options={"comment"="0=none, 1= read only, 2= read and write"})
     */
    private $accesslevel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Apikeys
     *
     * @ORM\ManyToOne(targetEntity="Apikeys")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="APIKeyID", referencedColumnName="APIKeyID")
     * })
     */
    private $apikeyid;

    /**
     * @var \Apipublicresources
     *
     * @ORM\ManyToOne(targetEntity="Apipublicresources")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="APIPublicResourceID", referencedColumnName="APIPublicResourceID")
     * })
     */
    private $apipublicresourceid;



    /**
     * Get apikeytoapipublicresourceid.
     *
     * @return int
     */
    public function getApikeytoapipublicresourceid()
    {
        return $this->apikeytoapipublicresourceid;
    }

    /**
     * Set accesslevel.
     *
     * @param int $accesslevel
     *
     * @return Apikeystoapipublicresources
     */
    public function setAccesslevel($accesslevel)
    {
        $this->accesslevel = $accesslevel;

        return $this;
    }

    /**
     * Get accesslevel.
     *
     * @return int
     */
    public function getAccesslevel()
    {
        return $this->accesslevel;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Apikeystoapipublicresources
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
     * Set apikeyid.
     *
     * @param \AppBundle\Entity\Apikeys|null $apikeyid
     *
     * @return Apikeystoapipublicresources
     */
    public function setApikeyid(\AppBundle\Entity\Apikeys $apikeyid = null)
    {
        $this->apikeyid = $apikeyid;

        return $this;
    }

    /**
     * Get apikeyid.
     *
     * @return \AppBundle\Entity\Apikeys|null
     */
    public function getApikeyid()
    {
        return $this->apikeyid;
    }

    /**
     * Set apipublicresourceid.
     *
     * @param \AppBundle\Entity\Apipublicresources|null $apipublicresourceid
     *
     * @return Apikeystoapipublicresources
     */
    public function setApipublicresourceid(\AppBundle\Entity\Apipublicresources $apipublicresourceid = null)
    {
        $this->apipublicresourceid = $apipublicresourceid;

        return $this;
    }

    /**
     * Get apipublicresourceid.
     *
     * @return \AppBundle\Entity\Apipublicresources|null
     */
    public function getApipublicresourceid()
    {
        return $this->apipublicresourceid;
    }
}
