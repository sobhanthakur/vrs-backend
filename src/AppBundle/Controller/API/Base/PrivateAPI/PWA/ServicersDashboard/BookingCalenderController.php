<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 7/1/21
 * Time: 12:13 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\ServicersDashboard;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class BookingCalenderController extends FOSRestController
{
    /**
     * Get Bookings for Booking Calender
     * @SWG\Tag(name="Booking Calender")
     * @Rest\Get("/calender/feed-booking", name="vrs_pwa_calender_feed_booking_get")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 the following request format:
                {
                ""StartDate"":""2020-02-01"",""EndDate"":""2020-02-01""
                }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="",
     * )
     * @return array
     * @param Request $request
     */
    public function BookingCalenderDetails(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $bookingCalenderService = $this->container->get('vrscheduler.booking_calender_service');
            $content = json_decode(base64_decode($request->get('data')),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $bookingCalenderService->GetBookingCalenderDetails($servicerID,$content);
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
     * Get Bookings for Booking Calender
     * @SWG\Tag(name="Booking Calender")
     * @Rest\Get("/calender/properties", name="vrs_pwa_calender_properties_get")
     * @SWG\Response(
     *     response=200,
     *     description="List of properties",
     * )
     * @return array
     * @param Request $request
     */
    public function BookingCalenderProperties(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $bookingCalenderService = $this->container->get('vrscheduler.booking_calender_service');
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $bookingCalenderService->GetBookingCalenderProperties($servicerID);
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