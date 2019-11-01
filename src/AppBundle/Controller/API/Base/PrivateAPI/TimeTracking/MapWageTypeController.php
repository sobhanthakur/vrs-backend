<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 1/11/19
 * Time: 1:14 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\TimeTracking;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;
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
}