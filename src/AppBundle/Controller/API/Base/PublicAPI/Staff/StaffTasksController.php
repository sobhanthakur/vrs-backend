<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 10/2/20
 * Time: 5:23 PM
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

class StaffTasksController extends FOSRestController
{
    /**
     * StaffController used to  fetch all staff details
     *
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Staff Tasks")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all staff tasks  details",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/stafftasks"
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
     * @Get("/stafftasks", name="staff_tasks_get")
     */
    public function GetStaffTasks(Request $request)
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['STAFF_TASKS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get task detail
            $staffTasksService = $this->container->get('vrscheduler.public_staff_tasks_service');
            $staffTasksDetails = $staffTasksService->getStaffTasks($authDetails, $queryParameter, $pathInfo);

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

        return $staffTasksDetails;
    }

    /**
     * StaffController used to  fetch all staff details
     *
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Staff Tasks")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all staff tasks  details",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/stafftasks"
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
     * @Get("/stafftasks/{id}", name="staff_tasks_get_id")
     */
    public function GetStaffTasksByID(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting staffTaskID from parameter
        $staffTaskID = $request->get('id');

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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['STAFF_TASKS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get task detail
            $staffTasksService = $this->container->get('vrscheduler.public_staff_tasks_service');
            $staffTasksDetails = $staffTasksService->getStaffTasks($authDetails, $queryParameter, $pathInfo, $staffTaskID);

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

        return $staffTasksDetails;
    }

}