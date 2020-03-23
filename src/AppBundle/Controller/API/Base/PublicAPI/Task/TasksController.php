<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 8/2/20
 * Time: 9:46 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Task;

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

class TasksController extends FOSRestController
{
    /**
     * TaskController controller to fetch all task details
     *
     *
     * @SWG\Tag(name="Task")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all task rules of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/tasks"
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
     *                  "TaskID": 12049,
     *                   "TaskRuleID": 1,
     *                   "PropertyBookingID": 1204,
     *                   "PropertyID": 13,
     *                   "TaskName": null,
     *                   "TaskDescription": null,
     *                   "Approved": null,
     *                   "ApprovedDate": null,
     *                   "Completed": "1",
     *                   "Billable": true,
     *                   "LaborAmount": 0,
     *                   "MaterialsAmount": 0,
     *                   "TaskDate": "20170427",
     *                   "CompleteConfirmedDate": "20170705",
     *                   "CreateDate": "20170414"
     *                   },
     *                  {
     *                  "TaskID": 12040,
     *                   "TaskRuleID": 4,
     *                   "PropertyBookingID": 1200,
     *                   "PropertyID": 10,
     *                   "TaskName": null,
     *                   "TaskDescription": null,
     *                   "Approved": null,
     *                   "ApprovedDate": null,
     *                   "Completed": "1",
     *                   "Billable": true,
     *                   "LaborAmount": 0,
     *                   "MaterialsAmount": 0,
     *                   "TaskDate": "20170427",
     *                   "CompleteConfirmedDate": "20170705",
     *                   "CreateDate": "20170414"
     *                   }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/tasks", name="tasks_get")
     */
    public function GetTasks(Request $request)
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['TASKS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get task detail
            $tasksService = $this->container->get('vrscheduler.public_tasks_service');
            $tasksDetails = $tasksService->getTasks($authDetails, $queryParameter, $pathInfo);

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
        return $tasksDetails;
    }

    /**
     * TaskController controller to fetch  task details by id
     *
     *
     * @SWG\Tag(name="Task")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all task rules of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/tasks"
     *          ),
     *          @SWG\Property(
     *              property="has_more",
     *              type="boolean",
     *              example="true"
     *          ),
     *       @SWG\Property(
     *              property="data",
     *              example=
     *                {
     *                  {
     *                  "TaskID": 12049,
     *                   "TaskRuleID": 1,
     *                   "PropertyBookingID": 1204,
     *                   "PropertyID": 13,
     *                   "TaskName": null,
     *                   "TaskDescription": null,
     *                   "Approved": null,
     *                   "ApprovedDate": null,
     *                   "Completed": "1",
     *                   "Billable": true,
     *                   "LaborAmount": 0,
     *                   "MaterialsAmount": 0,
     *                   "TaskDate": "20170427",
     *                   "CompleteConfirmedDate": "20170705",
     *                   "CreateDate": "20170414"
     *                   }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/tasks/{id}", name="tasks_get_id")
     */
    public function GetTasksByID(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting taskId from parameter
        $taskID = $request->get('id');

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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['TASKS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get task detail
            $tasksService = $this->container->get('vrscheduler.public_tasks_service');
            $tasksDetails = $tasksService->getTasks($authDetails, $queryParameter, $pathInfo, $taskID);

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
        return $tasksDetails;
    }

}