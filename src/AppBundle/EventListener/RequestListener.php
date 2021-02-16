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
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AppBundle\Service\BaseService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RequestListener
 * @package AppBundle\EventListener
 */
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

    /**
     * @param GetResponseEvent $event
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Rate limit only for Public APIs
        if(in_array($route, ApiRoutes::PUBLIC_ROUTES)) {
            // Cache Rate Limiting
            $cache = new FilesystemCache();
            // If IP doesn't exist
            if(!$cache->has($request->getClientIp())) {
                $cache->set($request->getClientIp(),1,GeneralConstants::RATE_LIMIT_TTL);
            } else {
                $count = $cache->get($request->getClientIp());
                if($count >= GeneralConstants::RATE_LIMIT) {
                    throw new HttpException(429,ErrorConstants::LIMIT_EXHAUST);
                }
                $cache->set($request->getClientIp(),$count+1,GeneralConstants::RATE_LIMIT_TTL);
            }
        }

        // If Request Method is OPTIONS, then send set the necessary headers.
        if ($event->getRequest()->getMethod() === 'OPTIONS') {
            $event->setResponse(
                new Response('', 204, [
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                    'Access-Control-Allow-Headers' => 'X-Requested-With, Cache-Control, Content-Type, Authorization, Accept-Language,Content-Language,Last-Event-ID,X-HTTP-Method-Override, Offline'
                ])
            );
            return ;
        }

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

        // Check if the incoming route is for the translation APIs
        if(in_array($route, ApiRoutes::TRANSLATION_ROUTES)) {
            $authService = $this->serviceContainer->get('vrscheduler.authentication_service');
            $authenticateResult = $authService->VerifyAuthToken($request,70);
            if($authenticateResult['status']) {
                $request->attributes->set('AuthPayload',$authenticateResult);
            }
        }


        // Check if the incoming public route is present in the array
        if(in_array($route, ApiRoutes::PUBLIC_ROUTES)) {
            $authService = $this->serviceContainer->get('vrscheduler.public_authentication_service');
            $authenticateResult = $authService->VerifyAuthToken($request);
            if($authenticateResult['status']) {
                $request->attributes->set('AuthPayload',$authenticateResult);
            }
        }

        // Check if the incoming request is for the PWA application
        if(in_array($route, ApiRoutes::PWA_ROUTES)) {
            $authService = $this->serviceContainer->get('vrscheduler.authentication_service');
            if(in_array($route, ApiRoutes::LOCATION_ROUTES)) {
                $mobileHeaders = $authService->SetMobileHeaders($request);
                $request->attributes->set(GeneralConstants::MOBILE_HEADERS,$mobileHeaders);
            }

            $authenticateResult = $authService->VerifyPWAAuthentication($request);
            if($authenticateResult['status']) {
                $request->attributes->set('AuthPayload',$authenticateResult);
            }
        }

        // Check if the incoming route is present in the array
        if(in_array($route, ApiRoutes::SMS_ROUTES)) {
            $authService = $this->serviceContainer->get('vrscheduler.authentication_service');
            $authService->SMSAuthentication($request);
        }

        // Stop Request logging temporarily
        // uncomment this in future.

        /*$this->apiLogger->debug('API Request: ', [
            'Request' => [
                'headers' => $request->headers->all(),
                'content' => $request->getContent(),
                'host' => $request->getSchemeAndHttpHost(),
                'uri' => $request->getRequestUri(),
                'method' => $request->getMethod()
            ]
        ]);*/

    }
}