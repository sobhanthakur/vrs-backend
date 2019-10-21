<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 21/10/19
 * Time: 4:48 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\TimeTracking;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class MapStaffsController extends FOSRestController
{
    /**
     * Fetch Employees from VRS.
     * @SWG\Tag(name="Map Staffs")
     * @SWG\Response(
     *     response=200,
     *     description="Fetch the list of active Employees"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbdemployees", name="vrs_qbdemployees")
     */
    public function FetchEmployees(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapBillingService = $this->container->get('vrscheduler.map_staffs');
            return $mapBillingService->FetchEmployees($customerID);
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