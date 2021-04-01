<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 6/1/20
 * Time: 4:16 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Properties;

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
 * Class PropertiesController
 * @package AppBundle\Controller\API\Base\PublicAPI\Properties
 */
class PropertiesController extends FOSRestController
{
    /**
     * Get properties Details
     *
     * @SWG\Tag(name="Properties")
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
     *                      "PropertyID": 1,
     *                      "Active": true,
     *                       "PropertyName": "Lake Jolanda",
     *                       "PropertyAbbreviation": "LJ",
     *                       "PropertyNotes": null,
     *                       "InternalNotes": "",
     *                       "Address": "13905 Highway 2, Leavenworth WA 98826",
     *                       "Lat": 0,
     *                       "Lon": 0,
     *                      "DoorCode": "",
     *                      "DefaultCheckInTime": 15,
     *                      "DefaultCheckInTimeMinutes": 0,
     *                      "DefaultCheckOutTime": 11,
     *                      "DefaultCheckOutTimeMinutes": 0,
     *                      "OwnerID": 2,
     *                     "RegionID": 655,
     *                      "CreateDate": "20170617"
     *                  },
     *                  {
     *                      "PropertyID": 2,
     *                      "Active": true,
     *                       "PropertyName": "DL Bear Ridge",
     *                       "PropertyAbbreviation": "DLBR",
     *                       "PropertyNotes": null,
     *                       "InternalNotes": "",
     *                       "Address": "17595 Chumstick Hwy, Leavenworth, Wa 9882",
     *                       "Lat": 0,
     *                       "Lon": 0,
     *                      "DoorCode": "",
     *                      "DefaultCheckInTime": 15,
     *                      "DefaultCheckInTimeMinutes": 0,
     *                      "DefaultCheckOutTime": 11,
     *                      "DefaultCheckOutTimeMinutes": 0,
     *                      "OwnerID": 2,
     *                     "RegionID": 655,
     *                      "CreateDate": "20170617"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/properties", name="properties_get")
     */
    public function GetProperties(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Get all query parameter and set it in an array
        $queryParameter = array();
        $params = $request->query->all();
        foreach ($params as $key => $param) {
            (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
        }

        try {
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTIES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            if (!$restriction->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property details
            $propertiesService = $this->container->get('vrscheduler.public_properties_service');
            $propertyDetails = $propertiesService->getProperties($authDetails, $queryParameter, $pathInfo, $restriction);
            return $propertyDetails;
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
     * Properties
     *
     * @SWG\Tag(name="Properties")
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
     *                      "PropertyID": 1,
     *                      "Active": true,
     *                       "PropertyName": "Lake Jolanda",
     *                       "PropertyAbbreviation": "LJ",
     *                       "PropertyNotes": null,
     *                       "InternalNotes": "",
     *                       "Address": "13905 Highway 2, Leavenworth WA 98826",
     *                       "Lat": 0,
     *                       "Lon": 0,
     *                      "DoorCode": "",
     *                      "DefaultCheckInTime": 15,
     *                      "DefaultCheckInTimeMinutes": 0,
     *                      "DefaultCheckOutTime": 11,
     *                      "DefaultCheckOutTimeMinutes": 0,
     *                      "OwnerID": 2,
     *                     "RegionID": 655,
     *                      "CreateDate": "20170617"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/properties/{id}", name="properties_get_id")
     */
    public function getPropertiesById(Request $request)
    {
        $queryParameter = array();

        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting properyId from parameter
        $propertyID = $request->get('id');

        //Getting parameter from the API
        $params = $request->query->all();
        foreach ($params as $key => $param) {
            (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
        }

        try {
            //Getting date from jwt token
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //get base path
            $pathInfo = $request->getPathInfo();

            //check accessbility of the consumer to the resource
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTIES'];
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check resteiction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            if (!$restriction->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //get property details
            $propertiesService = $this->container->get('vrscheduler.public_properties_service');
            $property = $propertiesService->getProperties($authDetails, $queryParameter, $pathInfo, $restriction, $propertyID);
            return $property;

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
     * Insert new Property property
     *
     * @SWG\Tag(name="Properties")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="PropertyName",
     *              type="string",
     *              example="PropertyName"
     *         ),
     *         @SWG\Property(
     *              property="Abbreviation",
     *              type="string",
     *              example="PN"
     *         ),
     *         @SWG\Property(
     *              property="Address",
     *              type="string",
     *              example="Property Address"
     *         ),
     *         @SWG\Property(
     *              property="RegionID",
     *              type="string",
     *              example="Some RegionID"
     *         ),
     *         @SWG\Property(
     *              property="DefaultCheckInTime",
     *              type="string",
     *              example="16"
     *         ),
     *         @SWG\Property(
     *              property="DefaultCheckInTimeInMinutes",
     *              type="string",
     *              example=30
     *         ),
     *         @SWG\Property(
     *              property="DefaultCheckOutTime",
     *              type="string",
     *              example="16"
     *         ),
     *         @SWG\Property(
     *              property="DefaultCheckOutTimeInMinutes",
     *              type="string",
     *              example=30
     *         ),
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Insert new property",
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
     *                      "PropertyID": 1,
     *                      "PropertyName": "property_name",
     *                      "Abbreviation": "pn",
     *                      "Address": "Property Address",
     *                      "OwnerID":10,
     *                      "RegionID": 14,
     *                      "DefaultCheckInTime": 14,
     *                      "DefaultCheckInTimeInMinutes": "30",
     *                      "DefaultCheckOutTime": 14,
     *                      "DefaultCheckOutTimeInMinutes": "30",
     *                      "CreateDate": "20190302"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Post("/properties", name="property_post")
     */
    public function postProperty(Request $request)
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
            $apiRequest = $this->validatePropertyRequest($content);
            if ($apiRequest[GeneralConstants::STATUS] === false) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //Get pathinfo
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTIES'];

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel !== 2) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyService = $this->container->get('vrscheduler.public_properties_service');
            return $propertyService->InsertProperty($content,$authDetails);
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
    private function validatePropertyRequest($content)
    {
        //validate request key for processing
        if (
            (!isset($content['PropertyName']) && !(gettype($content['PropertyName']) == "string")) ||
            (!isset($content['Abbreviation']) && !(gettype($content['Abbreviation']) == "string")) ||
            (!isset($content['Address']) && !(gettype($content['Address']) == "string")) ||
            (!isset($content['RegionID']) && !(gettype($content['RegionID']) == "integer")) ||
            (!isset($content['OwnerID']) && !(gettype($content['OwnerID']) == "integer")) ||
            (!isset($content['DefaultCheckInTime']) && !(gettype($content['DefaultCheckInTime']) == "integer")) ||
            (!isset($content['DefaultCheckInTimeInMinutes']) && !(gettype($content['DefaultCheckInTimeInMinutes']) == "integer")) ||
            (!isset($content['DefaultCheckOutTime']) && !(gettype($content['DefaultCheckOutTime']) == "integer")) ||
            (!isset($content['DefaultCheckOutTimeInMinutes']) && !(gettype($content['DefaultCheckOutTimeInMinutes']) == "integer"))
        ) {
            return [GeneralConstants::STATUS => false];
        }

        //returns true if key in request object is valid
        return true;
    }
}