<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 18/2/20
 * Time: 10:57 AM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\QuickbooksOnline;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use QuickBooksOnline\API\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class SyncBillingController extends FOSRestController
{
    /**
     * Connects to Quickbooks online and create Billing information
     * @SWG\Tag(name="Quickbooks Online")
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
     *     description="Success"
     * )
     * @return array
     * @throws ServiceException
     * @param Request $request
     * @Get("/qbo/syncbilling", name="vrs_qbo_syncbilling")
     */
    public function SyncBilling(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $data = json_decode(base64_decode($request->get('data')),true);
            $integrationID = $data['IntegrationID'];
            $qbService = $this->container->get('vrscheduler.quickbooksonline_billing');
            return $qbService->SyncBilling($customerID,$this->container->getParameter('QuickBooksConfiguration'),$integrationID);
        } catch (ServiceException $exception) {
            $message = $exception->getMessage();
            $message = preg_match('/<Message>(.*)<\/Message>/m',$message, $match);
            if(!$message) {
                throw new UnprocessableEntityHttpException(ErrorConstants::QBO_CONNECTION_ERROR);
            }
            throw new BadRequestHttpException($match[1]);
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