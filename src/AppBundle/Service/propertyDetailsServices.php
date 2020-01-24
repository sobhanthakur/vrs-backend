<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 7/1/20
 * Time: 2:24 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class propertyDetailsServices
 * @package AppBundle\Service
 */
class propertyDetailsServices extends BaseService
{
    /**
     * Function to validate and get properties details of the consumer
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $propertyID
     *
     * @return mixed
     */
    public function getProperties($authDetails, $queryParameter, $pathInfo, $restriction, $propertyID = null)
    {
        $returnData = array();
        try {
            //Get properties Repo
            $propertiesRepo = $this->entityManager->getRepository('AppBundle:Properties');

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

            //Setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //Getting properties Detail
            $propertyData = $propertiesRepo->fetchProperties($authDetails['customerID'], $queryParameter, $propertyID, $restriction, $offset);

            //return 404 if resource not found
            if(empty($propertyData)){
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $hasMoreDate = count($restrictions = $propertiesRepo->fetchProperties($authDetails['customerID'], $queryParameter, $propertyID, $restriction, $offset + 1));

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($propertyData); $i++) {
                if (isset($propertyData[$i]['CreateDate'])) {
                    $propertyData[$i]['CreateDate'] = $propertyData[$i]['CreateDate']->format('Ymd');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            $hasMoreDate != 0 ? $returnData['has_more'] = true : $returnData['has_more'] = false;
            $returnData['data'] = $propertyData;

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