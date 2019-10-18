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
            default :
                $messageKey = ErrorConstants::INTERNAL_ERR;
                break;
        }
        $responseService = $this->serviceContainer->get('vrscheduler.api_response_service');

        // Creating Http Error response.
        $result = $responseService->createApiErrorResponse($messageKey, $status);
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