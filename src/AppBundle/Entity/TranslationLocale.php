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
 * TranslationLocale
 *
 * @ORM\Table(name="TranslationLocale")
 * @ORM\Entity
 */
class TranslationLocale
{
    /**
     * @var int
     *
     * @ORM\Column(name="TranslationLocaleID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $translationlocaleid;

    /**
     * @var string
     *
     * @ORM\Column(name="TranslationLocale", type="string", length=50, nullable=false)
     */
    private $translationlocale;

    /**
     * Get translationlocaleid.
     *
     * @return int
     */
    public function getTranslationlocaleid()
    {
        return $this->translationlocaleid;
    }

    /**
     * Set translationlocale.
     *
     * @param string $translationlocale
     *
     * @return TranslationLocale
     */
    public function setTranslationlocale($translationlocale)
    {
        $this->translationlocale = $translationlocale;

        return $this;
    }

    /**
     * Get translationlocale.
     *
     * @return string
     */
    public function getTranslationlocale()
    {
        return $this->translationlocale;
    }
}
