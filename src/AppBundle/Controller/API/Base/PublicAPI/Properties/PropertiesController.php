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
     * Properties
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
            $restriction = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::PROPERTIES];
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTIES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //check resteiction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property details
            $propertiesService = $this->container->get('vrscheduler.public_properties_service');
            $propertyDetails = $propertiesService->getProperties($authDetails, $queryParameter, $pathInfo);
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
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //get property details
            $propertiesService = $this->container->get('vrscheduler.public_properties_service');
            $property = $propertiesService->getProperties($authDetails, $queryParameter, $pathInfo, $propertyID);
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


}