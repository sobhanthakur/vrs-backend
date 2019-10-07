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
use FOS\RestBundle\Controller\Annotations\Options;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
}