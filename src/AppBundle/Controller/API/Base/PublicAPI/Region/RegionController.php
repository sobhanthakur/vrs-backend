<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/1/20
 * Time: 4:08 PM
 */

namespace AppBundle\Controller\API\Base\PublicAPI\Region;

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


class RegionController extends FOSRestController
{
    /**
     * Regions
     * @SWG\Tag(name="Regions")
     * @SWG\Response(
     *     response=200,
     *     description="Returns all regions of the customer",
     *     @SWG\Schema(
     *         @SWG\Property(
     *              property="url",
     *              type="string",
     *              example="/api/v1/regiongroups"
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
     *                      "RegionID": 1,
     *                      "RegionGroupID": 3,
     *                      "Region": "NorthSide",
     *                      "Color": "#ffcccc",
     *                      "TimeZoneID": 3,
     *                      "CreateDate": "20190302"
     *
     *                  },
     *                  {
     *                      "RegionID": 2,
     *                      "RegionGroupID": 3,
     *                      "Region": "West-NorthSide",
     *                      "Color": "#ffcccc",
     *                      "TimeZoneID": 4,
     *                      "CreateDate": "20190301"
     *
     *                  }
     *              }
     *         )
     *     )
     * )
     * @return array
     * @param Request $request
     * @Get("/regions", name="regions_get")
     */
    public function GetRegionGroup(Request $request)
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
            //collecting authdetails
            $authDetails = $request->attributes->get(GeneralConstants::AUTHPAYLOAD);
            $restriction = $authDetails[GeneralConstants::PROPERTIES];

            //Get pathinfo
            $pathInfo = $request->getPathInfo();
            $baseName = GeneralConstants::CHECK_API_RESTRICTION['REGIONS'];

            //Get auth service
            $authService = $this->container->get('vrscheduler.public_authentication_service');
            //check resteiction for the user
            $restrictionStatus = $authService->resourceRestriction($restriction, $baseName);
            if (!$restrictionStatus->accessLevel) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHORIZATION);
            }

            //Get regions details
            $regionsService = $this->container->get('vrscheduler.public_regions_service');
            $regionsDetails = $regionsService->getRegions($authDetails, $queryParameter, $pathInfo);
            return $regionsDetails;

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