<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/9/20
 * Time: 11:27 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Translations
 *
 * @ORM\Table(name="Translations")
 * @ORM\Entity
 */
class Translations
{

    /**
     * @var int
     *
     * @ORM\Column(name="TranlationID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $translationid;

    /**
     * @var string
     *
     * @ORM\Column(name="Translation", type="string", length=50, nullable=false)
     */
    private $translation;

    /**
     * @var TranslationLocale
     *
     * @ORM\ManyToOne(targetEntity="TranslationLocale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TranslationLocaleID", referencedColumnName="TranslationLocaleID")
     * })
     */
    private $translationLocaleID;

    /**
     * @var TranslationTexts
     *
     * @ORM\ManyToOne(targetEntity="TranslationTexts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TranslationTextID", referencedColumnName="TranslationTextID")
     * })
     */
    private $translationTextID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false)
     */
    private $createdate;

    /**
     * Get translationid.
     *
     * @return int
     */
    public function getTranslationid()
    {
        return $this->translationid;
    }

    /**
     * Set translation.
     *
     * @param string $translation
     *
     * @return Translations
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation.
     *
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Translations
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
     * Set translationLocaleID.
     *
     * @param \AppBundle\Entity\TranslationLocale|null $translationLocaleID
     *
     * @return Translations
     */
    public function setTranslationLocaleID(\AppBundle\Entity\TranslationLocale $translationLocaleID = null)
    {
        $this->translationLocaleID = $translationLocaleID;

        return $this;
    }

    /**
     * Get translationLocaleID.
     *
     * @return \AppBundle\Entity\TranslationLocale|null
     */
    public function getTranslationLocaleID()
    {
        return $this->translationLocaleID;
    }

    /**
     * Set translationTextID.
     *
     * @param \AppBundle\Entity\TranslationTexts|null $translationTextID
     *
     * @return Translations
     */
    public function setTranslationTextID(\AppBundle\Entity\TranslationTexts $translationTextID = null)
    {
        $this->translationTextID = $translationTextID;

        return $this;
    }

    /**
     * Get translationTextID.
     *
     * @return \AppBundle\Entity\TranslationTexts|null
     */
    public function getTranslationTextID()
    {
        return $this->translationTextID;
    }
}
