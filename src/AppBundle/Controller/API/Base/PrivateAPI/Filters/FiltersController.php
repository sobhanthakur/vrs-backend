<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 14/10/19
 * Time: 1:26 PM
 */

namespace AppBundle\Controller\API\Base\PrivateAPI\Filters;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swagger\Annotations as SWG;

class FiltersController extends FOSRestController
{
    /**
     * Fetches property tags
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Property tags"
     * )
     * @return array
     * @param Request $request
     * @Get("/propertytags", name="vrs_propertytags")
     */
    public function PropertyTags(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->PropertyGroupsFilter($customerID);
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
     * Fetches Region Groups
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Regions Groups"
     * )
     * @return array
     * @param Request $request
     * @Get("/regions", name="vrs_regions")
     */
    public function RegionGroups(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->RegionGroupsFilter($customerID);
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
     * Fetches Owners
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Owners"
     * )
     * @return array
     * @param Request $request
     * @Get("/owners", name="vrs_owners")
     */
    public function Owners(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->OwnersFilter($customerID);
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
     * Fetches Staff Tags
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Staff Tags"
     * )
     * @return array
     * @param Request $request
     * @Get("/stafftags", name="vrs_stafftags")
     */
    public function StaffTags(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->StaffTagFilter($customerID);
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
     * Fetches Departments
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Departments"
     * )
     * @return array
     * @param Request $request
     * @Get("/departments", name="vrs_departments")
     */
    public function Departments(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->DepartmentsFilter($customerID);
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
     * Fetches Properties
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Properties"
     * )
     * @return array
     * @param Request $request
     * @Get("/property", name="vrs_property")
     */
    public function Property(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->PropertyFilter($customerID);
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
     * Fetches Staffs from VRS
     * @SWG\Tag(name="Filters")
     * @SWG\Response(
     *     response=200,
     *     description="Fetches Staffs from VRS"
     * )
     * @return array
     * @param Request $request
     * @Get("/staff", name="vrs_staff")
     */
    public function Staff(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        try {
            $customerID = $request->attributes->get('AuthPayload')['message']['CustomerID'];
            $filterService = $this->container->get('vrscheduler.filter_service');
            return $filterService->PropertyGroupsFilter($customerID);
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