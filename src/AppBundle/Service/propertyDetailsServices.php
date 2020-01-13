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
    public function getProperties($authDetails, $queryParameter, $pathInfo, $propertyID = null)
    {
        $returnData = array();
        try {
            //Get properties Repo
            $propertiesRepo = $this->entityManager->getRepository('AppBundle:Properties');

            //cheking valid query parameters
            $checkParams = array_diff(array_keys($queryParameter), GeneralConstants::PROPERTIES_PARAMS);
            if (count($checkParams) > 0) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //cheking valid data of query parameter
            $validationCheck =$this->validationCheck($queryParameter);
            if(!$validationCheck){
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //Setting offset
            (isset($queryParameter['startingafter']) ? $offset = $queryParameter['startingafter'] : $offset = 1);

            //Getting properties Detail
            $propertyData = $propertiesRepo->fetchProperties($authDetails['customerID'], $queryParameter, $propertyID, $offset);

            //checking if more records are there to fetch from db
            $hasMoreDate = count($restrictions = $propertiesRepo->fetchProperties($authDetails['customerID'], $queryParameter, $propertyID, $offset + 1));

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($propertyData); $i++) {
                $propertyData[$i]['CreateDate'] = $propertyData[$i]['CreateDate']->format('Ymd');
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

    /**
     * Function to validate query paramter request value
     *
     * @param $queryParameter
     *
     * @return boolean
     */
    public function validationCheck($queryParameter)
    {
        foreach ($queryParameter as $paramKey => $paramValue) {
            switch ($paramKey) {
                case 'active':
                    if (is_string($paramValue) === false) {
                        return false;
                    }
                    break;

                case 'ownerid':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;

                case 'regionid':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;

                case 'fields':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;
                case 'sort':
                    if ((is_string($paramValue)) === false) {
                        return false;
                    }
                    break;

                case 'limit':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;
                case 'startingafter':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;
            }

        }

        return true;
    }

}