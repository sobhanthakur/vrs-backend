<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 9/2/21
 * Time: 12:54 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\PropertyStatus;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Swagger\Annotations as SWG;

class PropertyStatuses extends FOSRestController
{
    /**
     * Get property Statuses
     *
     * @SWG\Tag(name="Property Status")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all property statuses",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/propertystatuses"
     *          ),
     *          @SWG\Property(
     *              property="has_more",
     *              type="boolean",
     *              example="true"
     *          ),
     *       @SWG\Property(
     *              property="data",
     *              example=
     *               {
     *                  {
                            "PropertyStatusID": 132,
                            "PropertyStatus": "Inspected",
                            "SetOnCheckIn": 0,
                            "SetOnCheckOut": 0,
                            "CreateDate": 20190302
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/propertystatuses", name="property_statuses_get")
     */
    public function GetPropertyStatuses(Request $request)
    {
        //setting logger
        $logger = $this->container->get(GeneralConstants::MONOLOG_EXCEPTION);

        //Get all query parameter and set it in an array
        $queryParameter = array();
        $params = $request->query->all();
        foreach ($params as $key => $param) {
            (isset($param) && $param != "") ? $queryParameter[strtolower($key)] = strtolower($param) : null;
        }

        try {
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['PROPERTIES'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');

            //check restriction for the user
            $restriction = $authService->resourceRestriction($restriction, $baseName);
            if (!$restriction->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get property details
            $propertiesService = $this->container->get('vrscheduler.public_properties_service');
            $propertyDetails = $propertiesService->getProperties($authDetails, $queryParameter, $pathInfo, $restriction);
            return $propertyDetails;
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