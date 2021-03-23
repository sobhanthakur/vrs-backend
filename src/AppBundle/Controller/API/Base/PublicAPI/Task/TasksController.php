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
use FOS\RestBundle\Controller\Annotations\Post;
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
                        {
                        "TaskID": 1,
                        "TaskRuleID": 0,
                        "PropertyBookingID": null,
                        "PropertyID": 6803,
                        "TaskName": " 9:30-12:30",
                        "TaskDescription": "",
                        "Approved": null,
                        "ApprovedDate": null,
                        "Completed": "1",
                        "Billable": true,
                        "LaborAmount": 0,
                        "MaterialsAmount": 0,
                        "TaskDate": "20181205",
                        "CompleteConfirmedDate": "20181205",
                        "CreateDate": "20181204",
                        "TaskStartDate": "20181205",
                        "TaskStartTime": 9,
                        "TaskCompleteByDate": "20181205",
                        "TaskCompleteByTime": 13,
                        "TaskTime": "09:30:00",
                        "Property": {
                        "PropertyID": 1,
                        "Active": true,
                        "PropertyName": "P_Name",
                        "PropertyAbbreviation": "PN",
                        "PropertyNotes": null,
                        "InternalNotes": "",
                        "Address": "Address",
                        "Lat": 10.163195,
                        "Lon": -80.653259,
                        "DoorCode": "See Notes",
                        "DefaultCheckInTime": 16,
                        "DefaultCheckInTimeMinutes": 0,
                        "DefaultCheckOutTime": 10,
                        "DefaultCheckOutTimeMinutes": 0,
                        "OwnerID": "1",
                        "RegionID": "1",
                        "CreateDate": "20181204"
                        },
                        "Staff": {
                        {
                        "StaffID": 1609,
                        "Name": "StaffName",
                        "Abbreviation": "Staff Abbreviation",
                        "Email": "staffemail",
                        "Phone": "1234",
                        "CountryID": 1,
                        "Active": true,
                        "CreateDate": "20181129"
                        }
                        },
                        "NextPropertyBooking": {
                        "PropertyBookingID": 274085,
                        "PropertyID": "6949",
                        "CheckIn": "20190620",
                        "CheckInTime": 16,
                        "CheckInTimeMinutes": 0,
                        "CheckOut": "20190621",
                        "CheckOutTime": 10,
                        "CheckOutTimeMinutes": 0,
                        "Guest": "Jennifer Yung",
                        "GuestEmail": "guestemail",
                        "GuestPhone": "0987",
                        "NumberOfGuests": 10,
                        "NumberOfPets": 0,
                        "NumberOfChildren": 0,
                        "IsOwner": 0,
                        "BookingTags": "",
                        "ManualBookingTags": null,
                        "CreateDate": "20181210",
                        "Active": true
                        }
                        }
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
                        {
                        "TaskID": 1,
                        "TaskRuleID": 0,
                        "PropertyBookingID": null,
                        "PropertyID": 6803,
                        "TaskName": " 9:30-12:30",
                        "TaskDescription": "",
                        "Approved": null,
                        "ApprovedDate": null,
                        "Completed": "1",
                        "Billable": true,
                        "LaborAmount": 0,
                        "MaterialsAmount": 0,
                        "TaskDate": "20181205",
                        "CompleteConfirmedDate": "20181205",
                        "CreateDate": "20181204",
                        "TaskStartDate": "20181205",
                        "TaskStartTime": 9,
                        "TaskCompleteByDate": "20181205",
                        "TaskCompleteByTime": 13,
                        "TaskTime": "09:30:00",
                        "Property": {
                        "PropertyID": 1,
                        "Active": true,
                        "PropertyName": "P_Name",
                        "PropertyAbbreviation": "PN",
                        "PropertyNotes": null,
                        "InternalNotes": "",
                        "Address": "Address",
                        "Lat": 10.163195,
                        "Lon": -80.653259,
                        "DoorCode": "See Notes",
                        "DefaultCheckInTime": 16,
                        "DefaultCheckInTimeMinutes": 0,
                        "DefaultCheckOutTime": 10,
                        "DefaultCheckOutTimeMinutes": 0,
                        "OwnerID": "1",
                        "RegionID": "1",
                        "CreateDate": "20181204"
                        },
                        "Staff": {
                        {
                        "StaffID": 1609,
                        "Name": "StaffName",
                        "Abbreviation": "Staff Abbreviation",
                        "Email": "staffemail",
                        "Phone": "1234",
                        "CountryID": 1,
                        "Active": true,
                        "CreateDate": "20181129"
                        }
                        },
                        "NextPropertyBooking": {
                        "PropertyBookingID": 274085,
                        "PropertyID": "6949",
                        "CheckIn": "20190620",
                        "CheckInTime": 16,
                        "CheckInTimeMinutes": 0,
                        "CheckOut": "20190621",
                        "CheckOutTime": 10,
                        "CheckOutTimeMinutes": 0,
                        "Guest": "Jennifer Yung",
                        "GuestEmail": "guestemail",
                        "GuestPhone": "0987",
                        "NumberOfGuests": 10,
                        "NumberOfPets": 0,
                        "NumberOfChildren": 0,
                        "IsOwner": 0,
                        "BookingTags": "",
                        "ManualBookingTags": null,
                        "CreateDate": "20181210",
                        "Active": true
                        }
                        }
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

    /**
     * Insert task details
     *
     * @SWG\Tag(name="Task")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="PropertyID",
     *              type="string",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="TaskRuleID",
     *              type="string",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="TaskName",
     *              type="string",
     *              example="TaskName"
     *         ),
     *         @SWG\Property(
     *              property="TaskDescription",
     *              type="string",
     *              example="Some Description"
     *         ),
     *         @SWG\Property(
     *              property="TaskStartDate",
     *              type="string",
     *              example="2019-01-01"
     *         ),
     *         @SWG\Property(
     *              property="TaskStartTime",
     *              type="string",
     *              example=10
     *         ),
     *         @SWG\Property(
     *              property="TaskCompleteByDate",
     *              type="string",
     *              example="2019-01-01"
     *         ),
     *         @SWG\Property(
     *              property="TaskCompleteByTime",
     *              type="string",
     *              example=10
     *         ),
     *         @SWG\Property(
     *              property="TaskDate",
     *              type="string",
     *              example="2019-01-01"
     *         ),
     *         @SWG\Property(
     *              property="TaskTime",
     *              type="string",
     *              example=10
     *         ),
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Insert Task Details",
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
                        {
                        "TaskID": 1,
                        "TaskRuleID": 0,
                        "PropertyBookingID": null,
                        "PropertyID": 6803,
                        "TaskName": " 9:30-12:30",
                        "TaskDescription": "",
                        "Approved": null,
                        "ApprovedDate": null,
                        "Completed": "1",
                        "Billable": true,
                        "LaborAmount": 0,
                        "MaterialsAmount": 0,
                        "TaskDate": "20181205",
                        "CompleteConfirmedDate": "20181205",
                        "CreateDate": "20181204",
                        "TaskStartDate": "20181205",
                        "TaskStartTime": 9,
                        "TaskCompleteByDate": "20181205",
                        "TaskCompleteByTime": 13,
                        "TaskTime": "09:30:00",
                        "Property": {
                        "PropertyID": 1,
                        "Active": true,
                        "PropertyName": "P_Name",
                        "PropertyAbbreviation": "PN",
                        "PropertyNotes": null,
                        "InternalNotes": "",
                        "Address": "Address",
                        "Lat": 10.163195,
                        "Lon": -80.653259,
                        "DoorCode": "See Notes",
                        "DefaultCheckInTime": 16,
                        "DefaultCheckInTimeMinutes": 0,
                        "DefaultCheckOutTime": 10,
                        "DefaultCheckOutTimeMinutes": 0,
                        "OwnerID": "1",
                        "RegionID": "1",
                        "CreateDate": "20181204"
                        },
                        "Staff": {
                        {
                        "StaffID": 1609,
                        "Name": "StaffName",
                        "Abbreviation": "Staff Abbreviation",
                        "Email": "staffemail",
                        "Phone": "1234",
                        "CountryID": 1,
                        "Active": true,
                        "CreateDate": "20181129"
                        }
                        },
                        "NextPropertyBooking": {
                        "PropertyBookingID": 274085,
                        "PropertyID": "6949",
                        "CheckIn": "20190620",
                        "CheckInTime": 16,
                        "CheckInTimeMinutes": 0,
                        "CheckOut": "20190621",
                        "CheckOutTime": 10,
                        "CheckOutTimeMinutes": 0,
                        "Guest": "Jennifer Yung",
                        "GuestEmail": "guestemail",
                        "GuestPhone": "0987",
                        "NumberOfGuests": 10,
                        "NumberOfPets": 0,
                        "NumberOfChildren": 0,
                        "IsOwner": 0,
                        "BookingTags": "",
                        "ManualBookingTags": null,
                        "CreateDate": "20181210",
                        "Active": true
                        }
                        }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Post("/tasks", name="tasks_post")
     */
    public function postTask(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        try {
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //parse the json content from request
            $content = $authService->parseContent($request->getContent(), "json");

            //validate the request
            $apiRequest = $this->validateTaskRequest($content);
            if (!$apiRequest['status']) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //Get pathinfo
//            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['TASKS'];

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);

            //check access level for read and write
            $accessLevel = ($restriction->accessLevel !== 2) ? false : true;
            if (!$accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property booking details
            $taskService = $this->container->get('vrscheduler.public_tasks_service');
            return $taskService->insertTaskDetails($content, $authDetails);
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
    private function validateTaskRequest($content)
    {
        $validation = [];
        //validate request key for processing
        $validation['PropertyID'] = !isset($content['PropertyID']) && !(gettype($content['PropertyID']) == "integer");
        $validation['TaskRuleID'] = !isset($content['TaskRuleID']) && !(gettype($content['TaskRuleID']) == "integer");
        $validation['TaskDescription'] = !isset($content['TaskDescription']) && !(gettype($content['TaskDescription']) == "string");
        $validation['TaskStartDate'] = !isset($content['TaskStartDate']) && !(gettype($content['TaskStartDate']) == "string");
        $validation['TaskStartTime'] = !isset($content['TaskStartTime']) && !(gettype($content['TaskStartTime']) == "integer");
        $validation['TaskCompleteByDate'] = !isset($content['TaskCompleteByDate']) && !(gettype($content['TaskCompleteByDate']) == "string");
        $validation['TaskCompleteByTime'] = !isset($content['TaskCompleteByTime']) && !(gettype($content['TaskCompleteByTime']) == "integer");
        $validation['TaskDate'] = !isset($content['TaskDate']) && !(gettype($content['TaskDate']) == "string");
        $validation['TaskTime'] = !isset($content['TaskTime']) && !(gettype($content['TaskTime']) == "integer");

        foreach ($validation as $item) {
            if ($item) {
                return [GeneralConstants::STATUS => false];
            }
        }

        return [GeneralConstants::STATUS => true];
    }
}