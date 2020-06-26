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
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.unscheduled_task');
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
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.unscheduled_task');
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
     * @SWG\Response(
     *     response=200,
     *     description="Image tab details for unscheduled tasks",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="PropertyDetails",
     *              type="string",
     *              example=
                        {

                        }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function ImagesTab(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.unscheduled_task');
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

}