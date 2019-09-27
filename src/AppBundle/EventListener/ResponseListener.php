<?php
/**
 *  ResponseListener for Handling the operations before releasing Response From Application.
 *
 *  @category EventListener
 *  @author Sobhan Thakur
 */

namespace AppBundle\EventListener;

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

        //Enable Headers for CORS
        $response->headers->set('Access-Control-Allow-Origin','*');
        $response->headers->set('Access-Control-Allow-Credentials', true);

        $responseContent = $response->getContent();

        $request = $event->getRequest();
        $this->apiLogger->debug('API Request/Response',
            array_merge($request->headers->all(),
                [
                    'host' => $request->getSchemeAndHttpHost(),
                    'uri' => $request->getRequestUri(),
                    'method' => $request->getMethod(),
                    'request_content' => $request->getContent(),
                    'response_content' => $responseContent
                ]
            )
        );
    }
}