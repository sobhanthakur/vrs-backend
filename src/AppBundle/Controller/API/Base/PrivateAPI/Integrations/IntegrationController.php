<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 9:48 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Integrations;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
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
     * @SWG\Tag(name="IntegrationDetails")
     * * @SWG\Response(
     *     response=200,
     *     description="Shows list of installed + available integrations"
     * )
     * @return array
     * @param Request $request
     * @Get("/integrations", name="vrs_integrations_get")
     */
    public function ListIntegrations(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $integrationService = $this->container->get(GeneralConstants::INTEGRATION_SERVICE);
            return $integrationService->GetAllIntegrations($request->attributes->get(GeneralConstants::AUTHPAYLOAD));
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
     * Install/Updates Quickbooks Integration.
     * @SWG\Tag(name="IntegrationDetails")
     * @Put("/qwc/register", name="vrs_qwc_register_put")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="For Version: 0=Quickbooks-Enterprise,1=Quickbooks-Pro,2=Quickbooks-Online",
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
     *         ),
     *        @SWG\Property(
     *              property="TimeTrackingType",
     *              type="String",
     *              example="0"
     *         ),
     *         @SWG\Property(
     *              property="RealmID",
     *              type="string",
     *              example="123146180022419"
     *         ),
     *         @SWG\Property(
     *              property="Version",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="Type",
     *              type="integer",
     *              example=0
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Creates/Updates the integration info for the customer",
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
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $integrationService = $this->container->get(GeneralConstants::INTEGRATION_SERVICE);
            $content = json_decode($request->getContent(),true);
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            return $integrationService->UpdateQuickbooksDesktop($content,$customerID);
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
     * Disconnect Quickbooks Integration.
     * @SWG\Tag(name="IntegrationDetails")
     * @Put("/qbddisconnect", name="vrs_qwd_disconnect")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="IntegrationID",
     *              type="integer",
     *              example=1
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Disconnects QBD Integration",
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
    public function DisconnectQBDIntegration(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $integrationService = $this->container->get(GeneralConstants::INTEGRATION_SERVICE);
            $content = json_decode($request->getContent(),true);
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            return $integrationService->DisconnectQBD($customerID,$content);
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