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
use AppBundle\Entity\Owners;
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

            //check for limit option in query paramter
            (isset($queryParameter[GeneralConstants::PARAMS['PER_PAGE']]) ? $limit = $queryParameter[GeneralConstants::PARAMS['PER_PAGE']] : $limit = 20);

            //Setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //Getting owners Detail
            $ownerData = $ownersRepo->getItems($authDetails['customerID'], $queryParameter, $ownerID, $restriction, $offset, $limit);

            //return 404 if resource not found
            if(empty($ownerData)){
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = count($ownersRepo->getItemsCount($authDetails['customerID'], $queryParameter, $ownerID, $offset));

            //setting page count
            $totalPage = (int) ceil($totalItems/$limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($ownerData); $i++) {
                if (isset($ownerData[$i][GeneralConstants::CREATEDATE])) {
                    $ownerData[$i][GeneralConstants::CREATEDATE] = $ownerData[$i][GeneralConstants::CREATEDATE]->format('Ymd');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit)  ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $ownerData;
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
            $this->logger->error(GeneralConstants::OWNER_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }

    /**
     * @param $content
     * @param $authDetails
     * @return array
     */
    public function InsertOwner($content, $authDetails)
    {
        try {
            // Check if the owner already exists
            $owner = $this->entityManager->getRepository('AppBundle:Owners')->findOneBy(array(
                'ownername' => $content['OwnerName']
            ));
            if ($owner) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            // Insert Owner
            $owner = new Owners();
            $owner->setCustomerid($this->entityManager->getRepository('AppBundle:Customers')->find($authDetails['customerID']));
            $owner->setOwnername($content['OwnerName']);
            $owner->setOwneremail($content['OwnerEmail']);
            $this->entityManager->persist($owner);
            $this->entityManager->flush();

            // Format Response
            return array(
                "OwnerID" => $owner->getOwnerid(),
                "OwnerName" => $owner->getOwnername(),
                "OwnerEmail" => $owner->getOwneremail(),
                'CreateDate' => $owner->getCreatedate()->format('YmdHis')
            );
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
    }
}