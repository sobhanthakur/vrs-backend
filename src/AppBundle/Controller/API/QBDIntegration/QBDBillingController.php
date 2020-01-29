<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 19/12/19
 * Time: 1:07 PM
 */

namespace AppBundle\Controller\API\QBDIntegration;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\QBDHelpers\Applications\QBDBillingBatchService;

/**
 * Class QBDBillingController
 * @package AppBundle\Controller\API\QBDIntegration
 */
class QBDBillingController extends Controller
{
    /**
     * @Route("qbdsalesorderitems")
     * @param Request $request
     * @return Response
     */
    public function QBDBillingBatchRequest(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $apiLogger = $this->container->get(GeneralConstants::MONOLOG_API);
        $login = null;
        $password = null;
        $entityManager = $this->get('doctrine.orm.default_entity_manager');
        $serviceContainer = null;

        try {
            $content = simplexml_load_string($request->getContent())->xpath('soap:Body');
            if (array_key_exists('authenticate', $content[0])) {
                $login = $content[0]->authenticate->strUserName;
                $password = $content[0]->authenticate->strPassword;
                $serviceContainer = $this->get('service_container');
            }

            $qbdResource = new QBDBillingBatchService([
                'login' => $login,
                'password' => $password,
                'iterator' => null,
                'wsdlPath' => $this->container->getParameter('wsdlpath')
            ], $entityManager, $serviceContainer,$apiLogger);

            $server = new \SoapServer($this->container->getParameter('wsdlpath'), array('cache_wsdl' => WSDL_CACHE_NONE));
            $server->setObject($qbdResource);

            $response = new Response();
            $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
            ob_start();
            $server->handle();
            $response->setContent(ob_get_clean());
            return $response;
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