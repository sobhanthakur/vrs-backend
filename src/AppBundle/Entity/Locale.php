<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/9/20
 * Time: 11:18 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Locale
 *
 * @ORM\Table(name="Locales")
 * @ORM\Entity
 */
class Locale
{
    /**
     * @var int
     *
     * @ORM\Column(name="LocaleID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $localeid;

    /**
     * @var string
     *
     * @ORM\Column(name="Locale", type="string", length=50, nullable=false)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="LocaleReadable", type="string", length=100, nullable=true)
     */
    private $localereadable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ActiveForDates", type="boolean", nullable=false)
     */
    private $activefordates = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ActiveForLanguages", type="boolean", nullable=false)
     */
    private $activeforlanguages = false;

    /**
     * Get localeid.
     *
     * @return int
     */
    public function getLocaleid()
    {
        return $this->localeid;
    }

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return Locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set localereadable.
     *
     * @param string|null $localereadable
     *
     * @return Locale
     */
    public function setLocalereadable($localereadable = null)
    {
        $this->localereadable = $localereadable;

        return $this;
    }

    /**
     * Get localereadable.
     *
     * @return string|null
     */
    public function getLocalereadable()
    {
        return $this->localereadable;
    }

    /**
     * Set activefordates.
     *
     * @param bool $activefordates
     *
     * @return Locale
     */
    public function setActivefordates($activefordates)
    {
        $this->activefordates = $activefordates;

        return $this;
    }

    /**
     * Get activefordates.
     *
     * @return bool
     */
    public function getActivefordates()
    {
        return $this->activefordates;
    }

    /**
     * Set activeforlanguages.
     *
     * @param bool $activeforlanguages
     *
     * @return Locale
     */
    public function setActiveforlanguages($activeforlanguages)
    {
        $this->activeforlanguages = $activeforlanguages;

        return $this;
    }

    /**
     * Get activeforlanguages.
     *
     * @return bool
     */
    public function getActiveforlanguages()
    {
        return $this->activeforlanguages;
    }
}
