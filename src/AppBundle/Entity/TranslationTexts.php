<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/9/20
 * Time: 11:25 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TranslationTexts
 *
 * @ORM\Table(name="TranslationTexts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TranslationTextsRepository")
 */
class TranslationTexts
{

    /**
     * @var int
     *
     * @ORM\Column(name="TranslationTextID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $translationtextid;

    /**
     * @var string
     *
     * @ORM\Column(name="EnglishText", type="string", length=50, nullable=false)
     */
    private $englishtext;

    /**
     * Get translationtextid.
     *
     * @return int
     */
    public function getTranslationtextid()
    {
        return $this->translationtextid;
    }

    /**
     * Set englishtext.
     *
     * @param string $englishtext
     *
     * @return TranslationTexts
     */
    public function setEnglishtext($englishtext)
    {
        $this->englishtext = $englishtext;

        return $this;
    }

    /**
     * Get englishtext.
     *
     * @return string
     */
    public function getEnglishtext()
    {
        return $this->englishtext;
    }
}
