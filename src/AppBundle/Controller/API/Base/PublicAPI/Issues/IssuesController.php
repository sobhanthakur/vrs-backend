<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 21/1/20
 * Time: 1:58 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Issues;

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

class IssuesController extends FOSRestController
{
    /**
     * Fetch all issues of the consumer
     * @SWG\Tag(name="Issues")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all issues booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/issues"
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
     * @Get("/issues", name="issues_get")
     */
    public function GetIssues(Request $request)
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['ISSUES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check resteiction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get issue booking details
            $issueService = $this->container->get('vrscheduler.public_issues_service');
            $issues = $issueService->getIssues($authDetails, $queryParameter, $pathInfo);

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
        return $issues;
    }

}