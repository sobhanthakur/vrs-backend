<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apipublicresources
 *
 * @ORM\Table(name="APIPublicResources")
 * @ORM\Entity
 */
class Apipublicresources
{
    /**
     * @var int
     *
     * @ORM\Column(name="APIPublicResourceID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $apipublicresourceid;

    /**
     * @var string
     *
     * @ORM\Column(name="ResourceName", type="string", length=100, nullable=false)
     */
    private $resourcename;

    /**
     * @var bool
     *
     * @ORM\Column(name="HasWrite", type="boolean", nullable=false)
     */
    private $haswrite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';



    /**
     * Get apipublicresourceid.
     *
     * @return int
     */
    public function getApipublicresourceid()
    {
        return $this->apipublicresourceid;
    }

    /**
     * Set resourcename.
     *
     * @param string $resourcename
     *
     * @return Apipublicresources
     */
    public function setResourcename($resourcename)
    {
        $this->resourcename = $resourcename;

        return $this;
    }

    /**
     * Get resourcename.
     *
     * @return string
     */
    public function getResourcename()
    {
        return $this->resourcename;
    }

    /**
     * Set haswrite.
     *
     * @param bool $haswrite
     *
     * @return Apipublicresources
     */
    public function setHaswrite($haswrite)
    {
        $this->haswrite = $haswrite;

        return $this;
    }

    /**
     * Get haswrite.
     *
     * @return bool
     */
    public function getHaswrite()
    {
        return $this->haswrite;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Apipublicresources
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
