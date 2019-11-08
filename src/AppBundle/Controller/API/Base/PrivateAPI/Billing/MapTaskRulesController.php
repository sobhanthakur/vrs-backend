<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 25/10/19
 * Time: 5:00 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Billing;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class MapTaskRulesController extends FOSRestController
{
    /**
     * Fetch Task Rules from VRS.
     * @SWG\Tag(name="Billing")
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
    ""Department"": [1,2,3,4,5],
    ""Billable"": true,
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
     *     description="Provides the list of Task Rules based on the filters"
     * )
     * @return array
     * @param Request $request
     * @Get("/taskrules", name="vrs_task_rules")
     */
    public function MapTaskRules(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapTaskRuleService = $this->container->get('vrscheduler.map_task_rules');
            return $mapTaskRuleService->MapTaskRules($customerID, $data,$request->getSession());
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
     * Fetch Items from VRS.
     * @SWG\Tag(name="Billing")
     * @SWG\Response(
     *     response=200,
     *     description="Fetch the list of active Items"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbditems", name="vrs_qbditems")
     */
    public function FetchItems(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapTaskRuleService = $this->container->get('vrscheduler.map_task_rules');
            return $mapTaskRuleService->FetchItems($customerID);
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