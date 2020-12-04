<?php
/**
 *  ResponseListener for Handling the operations before releasing Response From Application.
 *
 *  @category EventListener
 *  @author Sobhan Thakur
 */

namespace AppBundle\EventListener;

use AppBundle\Constants\ApiRoutes;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use AppBundle\Service\BaseService;
class ResponseListener extends BaseService
{
    /**
     * @var LoggerInterface
     */
    private $apiLogger;

    /**
     * ResponseListener constructor.
     *
     * @param LoggerInterface $apiLogger
     */
    public function __construct(LoggerInterface $apiLogger)
    {
        $this->apiLogger = $apiLogger;
    }

    /**
     * Function to be executed before releasing final Response.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();
        if($request->getMethod() === 'POST' &&
            $response->getStatusCode() === 200
        ) {
            $response->setStatusCode(201);
        }

        //Enable Headers for CORS
        $response->headers->set('Access-Control-Allow-Origin','*');
        $response->headers->set('Access-Control-Allow-Headers','X-Requested-With, Cache-Control, Content-Type, Authorization, Accept-Language,Content-Language,Last-Event-ID,X-HTTP-Method-Override, Offline');
        $response->headers->set('Access-Control-Allow-Methods','GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Credentials', true);

        $responseContent = $response->getContent();

        $route = $request->attributes->get('_route');

        if (!in_array($route,ApiRoutes::RESTRICT_RESPONSE_LOGS)) {
            $this->apiLogger->debug('API Request/Response',
                array_merge($request->headers->all(),
                    [
                        'request_content' => $request->getContent(),
                        'response_content' => $responseContent,
                        'response_status_code' => $response->getStatusCode()
                    ]
                )
            );
        }
    }
}