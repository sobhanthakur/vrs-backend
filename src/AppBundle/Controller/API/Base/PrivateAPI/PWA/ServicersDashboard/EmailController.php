<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 13/10/20
 * Time: 10:39 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\ServicersDashboard;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations\Post;

class EmailController extends FOSRestController
{
    /**
     * Email FE Errors
     * @SWG\Tag(name="Emails")
     * @Post("/email", name="vrs_pwa_error_email_fe")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Enter Request JSON",
     *         @SWG\Property(
     *              property="Data",
     *              example=
     *                  {
                            "Subject" : "Frontend error on frontend/app.vrscheduler.com",
                            "Error" : "Error_Message",
                            "Source" : "FE"
     *                  }
     *     )
     *  )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Send error mail",
     * )
     * @return array
     * @param Request $request
     */
    public function SendFEErrorMail(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $mailService = $this->container->get('vrscheduler.mail_service');
            $content = json_decode($request->getContent(),true);
            return $mailService->SendMailFunction($content);
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