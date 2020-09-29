<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/9/20
 * Time: 11:24 AM
 */

namespace AppBundle\Service;


use AppBundle\Constants\ErrorConstants;
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
     * @return mixed
     */
    public function GetLocalesByID($id)
    {
        try {
            $response = [];
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
}