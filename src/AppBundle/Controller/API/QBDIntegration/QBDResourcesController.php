<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 9/12/19
 * Time: 4:19 PM
 */

namespace AppBundle\Controller\API\QBDIntegration;

use AppBundle\Constants\GeneralConstants;
use AppBundle\QBDHelpers\Applications\QBDResourcesService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QBDResourcesController
 * @package AppBundle\Controller\API\QBDIntegration
 */
class QBDResourcesController extends Controller
{
    /**
     * @Route("qbdresources/sync")
     * @param Request $request
     * @return Response
     */
    public function QBDResourcesRequest(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        try {
//        $content = simplexml_load_string($request->getContent())->xpath('soap:Body');
            $qbdResource = new QBDResourcesService([
                'login' => 'VRS5d9dd0b9237e6',
                'password' => '1',
                'iterator' => null,
                'wsdlPath' => $this->container->getParameter('wsdlpath')
            ]);

            $server = new \SoapServer($this->container->getParameter('wsdlpath'),array('cache_wsdl' => WSDL_CACHE_NONE));
            $server->setObject($qbdResource);

            $response = new Response();
            $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
            ob_start();
            $server->handle();
            $response->setContent(ob_get_clean());

            return $response;
        } catch (\Exception $exception) {
            throw $exception;
            $logger->error($exception->getMessage());
        }
    }
}