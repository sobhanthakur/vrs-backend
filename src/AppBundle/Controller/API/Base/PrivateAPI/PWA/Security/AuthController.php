<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 4/2/20
 * Time: 2:57 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\PWA\Security;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class AuthController extends FOSRestController
{
    /**
     * Provide ServicerID and password to authenticate and get a JWT token in return
     * @SWG\Tag(name="Authenticate")
     * @Post("/authenticate", name="vrs_pwa_authenticate")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="ServicerID",
     *              type="integer",
     *              example=1920
     *         ),
     *         @SWG\Property(
     *              property="Password",
     *              type="string",
     *              example="password"
     *         )
     *     )
     *  )
     * @SWG\Response(
     *     response=201,
     *     description="Authenticates the servicer and returns a JWT in return.",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="AccessToken",
     *              type="string",
     *              example="abcsg.vvdd.12fff"
     *          ),
     *          @SWG\Property(
     *              property="ServicerID",
     *              type="integer",
     *              example=1920
     *          ),
     *          @SWG\Property(
     *              property="ServicerName",
     *              type="string",
     *              example="Jill Mason"
     *          ),
     *          @SWG\Property(
     *              property="TimeTracking",
     *              type="boolean",
     *              example=1
     *          ),
     *          @SWG\Property(
     *              property="AcceptDecline",
     *              type="boolean",
     *              example=1
     *          ),
     *          @SWG\Property(
     *              property="Mileage",
     *              type="boolean",
     *              example=0
     *          ),
     *          @SWG\Property(
     *              property="StartEarly",
     *              type="boolean",
     *              example=1
     *          ),
     *          @SWG\Property(
     *              property="ChangeDate",
     *              type="boolean",
     *              example=1
     *          )
     *     )
     * )
     * @return array
     * @param Request $request
     */
    public function PWAAuthentication(Request $request)
    {
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);
        $response = null;
        try {
            $authenticationService = $this->container->get('vrscheduler.authentication_service');
            $content = json_decode($request->getContent(),true);
            return $authenticationService->PWAAutheticate($content);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
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