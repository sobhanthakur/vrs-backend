<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 4/11/19
 * Time: 11:35 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Billing;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class BillingApprovalController extends FOSRestController
{
    /**
     * Fetch Completed and Billable Tasks from VRS
     * @SWG\Tag(name="Billing")
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
    ""Property"": [1,2,3,4,5],
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
     * @Get("/tasks", name="vrs_tasks")
     */
    public function MapTasks(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $billingApprovalService = $this->container->get('vrscheduler.billing_approval');
            return $billingApprovalService->MapTasks($customerID, $data);
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
     * Save Tasks and approve/exclude for billing.
     * @SWG\Tag(name="Billing")
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
     *                      "TaskID":10,
     *                      "Status":1
     *                  }
     *              }
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Save Tasks and approve/exclude for billing.",
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
     * @Post("/qbdbilling/approve", name="vrs_qbdbilling_approve")
     */
    public function SaveBillingApproval(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $billingApprovalService = $this->container->get('vrscheduler.billing_approval');
            $content = json_decode($request->getContent(),true);
            return $billingApprovalService->ApproveBilling($customerID,$content);
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