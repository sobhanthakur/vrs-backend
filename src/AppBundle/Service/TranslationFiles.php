<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/9/20
 * Time: 11:41 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Entity\Translations;
use AppBundle\Entity\TranslationTexts;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TranslationFiles extends BaseService
{
    public function GenerateTranslationFiles()
    {
        try {

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to Generate Files Due to:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    public function InsertToDB($localeID,$content)
    {
        try {
            $localeObj = $this->entityManager->getRepository('AppBundle:TranslationLocale')->find($localeID);
            if (!$localeObj) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_LOCALE_ID);
            }

            foreach ($content as $key => $value) {
                // Check if english text exists.
                $englishText = $this->entityManager->getRepository('AppBundle:TranslationTexts')->findOneBy(array('englishtext'=>$key));
                if (!$englishText) {
                    // Create new Entry if the english word doesn't exist
                    $englishText = new TranslationTexts();
                    $englishText->setEnglishtext($key);
                    $this->entityManager->persist($englishText);
                    $this->entityManager->flush();
                }

                // Check if localeObj and englishText exists in the translations Table
                $translation = $this->entityManager->getRepository('AppBundle:Translations')->findOneBy(array(
                    'translationLocaleID' => $localeObj,
                    'translationTextID' => $englishText
                ));
                if (!$translation) {
                    $translation = new Translations();
                    $translation->setTranslationLocaleID($localeObj);
                    $translation->setTranslationTextID($englishText);
                }

                $translation->setTranslatedtext($value);
                $this->entityManager->persist($translation);
            }

            $this->entityManager->flush();

            return array("Status" => "Success","Message" => "Translation Entries Created");

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to Generate Files Due to:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

}