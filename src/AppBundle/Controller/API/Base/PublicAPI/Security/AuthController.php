<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 24/12/19
 * Time: 2:54 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Security;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class AuthController
 * @package AppBundle\Controller\API\Base\PublicAPI\Security
 */
class AuthController extends FOSRestController
{
    /**
     * Validate your Api Key and value and return Access and refresh token
     * @SWG\Tag(name="Authentication")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="API_Key",
     *              type="string",
     *              example="2A55E772-8005-44CE-85FEF957FCEC152F"
     *         ),
     *         @SWG\Property(
     *              property="Value",
     *              type="string",
     *              example= "BDBB88ED-1801-4418-B84AEF251A35C88E"
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Provide access and refresh token to the user.",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Access_Token",
     *              type="object",
     *              example=  {
     *"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6IjM2MDAwMDAwMDAwMDAifQ.eyJDdXN0b21lcklEIjoxLCJDdXN0b21lck5hbWUiOiJTYXJhaCIsInByb3BlcnRpZXMiOlt7InJlc291cnNlTmFtZSI6Ik93bmVycyIsImFjY2Vzc0xldmVsIjoxfSx7InJlc291cnNlTmFtZSI6IlJlZ2lvbnMiLCJhY2Nlc3NMZXZlbCI6MX0seyJyZXNvdXJzZU5hbWUiOiJSZWdpb25Hcm91cHMiLCJhY2Nlc3NMZXZlbCI6MX0seyJyZXNvdXJzZU5hbWUiOiJQcm9wZXJ0aWVzIiwiYWNjZXNzTGV2ZWwiOjF9XSwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDIwMDEwOTExNTYifQ.hAKZUi6CnPrWsMjPY45dubEYqGopnqoJirB7IEqTTgI",
     *"Created": "202001091156",
     *"Expiry": "3600"
     *}
     *          ),
     *     @SWG\Property(
     *              property="Refresh_Token",
     *              type="object",
     *              example=  {
     *"token": "eyJ0eXAiOiJKV1QiLCJhbGciiiJIUzI1NiIsImV4cCI6IjM2MDAwMDAwMDAwMDAifQ.eyJDdXN0b21lcklEIjoxLCJDdXN0b21lck5hbWUiOiJTYXJhaCIsInByb3BlcnRpZXMiOlt7InJlc291cnNlTmFtZSI6Ik93bmVycyIsImFjY2Vzc0xldmVsIjoxfSx7InJlc291cnNlTmFtZSI6IlJlZ2lvbnMiLCJhY2Nlc3NMZXZlbCI6MX0seyJyZXNvdXJzZU5hbWUiOiJSZWdpb25Hcm91cHMiLCJhY2Nlc3NMZXZlbCI6MX0seyJyZXNvdXJzZU5hbWUiOiJQcm9wZXJ0aWVzIiwiYWNjZXNzTGV2ZWwiOjF9XSwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDIwMDEwOTExNTYifQ.hAKZUi6CnPrWsMjPY45dubEYqGopnqoJirB7IEqTTgI",
     *"Created": "202001091156",
     *"Expiry": "86400"
     *}
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
     * @Post("/oauth/login", name="oauth_login_post")
     */
    public function GenerateAuthToken(Request $request)
    {
        try {
            //setting logger exception
            $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //parse the json content from request
            $content = $authService->parseContent($request->getContent(), "json");
            if (isset($content['status']) && $content['status'] === false) {
                throw new BadRequestHttpException(null, $content['error']);
            }

            //validate the request
            $apiRequest = $this->validateRequest($content);
            if ($apiRequest['status'] === false) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //validate the user
            $authDetails = $authService->authDetails($content);
            return $authDetails;
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
     * Returns a auth token using refresh token
     * @SWG\Tag(name="Authentication")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns auth token",
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
     *          ),
     *      @SWG\Property(
     *              property="Access_Token",
     *              type="object",
     *              example=  {
     *"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6IjM2MDAwMDAwMDAwMDAifQ.eyJDdXN0b21lcklEIjoxLCJDdXN0b21lck5hbWUiOiJTYXJhaCIsInByb3BlcnRpZXMiOlt7InJlc291cnNlTmFtZSI6Ik93bmVycyIsImFjY2Vzc0xldmVsIjoxfSx7InJlc291cnNlTmFtZSI6IlJlZ2lvbnMiLCJhY2Nlc3NMZXZlbCI6MX0seyJyZXNvdXJzZU5hbWUiOiJSZWdpb25Hcm91cHMiLCJhY2Nlc3NMZXZlbCI6MX0seyJyZXNvdXJzZU5hbWUiOiJQcm9wZXJ0aWVzIiwiYWNjZXNzTGV2ZWwiOjF9XSwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDIwMDEwOTExNTYifQ.hAKZUi6CnPrWsMjPY45dubEYqGopnqoJirB7IEqTTgI",
     *"Created": "202001091156",
     *"Expiry": "3600"
     *}
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/oauth/refresh", name="oauth_refresh_get")
     */
    public function RenewAuthToken(Request $request)
    {
        try {
            //setting logger
            $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //validate the request and return new auth token
            $apiRequest = $authService->renewToken($request);

            return $apiRequest;
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
     * Function to validate the request object
     *
     * @param $content
     *
     * @return mixed
     */
    private function validateRequest($content)
    {
        //validate request key for processing
        $apiKeyNotSet = !isset($content['API_Key']) || !(gettype($content['API_Key']) == "string");
        $apiValueNotSet = !isset($content['API_Value']) || !(gettype($content['API_Value']) == "string");

        //returns false if request object is not valid
        if ($apiKeyNotSet) {
            return ['status' => false];
        }
        if ($apiValueNotSet) {
            return ['status' => false];
        }

        //returns true if key in request object is valid
        return true;
    }

}