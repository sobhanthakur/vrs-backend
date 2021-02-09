<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 9/2/21
 * Time: 3:27 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PropertyStatusService extends BaseService
{
    /**
     * Function to validate and get properties details of the consumer
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $propertyStatusID
     *
     * @return mixed
     */
    public function getPropertStatus($authDetails, $queryParameter, $pathInfo, $restriction, $propertyStatusID = null)
    {
        try {
            //Get property Status Repo
            $propertyStatusRepo = $this->entityManager->getRepository('AppBundle:PropertyStatuses');

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

            //Getting property Status Detail
            $propertyData = $propertyStatusRepo->getItems($authDetails['customerID'], $queryParameter, $propertyStatusID, $restriction, $offset, $limit);

            //return 404 if resource not found
            if (empty($propertyData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = count($propertyStatusRepo->getItemsCount($authDetails['customerID'], $queryParameter, $propertyStatusID, $offset));

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($propertyData); $i++) {
                if (isset($propertyData[$i]['CreateDate'])) {
                    $propertyData[$i]['CreateDate'] = $propertyData[$i]['CreateDate']->format('Ymd');
                    $propertyData[$i]['SetOnCheckIn'] = (int)$propertyData[$i]['SetOnCheckIn'];
                    $propertyData[$i]['SetOnCheckOut'] = (int)$propertyData[$i]['SetOnCheckOut'];
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $propertyData;
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
            $this->logger->error(GeneralConstants::PROPERTY_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;

    }
}