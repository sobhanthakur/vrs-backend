<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 20/4/20
 * Time: 3:40 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\ServicersDashboard;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations\Get;

class TabsController extends FOSRestController
{
    /**
     * Shows Task Details that needs to be shown in the servicers dashboard
     * @SWG\Tag(name="Tabs")
     * @Get("/tabs/log", name="vrs_pwa_tabs_log")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Base64 the following request format:
    {
    ""PropertyID"":1
    }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Authenticates the servicer and returns a JWT in return.",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Tasks",
     *              type="string",
     *              example= {
     *    "PropertyName": "Chapel Hill Open Issues",
     *    "Details": {
     *        {
     *            "CreateDAte": "2019-06-19 03:01:58.060",
     *            "Issue": "unscheduled issue",
     *            "FromTaskID": null,
     *            "SubmittedByServicerID": "142",
     *            "CustomerName": "Jill Mason",
     *            "SubmittedByName": "LC-Ellie",
     *            "TimeZoneRegion": "US/Pacific",
     *            "Urgent": "0",
     *            "IssueType": "0",
     *            "PropertyID": "171",
     *            "Notes": ""
     *        }
     *    }
     *}
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function LogTab(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $tabsService = $this->container->get('vrscheduler.tabs_service');
            $content = json_decode(base64_decode($request->get('data')),true);
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $tabsService->GetLog($content);
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