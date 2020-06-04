<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 8/2/20
 * Time: 10:13 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TasksService extends BaseService
{
    /**
     * Function to validate and get all tasks
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $tasksID
     *
     * @return mixed
     */
    public function getTasks($authDetails, $queryParameter, $pathInfo, $tasksID = null)
    {
        $returnData = array();
        try {
            //Get Tasks Repo
            $tasksRepo = $this->entityManager->getRepository('AppBundle:Tasks');

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
            $taskRulesData = $tasksRepo->getItems($authDetails['customerID'], $queryParameter, $tasksID, $offset, $limit);


            //return 404 if resource not found
            if (empty($taskRulesData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int)$tasksRepo->getItemsCount($authDetails['customerID'], $queryParameter, $tasksID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i=0; $i<count($taskRulesData); $i++) {
                if (isset($taskRulesData[$i]['CreateDate'])) {
                    $taskRulesData[$i]['CreateDate'] = $taskRulesData[$i]['CreateDate']->format('Ymd');
                }

                if (isset($taskRulesData[$i]['TaskDate'])) {
                    $taskRulesData[$i]['TaskDate'] = $taskRulesData[$i]['TaskDate']->format('Ymd');
                }

                if (isset($taskRulesData[$i]['CompleteConfirmedDate'])) {
                    $taskRulesData[$i]['CompleteConfirmedDate'] = $taskRulesData[$i]['CompleteConfirmedDate']->format('Ymd');
                }

                if (isset($taskRulesData[$i]['ApprovedDate'])) {
                    $taskRulesData[$i]['ApprovedDate'] = $taskRulesData[$i]['ApprovedDate']->format('Ymd');
                }

                if (isset($taskRulesData[$i]['TaskStartDate'])) {
                    $taskRulesData[$i]['TaskStartDate'] = $taskRulesData[$i]['TaskStartDate']->format('Ymd');
                }

                if (isset($taskRulesData[$i]['TaskCompleteByDate'])) {
                    $taskRulesData[$i]['TaskCompleteByDate'] = $taskRulesData[$i]['TaskCompleteByDate']->format('Ymd');
                }

                if (isset($taskRulesData[$i]['TaskTime'])) {
                    $taskRulesData[$i]['TaskTime'] = $taskRulesData[$i]['TaskTime']->format('H:i:s');
                }

                // Set Task Name as ServiceName-TaskName
                $taskRulesData[$i]['TaskName'] = $taskRulesData[$i]['ServiceName'].' '.$taskRulesData[$i]['TaskName'];
                unset($taskRulesData[$i]['ServiceName']);

                // Add Property Details in a separate Object
                $properties = [];
                $nextPropertyBooking = [];

                // Preg Replace Key Containing Properties_, npb_
                foreach ($taskRulesData[$i] as $key => $value) {
                    if (strpos($key, 'Properties_') !== false) {
                        if (strpos($key,'Date') && $taskRulesData[$i][$key]) {
                            $taskRulesData[$i][$key] = $taskRulesData[$i][$key]->format('Ymd');
                        }
                        $trimmedKey = explode('Properties_',$key);
                        $properties[$trimmedKey[1]] = $taskRulesData[$i][$key];
                        unset($taskRulesData[$i][$key]);
                    }
                    if (strpos($key, 'npb_') !== false) {
                        if (($key === 'npb_CheckIn' || $key === 'npb_CheckOut' || $key === 'npb_CreateDate') && $taskRulesData[$i][$key]) {
                            $taskRulesData[$i][$key] = $taskRulesData[$i][$key]->format('Ymd');
                        }
                        $trimmedKey = explode('npb_',$key);
                        $nextPropertyBooking[$trimmedKey[1]] = $taskRulesData[$i][$key];
                        unset($taskRulesData[$i][$key]);
                    }
                }

                $staffs = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->StaffDetailsInTasks($taskRulesData[$i]['TaskID']);
                foreach ($staffs as $key => $value) {
                    $staffs[$key]['CreateDate'] = $staffs[$key]['CreateDate'] ? $staffs[$key]['CreateDate']->format('Ymd') : null;
                }

                $taskRulesData[$i]['Property'] = $properties;
                $taskRulesData[$i]['Staff'] = $staffs;
                $taskRulesData[$i]['NextPropertyBooking'] = $nextPropertyBooking;
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
            $this->logger->error(GeneralConstants::TASKS_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $returnData;
    }

}