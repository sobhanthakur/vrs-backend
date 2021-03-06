<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 21/10/19
 * Time: 4:48 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\TimeTracking;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class MapStaffsController extends FOSRestController
{
    /**
     * Fetch Employees from VRS.
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Response(
     *     response=200,
     *     description="Fetch the list of active Employees"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbdemployees", name="vrs_qbdemployees")
     */
    public function FetchEmployees(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $mapBillingService = $this->container->get(GeneralConstants::MAP_STAFF_SERVICE);
            return $mapBillingService->FetchEmployees($customerID);
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
     * Fetch Staffs from VRS.
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Make changes and encode the following to Base64:
            {
            ""IntegrationID"":1,
            ""Filters"": {
            ""Status"": [""Matched"",""Not Yet Matched""],
            ""StaffTag"": [1,2,3,4,5],
            ""Department"": [10,12,19],
            ""CreateDate"":  {
            ""From"" : ""2018-09-09"",
            ""To"" : ""2018-09-09""
            }
            },
            ""Pagination"": {
            ""Offset"": 1,
            ""Limit"": 10
            }
            }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Fetch the list of Staffs"
     * )
     * @return array
     * @param Request $request
     * @Get("/staffs", name="vrs_staffs")
     */
    public function MapStaffs(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $mapStaffs = $this->container->get(GeneralConstants::MAP_STAFF_SERVICE);
            return $mapStaffs->MapStaffs($customerID, $data);
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
     * Map and Save VRS Staffs with QBD Employees.
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="IntegrationID",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="Data",
     *              example=
     *               {
     *                  {
     *                      "PropertyID":32,
     *                      "IntegrationQBDCustomerID":2
     *                  },
     *                  {
     *                      "PropertyID":35,
     *                      "IntegrationQBDCustomerID":12
     *                  }
     *              }
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Save Mapped info of VRS Staffs and QBD Employees",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ReasonCode",
     *              type="integer",
     *              example=0
     *          ),
     *          @SWG\Property(
     *              property="ReasonText",
     *              type="string",
     *              example="Success"
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Post("/qbdemployees/map", name="vrs_qbdemployees_map")
     */
    public function MapStaffsToEmployees(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $mapStaffs = $this->container->get(GeneralConstants::MAP_STAFF_SERVICE);
            $content = json_decode($request->getContent(),true);
            return $mapStaffs->MapStaffsToEmployees($customerID, $content);
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