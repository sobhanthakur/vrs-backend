<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/9/20
 * Time: 11:24 AM
 */

namespace AppBundle\Service;


use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Locale;
use AppBundle\Entity\Translations;
use AppBundle\Entity\TranslationTexts;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TranslationService
 * @package AppBundle\Service
 */
class TranslationService extends BaseService
{

    /**
     * @return mixed
     */
    public function GetEnglishTexts()
    {
        try {
            // Add Pagination
            // Remove this in future
            /*$limit = 20;
            $offset = 1;
            if (array_key_exists(GeneralConstants::PAGINATION,$data)) {
                $pagination = $data[GeneralConstants::PAGINATION];
                $limit = $pagination['Limit'];
                $offset = $pagination['Offset'];
            }*/

            return $this->entityManager->getRepository('AppBundle:TranslationTexts')->EnglishTexts();

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to fetch English Texts Due to:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @return mixed
     */
    public function GetLocales()
    {
        try {
            return $this->entityManager->getRepository('AppBundle:Locale')->GetLocales();
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to fetch English Texts Due to:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $id
     * @param $data
     * @return array
     */
    public function GetLocalesByID($id)
    {
        try {
            $response = [];

//            Remove Pagination
//            Remove comments in future
            /*$limit = 20;
            $offset = 1;
            if (array_key_exists(GeneralConstants::PAGINATION,$data)) {
                $pagination = $data[GeneralConstants::PAGINATION];
                $limit = $pagination['Limit'];
                $offset = $pagination['Offset'];
            }*/

            $english = $this->entityManager->getRepository('AppBundle:TranslationTexts')->EnglishTexts();
            foreach ($english as $eng) {
                $translationID = null;
                $translatedText = null;
                $translation = $this->entityManager->getRepository('AppBundle:Translations')->findOneBy(array(
                    'translationTextID' => $eng['TranslationTextID'],
                    'LocaleID' => $id
                ));
                if ($translation) {
                       $translationID = $translation->getTranslationid();
                       $translatedText = $translation->getTranslatedtext();
                }

                $response[] = array(
                    "EnglishTextID" => $eng['TranslationTextID'],
                    "EnglishText" => $eng['EnglishText'],
                    "TranslationID" => $translationID,
                    "TranslatedText" => $translatedText
                );
            }

            return $response;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to fetch English Texts Due to:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @return array
     */
    public function UpdateTranslation($content)
    {
        $translation = null;
        try {
            $locale = $this->entityManager->getRepository('AppBundle:Locale')->findOneBy(array(
                'localeid' => $content['LocaleID'],
                'activeforlanguages' => true
            ));
            if (!$locale) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_LOCALE_ID);
            }

            $englishText = $this->entityManager->getRepository('AppBundle:TranslationTexts')->find($content['EnglishTextID']);
            if (!$englishText) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_ENGLISHTEXT_ID);
            }

            if (array_key_exists('TranslationID',$content) && $content['TranslationID']) {
                // Update translationID
                $translation = $this->entityManager->getRepository('AppBundle:Translations')->find($content['TranslationID']);
                if (!$translation) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TRANSLATION_ID);
                }

                $translation->setTranslatedtext($content['TranslatedText']);
                $this->entityManager->persist($translation);
            } else {
                // Create New Translation
                $translation = new Translations();
                $translation->setTranslationLocaleID($locale);
                $translation->setTranslationTextID($englishText);
                $translation->setTranslatedtext($content['TranslatedText']);
                $this->entityManager->persist($translation);
            }
            $this->entityManager->flush();
            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => "Success",
                "TranslationID" => $translation->getTranslationid()
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to Update Translation:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @return array
     */
    public function AddNewLocale($content)
    {
        try {
            $locale = $this->entityManager->getRepository('AppBundle:Locale')->findOneBy(array(
                'locale' => $content['Locale'],
                'activeforlanguages' => true
            ));
            if ($locale) {
                throw new UnprocessableEntityHttpException(ErrorConstants::LOCALE_EXISTS);
            }

            $locale = $content['Locale'];
            $localeReadable = $content['LocaleReadable'];
            $activeForDates = $content['ActiveForDates'] ? (int)$content['ActiveForDates'] === 1 : 0;
            $activeForLanguages = true;

            $localeID = new Locale();
            $localeID->setActivefordates($activeForDates);
            $localeID->setActiveforlanguages($activeForLanguages);
            $localeID->setLocale($locale);
            $localeID->setLocalereadable($localeReadable);
            $this->entityManager->persist($localeID);
            $this->entityManager->flush();

            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => "Success",
                'LocaleID' => $localeID->getLocaleid()
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to add Locale due to :" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @return array
     */
    public function AddNewEnglishText($content)
    {
        try {
            $english = $this->entityManager->getRepository('AppBundle:TranslationTexts')->findOneBy(array(
                'englishtext' => $content['EnglishText']
            ));
            if ($english) {
                throw new UnprocessableEntityHttpException(ErrorConstants::ENGLISH_EXISTS);
            }

            $english = $content['EnglishText'];

            $translationText = new TranslationTexts();
            $translationText->setEnglishtext($english);
            $this->entityManager->persist($translationText);
            $this->entityManager->flush();

            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => "Success",
                'TranslationTextID' => $translationText->getTranslationtextid()
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to add new word due to: " .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}