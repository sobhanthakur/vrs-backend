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

/**
 * Class TranslationFiles
 * @package AppBundle\Service
 */
class TranslationFiles extends BaseService
{
    /**
     * @return array
     */
    public function GenerateTranslationFiles()
    {
        $response = [];
        $filePath = $this->serviceContainer->getParameter('filepath');
        try {
            $locales = $this->entityManager->getRepository('AppBundle:Locale')->findAll();
            foreach ($locales as $locale) {
                $details = $this->entityManager->getRepository('AppBundle:Translations')->GetTranslations($locale->getLocaleid());

                $temp = [];
                foreach ($details as $inner) {
                    $temp = array_merge($temp,array(
                        $inner['EnglishText'] => $inner['TranslatedText']
                    ));
                }
                $localPath = $filePath.$locale->getLocale().".json";
                $temp = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                }, json_encode($temp,JSON_PRETTY_PRINT));
                file_put_contents($localPath,stripslashes($temp));
                $response[$locale->getLocale()] = $this->SendToS3($localPath,$locale->getLocale());
            }
            return $response;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to Generate Files Due to:" .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        } finally {
            // Delete all json files from the server
            foreach (glob($filePath."*.json") as $filename) {
                unlink($filename);
            }
        }
    }

    /**
     * @param $localeID
     * @param $content
     * @return array
     */
    public function InsertToDB($localeID, $content)
    {
        try {
            $localeObj = $this->entityManager->getRepository('AppBundle:Locale')->find($localeID);
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
                    'LocaleID' => $localeObj,
                    'translationTextID' => $englishText
                ));
                if (!$translation) {
                    $translation = new Translations();
                    $translation->setTranslationLocaleID($localeObj);
                    $translation->setTranslationTextID($englishText);
                }

                $translation->setTranslatedtext($value);
                $this->entityManager->persist($translation);
                $this->entityManager->flush();
            }

            return array("Status" => "Success","Message" => "Translation Entries Created");

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Unable to Create translation entries in DB due to: " .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $filePath
     * @param $filename
     * @return mixed
     */
    public function SendToS3($filePath, $filename)
    {
        try {

            // aws Parameters
            $aws = $this->serviceContainer->getParameter('aws');

            // Connect to s3
            $s3 = new \Aws\S3\S3Client([
                'region'  => $aws['region'],
                'version' => 'latest',
                'credentials' => [
                    'key'    => $aws['key'],
                    'secret' => $aws['secret'],
                ]
            ]);

            // Push Image
            $result = $s3->putObject([
                'Bucket' => $aws['bucket_name'],
                'ACL' => 'public-read',
                'Key'    => 'locales/'.$filename.".json",
                'SourceFile' => $filePath
            ]);

            if ($result) {
                if ($result['@metadata']['statusCode'] === 200) {
                     return $result['@metadata']['effectiveUri'];
                }
            }

            return false;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error("Failed Uploading translation files to S3 due to: " .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

}