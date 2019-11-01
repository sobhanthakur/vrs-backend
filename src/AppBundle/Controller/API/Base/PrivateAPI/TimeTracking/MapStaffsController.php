<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 21/10/19
 * Time: 4:48 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\TimeTracking;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
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
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapBillingService = $this->container->get('vrscheduler.map_staffs');
            return $mapBillingService->FetchEmployees($customerID);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
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
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapStaffs = $this->container->get('vrscheduler.map_staffs');
            return $mapStaffs->MapStaffs($customerID, $data);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}