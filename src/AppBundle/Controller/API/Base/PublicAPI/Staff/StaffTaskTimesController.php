<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/2/20
 * Time: 12:16 PM
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

class StaffTaskTimesController extends FOSRestController
{
    /**
     * StaffTaskTimesController used to  fetch all staff task times details
     *
     *
     * @SWG\Tag(name="Staff Task Times")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all staff task times details",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/stafftasktimes"
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
     *                      "StaffDayTimeID": 132,
     *                      "StaffID": 1507,
     *                      "ClockIn": "20181107092544",
     *                      "ClockOut": 20181107100643,
     *                      "InLat": 47.599614063215,
     *                      "InLon":  -120.656419605377,
     *                      "OutLat": 47.599689761945,
     *                      "OutLon": -120.661492376551,
     *                      "Note": "Testinteg No",
     *                      "AutoLogOutFlagxxxx": false,
     *
     *                  },
     *                  {
     *                      "StaffDayTimeID": 134,
     *                      "StaffID": 1507,
     *                      "ClockIn": 20181107092544,
     *                      "ClockOut": 20181107100643,
     *                      "InLat": 47.599614063215,
     *                      "InLon":  -120.656419605377,
     *                      "OutLat": 47.599689761945,
     *                      "OutLon": -120.661492376551,
     *                      "Note": "Testinteg No",
     *                      "AutoLogOutFlagxxxx": false,
     *
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/stafftasktimes", name="staff_task_times_get")
     */
    public function GetStaffTaskTimes(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Get all query parameter and set it in an array
        $queryParameter = array();
        $params = $request->query->all();
        foreach ($params as $key => $param) {
            (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
        }

        try{

            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //Get pathinfo
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['STAFF_TASK_TIMES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get staff detail
            $staffTaskTimesService = $this->container->get('vrscheduler.public_staff_task_times_service');
            $staffTaskTimesDetails = $staffTaskTimesService->getStaffTaskTimes($authDetails, $queryParameter, $pathInfo);

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

        return $staffTaskTimesDetails;
    }

}