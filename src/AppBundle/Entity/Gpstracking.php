<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gpstracking
 *
 * @ORM\Table(name="GPSTracking", indexes={@ORM\Index(name="IDX_521799583C7E7BEF", columns={"ServicerID"})})
 * @ORM\Entity
 */
class Gpstracking
{
    /**
     * @var int
     *
     * @ORM\Column(name="GPSTrackingID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $gpstrackingid;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsMobile", type="boolean", nullable=false)
     */
    private $ismobile = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="UserAgent", type="text", length=-1, nullable=true)
     */
    private $useragent;

    /**
     * @var float
     *
     * @ORM\Column(name="Latitude", type="float", precision=53, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="Longitude", type="float", precision=53, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Accuracy", type="float", precision=53, scale=0, nullable=true)
     */
    private $accuracy;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * Get gpstrackingid.
     *
     * @return int
     */
    public function getGpstrackingid()
    {
        return $this->gpstrackingid;
    }

    /**
     * Set ismobile.
     *
     * @param bool $ismobile
     *
     * @return Gpstracking
     */
    public function setIsmobile($ismobile)
    {
        $this->ismobile = $ismobile;

        return $this;
    }

    /**
     * Get ismobile.
     *
     * @return bool
     */
    public function getIsmobile()
    {
        return $this->ismobile;
    }

    /**
     * Set useragent.
     *
     * @param string|null $useragent
     *
     * @return Gpstracking
     */
    public function setUseragent($useragent = null)
    {
        $this->useragent = $useragent;

        return $this;
    }

    /**
     * Get useragent.
     *
     * @return string|null
     */
    public function getUseragent()
    {
        return $this->useragent;
    }

    /**
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return Gpstracking
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return Gpstracking
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set accuracy.
     *
     * @param float|null $accuracy
     *
     * @return Gpstracking
     */
    public function setAccuracy($accuracy = null)
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    /**
     * Get accuracy.
     *
     * @return float|null
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Gpstracking
     */
    public function setCreatedate($createdate = null)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime|null
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Gpstracking
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
