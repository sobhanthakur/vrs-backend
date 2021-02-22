<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 14/1/20
 * Time: 8:10 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RegionGroupsServices`
 * @package AppBundle\Service
 */
class RegionGroupsServices extends BaseService
{
    /**
     * Function to validate and get region group details
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $regionGroupsID
     *
     * @return mixed
     */
    public function getRegionGroup($authDetails, $queryParameter, $pathInfo, $regionGroupsID = null)
    {
        $returnData = array();
        try {
            //Get region Repo
            $regionGroupsRepo = $this->entityManager->getRepository('AppBundle:Regiongroups');

            //cheking valid query parameters
            $checkParams = array_diff(array_keys($queryParameter), GeneralConstants::PARAMS);
            if (count($checkParams) > 0) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //cheking valid data of query parameter
            $validation = $this->serviceContainer->get('vrscheduler.public_general_service');
            $validationCheck = $validation->validationCheck($queryParameter);
            if (!$validationCheck) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //check for limit option in query paramter
            (isset($queryParameter[GeneralConstants::PARAMS['PER_PAGE']]) ? $limit = $queryParameter[GeneralConstants::PARAMS['PER_PAGE']] : $limit = 20);

            //Setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //Getting region groups Detail
            $regionGroupsData = $regionGroupsRepo->getItems($authDetails['customerID'], $queryParameter, $regionGroupsID, $offset, $limit);

            //return 404 if resource not found
            if (empty($regionGroupsData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = count($regionGroupsRepo->getItemsCount($authDetails['customerID'], $queryParameter, $regionGroupsID, $offset));

            //setting page count
            $totalPage = (int) ceil($totalItems/$limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($regionGroupsData); $i++) {
                if (isset($regionGroupsData[$i][GeneralConstants::CREATEDATE])) {
                    $regionGroupsData[$i][GeneralConstants::CREATEDATE] = $regionGroupsData[$i][GeneralConstants::CREATEDATE]->format('Ymd');
                }
            }
            
            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit)  ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $regionGroupsData;
            $returnData['page_count'] = $totalPage;
            $returnData['page_size'] = $limit;
            $returnData['page'] = $offset;
            $returnData['total_items'] = $totalItems;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::REGION_GROUPS_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }
}