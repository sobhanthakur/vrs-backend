<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 25/10/19
 * Time: 5:00 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Billing;
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
    ""Billable"": [""Billable"",""Not Billable""],
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
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $mapTaskRuleService = $this->container->get(GeneralConstants::MAP_TASKTULES_SERVICE);
            return $mapTaskRuleService->MapTaskRules($customerID, $data);
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
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $mapTaskRuleService = $this->container->get(GeneralConstants::MAP_TASKTULES_SERVICE);
            return $mapTaskRuleService->FetchItems($customerID);
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
     * Map and Save VRS TaskRules with QBD items.
     * @SWG\Tag(name="Billing")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="For BillType, Labor=0,Materials=1",
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
     *                      "TaskRuleID":32,
     *                      "IntegrationQBDItemID":2,
     *                      "BillType":0
     *                  },
     *                  {
     *                      "TaskRuleID":35,
     *                      "IntegrationQBDItemID":12,
     *                      "BillType":1
     *                  }
     *              }
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Save Mapped info of VRS TaskRules and QBD Items",
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
     * @Post("/qbditems/map", name="vrs_qbditems_map")
     */
    public function MapTaskRulesToitems(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $mapTaskRuleService = $this->container->get(GeneralConstants::MAP_TASKTULES_SERVICE);
            $content = json_decode($request->getContent(),true);
            return $mapTaskRuleService->MapTaskRulesToItems($customerID,$content);
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