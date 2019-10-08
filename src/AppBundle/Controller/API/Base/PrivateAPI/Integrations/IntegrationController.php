<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 9:48 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Integrations;

use AppBundle\Constants\ErrorConstants;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Options;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class IntegrationController extends FOSRestController
{
    /**
     * Get List Of Available and Installed integrations
     * @return array
     * @param Request $request
     * @Get("/integrations", name="vrs_integrations_get")
     */
    public function ListIntegrations(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $response = null;
        try {
            $integrationService = $this->container->get('vrscheduler.integration_service');
            $response = $integrationService->GetAllIntegrations($request->attributes->get('AuthPayload'));
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $response;
    }

    /**
     * Install/Updates Quickbooks Integration.
     * @Post("/qwc/register", name="vrs_qwc_register_post")
     * @Put("/qwc/register", name="vrs_qwc_register_put")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="StartDate",
     *              type="string",
     *              example="2019-10-15"
     *         ),
     *         @SWG\Property(
     *              property="IntegrationID",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="Password",
     *              type="string",
     *              example="Password"
     *         ),
     *         @SWG\Property(
     *              property="QBDSyncBilling",
     *              type="boolean",
     *              example=true
     *         ),
     *         @SWG\Property(
     *              property="QBDSyncTimeTracking",
     *              type="boolean",
     *              example=false
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Send Success ReasonText with 200 status code",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          ),
     *          @SWG\Property(
     *              property="ReasonText",
     *              type="string",
     *              example="Success"
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function QBDIntegration(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $response = null;
        try {
            $integrationService = $this->container->get('vrscheduler.integration_service');
            $method = $request->getMethod();
            $content = json_decode($request->getContent(),true);
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            switch ($method) {
                case 'POST' :
                    $response = $integrationService->InstallQuickbooksDesktop($content,$customerID);
                    break;
                case 'PUT':
                    $response = $integrationService->UpdateQuickbooksDesktop($content,$customerID);
                    break;
            }
            return $response;
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $response;
    }
}