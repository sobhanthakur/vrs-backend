<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 7/2/20
 * Time: 3:15 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Staff;

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
use Noxlogic\RateLimitBundle\Annotation\RateLimit;

class StaffController extends FOSRestController
{
    /**
     * StaffController used to  fetch all staff details
     *
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Staff")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all staff details",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/staff"
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
     *                      "StaffID": 132,
     *                      "Name": "James Gillette",
     *                      "Abbreviation": "JG",
     *                      "Email": "demo@gmail.com",
     *                      "Phone": "99999999",
     *                      "CountryID": "225",
     *                      "Active": "true",
     *                      "CreateDate": "20180825"
     *
     *                  },
     *                  {
     *                      "StaffID": 137,
     *                      "Name": "James Bond",
     *                      "Abbreviation": "JG",
     *                      "Email": "demo@gmail.com",
     *                      "Phone": "99999999",
     *                      "CountryID": "225",
     *                      "Active": "true",
     *                      "CreateDate": "20180825"
     *
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/staff", name="staff_get")
     */
    public function GetStaff(Request $request)
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['STAFF'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get staff detail
            $staffService = $this->container->get('vrscheduler.public_staff_service');
            $staffDetails = $staffService->getStaff($authDetails, $queryParameter, $pathInfo);

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
        return $staffDetails;
    }

    /**
     * StaffController used to staff details by id
     *
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Staff")
     * @SWG\Response(
     *     response=200,
     *     description="Returns staff details by id",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/staff/5"
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
     *                      "StaffID": 137,
     *                      "Name": "James Bond",
     *                      "Abbreviation": "JG",
     *                      "Email": "demo@gmail.com",
     *                      "Phone": "99999999",
     *                      "CountryID": "225",
     *                      "Active": "true",
     *                      "CreateDate": "20180825"
     *
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/staff/{id}", name="staff_get_id")
     */
    public function getStaffById(Request $request)
    {
        $queryParameter = array();

        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting staffID from parameter
        $staffID = $request->get('id');

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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['STAFF'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get staff details
            $staffService = $this->container->get('vrscheduler.public_staff_service');
            $staffDetails = $staffService->getStaff($authDetails, $queryParameter, $pathInfo, $staffID);
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
        return $staffDetails;
    }

}