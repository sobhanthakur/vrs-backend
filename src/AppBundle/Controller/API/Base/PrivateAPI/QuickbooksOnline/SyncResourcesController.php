<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 17/2/20
 * Time: 10:38 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\QuickbooksOnline;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class SyncResourcesController extends FOSRestController
{
    /**
     * Connects to Quickbooks online to fetch the list of customers and items
     * @SWG\Tag(name="Quickbooks Online")
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbo/syncresources", name="vrs_qbo_syncresources")
     */
    public function SyncResources(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $qbService = $this->container->get('vrscheduler.quickbooksonline_resources');
            return $qbService->SyncResources($customerID,$this->container->getParameter('QuickBooksConfiguration'));
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