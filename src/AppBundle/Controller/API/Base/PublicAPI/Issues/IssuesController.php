<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 21/1/20
 * Time: 1:58 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Issues;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;

class IssuesController extends FOSRestController
{
    /**
     * Fetch all issues of the consumer
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Issues")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all issues booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/issues"
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
     *                      "IssueID": 135,
     *                      "StatusID": 0,
     *                      "IssueType": 1,
     *                      "Urgent": false,
     *                      "Issue": "Maintenance - Dump",
     *                      "Notes": "",
     *                      "StaffNotes": null,
     *                      "InternalNotes": null,
     *                      "Image1": "https://images.vrscheduler.com/70/IMG_5076.JPG",
     *                      "Image2": "",
     *                      "Image3": "",
     *                      "Billable": false,
     *                      "PropertyID": 36,
     *                      "ClosedDate": "20170809",
     *                      "CreateDate": "20170804"
     *                  },
     *                  {
     *                      "IssueID": 137,
     *                      "StatusID": 0,
     *                      "IssueType": 1,
     *                      "Urgent": false,
     *                      "Issue": "Maintenance - Demo",
     *                      "Notes": "",
     *                      "StaffNotes": null,
     *                      "InternalNotes": null,
     *                      "Image1": "https://images.vrscheduler.com/70/IMG_5096.JPG",
     *                      "Image2": "",
     *                      "Image3": "",
     *                      "Billable": false,
     *                      "PropertyID": 36,
     *                      "ClosedDate": "20170809",
     *                      "CreateDate": "20170804"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/issues", name="issues_get")
     */
    public function GetIssues(Request $request)
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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['ISSUES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check resteiction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get issue booking details
            $issueService = $this->container->get('vrscheduler.public_issues_service');
            $issues = $issueService->getIssues($authDetails, $queryParameter, $pathInfo);
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
        return $issues;
    }

    /**
     * Fetch all issues of the consumer
     * @RateLimit(limit = GeneralConstants::LIMIT, period = GeneralConstants::PERIOD)
     * @SWG\Tag(name="Issues")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all issues booking details of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/issues"
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
     *                      "IssueID": 135,
     *                      "StatusID": 0,
     *                      "IssueType": 1,
     *                      "Urgent": false,
     *                      "Issue": "Maintenance - Dump",
     *                      "Notes": "",
     *                      "StaffNotes": null,
     *                      "InternalNotes": null,
     *                      "Image1": "https://images.vrscheduler.com/70/IMG_5076.JPG",
     *                      "Image2": "",
     *                      "Image3": "",
     *                      "Billable": false,
     *                      "PropertyID": 36,
     *                      "ClosedDate": "20170809",
     *                      "CreateDate": "20170804"
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/issues/{id}", name="issues_get_id")
     */
    public function getIssueById(Request $request)
    {

        $queryParameter = array();

        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Getting issueId from parameter
        $issuesID = $request->get('id');

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
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['ISSUES'];
            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check resteiction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }
            //Get issue booking details
            $issuesService = $this->container->get('vrscheduler.public_issues_service');
            $issues = $issuesService->getIssues($authDetails, $queryParameter, $pathInfo, $issuesID);


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
        return $issues;
    }

}