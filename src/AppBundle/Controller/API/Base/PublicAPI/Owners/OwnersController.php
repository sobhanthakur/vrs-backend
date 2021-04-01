<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 13/1/20
 * Time: 4:56 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Owners;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Swagger\Annotations as SWG;

/**
 * Class OwnersController
 * @package AppBundle\Controller\API\Base\PublicAPI\Owners
 */
class OwnersController extends FOSRestController
{
    /**
     * Get Owners Details
     *
     * @SWG\Tag(name="Owners")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all properties",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/properties"
     *          ),
     *          @SWG\Property(
     *              property="has_more",
     *              type="boolean",
     *              example="true"
     *          ),
     *       @SWG\Property(
     *              property="data",
     *              example=
     *               {
     *                  {
     *                      "OwnerID": 1,
     *                      "OwnerName": "McCrummen",
     *                       "OwnerEmail": "prabhat@centerforsi.org",
     *                       "OwnerPhone": "94385712",
     *                       "CountryID": 224,
     *                       "CreateDate": "20170503"
     *                  }
     *
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/owners", name="owners_get")
     *
     *
     */
    public function GetOwners(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        try {
            //Get all query parameter and set it in an array
            $queryParameter = array();
            $params = $request->query->all();
            foreach ($params as $key => $param) {
                (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
            }

            //Get auth Details
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //get api path info and basename
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['OWNERS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            if (!$restriction->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get owners details
            $ownersService = $this->container->get('vrscheduler.public_owners_service');
            $ownerDetails = $ownersService->getOwners($authDetails, $queryParameter, $pathInfo, $restriction);

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
        return $ownerDetails;
    }

    /**
     * Insert new Owner
     *
     * @SWG\Tag(name="Owners")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="OwnerName",
     *              type="string",
     *              example="OwnerName"
     *         ),
     *         @SWG\Property(
     *              property="OwnerEmail",
     *              type="string",
     *              example="owner@email.com"
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Insert new property",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="OwnerID",
     *              type="string",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="OwnerName",
     *              type="string",
     *              example="OwnerName"
     *         ),
     *         @SWG\Property(
     *              property="OwnerEmail",
     *              type="string",
     *              example="owner@email.com"
     *         ),
     *         @SWG\Property(
     *              property="CreateDate",
     *              type="string",
     *              example="2019-02-01"
     *         )
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Post("/owners", name="owner_post")
     */
    public function postOwner(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        try {
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $authService = $this->container->get(GeneralConstants::AUTH_PUBLIC_SERVICE);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //parse the json content from request
            $content = $authService->parseContent($request->getContent(), "json");

            //validate the request
            $apiRequest = $this->validateOwnerRequest($content);
            if ($apiRequest[GeneralConstants::STATUS] === false) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //Get pathinfo
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['OWNERS'];

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel !== 2) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Add Owner
            $ownerService = $this->container->get('vrscheduler.public_owners_service');
            return $ownerService->InsertOwner($content,$authDetails);
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
            throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
        }
    }

    /**
     * Function to validate the request object
     *
     * @param $content
     *
     * @return mixed
     */
    private function validateOwnerRequest($content)
    {
        //validate request key for processing
        if (
            (!isset($content['OwnerName']) && !(gettype($content['OwnerName']) == "string")) ||
            (!isset($content['OwnerEmail']) && !(gettype($content['OwnerEmail']) == "string"))
        ) {
            return [GeneralConstants::STATUS => false];
        }

        //returns true if key in request object is valid
        return true;
    }
}