<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 10/2/20
 * Time: 11:33 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\ServicersDashboard;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class ServicersDashboardController extends FOSRestController
{
    /**
     * Shows Task Details that needs to be shown in the servicers dashboard
     * @SWG\Tag(name="Servicers Dashboard")
     * @Get("/tasks", name="vrs_pwa_tasks")
     * @SWG\Response(
     *     response=200,
     *     description="Authenticates the servicer and returns a JWT in return.",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Tasks",
     *              type="string",
     *              example=
     *               {
     *                  {
     *                      "GuestDetails" : {
     *                        "Name" : "John Smith" ,
     *                        "Email" : "smithjohn@gmail.com",
     *                        "Phone" : "902124",
     *                        "Number" : "2"
     *                      },
     *                      "TaskEstimates" : {
     *                          "Min" : 0,
     *                          "Max" : 2
     *                      },
     *                      "AcceptDecline" : 1,
     *                      "IsLead" : 1,
     *                      "Expand" : 1,
     *                      "StartTask" : 1,
     *                      "PauseTask" : 1,
     *                      "AssignedDate" : "2019-10-10",
     *                      "Window": {
     *                          "FromDate": "2017-04-16T00:00:00+00:00",
     *                          "ToDate": "2017-04-16T00:00:00+00:00",
     *                          "FromTime": 11,
     *                          "ToTime": 15,
     *                          "FromMinutes": 0,
     *                          "ToMinutes": 0,
     *                      },
     *                      "Details" : {
     *                          "TaskID" : 1,
     *                          "TaskName" : "Check IN",
     *                          "Region" : "USA",
     *                          "RegionColor" : "#DAA902",
     *                          "TaskDescription" : "TaskDescription",
     *                          "Map" : {
     *                              "Lat" : "100.21",
     *                              "Long" : "104.22"
     *                          }
     *                      }
     *                  }
     *              }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function ServicersDashboard(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.servicers_dashboard');
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->GetTasks($servicerID);
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
     * Starts the task
     * @SWG\Tag(name="Servicers Dashboard")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=1801
     *         ),
     *         @SWG\Property(
     *              property="StartPause",
     *              type="integer",
     *              example=1
     *          ),
     *         @SWG\Property(
     *              property="DateTime",
     *              type="string",
     *              example="2020/05/20 11:54:00"
     *          )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Starts the task.",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Started",
     *              type="string",
     *              example="12:57AM"
     *          )
     *     )
     * )
     * @Post("/starttask", name="vrs_pwa_starttask")
     * @return array
     * @param Request $request
     */
    public function StartTask(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.starttask_service');
            $content = json_decode($request->getContent(),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            $mobileHeaders = $request->attributes->get(GeneralConstants::MOBILE_HEADERS);
            return $servicersDashboard->StartTask($servicerID,$content,$mobileHeaders);
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
     * Clocks In/Out
     * @SWG\Tag(name="Servicers Dashboard")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="1=ClockIn, 0=ClockOut",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ClockInOut",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="Mileage",
     *              type="integer",
     *              example=1000
     *          ),
     *         @SWG\Property(
     *              property="DateTime",
     *              type="string",
     *              example="2020/05/20 11:54:00"
     *          )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Clocks In/Out",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Status",
     *              type="string",
     *              example="Success"
     *          )
     *     )
     * )
     * @Post("/clockinout", name="vrs_pwa_clockinout")
     * @return array
     * @param Request $request
     */
    public function ClockInOut(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.servicers_dashboard');
            $content = json_decode($request->getContent(),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->ClockInOut($servicerID,$content);
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
     * Accepts/Declines a task
     * @SWG\Tag(name="Servicers Dashboard")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="1=Accept, 0=Decline",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=100
     *         ),
     *     @SWG\Property(
     *              property="AcceptDecline",
     *              type="integer",
     *              example=1
     *         ),
     *     @SWG\Property(
     *              property="DateTime",
     *              type="string",
     *              example="2020-06-06 08:00:00"
     *         )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Accepts/Declines a task",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Status",
     *              type="string",
     *              example="Success"
     *          )
     *     )
     * )
     * @Post("/task/acceptdecline", name="vrs_pwa_task_accept_decline")
     * @return array
     * @param Request $request
     */
    public function AcceptDeclineTask(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.servicers_dashboard');
            $content = json_decode($request->getContent(),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->AcceptDeclineTask($servicerID,$content);
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
     * Changes the task date
     * @SWG\Tag(name="Servicers Dashboard")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="TaskID",
     *              type="integer",
     *              example=100
     *         ),
     *     @SWG\Property(
     *              property="TaskDate",
     *              type="string",
     *              example="2019-11-02"
     *         )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Changes the task date",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Status",
     *              type="string",
     *              example="Success"
     *          )
     *     )
     * )
     * @Post("/task/changedate", name="vrs_pwa_task_changedate")
     * @return array
     * @param Request $request
     */
    public function ChangeTaskDate(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.servicers_dashboard');
            $content = json_decode($request->getContent(),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->ChangeTaskDate($servicerID,$content);
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