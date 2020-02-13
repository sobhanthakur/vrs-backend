<?php
/**
 * Created by Sobhan Thakur.
 * Date: 11/9/19
 * Time: 6:38 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Security;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Options;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use QuickBooksOnline\API\DataService\DataService;

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
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
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
            $logger->error(__FUNCTION__.GeneralConstants::FUNCTION_LOG.
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
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $authService = $this->container->get('vrscheduler.authentication_service');
            $authenticationResult = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $newToken = $authService->CreateNewToken($authenticationResult);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->container->get('translator.default')->trans('api.response.success.message'),
                'Token' => $newToken
            );
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__.GeneralConstants::FUNCTION_LOG.
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @return null
     * @param Request $request
     * @Rest\Get("/", name="qwc_cert")
     */
    public function QWCSupportURL(Request $request)
    {
        return array(
            'ReasonCode' => 0,
            'ReasonText' => $this->container->get('translator.default')->trans('api.response.success.message')
        );
    }

    /**
     * @Rest\Get("/qbo", name="vrs_qbo_authenticate")
     * @param Request $request
     * @return array
     * @throws \QuickBooksOnline\API\Exception\SdkException
     */
    public function QBO(Request $request)
    {
        try {
            $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

            // Capture Quickbooks Config Parameters
            $quickbooksConfig = $this->container->getParameter('QuickBooksConfiguration');

            // Configure Data Service
            $dataService = DataService::Configure(array(
                'auth_mode' => $quickbooksConfig['AuthMode'],
                'ClientID' => $quickbooksConfig['ClientID'],
                'ClientSecret' => $quickbooksConfig['ClientSecret'],
                'RedirectURI' => $quickbooksConfig['RedirectURI'],
                'scope' => $quickbooksConfig['Scope'],
                'baseUrl' => $quickbooksConfig['BaseURL']
            ));
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->container->get('translator.default')->trans('api.response.success.message'),
                'RedirectURI' => $authUrl
            );
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
     * @return null
     * @param Request $request
     * @Rest\Get("/callback", name="qwc_callback")
     */
    public function QBOMethod(Request $request)
    {
        try {
            $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

            // Capture Quickbooks Config Parameters
            $quickbooksConfig = $this->container->getParameter('QuickBooksConfiguration');

            $authService = $this->container->get('vrscheduler.quickbooksonline_authentication')->QBOAuthentication($quickbooksConfig,$request);

            if(!$authService) {
                throw new UnprocessableEntityHttpException();
            }

            // Redirect on success
            return $this->redirect($this->container->getParameter('QuickBooksConfiguration')['RedirectAfterCallback']);

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