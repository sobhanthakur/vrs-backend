<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/2/20
 * Time: 1:20 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StaffTaskTimeService  extends BaseService
{
    /**
     * Function to validate and get all staff details
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $staffTaskTimesID
     *
     * @return mixed
     */
    public function getStaffTaskTimes($authDetails, $queryParameter, $pathInfo, $staffTaskTimesID = null)
    {
        $returnData = array();
        try {

            //Get staff task time Repo
            $staffTaskTimesRepo = $this->entityManager->getRepository('AppBundle:Timeclocktasks');

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

            //Getting staff task time Detail
            $staffTaskTimeData = $staffTaskTimesRepo->getItems($authDetails['customerID'], $queryParameter, $staffTaskTimesID, $offset, $limit);


            //return 404 if resource not found
            if (empty($staffTaskTimeData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int)$staffTaskTimesRepo->getItemsCount($authDetails['customerID'], $queryParameter, $staffTaskTimesID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($staffTaskTimeData); $i++) {
                if (isset($staffTaskTimeData[$i]['ClockIn'])) {
                    $staffTaskTimeData[$i]['ClockIn'] = $staffTaskTimeData[$i]['ClockIn']->format('Ymdhis');
                }

                if (isset($staffTaskTimeData[$i]['ClockOut'])) {
                    $staffTaskTimeData[$i]['ClockOut'] = $staffTaskTimeData[$i]['ClockOut']->format('Ymdhis');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $staffTaskTimeData;
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