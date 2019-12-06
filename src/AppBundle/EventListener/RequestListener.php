<?php
/**
 * Request Listener for handling Authentication and Logging of Requests received by Application.
 *
 * @author Sobhan Thakur
 *
 * @category Listener
 *
 */

namespace AppBundle\EventListener;

use AppBundle\Constants\ApiRoutes;
use AppBundle\Constants\GeneralConstants;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AppBundle\Service\BaseService;
use Symfony\Component\HttpFoundation\Response;

class RequestListener extends BaseService
{
    /**
     * @var LoggerInterface
     */
    private $apiLogger;

    /**
     *  RequestListener constructor.
     *
     * @param LoggerInterface $apiLogger
     */
    public function __construct(LoggerInterface $apiLogger)
    {
        $this->apiLogger = $apiLogger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // If Request Method is OPTIONS, then send set the necessary headers.
        if ($event->getRequest()->getMethod() === 'OPTIONS') {
            $event->setResponse(
                new Response('', 204, [
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                    'Access-Control-Allow-Headers' => 'X-Requested-With, Cache-Control, Content-Type, Authorization'
                ])
            );
            return ;
        }
        $route = $request->attributes->get('_route');

        // Add authorization header
        if (!$request->headers->has(GeneralConstants::AUTHORIZATION) && function_exists('apache_request_headers')) {
            $all = apache_request_headers();
            if (isset($all[GeneralConstants::AUTHORIZATION])) {
                $request->headers->set(GeneralConstants::AUTHORIZATION, $all[GeneralConstants::AUTHORIZATION]);
            }
        }

        // Check if the incoming route is present in the array
        if(in_array($route, ApiRoutes::ROUTES)) {
            $authService = $this->serviceContainer->get('vrscheduler.authentication_service');
            $authenticateResult = $authService->VerifyAuthToken($request);
            if($authenticateResult['status']) {
                $request->attributes->set('AuthPayload',$authenticateResult);
            }
        }
        $this->apiLogger->debug('API Request: ', [
            'Request' => [
                'headers' => $request->headers->all(),
                'content' => $request->getContent()
            ]
        ]);

    }
}