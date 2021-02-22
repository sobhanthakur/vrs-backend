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

class StaffTasksService extends BaseService
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
        $allIds = array();
        $timestamp = null;
        $resultArray = array();

        try {
            //Get Taskstoservicers Repo
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

            //checking if more records are there to fetch from db
            $allIds = $staffTasksRepo->getAllTaskID($authDetails['customerID'], $queryParameter, $staffTasksID, $offset, $limit);

            //getting staff task Detail
            $staffTasksData = $staffTasksRepo->getItems($authDetails['customerID'], $queryParameter, $staffTasksID, $offset, $limit, $allIds);

            //return 404 if resource not found
            if (empty($staffTasksData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = $staffTasksRepo->getItemsCount($authDetails['customerID'], $queryParameter, $staffTasksID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //calculating Timetracked for each staff
            foreach ($staffTasksData as $value) {
                if (isset($value['ClockIn'])) {
                    $value['ClockIn'] = $value['ClockIn']->getTimestamp();
                    $value['ClockOut'] = $value['ClockOut']->getTimestamp();
                    $value['TimeTracked'] = $value['ClockOut'] - $value['ClockIn'];

                    if (array_key_exists($value['StaffTaskID'], $resultArray)) {
                        $resultArray[$value['StaffTaskID']]['TimeTracked'] += $value['TimeTracked'];
                    } else {
                        $resultArray[$value['StaffTaskID']] = $value;
                    }
                }
            }

            //parsing result array
            $result = array_values($resultArray);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($result); $i++) {
                if (isset($result[$i]['ApprovedDate'])) {
                    $result[$i]['ApprovedDate'] = $result[$i]['ApprovedDate']->format('Ymd');
                }

                if (isset($result[$i]['CompleteConfirmedDate'])) {
                    $result[$i]['CompleteConfirmedDate'] = $result[$i]['CompleteConfirmedDate']->format('Ymd');
                }

                //Time worked by staff per task in hour format
                $timeWorkedInHour = $result[$i]['TimeTracked'] / 3600;

                //Formating time worked by staff to required format
                if (isset($result[$i]['TimeTracked'])) {
                    $result[$i]['TimeTracked'] = $this->mediaTimeDeFormater($result[$i]['TimeTracked']);
                }

                isset($result[$i]['ServiceName']) ? $serviceName = $result[$i]['ServiceName'] : $serviceName  = '';
                isset($result[$i]['TaskShortName']) ? $taskshortName = $result[$i]['TaskShortName'] : $taskshortName  = '';

                $result[$i]['TaskName'] = trim($serviceName." ".$taskshortName);

                //Setting Pay type  for response
                if (isset($result[$i]['PayType'])) {
                    switch ($result[$i]['PayType']) {
                        case 0:
                            $result[$i]['Pay'] = 0;
                            break;
                        case 1:
                            $result[$i]['Pay'] = $result[$i]['PiecePay'];
                            break;
                        case 2:
                            $result[$i]['Pay'] = $result[$i]['PiecePay'] * $result[$i]['ServicerPayRate'];
                            break;
                        case 3:
                            $totalWorked = $timeWorkedInHour * $result[$i]['ServicerPayRate'];
                            $result[$i]['Pay'] = round($totalWorked, 2);
                            break;
                        default:
                            $result[$i]['Pay'] = null;
                    }
                }

                //unsetting variables
                unset($result[$i]['ServiceName']);
                unset($result[$i]['TaskShortName']);
                unset($result[$i]['ServicerPayRate']);
                unset($result[$i]['ClockIn']);
                unset($result[$i]['ClockOut']);

            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $result;
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
            $this->logger->error(GeneralConstants::STAFF_TASK_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $returnData;
    }

    /**
     * Format seconds to display format in staff task api
     * @param $seconds
     *
     * @return string
     */
    function mediaTimeDeFormater($seconds)
    {
        if (!is_numeric($seconds))
            throw new Exception("Invalid Parameter Type!");


        $ret = "";

        $hours = (string )floor($seconds / 3600);
        $secs = (string )$seconds % 60;
        $mins = (string )floor(($seconds - ($hours * 3600)) / 60);

        if (strlen($hours) == 1)
            $hours = "0" . $hours;
        if (strlen($secs) == 1)
            $secs = "0" . $secs;
        if (strlen($mins) == 1)
            $mins = "0" . $mins;

        if ($hours == 0)
            $ret = "$mins:$secs";
        else
            $ret = "$hours:$mins:$secs";

        return $ret;
    }

}