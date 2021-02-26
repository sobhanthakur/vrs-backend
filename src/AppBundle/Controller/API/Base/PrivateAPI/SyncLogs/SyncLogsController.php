<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 28/11/19
 * Time: 3:05 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\SyncLogs;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations\Put;

class SyncLogsController extends FOSRestController
{
    /**
     * Get OverAll Sync Logs
     * @SWG\Tag(name="Sync Logs")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base 64 the following Payload with appropriate changes:
    {
    ""IntegrationID"":1,
    ""Filters"": {
    ""BatchType"":[""Billing"",""Time Tracking""],
    ""CompletedDate"":  {
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
     * * @SWG\Response(
     *     response=200,
     *     description="Shows the Sync details with sync type and no. of records sent"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbdsynclogs", name="vrs_synclogs")
     */
    public function ListSyncLogs(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $syncLogsService = $this->container->get(GeneralConstants::SYNC_LOGS_SERVICE);
            return $syncLogsService->FetchAllSyncLogs($customerID,$data);
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
     * Get Batch Wise Sync Logs
     * @SWG\Tag(name="Sync Logs")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="0=Billing & 1=Time Tracking
    Base 64 the following Payload with appropriate changes:
    {
    ""IntegrationID"":1,
    ""BatchType"":0,
    ""BatchID"":1,
    ""Pagination"": {
    ""Offset"": 1,
    ""Limit"": 10
    }
    }"
     *     )
     *  )
     * * @SWG\Response(
     *     response=200,
     *     description="Shows the Sync details with sync type and no. of records sent"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbdsynclogs/batch", name="vrs_synclogs_batch")
     */
    public function BatchWiseSyncLogs(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $syncLogsService = $this->container->get(GeneralConstants::SYNC_LOGS_SERVICE);
            return $syncLogsService->BatchWiseLogs($customerID,$data);
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
     * Reset Sync Logs
     * @SWG\Tag(name="Sync Logs")
     * @Put("/logs/reset", name="vrs_logs_reset")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="BatchID",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="BatchType",
     *              type="integer",
     *              example=0
     *         )
     *     )
     *  ),
     * @SWG\Response(
     *     response=200,
     *     description="Reset that batch if sync fails",
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
     */
    public function ResetSyncBatch(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $content = json_decode($request->getContent(),true);
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $syncLogsService = $this->container->get(GeneralConstants::SYNC_LOGS_SERVICE);
            return $syncLogsService->ResetSyncLogs($customerID,$content);
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