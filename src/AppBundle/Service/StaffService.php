<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 7/2/20
 * Time: 3:36 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StaffService extends BaseService
{
    /**
     * Function to validate and get all staff details
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $taskRulesID
     *
     * @return mixed
     */
    public function getStaff($authDetails, $queryParameter, $pathInfo, $taskRulesID = null)
    {
        $returnData = array();
        try {

            //Get staff Repo
            $staffRepo = $this->entityManager->getRepository('AppBundle:Servicers');

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

            //Getting staff Detail
            $staffData = $staffRepo->getItems($authDetails['customerID'], $queryParameter, $taskRulesID, $offset, $limit);


            //return 404 if resource not found
            if (empty($staffData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int)$staffRepo->getItemsCount($authDetails['customerID'], $queryParameter, $taskRulesID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($staffData); $i++) {
                if (isset($staffData[$i]['CreateDate'])) {
                    $staffData[$i]['CreateDate'] = $staffData[$i]['CreateDate']->format('Y-m-d');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $staffData;
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
            $this->logger->error(GeneralConstants::STAFF_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $returnData;
    }

}