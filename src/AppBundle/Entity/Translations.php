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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TranslationsRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(name="TranslatedText", type="string", length=50, nullable=false)
     */
    private $translatedtext;

    /**
     * @var Locale
     *
     * @ORM\ManyToOne(targetEntity="Locale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LocaleID", referencedColumnName="LocaleID")
     * })
     */
    private $LocaleID;

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
     * @param \AppBundle\Entity\Locale|null $translationLocaleID
     *
     * @return Translations
     */
    public function setTranslationLocaleID(\AppBundle\Entity\Locale $translationLocaleID = null)
    {
        $this->translationLocaleID = $translationLocaleID;

        return $this;
    }

    /**
     * Get translationLocaleID.
     *
     * @return \AppBundle\Entity\Locale|null
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

    /**
     * Set translatedtext.
     *
     * @param string $translatedtext
     *
     * @return Translations
     */
    public function setTranslatedtext($translatedtext)
    {
        $this->translatedtext = $translatedtext;

        return $this;
    }

    /**
     * Get translatedtext.
     *
     * @return string
     */
    public function getTranslatedtext()
    {
        return $this->translatedtext;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedate() == null) {
            $datetime = new \DateTime('now', new \DateTimeZone('UTC'));
            $this->setCreatedate($datetime);
        }
    }
}
