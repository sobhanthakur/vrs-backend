<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 28/11/19
 * Time: 3:05 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\SyncLogs;
use AppBundle\Constants\ErrorConstants;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

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
     *     description="Fetch All Sync Logs:
    {
    ""IntegrationID"":1,
    ""Filters"": {
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
        $logger = $this->container->get('monolog.logger.exception');
        $response = null;
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $syncLogsService = $this->container->get('vrscheduler.sync_logs');
            $response = $syncLogsService->FetchAllSyncLogs($customerID,$data);
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
        return $response;
    }
}