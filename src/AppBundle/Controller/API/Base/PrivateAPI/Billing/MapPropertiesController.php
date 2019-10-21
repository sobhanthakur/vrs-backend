<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 16/10/19
 * Time: 12:27 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Billing;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;


class MapPropertiesController extends FOSRestController
{
    /**
     * Fetch Properties from VRS.
     * @SWG\Tag(name="Map Properties")
     * @SWG\Parameter(
     *     name="data",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Make changes and encode the following to Base64:
          {
            ""IntegrationID"":1,
            ""Filters"": {
            ""Status"": [""Matched"",""Not Yet Matched""],
            ""PropertyTag"": [1,2,3,4,5],
            ""Region"": [10,12,19],
            ""Owner"" : [20,11,29],
            ""CreateDate"":  {
            ""From"" : ""2018-09-09"",
            ""To"" : ""2018-09-09""
            }
            },
            ""Pagination"": {
            ""Offset"": 1,
            ""Limit"": 10
            }
      }"
     *     )
     *  )
     * @SWG\Response(
     *     response=200,
     *     description="Provides the list of properties based on the filters"
     * )
     * @return array
     * @param Request $request
     * @Get("/properties", name="vrs_properties")
     */
    public function MapProperties(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $data = json_decode(base64_decode($request->get('data')),true);
            if(empty($data)) {
                $data = [];
            }
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapBillingService = $this->container->get('vrscheduler.map_properties');
            return $mapBillingService->MapProperties($customerID, $data);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Fetch Customers from VRS.
     * @SWG\Tag(name="Map Properties")
     * @SWG\Response(
     *     response=200,
     *     description="Fetch the list of active customers"
     * )
     * @return array
     * @param Request $request
     * @Get("/qbdcustomers", name="vrs_qbdcustomers")
     */
    public function FetchCustomers(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $mapBillingService = $this->container->get('vrscheduler.map_properties');
            return $mapBillingService->FetchCustomers($customerID);
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $logger->error(__FUNCTION__ . ' function failed due to Error : ' .
                $exception->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}