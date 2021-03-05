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
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
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
     *
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION[GeneralConstants::PROPERTY_BOOKINGS];

            //Get auth service
            $authService = $this->container->get(GeneralConstants::AUTH_PUBLIC_SERVICE);

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel == 0) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyBookingService = $this->container->get(GeneralConstants::PROPERTY_BOOKING_PUBLIC);
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
     *
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
     *                      "ImportBookingID" : 123,
     *                      "PMSNote" : "PMSNote",
     *                      "PMSHousekeepingNote" : "PMSHousekeepingNote",
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION[GeneralConstants::PROPERTY_BOOKINGS];
            $authService = $this->container->get(GeneralConstants::AUTH_PUBLIC_SERVICE);
            //check resteiction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            $accessLevel = ($restriction->accessLevel == 0) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyBookingService = $this->container->get(GeneralConstants::PROPERTY_BOOKING_PUBLIC);
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

    /**
     * Insert property booking Details
     *
     * @SWG\Tag(name="Property Booking")
     * @SWG\Response(
     *     response=200,
     *     description="Insert property booking Details",
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
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Post("/propertybookings", name="property_bookings_post")
     */
    public function postPropertyBooking(Request $request)
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
            $apiRequest = $this->validatePropertyBookingRequest($content);
            if ($apiRequest[GeneralConstants::STATUS] === false) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //Get pathinfo
            $baseName = GeneralConstants::CHECK_API_RESTRICTION[GeneralConstants::PROPERTY_BOOKINGS];

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel == 0) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyBookingService = $this->container->get(GeneralConstants::PROPERTY_BOOKING_PUBLIC);
            $insertPropertyBooking = $propertyBookingService->insertPropertBookingDetails($content, $authDetails);


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

        return $insertPropertyBooking;

    }


    /**
     *  Update property booking  of the consumer by id
     *
     * @SWG\Tag(name="Property Booking")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all property booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/propertybookings/1322343"
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
     * @Put("/propertybookings/{id}", name="property_bookings_put")
     */
    public function updatePropertyBooking(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        try {
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $authService = $this->container->get(GeneralConstants::AUTH_PUBLIC_SERVICE);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //Getting properyId from parameter
            $propertyBookingID = $request->get('id');

            //parse the json content from request
            $content = $authService->parseContent($request->getContent(), "json");

            //Get pathinfo
            $baseName = GeneralConstants::CHECK_API_RESTRICTION[GeneralConstants::PROPERTY_BOOKINGS];

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel == 0) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $propertyBookingService = $this->container->get(GeneralConstants::PROPERTY_BOOKING_PUBLIC);
            return $propertyBookingService->insertPropertBookingDetails($content, $authDetails, $propertyBookingID);

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
     * Fetch all property booking details of the consumer by id
     *
     * @SWG\Tag(name="Property Booking")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all property booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="string",
     *              example=0
     *          ),
     *          @SWG\Property(
     *              property="ReasonText",
     *              type="boolean",
     *              example="Data is succesfully deleted"
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Delete("/propertybookings/{id}", name="property_bookings_delete")
     */
    public function deletePropertyBooking(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        try {
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $authService = $this->container->get(GeneralConstants::AUTH_PUBLIC_SERVICE);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //Getting properyId from parameter
            $propertyBookingID = $request->get('id');

            //check restriction for the user
            $baseName = GeneralConstants::CHECK_API_RESTRICTION[GeneralConstants::PROPERTY_BOOKINGS];
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            //check access level for read and write
            $accessLevel = ($restriction->accessLevel == 0) ? $accessLevel = false : $accessLevel = true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //delete property booking details
            $propertyBookingService = $this->container->get(GeneralConstants::PROPERTY_BOOKING_PUBLIC);
            return $propertyBookingService->deletePropertBookingDetails($propertyBookingID);

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
    private function validatePropertyBookingRequest($content)
    {
        //validate request key for processing
        $propertyID = !isset($content['PropertyID']) || !(gettype($content['PropertyID']) == "integer");
        $checkIn = !isset($content['CheckIn']) || !(gettype($content['CheckIn']) == "string");
        $checkOut = !isset($content['CheckOut']) || !(gettype($content['CheckOut']) == "string");

        //returns false if request object is not valid
        if ($propertyID) {
            return [GeneralConstants::STATUS => false];
        }
        if ($checkIn) {
            return [GeneralConstants::STATUS => false];
        }

        if ($checkOut) {
            return [GeneralConstants::STATUS => false];
        }

        //returns true if key in request object is valid
        return true;
    }
}