<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/6/20
 * Time: 1:57 PM
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
class UnscheduledTask extends FOSRestController
{
    /**
     * Shows Task Details that needs to be shown in the servicers dashboard
     * @SWG\Tag(name="Unscheduled Task")
     * @Get("/unscheduled/properties", name="vrs_pwa_unscheduled_properties")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of properties for the unscheduled task",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Properties",
     *              type="string",
     *              example=
     *               {
     *                  {
     *     "PropertyID":123,
     *     "Propertyname":"ABCD"
     *                  }
     *              }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function GetAllProperties(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION); 
        try {
            $servicersDashboard = $this->container->get(GeneralConstants::UNSCHEDULED_TASK);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->GetProperties($servicerID);
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
     * Property Tab Details
     * @SWG\Tag(name="Unscheduled Task")
     * @Get("/unscheduled/tabs/property", name="vrs_pwa_unscheduled_tabs_property")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 the following request format:
    {
    ""PropertyID"":1
    }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Property tab details for unscheduled tasks",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="PropertyDetails",
     *              type="string",
     *              example=
    {
    "PropertyDetails": {
    "PropertyID": 171,
    "PropertyFile": null,
    "Description": "Please enter through the back door.",
    "Address": "3523 Chapel Hill Dr",
    "DoorCode": "1234",
    "PropertyName": "Chapel Hill",
    "InternalPropertyNotes": "Internal Property Notes",
    "StaffDashboardNote": "This is the Staff Dashboard Note"
    },
    "ServicerDetails": {
    "AllowAdminAccess": 1,
    "Servicers_Email": "",
    "Customers_Email": "jill@vrscheduler.com"
    }
    }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function PropertyTab(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION); 
        try {
            $servicersDashboard = $this->container->get(GeneralConstants::UNSCHEDULED_TASK);
            $content = json_decode(base64_decode($request->get('data')),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->PropertyTab($servicerID,$content);
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
     * Image Tab Details
     * @SWG\Tag(name="Unscheduled Task")
     * @Get("/unscheduled/tabs/image", name="vrs_pwa_unscheduled_tabs_Image")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 the following request format:
    {
    ""PropertyID"":1
    }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Image tab details for unscheduled tasks",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Images",
     *              type="string",
     *              example=
                        {

     *     {
                    "SortOrder": 1,
                    "ImageID": 15,
                    "ImageTitle": "Kitchen Layout",
                    "Image": "abcd.jpg",
                    "ImageDescription": ""
                    }
     *                      }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function ImagesTab(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION); 
        try {
            $servicersDashboard = $this->container->get(GeneralConstants::UNSCHEDULED_TASK);
            $content = json_decode(base64_decode($request->get('data')),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->ImageTab($servicerID,$content);
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
     * Details for Unscheduled tasks
     * @SWG\Tag(name="Unscheduled Task")
     * @Get("/unscheduled/tasks", name="vrs_pwa_unscheduled_tasks")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 the following request format:
    {
    ""PropertyID"":1
    }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Details for the unscheduled task",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Details",
     *              type="string",
     *              example=
                    {

                        "ShowIssuesLog": "1",
                        "TaskName": "MAINT",
                        "IncludeServicerNote": "1",
                        "IncludeToOwnerNote": "1",
                        "DefaultToOwnerNote": "Clean",
                        "StaffDashboardNote": "This is the Staff Dashboard Note",
                        "PropertyName": "Property Name"
                    }
     *          ),
     *     @SWG\Property(
     *              property="Tabs",
     *              type="string",
     *              example=
                    {
                    "Manage" : 1,
                    "Property" : 1,
                    "Log" : 1,
                    "Image" : 1
                    }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function UnscheduledTaskDetails(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION); 
        try {
            $servicersDashboard = $this->container->get(GeneralConstants::UNSCHEDULED_TASK);
            $content = json_decode(base64_decode($request->get('data')),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->UnscheduledTaskDetails($servicerID,$content);
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
     * Marks Complete/Incomplete and assign task
     * @SWG\Tag(name="Unscheduled Task")
     * @Post("/unscheduled/tasks/complete", name="vrs_pwa_unscheduled_tasks_complete")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="CompleteStatus: 1 = Mark Task Complete, 0 = Add Incomplete Task and Start",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="PropertyID",
     *              type="integer",
     *              example=1801
     *         ),
     *       @SWG\Property(
     *              property="CompleteStatus",
     *              type="boolean",
     *              example=1
     *         ),
     *      @SWG\Property(
     *              property="DateTime",
     *              type="string",
     *              example="2020/07/06 08:53:00"
     *         ),
     *       @SWG\Property(
     *              property="Details",
     *              type="string",
     *              example={
                        "NoteToOwner" : "This is note to Owner",
                        "UnscheduledTaskNote" : "This is Unscheduled Task note",
                        "SendToOwnerNote" : 1,
                        "TaskName" : "TaskName"
                        }
     *         )
     *    )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Marks Complete/Incomplete and assign a task",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function UnscheduledAssignTask(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION); 
        try {
            $servicersDashboard = $this->container->get(GeneralConstants::UNSCHEDULED_TASK);
            $content = json_decode($request->getContent(),true);
            // Send an empty array if content is blank
            if (empty($content)) {
                return [];
            }
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            $mobileHeaders = $request->attributes->get(GeneralConstants::MOBILE_HEADERS);
            return $servicersDashboard->CompleteUnscheduledTask($servicerID,$content,$mobileHeaders);
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