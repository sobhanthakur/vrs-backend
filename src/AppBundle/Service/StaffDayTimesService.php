<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 18/2/20
 * Time: 7:20 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StaffDayTimesService extends BaseService
{
    /**
     * Function to validate and get all staff task times details
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $staffDayTimesID
     *
     * @return mixed
     */
    public function getStaffDayTimes($authDetails, $queryParameter, $pathInfo, $staffDayTimesID = null)
    {
        $returnData = array();
        try {

            //Get staff task time Repo
            $staffDayTimesRepo = $this->entityManager->getRepository('AppBundle:Timeclockdays');

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
            $staffDayTimesData = $staffDayTimesRepo->getItems($authDetails['customerID'], $queryParameter, $staffDayTimesID, $offset, $limit);


            //return 404 if resource not found
            if (empty($staffDayTimesData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int)$staffDayTimesRepo->getItemsCount($authDetails['customerID'], $queryParameter, $staffDayTimesID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($staffDayTimesData); $i++) {
                if (isset($staffDayTimesData[$i]['ClockIn'])) {
                    $staffDayTimesData[$i]['ClockIn'] = $staffDayTimesData[$i]['ClockIn']->format('Y-m-d H:i:s');
                }

                if (isset($staffDayTimesData[$i]['ClockOut'])) {
                    $staffDayTimesData[$i]['ClockOut'] = $staffDayTimesData[$i]['ClockOut']->format('Y-m-d H:i:s');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $staffDayTimesData;
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
            $this->logger->error(GeneralConstants::STAFF_DAY_TIMES_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $returnData;
    }

}