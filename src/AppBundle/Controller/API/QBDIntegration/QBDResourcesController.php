<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 9/12/19
 * Time: 4:19 PM
 */

namespace AppBundle\Controller\API\QBDIntegration;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function AddCustomersRequest(Request $request)
    {

    }
}