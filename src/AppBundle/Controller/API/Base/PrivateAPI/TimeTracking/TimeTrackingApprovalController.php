<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 22/11/19
 * Time: 11:41 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\TimeTracking;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

/**
 * Class TimeTrackingApprovalController
 * @package AppBundle\Controller\API\Base\PrivateAPI\TimeTracking
 */
class TimeTrackingApprovalController extends FOSRestController
{
    /**
     * Fetch Completed and Billable Tasks from VRS
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Fetch Completed and Billable Tasks from VRS:
    {
    ""IntegrationID"":1,
    ""Filters"": {
    ""Status"": [""Approved"",""New"",""Excluded""],
    ""Staff"": [1,2,3,4,5],
    ""CompletedDate"":  {
    ""From"" : ""2018-09-09"",
    ""To"" : ""2018-09-09""
    },
    ""CreateDate"":  {
    ""From"" : ""2019-01-11"",
    ""To"" : ""2019-01-15""
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
     *     description="Fetches the list of tasks that are completed and billable."
     * )
     * @return array
     * @param Request $request
     * @Get("/timeclockdays", name="vrs_timeclockdays")
     */
    public function TimeClockRequest(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $timetrackingApprovalService = $this->container->get('vrscheduler.timetracking_approval');
            return $timetrackingApprovalService->FetchTimeClock($customerID, $data);
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
     * Save TimeClockDays and approve/exclude for TimeTracking.
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Status Codes: 0=Exclude, 1=Approve",
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
     *                      "TimeClockDaysID":10,
     *                      "Status":1,
     *                      "Date":"2019-10-12"
     *                  }
     *              }
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Save TimeClockDays and approve/exclude for TimeTracking.",
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
     * @Post("/qbdtimetracking/approve", name="vrs_qbdtimetracking_approve")
     */
    public function SaveTimeTrackingApproval(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $timetrackingApprovalService = $this->container->get('vrscheduler.timetracking_approval');
            $content = json_decode($request->getContent(),true);
            return $timetrackingApprovalService->ApproveTimeTracking($customerID,$content);
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
     * Get Drive Time for Approved Staffs
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 encode {""IntegrationID"":1}. Example eyJJbnRlZ3JhdGlvbklEIjoxfQ=="
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Returns the drive time records for the approved staffs"
     * )
     * @return array
     * @param Request $request
     * @Get("/drivetime", name="vrs_staffs_drivetime")
     */
    public function DriveTime(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $timetrackingApprovalService = $this->container->get('vrscheduler.timetracking_approval');
            return $timetrackingApprovalService->FetchDriveTimeForStaffs($customerID, $data);
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