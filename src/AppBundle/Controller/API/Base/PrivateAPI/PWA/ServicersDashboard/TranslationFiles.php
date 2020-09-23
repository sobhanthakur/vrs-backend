<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/9/20
 * Time: 11:37 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\ServicersDashboard;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class TranslationFiles extends FOSRestController
{
    /**
     * Generate Translation Files
     * @SWG\Tag(name="Translations")
     * @Get("/translations", name="vrs_pwa_translation")
     * @SWG\Response(
     *     response=200,
     *     description="Generates and json files",
     * )
     * @return array
     * @param Request $request
     */
    public function GenerateTranslationFiles(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $translationService = $this->container->get('vrscheduler.translation_files');
            return $translationService->GenerateTranslationFiles();
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Insert Translation Request
     * @SWG\Tag(name="Translations")
     * @Post("/translations", name="vrs_pwa_translation_post")
     * @SWG\Parameter(
     *     name="TranslationLocaleID",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Enter TranslationLocaleID"
     *  )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Enter Request JSON",
     *         @SWG\Property(
     *              property="Data",
     *              example=
     *                  {
                            "Clock In" : "Iniciar Tempo",
                            "Clock Out": "Parar  Tempo",
                            "Start Task": "Iniciar tarefa",
                            "Start Date": "Data de início",
                            "End Date": "Data final",
                            "Pause Task Timer": "Pausar Temporizador de Tarefa",
                            "Accept": "Aceitar",
                            "Decline": "Declínio",
                            "Overdue": "Atrasado"
     *                  }
     *     )
     *  )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Generates and json files",
     * )
     * @return array
     * @param Request $request
     */
    public function CreateTranslationEntries(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $translationService = $this->container->get('vrscheduler.translation_files');
            $localeID = $request->get('TranslationLocaleID');
            $content = json_decode($request->getContent(),true);
            return $translationService->InsertToDB($localeID,$content);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . GeneralConstants::FUNCTION_LOG .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}