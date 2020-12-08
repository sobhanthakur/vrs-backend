<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 8/12/20
 * Time: 12:22 PM
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

class SMSController extends FOSRestController
{
    /**
     * Send SMS
     * @SWG\Tag(name="SMS")
     * @Post("/sms", name="vrs_aws_sns")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Enter Request JSON",
     *         @SWG\Property(
     *              property="Data",
     *              example=
     *                  {
                            "PhoneNumber" : "+1-4845219760",
                            "Message" : "Text Message"
     *                  }
     *     )
     *  )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Send SMS",
     * )
     * @return array
     * @param Request $request
     */
    public function SendSMS(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $smsService = $this->container->get('vrscheduler.sms_service');
            $content = json_decode($request->getContent(),true);
            return $smsService->SendSMS($content);
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