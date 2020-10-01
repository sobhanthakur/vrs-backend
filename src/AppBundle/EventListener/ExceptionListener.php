<?php
/**
 * Exception listener class for the kernel exceptions
 *
 * @author Sobhan Thakur
 *
 * @category Listener
 *
 */

namespace AppBundle\EventListener;

use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use AppBundle\Service\BaseService;
use AppBundle\Constants\ErrorConstants;

/**
 * Class ExceptionListener
 * @package AppBundle\EventListener
 */
class ExceptionListener extends BaseService
{
    /**
     * Function for handling exceptions
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $status = method_exists($event->getException(), 'getStatusCode')
            ? $event->getException()->getStatusCode()
            : 500;

        $exceptionMessage = $event->getException()->getMessage();

        if($event->getException()->getCode() == 429){
            $status = 429;
        }

        // Log the Exception Not thrown from controllers because other have been logged Already in controllers.
        $this->logger->error("Error",
            [
                $status => $event->getException()->getMessage(),
                'TRACE' => $event->getException()->getTraceAsString()
            ]
        );

        switch ($status) {
            case 400:
            case 401:
            case 409:
            case 422:
            case 502:
                $messageKey = $exceptionMessage;
                break;
            case 403:
                $messageKey = ErrorConstants::INVALID_AUTHORIZATION;
                break;
            case 404:
                $messageKey = ErrorConstants::RESOURCE_NOT_FOUND;
                break;
            case 405:
                $messageKey = ErrorConstants::METHOD_NOT_ALLOWED;
                break;
            case 408:
                $messageKey = ErrorConstants::REQ_TIME_OUT;
                break;
            case 500:
                $messageKey = ErrorConstants::INTERNAL_ERR;
                break;
            case 503:
                $messageKey = ErrorConstants::SERVICE_UNAVAIL;
                break;
            case 504:
                $messageKey = ErrorConstants::GATEWAY_TIMEOUT;
                break;
            case 429:
                $messageKey = ErrorConstants::LIMIT_EXHAUST;
                break;
            default :
                $messageKey = ErrorConstants::INTERNAL_ERR;
                break;
        }

        // Send Mail on Error (422,500)
        $request = $event->getRequest();
        if ($status === 422 || $status === 500) {
            $content = [];
            $content['Subject'] = "HTTP Error: ".$status." ON ".$this->serviceContainer->getParameter('api_host');
            $content['JWT'] = $request->headers->has('authorization') ? explode(" ",$request->headers->get('authorization'))[1] : "";
            $content['RequestContent'] = $request->getContent();
            $content['Error'] = $exceptionMessage;

            $authPayload = [];
            $authPayload['CustomerID'] = null;
            $authPayload['ServicerID'] = null;
            if ($request->attributes->get('AuthPayload'))
            {
                $temp1 = $request->attributes->get('AuthPayload');
                if (array_key_exists('message',$temp1)) {
                    $temp2 = $temp1['message'];
                    array_key_exists('ServicerID',$temp2) ? $authPayload['ServicerID'] = $temp2['ServicerID'] : $authPayload['ServicerID'] = null;
                    array_key_exists('CustomerID',$temp2) ? $authPayload['CustomerID'] = $temp2['CustomerID'] : $authPayload['CustomerID'] = null;
                }
            }
            $content['UserInfo'] = $authPayload;
            $this->serviceContainer->get('vrscheduler.mail_service')->SendMailFunction($content);
        }

        $responseService = $this->serviceContainer->get('vrscheduler.api_response_service');

        $result = $responseService->createApiErrorResponse($messageKey);
        $response = new JsonResponse($result, $status);

        // Logging Exception in Exception log.
        $this->logger->error('VRS Exception :', [
            'Response' => [
                'Headers' => $response->headers->all(),
                'Content' => $response->getContent()
            ]
        ]);
        $event->setResponse($response);
    }
}