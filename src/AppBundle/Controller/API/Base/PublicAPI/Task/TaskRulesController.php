<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 5/2/20
 * Time: 12:05 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Task;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Swagger\Annotations as SWG;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;

class TaskRulesController extends FOSRestController
{
    /**
     * TaskRulesController controller to fetch all task rule details
     *
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Task Rules")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all task rules of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/taskrules"
     *          ),
     *          @SWG\Property(
     *              property="has_more",
     *              type="boolean",
     *              example="true"
     *          ),
     *       @SWG\Property(
     *              property="data",
     *              example=
     *               {
     *                  {
     *                      "TaskRuleID": 132,
     *                      "Active": true,
     *                      "TaskRule": "Bay View Bungalow",
     *                      "Abbreviation": "BVB",
     *                      "CreateDate": "20190302"
     *
     *                  },
     *                  {
     *                      "TaskRuleID": 133,
     *                      "Active": true,
     *                      "TaskRule": "Bay View Bungalow",
     *                      "Abbreviation": "LIS",
     *                      "CreateDate": "20190332"
     *
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/taskrules", name="taskrules_get")
     */
    public function GetTaskRules(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Get all query parameter and set it in an array
        $queryParameter = array();
        $params = $request->query->all();
        foreach ($params as $key => $param) {
            (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
        }

        try {
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //Get pathinfo
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['TASK_RULES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get taskrules detail
            $taskRulesService = $this->container->get('vrscheduler.public_task_rules_service');
            $taskRulesDetails = $taskRulesService->getTaskRules($authDetails, $queryParameter, $pathInfo);

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
        return $taskRulesDetails;
    }

    /**
     * Fetch task rules of the consumer by id
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Task Rules")
     * @SWG\Response(
     *     response=200,
     *     description="Returns task rules of the customer by id",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/taskrules/4"
     *          ),
     *          @SWG\Property(
     *              property="has_more",
     *              type="boolean",
     *              example="true"
     *          ),
     *       @SWG\Property(
     *              property="data",
     *              example=
     *               {
     *                  {
     *                      "TaskRuleID": 132,
     *                      "Active": true,
     *                      "TaskRule": "Bay View Bungalow",
     *                      "Abbreviation": "BVB",
     *                      "CreateDate": "20190302"
     *
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/taskrules/{id}", name="task_rules_get_id")
     */
    public function getTaskRulesById(Request $request)
    {
        $queryParameter = array();

        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting taskRulesId from parameter
        $taskRulesID = $request->get('id');

        //Getting parameter from the API
        $params = $request->query->all();
        foreach ($params as $key => $param) {
            (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
        }

        try {
            //Getting date from jwt token
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //get base path
            $pathInfo = $request->getPathInfo();

            //check accessbility of the consumer to the resource
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['TASK_RULES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check restriction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get taskrules detail
            $taskRulesService = $this->container->get('vrscheduler.public_task_rules_service');
            $taskRulesDetails = $taskRulesService->getTaskRules($authDetails, $queryParameter, $pathInfo, $taskRulesID);
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
        return $taskRulesDetails;
    }
}