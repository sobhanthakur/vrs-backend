<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 13/2/20
 * Time: 3:32 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StaffTasksService  extends BaseService
{
    /**
     * Function to validate and get all staff tasks
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $staffTasksID
     *
     * @return mixed
     */
    public function getStaffTasks($authDetails, $queryParameter, $pathInfo, $staffTasksID = null)
    {
        $returnData = array();
        try {
            //Get Tasks Repo
            $staffTasksRepo = $this->entityManager->getRepository('AppBundle:Taskstoservicers');

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

            //setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //getting task Detail
            $staffTasksData = $staffTasksRepo->getItems($authDetails['customerID'], $queryParameter, $staffTasksID, $offset, $limit);

            //return 404 if resource not found
            if (empty($staffTasksData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int)$staffTasksRepo->getItemsCount($authDetails['customerID'], $queryParameter, $staffTasksID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($staffTasksData); $i++) {
                if(isset($staffTasksData[$i]['TimeTracked'])){
                    $staffTasksData[$i]['TimeTracked'] = gmdate("H:i:s", strtotime($staffTasksData[$i]['TimeTracked']));
                }

                switch ($staffTasksData[$i]['PayType']) {
                    case 0:
                        $staffTasksData[$i]['Pay'] = 0;
                        break;
                    case 1:
                        $staffTasksData[$i]['Pay'] = $staffTasksData[$i]['PiecePay'];
                        break;
                    case 2:
                        $staffTasksData[$i]['Pay'] = $staffTasksData[$i]['ServicerPayRate'];
                        break;
                    default:
                        $staffTasksData[$i]['Pay'] = null;
                }

                unset($staffTasksData[$i]['ServicerPayRate']);
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $staffTasksData;
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
            $this->logger->error(GeneralConstants::TASKS_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $returnData;
    }

}