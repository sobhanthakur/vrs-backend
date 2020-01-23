<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 13/1/20
 * Time: 5:21 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class OwnerDetailsServices
 * @package AppBundle\Service
 */
class OwnerDetailsServices extends BaseService
{

    /**
     * Function to validate and get owner details
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $ownerID
     *
     * @return mixed
     */
    public function getOwners($authDetails, $queryParameter, $pathInfo, $restriction, $ownerID = null)
    {
        $returnData = array();
        try {
            //Get owners Repo
            $ownersRepo = $this->entityManager->getRepository('AppBundle:Owners');

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
            (isset($queryParameter['startingafter']) ? $offset = $queryParameter['startingafter'] : $offset = 1);

            //Getting owners Detail
            $ownerData = $ownersRepo->fetchOwners($authDetails['customerID'], $queryParameter, $ownerID, $restriction, $offset);

            //checking if more records are there to fetch from db
            $hasMoreDate = count($restrictions = $ownersRepo->fetchOwners($authDetails['customerID'], $queryParameter, $ownerID, $restriction, $offset + 1));

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($ownerData); $i++) {
                if (isset($ownerData[$i]['CreateDate'])) {
                    $ownerData[$i]['CreateDate'] = $ownerData[$i]['CreateDate']->format('Ymd');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            $hasMoreDate != 0 ? $returnData['has_more'] = true : $returnData['has_more'] = false;
            $returnData['data'] = $ownerData;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::OWNER_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }

}