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
//use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Entity\Propertybookings;


class PropertyBookingsService extends BaseService
{
    /**
     * Function to validate and get all property bookings detail
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $propertyGroupID
     *
     * @return mixed
     */
    public function getPropertyBookings($authDetails, $queryParameter, $pathInfo, $restriction, $propertyGroupID = null)
    {
        $returnData = array();
        try {
            //Get propertyBooking Repo
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

            //check for limit option in query paramter
            (isset($queryParameter[GeneralConstants::PARAMS['PER_PAGE']]) ? $limit = $queryParameter[GeneralConstants::PARAMS['PER_PAGE']] : $limit = 20);

            //Setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //Getting property booking Detail
            $propertyBookingData = $propertyBookingRepo->getItems($authDetails['customerID'], $queryParameter, $propertyGroupID, $restriction, $offset, $limit);

            //return 404 if resource not found
            if (empty($propertyBookingData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = count($propertyBookingRepo->getItemsCount($authDetails['customerID'], $queryParameter, $propertyGroupID, $offset));

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);


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
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $propertyBookingData;
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
            $this->logger->error(GeneralConstants::PROPERTY_BOOKING_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }

    public function insertPropertBookingDetails($content, $authDetails, $propertyBookingID = null)
    {
        $returnData = array();
        $propertyBookingApiContent = array();

        try {

            //Get all query parameter and set it in an array
            foreach ($content as $key => $value) {
                (isset($value) && $value != "") ? $propertyBookingApiContent[strtolower($key)] = strtolower($value) : null;
            }

            //Get customerID
            $customerID = $authDetails['customerID'];

            //setting insertdata
            isset($propertyBookingApiContent['propertyid']) ? $propertyID = $propertyBookingApiContent['propertyid'] : $propertyID = null;
            isset($propertyBookingApiContent['checkin']) ? $checkIn = $propertyBookingApiContent['checkin'] : $checkIn = null;
            isset($propertyBookingApiContent['guest']) ? $guest = $propertyBookingApiContent['guest'] : $guest = null;
            isset($propertyBookingApiContent['guestemail']) ? $guestEmail = $propertyBookingApiContent['guestemail'] : $guestEmail = null;
            isset($propertyBookingApiContent['guestphone']) ? $guestPhone = $propertyBookingApiContent['guestphone'] : $guestPhone = null;
            isset($propertyBookingApiContent['numberofguest']) ? $numberOfGuest = $propertyBookingApiContent['numberofguest'] : $numberOfGuest = null;
            isset($propertyBookingApiContent['numberofchildren']) ? $numberOfChildren = $propertyBookingApiContent['numberofchildren'] : $numberOfChildren = null;
            isset($propertyBookingApiContent['numberofpet']) ? $numberOfPet = $propertyBookingApiContent['numberofpet'] : $numberOfPet = null;
            isset($propertyBookingApiContent['isowner']) ? $isOwner = $propertyBookingApiContent['isowner'] : $isOwner = null;
            isset($propertyBookingApiContent['bookingtags']) ? $bookingTags = $propertyBookingApiContent['bookingtags'] : $bookingTags = null;

            if (isset($propertyBookingID)) {
                $propertyBookingsRepo = $this->entityManager->getRepository('AppBundle:Propertybookings');
                $propertyBooking = $propertyBookingsRepo->findOneBy(array('propertybookingid' => $propertyBookingID));

                if (!$propertyBooking) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
                }
            } else {
                $propertyBooking = new Propertybookings();
            }

            //Checking property id for the customer
            $propertyRepo = $this->entityManager->getRepository('AppBundle:Properties');
            $property = $propertyRepo->findOneBy(array('customerid' => $customerID, 'propertyid' => $propertyID));
            if (!$property) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
            }

            //setting default data
            $checkInTimeValue = $property->getDefaultcheckintime();
            $checkInTimeMinutesValue = $property->getDefaultcheckintimeminutes();
            $checkOutTimeValue = $property->getDefaultcheckouttime();
            $CheckOutTimeMinutesValue = $property->getDefaultcheckouttimeminutes();
            isset($propertyBookingApiContent['checkintime']) ? $checkInTime = $propertyBookingApiContent['checkintime'] : $checkInTime = $checkInTimeValue;
            isset($propertyBookingApiContent['checkintimeminutes']) ? $checkInTimeMinutes = $propertyBookingApiContent['checkintimeminutes'] : $checkInTimeMinutes = $checkInTimeMinutesValue;
            isset($propertyBookingApiContent['checkout']) ? $checkOut = $propertyBookingApiContent['checkout'] : $checkOut = null;
            isset($propertyBookingApiContent['checkouttime']) ? $checkOutTime = $propertyBookingApiContent['checkouttime'] : $checkOutTime = $checkOutTimeValue;
            isset($propertyBookingApiContent['checkouttimeminutes']) ? $CheckOutTimeMinutes = $propertyBookingApiContent['checkouttimeminutes'] : $CheckOutTimeMinutes = $CheckOutTimeMinutesValue;

            //setting propertyid
            if (isset($propertyID)) {
                $propertyBooking->setPropertyid($property);
            }

            //setting checkin
            if (isset($checkIn)) {
                $date = new  \DateTime(date("Y-m-d", strtotime($checkIn)));
                $propertyBooking->setCheckin($date);
            }

            //setting checkintime
            if (isset($checkInTime)) {
                $propertyBooking->setCheckintime($checkInTime);
            }

            //setting checkInTimeMinutes
            if (isset($checkInTimeMinutes)) {
                $propertyBooking->setCheckintimeminutes($checkInTimeMinutes);
            }

            //setting checkout
            if (isset($checkOut)) {
                $date = new  \DateTime(date("Y-m-d", strtotime($checkOut)));
                $propertyBooking->setCheckout($date);
            }

            //setting checkouttime
            if (isset($checkOutTime)) {
                $propertyBooking->setCheckouttime($checkOutTime);
            }

            //setting checkouttimeminutes
            if (isset($CheckOutTimeMinutes)) {
                $propertyBooking->setCheckouttimeminutes($CheckOutTimeMinutes);
            }

            //setting guest
            if (isset($guest)) {
                $propertyBooking->setGuest($guest);
            }

            //setting guestemail
            if (isset($guestEmail)) {
                $propertyBooking->setGuestemail($guestEmail);
            }

            //setting guestphone
            if (isset($guestPhone)) {
                $propertyBooking->setGuestphone($guestPhone);
            }

            //setting numberofguest
            if (isset($numberOfGuest)) {
                $propertyBooking->setGuestphone($numberOfGuest);
            }

            //setting numberofchilden
            if (isset($numberOfChildren)) {
                $propertyBooking->setGuestphone($numberOfChildren);
            }

            //setting numberofpet
            if (isset($numberOfPet)) {
                $propertyBooking->setGuestphone($numberOfPet);
            }

            //setting isowner
            if (isset($isOwner)) {
                $propertyBooking->setGuestphone($isOwner);
            }

            //setting bookingTags
            if (isset($bookingTags)) {
                $propertyBooking->setBookingtags($bookingTags);
            }


            $this->entityManager->persist($propertyBooking);
            $this->entityManager->flush();

            $returnData['PropertyBookingID'] = $propertyBooking->getPropertybookingid();
            $returnData['PropertyID'] = $propertyBooking->getPropertyid()->getPropertyid();

            $checkin = $propertyBooking->getCheckin();
            isset($checkin) ? $returnData['CheckIn'] =
                $checkin->format('Ymd') : $returnData['CheckIn'] = null;

            $returnData['CheckInTime'] = $propertyBooking->getCheckintime();
            $returnData['CheckInTimeMinutes'] = $propertyBooking->getCheckintimeminutes();
            $returnData['CheckOut'] = $propertyBooking->getCheckout();
            $returnData['CheckOutTime'] = $propertyBooking->getCheckouttime();
            $returnData['CheckOutTimeMinutes'] = $propertyBooking->getCheckouttimeminutes();
            $returnData['Guest'] = $propertyBooking->getGuest();
            $returnData['GuestEmail'] = $propertyBooking->getGuestemail();
            $returnData['GuestPhone'] = $propertyBooking->getGuestphone();
            $returnData['NumberOfGuest'] = $propertyBooking->getNumberofguests();
            $returnData['NumberOfPets'] = $propertyBooking->getNumberofpets();
            $returnData['IsOwner'] = $propertyBooking->getIsowner();

            $checkout = $propertyBooking->getCheckout();
            isset($checkout) ? $returnData['CheckOut'] =
                $checkout->format('Ymd') : $returnData['CheckOut'] = null;

            $createdDate = $propertyBooking->getCreatedate();
            isset($createdDate) ? $returnData['CreateDate'] =
                $createdDate->format('Ymd') : $returnData['CreateDate'] = null;

            //dump($returnData); die();

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::PROPERTY_BOOKING_API .
                $exception->getMessage());
            throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
        }

        return $returnData;
    }

    public function deletePropertBookingDetails($propertyBookingID)
    {
        $returnData = array();
        try {
            $propertyBookingsRepo = $this->entityManager->getRepository('AppBundle:Propertybookings');
            $propertyBooking = $propertyBookingsRepo->findOneBy(array('propertybookingid' => $propertyBookingID));
            if (!$propertyBooking) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_BOOKING_ID);
            }
            $propertyBooking->setDeleted(1);
            $propertyBooking->setDeleteddate(new \DateTime());
            $this->entityManager->persist($propertyBooking);
            $this->entityManager->flush();
            $returnData['code'] = GeneralConstants::REASON_CODE;
            $returnData['message'] = GeneralConstants::REASON_TEXT;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::PROPERTY_BOOKING_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;
    }
}