<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timezones
 *
 * @ORM\Table(name="TimeZones")
 * @ORM\Entity
 */
class Timezones
{
    /**
     * @var int
     *
     * @ORM\Column(name="TimeZoneID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $timezoneid;

    /**
     * @var float
     *
     * @ORM\Column(name="TimeZone", type="float", precision=53, scale=0, nullable=false)
     */
    private $timezone;

    /**
     * @var string
     *
     * @ORM\Column(name="Region", type="string", length=100, nullable=false)
     */
    private $region;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Abbreviation", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $abbreviation;

    /**
     * @var float|null
     *
     * @ORM\Column(name="AddlValue", type="float", precision=53, scale=0, nullable=true)
     */
    private $addlvalue;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=true, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';



    /**
     * Get timezoneid.
     *
     * @return int
     */
    public function getTimezoneid()
    {
        return $this->timezoneid;
    }

    /**
     * Set timezone.
     *
     * @param float $timezone
     *
     * @return Timezones
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone.
     *
     * @return float
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set region.
     *
     * @param string $region
     *
     * @return Timezones
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set abbreviation.
     *
     * @param string|null $abbreviation
     *
     * @return Timezones
     */
    public function setAbbreviation($abbreviation = null)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string|null
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set addlvalue.
     *
     * @param float|null $addlvalue
     *
     * @return Timezones
     */
    public function setAddlvalue($addlvalue = null)
    {
        $this->addlvalue = $addlvalue;

        return $this;
    }

    /**
     * Get addlvalue.
     *
     * @return float|null
     */
    public function getAddlvalue()
    {
        return $this->addlvalue;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Timezones
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
}
