<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/1/20
 * Time: 8:53 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class PropertyBookingsService extends BaseService
{
    /**
     * Function to validate and get all property bookings detail
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $regionGroupsID
     *
     * @return mixed
     */
    public function getPropertyBookings($authDetails, $queryParameter, $pathInfo, $regionGroupsID = null)
    {
        $returnData = array();
        try {
            //Get region Repo
            $propertyBookingRepo = $this->entityManager->getRepository('AppBundle:Propertybookings');

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

            //Getting property booking Detail
            $propertyBookingData = $propertyBookingRepo->fetchPropertyBooking($authDetails['customerID'], $queryParameter, $regionGroupsID, $offset);

            //checking if more records are there to fetch from db
            $hasMoreDate = count($propertyBookingRepo->fetchPropertyBooking($authDetails['customerID'], $queryParameter, $regionGroupsID, $offset + 1));

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($propertyBookingData); $i++) {
                if (isset($propertyBookingData[$i]['CreateDate'])) {
                    $propertyBookingData[$i]['CreateDate'] = $propertyBookingData[$i]['CreateDate']->format('Ymd');
                    $propertyBookingData[$i]['CheckIn'] = $propertyBookingData[$i]['CheckIn']->format('Ymd');
                    $propertyBookingData[$i]['CheckOut'] = $propertyBookingData[$i]['CheckOut']->format('Ymd');
                }
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            $hasMoreDate != 0 ? $returnData['has_more'] = true : $returnData['has_more'] = false;
            $returnData['data'] = $propertyBookingData;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::REGION_GROUPS_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }

}