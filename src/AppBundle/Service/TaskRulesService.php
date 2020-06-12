<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 5/2/20
 * Time: 12:26 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskRulesService extends BaseService
{
    /**
     * Function to validate and get all taskrules of consumer
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $issueID
     *
     * @return mixed
     */
    public function getTaskRules($authDetails, $queryParameter, $pathInfo, $taskRulesID = null)
    {
        $returnData = array();
        try {
            //Get issue Repo
            $taskRulesRepo = $this->entityManager->getRepository('AppBundle:Services');

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

            //Getting issue Detail
            $taskRulesData = $taskRulesRepo->getItems($authDetails['customerID'], $queryParameter, $taskRulesID, $offset, $limit);


            //return 404 if resource not found
            if (empty($taskRulesData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int) $taskRulesRepo->getItemsCount($authDetails['customerID'], $queryParameter, $taskRulesID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($taskRulesData); $i++) {
                if (isset($taskRulesData[$i]['CreateDate'])) {
                    $taskRulesData[$i]['CreateDate'] = $taskRulesData[$i]['CreateDate']->format('Y-m-d');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $taskRulesData;
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
            $this->logger->error(GeneralConstants::ISSUES_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }

}