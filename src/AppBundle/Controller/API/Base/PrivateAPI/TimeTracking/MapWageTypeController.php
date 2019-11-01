<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 1/11/19
 * Time: 1:14 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\TimeTracking;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

/**
 * Class MapWageTypeController
 * @package AppBundle\Controller\API\Base\PrivateAPI\TimeTracking
 */
class MapWageTypeController extends FOSRestController
{
    /**
     * Fetch Available Payroll Item Wages from VRS.
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches the list of Quickbooks payroll item wages from VRS"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbdwageitem", name="vrs_qbd_wageitem")
     */
    public function MapProperties(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapWageItemService = $this->container->get('vrscheduler.map_wage_item');
            return $mapWageItemService->FetchQBDWageItems($customerID);
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
     * Updates Payroll Item wages with VRS Payroll Types
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Provide the IntegrationQBDPayrollItemWageID against PayByHour and PayByRate",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="IntegrationID",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="PayByHour",
     *              type="integer",
     *              example=1
     *         ),
     *         @SWG\Property(
     *              property="PayByRate",
     *              type="integer",
     *              example=2
     *         )
     *     )
     *  )
     * @SWG\Tag(name="Time Tracking")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Payroll Item wages with VRS Payroll Types"
     * )
     * @return array
     * @param Request $request
     * @Put("/wageitem", name="vrs_put_wageitem")
     */
    public function MapPayrollItemWages(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapWageItemService = $this->container->get('vrscheduler.map_wage_item');
            $content = json_decode($request->getContent(),true);
            if(empty($content)) {
                $content = [];
            }
            return $mapWageItemService->UpdatePayrollMapping($customerID, $content);
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
     * Get Mapped Payroll Item wages and VRS Payroll Types
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
     *     description="Get Mapped Payroll Item wages and VRS Payroll Types"
     * )
     * @return array
     * @param Request $request
     * @Get("/wageitem", name="vrs_get_wageitem")
     */
    public function GetMappedPayrollItemWages(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $mapWageItemService = $this->container->get('vrscheduler.map_wage_item');
            return $mapWageItemService->GetPayrollMapping($customerID, $data);
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