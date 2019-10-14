<?php
/**
 * Created by Sobhan Thakur.
 * Date: 11/9/19
 * Time: 6:38 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Security;

use AppBundle\Constants\ErrorConstants;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Options;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

/**
 * Class AuthController
 * @package AppBundle\Controller\API\Base\PrivateAPI\Security
 */
class AuthController extends FOSRestController
{
    /**
     * Validate your JWT Access Token
     * @SWG\Tag(name="Authentication")
     * @SWG\Response(
     *     response=200,
     *     description="Validate access token"
     * )
     * @return null
     * @param Request $request
     * @Post("/oauth/validate", name="oauth_validate_post")
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

    /**
     * RefreshToken API to create new token.
     * @SWG\Tag(name="Authentication")
     * @SWG\Response(
     *     response=200,
     *     description="Renew the access token"
     * )
     * @return array
     * @param Request $request
     * @Post("/oauth/refresh", name="oauth_refresh_post")
     */
    public function RefreshAuthToken(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $authService = $this->container->get('vrscheduler.authentication_service');
            $authenticationResult = $request->attributes->get('AuthPayload');
            $newToken = $authService->CreateNewToken($authenticationResult);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->container->get('translator.default')->trans('api.response.success.message'),
                'AuthenticationResponse' => array(
                    'Token' => $newToken
                )
            );
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
    }
}