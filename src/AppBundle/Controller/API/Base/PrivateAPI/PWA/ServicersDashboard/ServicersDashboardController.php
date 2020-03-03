<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 10/2/20
 * Time: 11:33 AM
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

class ServicersDashboardController extends FOSRestController
{
    /**
     * Shows Task Details that needs to be shown in the servicers dashboard
     * @SWG\Tag(name="Servicers Dashboard")
     * @Get("/tasks", name="vrs_pwa_tasks")
     * @SWG\Response(
     *     response=200,
     *     description="Authenticates the servicer and returns a JWT in return.",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="Tasks",
     *              type="string",
     *              example=
     *               {
     *                  {

     *                      "GuestDetails" : {
     *                        "Name" : "John Smith" ,
     *                        "Email" : "smithjohn@gmail.com",
     *                        "Phone" : "902124",
     *                        "Number" : "2"
     *                      },
     *                      "TaskEstimates" : {
     *                          "Min" : 0,
     *                          "Max" : 2
     *                      },
     *                      "AcceptDecline" : 1,
     *                      "Expand" : 1,
     *                      "StartTask" : 1,
     *                      "AssignedDate" : "2019-10-10",
     *                      "Window" : {
     *                          "From" : "2019-10-09",
     *                          "To" : "2019-10-11"
     *                      },
     *                      "Details" : {
     *                          "TaskID" : 1,
     *                          "TaskName" : "Check IN",
     *                          "Region" : "USA",
     *                          "RegionColor" : "#DAA902",
     *                          "Map" : {
     *                              "Lat" : "100.21",
     *                              "Long" : "104.22"
     *                          }
     *                      }
     *                  }
     *              }
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function ServicersDashboard(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $servicersDashboard = $this->container->get('vrscheduler.servicers_dashboard');
            $servicerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID];
            return $servicersDashboard->GetTasks($servicerID);
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