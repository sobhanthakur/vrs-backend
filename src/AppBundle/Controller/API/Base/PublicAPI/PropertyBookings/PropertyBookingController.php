<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/1/20
 * Time: 6:45 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\PropertyBookings;

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


class PropertyBookingController extends FOSRestController
{
    /**
     * Fetch all property booking details of the consumer
     * @SWG\Tag(name="Property Booking")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all property booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/propertybookings"
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
     *                      "PropertyBookingID": 1322343    ,
     *                      "PropertyID": 234,
     *                      "CheckIn": "20190824",
     *                      "CheckInTime": 14,
     *                      "CheckInTimeMinutes": 30,
     *                      "CheckOut": "20190826",
     *                      "CheckOutTime": 10,
     *                      "CheckOutTimeMinutes": 0,
     *                      "Guest": "Fred Smith",
     *                      "GuestEmail": "Fred@VRScheduler.com",
     *                      "GuestPhone": "541-555-1212",
     *                      "NumberOfGuest": 6,
     *                      "NumberOfChildren": 1,
     *                      "NumberOfPets": 1,
     *                      "IsOwner": 0,
     *                      "BookingTags": "BeachChairs,MidClean",
     *                      "ManualBookingTags": "PoolHeat,AllBedsAsKing",
     *                      "CreateDate": "20190302"
     *                  },
     *                 {
     *                      "PropertyBookingID": 1322345    ,
     *                      "PropertyID": 235,
     *                      "CheckIn": "20190825",
     *                      "CheckInTime": 14,
     *                      "CheckInTimeMinutes": 30,
     *                      "CheckOut": "20190826",
     *                      "CheckOutTime": 10,
     *                      "CheckOutTimeMinutes": 0,
     *                      "Guest": "Fred Smith",
     *                      "GuestEmail": "Fred@VRScheduler.com",
     *                      "GuestPhone": "541-555-1212",
     *                      "NumberOfGuest": 6,
     *                      "NumberOfChildren": 1,
     *                      "NumberOfPets": 1,
     *                      "IsOwner": 0,
     *                      "BookingTags": "Hre,Clean",
     *                      "ManualBookingTags": "PoolHeat,AllBedsAsKing",
     *                      "CreateDate": "20190302"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/propertybookings", name="property_bookings_get")
     */
    public function GetPropertyBooking(Request $request)
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
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //Get pathinfo
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTY_BOOKINGS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel == 0)? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyBookingService = $this->container->get('vrscheduler.public_property_bookings_service');
            $propertyBooking = $propertyBookingService->getPropertyBookings($authDetails, $queryParameter, $pathInfo, $restriction);

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
        return $propertyBooking;
    }

    /**
     * Fetch all property booking details of the consumer by id
     * @SWG\Tag(name="Property Booking")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all property booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/propertybookings/14"
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
     *                      "PropertyBookingID": 1322343    ,
     *                      "PropertyID": 234,
     *                      "CheckIn": "20190824",
     *                      "CheckInTime": 14,
     *                      "CheckInTimeMinutes": 30,
     *                      "CheckOut": "20190826",
     *                      "CheckOutTime": 10,
     *                      "CheckOutTimeMinutes": 0,
     *                      "Guest": "Fred Smith",
     *                      "GuestEmail": "Fred@VRScheduler.com",
     *                      "GuestPhone": "541-555-1212",
     *                      "NumberOfGuest": 6,
     *                      "NumberOfChildren": 1,
     *                      "NumberOfPets": 1,
     *                      "IsOwner": 0,
     *                      "BookingTags": "BeachChairs,MidClean",
     *                      "ManualBookingTags": "PoolHeat,AllBedsAsKing",
     *                      "CreateDate": "20190302"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/propertybookings/{id}", name="property_bookings_get_id")
     */
    public function getPropertyBookingById(Request $request)
    {
        $queryParameter = array();

        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting properyId from parameter
        $propertyBookingID = $request->get('id');

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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTY_BOOKINGS'];
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check resteiction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            $accessLevel = ($restriction->accessLevel == 0)? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyBookingService = $this->container->get('vrscheduler.public_property_bookings_service');
            $propertyBooking = $propertyBookingService->getPropertyBookings($authDetails, $queryParameter, $pathInfo, $restriction, $propertyBookingID);

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
        return $propertyBooking;
    }
}