<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Countries
 *
 * @ORM\Table(name="Countries")
 * @ORM\Entity
 */
class Countries
{
    /**
     * @var int
     *
     * @ORM\Column(name="CountryID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countryid;

    /**
     * @var string
     *
     * @ORM\Column(name="CountryPhoneCode", type="string", length=10, nullable=false, options={"fixed"=true})
     */
    private $countryphonecode;

    /**
     * @var string
     *
     * @ORM\Column(name="Country", type="string", length=100, nullable=false)
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false, options={"default"="1"})
     */
    private $sortorder = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';



    /**
     * Get countryid.
     *
     * @return int
     */
    public function getCountryid()
    {
        return $this->countryid;
    }

    /**
     * Set countryphonecode.
     *
     * @param string $countryphonecode
     *
     * @return Countries
     */
    public function setCountryphonecode($countryphonecode)
    {
        $this->countryphonecode = $countryphonecode;

        return $this;
    }

    /**
     * Get countryphonecode.
     *
     * @return string
     */
    public function getCountryphonecode()
    {
        return $this->countryphonecode;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Countries
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Countries
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Countries
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
