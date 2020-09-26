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
 * @ORM\Table(name="Locale")
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
}
