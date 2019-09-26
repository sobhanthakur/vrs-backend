<?php
/**
 * Created by Sobhan Thakur.
 * Date: 11/9/19
 * Time: 6:38 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Security;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Options;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AuthController
 * @package AppBundle\Controller\API\Base\PrivateAPI\Security
 */
class AuthController extends FOSRestController
{
    /**
     * @return null
     * @param Request $request
     * @Post("/oauth/validate", name="oauth_validate_post")
     * @Options("/oauth/validate", name="oauth_validate_options")
     */
    public function ValidateAuthToken(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $authService = $this->container->get('vrscheduler.authentication_service');
        $response = null;
        try {
            $authenticateResult = $authService->VerifyAuthToken($request);
            if($authenticateResult['status']) {
                //Get Restrictions
                $validateResponse = $authService->ValidateRestrictions($authenticateResult);

                //Get New Token with one hour validity
                $newToken = $authService->CreateNewToken($authenticateResult);
                $validateResponse['AccessToken'] = $newToken;

                //Structure the API response
                $authResponse = $this->container->get('vrscheduler.api_response_service');
                $response = $authResponse->createAuthApiSuccessResponse($validateResponse, $authenticateResult);
            }
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__.' function failed due to Error : '.
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $response;
    }

}