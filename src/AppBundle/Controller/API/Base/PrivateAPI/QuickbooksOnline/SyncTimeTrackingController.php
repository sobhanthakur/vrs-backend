<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 17/3/20
 * Time: 2:08 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\QuickbooksOnline;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use QuickBooksOnline\API\Exception\ServiceException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

/**
 * Class SyncTimeTrackingController
 * @package AppBundle\Controller\API\Base\PrivateAPI\QuickbooksOnline
 */
class SyncTimeTrackingController extends FOSRestController
{
    /**
     * Connects to Quickbooks online and create TimeActivity information
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
     * @return array | JsonResponse
     * @throws ServiceException
     * @param Request $request
     * @Get("/qbo/synctimetracking", name="vrs_qbo_synctimetracking")
     */
    public function SyncTimeTracking(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $qbLogger = $this->container->get(GeneralConstants::MONOLOG_QB);
        try {
            $customerID = $request->attributes->get(GeneralConstants::AUTHPAYLOAD)[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];
            $data = json_decode(base64_decode($request->get('data')),true);
            $integrationID = $data['IntegrationID'];
            $qbService = $this->container->get('vrscheduler.quickbooksonline_timetracking');
            return $qbService->SyncTimeTracking($customerID,$this->container->getParameter('QuickBooksConfiguration'),$integrationID);
        } catch (ServiceException $exception) {

            // SDK Exception Error Handling
            $qbLogger->debug('API Request: ', [
                'Error' => [
                    'TimeTracking' => $exception->getMessage()
                ]
            ]);

            $xml = $exception->getMessage();
            $from = '<IntuitResponse';
            $to = ']';
            $str = $xml;
            $sub = substr($str, strpos($str,$from),strlen($str));
            libxml_use_internal_errors(true);

            $exceptionMsg = simplexml_load_string(substr($sub,0,strpos($sub,$to)));
            if ($exceptionMsg) {
                $response = new JsonResponse();
                $response->setStatusCode(422);
                $response->setContent(json_encode([
                    'ReasonCode' => 1023,
                    'ReasonText' => (String)$exceptionMsg->Fault->Error->Detail
                ]));
                return $response;
            }

            throw new UnprocessableEntityHttpException(ErrorConstants::QBO_CONNECTION_ERROR);
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