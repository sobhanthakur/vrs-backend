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
            $propertyBookingRepo = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_PROPERTYBOOKINGS);

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
                if (isset($propertyBookingData[$i][GeneralConstants::CREATEDATE])) {
                    $propertyBookingData[$i][GeneralConstants::CREATEDATE] = $propertyBookingData[$i][GeneralConstants::CREATEDATE]->format('Ymd');
                }

                if (isset($propertyBookingData[$i][GeneralConstants::CHECKIN])) {
                    $propertyBookingData[$i][GeneralConstants::CHECKIN] = $propertyBookingData[$i][GeneralConstants::CHECKIN]->format('Ymd');
                }

                if (isset($propertyBookingData[$i][GeneralConstants::CHECKOUT])) {
                    $propertyBookingData[$i][GeneralConstants::CHECKOUT] = $propertyBookingData[$i][GeneralConstants::CHECKOUT]->format('Ymd');
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

    /**
     * Function to validate and create and update property booking
     *
     * @param $content
     * @param $authDetails
     * @param $propertyBookingID
     *
     * @return array
     */
    public function insertPropertBookingDetails($content, $authDetails, $propertyBookingID = null,$restriction = null)
    {
        $returnData = array();
        $propertyBookingApiContent = array();

        try {
            //Get all query parameter and set it in an array
            foreach ($content as $key => $value) {
                (isset($key)) ? $propertyBookingApiContent[strtolower($key)] = $value : null;
            }

            //Get customerID
            $customerID = $authDetails['customerID'];

            //setting insertdata
            isset($propertyBookingApiContent['propertyid']) ? $propID = $propertyBookingApiContent['propertyid'] : $propID = null;
            isset($propertyBookingApiContent['guest']) ? $guest = $propertyBookingApiContent['guest'] : $guest = null;
            isset($propertyBookingApiContent['guestemail']) ? $guestEmail = $propertyBookingApiContent['guestemail'] : $guestEmail = null;
            isset($propertyBookingApiContent['guestphone']) ? $guestPhone = $propertyBookingApiContent['guestphone'] : $guestPhone = null;
            isset($propertyBookingApiContent['numberofguest']) ? $numberOfGuest = $propertyBookingApiContent['numberofguest'] : $numberOfGuest = null;
            isset($propertyBookingApiContent['numberofchildren']) ? $numberOfChildren = $propertyBookingApiContent['numberofchildren'] : $numberOfChildren = null;
            isset($propertyBookingApiContent['numberofpets']) ? $numberOfPets = $propertyBookingApiContent['numberofpets'] : $numberOfPets = null;
            isset($propertyBookingApiContent['isowner']) ? $isOwner = $propertyBookingApiContent['isowner'] : $isOwner = null;
            isset($propertyBookingApiContent['bookingtags']) ? $bookingTags = $propertyBookingApiContent['bookingtags'] : $bookingTags = null;
            isset($propertyBookingApiContent['importbookingid']) ? $importBookingID = $propertyBookingApiContent['importbookingid'] : $importBookingID = null;
            isset($propertyBookingApiContent['bookingnote']) ? $boookingNote = $propertyBookingApiContent['bookingnote'] : $boookingNote = null;
            isset($propertyBookingApiContent['internalnote']) ? $internalNote = $propertyBookingApiContent['internalnote'] : $internalNote = null;
            isset($propertyBookingApiContent['pmsnote']) ? $pmsNote = $propertyBookingApiContent['pmsnote'] : $pmsNote = null;
            isset($propertyBookingApiContent['pmshousekeepingnote']) ? $pmsHouekeepingNote = $propertyBookingApiContent['pmshousekeepingnote'] : $pmsHouekeepingNote = null;

            if (isset($propertyBookingID)) {
                $returnData['msg'] = GeneralConstants::PROPERTIES_BOOKING_MESSEGE['UPDATE'];
                $propertyBookingsRepo = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_PROPERTYBOOKINGS);
                $propertyBooking = $propertyBookingsRepo->findOneBy(array('propertybookingid' => $propertyBookingID));
                $id = $propertyBooking->getPropertyid()->getPropertyid();

                $chkIn = $propertyBooking->getCheckin();
                $chkOut = $propertyBooking->getCheckout();

                if (isset($chkIn)) {
                    $chkIn = $chkIn->format('Ymd');
                }

                if (isset($chkOut)) {
                    $chkOut = $chkOut->format('Ymd');
                }

                if (!$propertyBooking) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_ID);
                }

            } else {
                $returnData['msg'] = GeneralConstants::PROPERTIES_BOOKING_MESSEGE['INSERT'];
                $propertyBooking = new Propertybookings();
            }

            isset($propID) ? $propertyID = $propID : $propertyID = $id;
            isset($propertyBookingApiContent['checkin']) ? $checkIn = $propertyBookingApiContent['checkin'] : $checkIn = $chkIn;
            isset($propertyBookingApiContent['checkout']) ? $checkOut = $propertyBookingApiContent['checkout'] : $checkOut = $chkOut;

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
            isset($propertyBookingApiContent['checkouttime']) ? $checkOutTime = $propertyBookingApiContent['checkouttime'] : $checkOutTime = $checkOutTimeValue;
            isset($propertyBookingApiContent['checkouttimeminutes']) ? $CheckOutTimeMinutes = $propertyBookingApiContent['checkouttimeminutes'] : $CheckOutTimeMinutes = $CheckOutTimeMinutesValue;

            //validating check and checkout time
            $workTime = strtotime($checkOut) - strtotime($checkIn);

            if ($workTime < 86400) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_CHECKOUT);
            }

            //Return error if bookings with checkout date is 2 days or earlier than today
            $currentDate = gmdate("Ymd");
            $constraint = strtotime($currentDate) - strtotime($checkOut);
            if ($constraint > 172800) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_TIMELOGIN_DETAILS);
            }

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
                $propertyBooking->setNumberofguests($numberOfGuest);
            }

            //setting numberofchilden
            if (isset($numberOfChildren)) {
                $propertyBooking->setNumberofchildren($numberOfChildren);
            }

            //setting numberofpet
            if (isset($numberOfPets)) {
                $propertyBooking->setNumberofpets($numberOfPets);
            }

            //setting isowner
            if (isset($isOwner)) {
                $propertyBooking->setIsowner($isOwner);
            }

            //setting bookingTags
            if (isset($bookingTags)) {
                $propertyBooking->setBookingtags($bookingTags);
            }

            // Set ImportBookingID
            if (isset($importBookingID)) {
                $propertyBooking->setImportbookingid($importBookingID);
            }

            // Set Booking Note
            if (isset($boookingNote)) {
                $propertyBooking->setGlobalnote($boookingNote);
            }

            // Set Internal Note
            if (isset($internalNote)) {
                $propertyBooking->setInternalnote($internalNote);
            }

            // Set PMS Note
            if (isset($pmsNote)) {
                $propertyBooking->setPmsnote($pmsNote);
            }

            // Set PMS Housekeeping Note
            if (isset($pmsHouekeepingNote)) {
                $propertyBooking->setPmshousekeepingnote($pmsHouekeepingNote);
            }

            $this->entityManager->persist($propertyBooking);
            $this->entityManager->flush();

            //setting return data
            $data[GeneralConstants::PROPERTYBOOKINGID] = $propertyBooking->getPropertybookingid();
            $data[GeneralConstants::PROPERTY_ID] = $propertyBooking->getPropertyid()->getPropertyid();

            $checkin = $propertyBooking->getCheckin();
            isset($checkin) ? $data[GeneralConstants::CHECKIN] =
                $checkin->format('Ymd') : $data[GeneralConstants::CHECKIN] = null;

            $data['CheckInTime'] = $propertyBooking->getCheckintime();
            $data['CheckInTimeMinutes'] = $propertyBooking->getCheckintimeminutes();
            $data[GeneralConstants::CHECKOUT] = $propertyBooking->getCheckout();
            $data['CheckOutTime'] = $propertyBooking->getCheckouttime();
            $data['CheckOutTimeMinutes'] = $propertyBooking->getCheckouttimeminutes();

            if ((int)$restriction->restrictPersonalData !== 1) {
                $data['Guest'] = $propertyBooking->getGuest();
                $data['GuestEmail'] = $propertyBooking->getGuestemail();
                $data['GuestPhone'] = $propertyBooking->getGuestphone();
            }

            $data['NumberOfGuest'] = $propertyBooking->getNumberofguests();
            $data['NumberOfPets'] = $propertyBooking->getNumberofpets();
            $data['IsOwner'] = $propertyBooking->getIsowner();

            $data['ImportBookingID'] = $propertyBooking->getImportbookingid();
            $data['BookingNote'] = $propertyBooking->getGlobalnote();
            $data['InternalNote'] = $propertyBooking->getInternalnote();
            $data['PMSNote'] = $propertyBooking->getPmsnote();
            $data['PMSHousekeepingNote'] = $propertyBooking->getPmshousekeepingnote();

            $checkout = $propertyBooking->getCheckout();
            isset($checkout) ? $data[GeneralConstants::CHECKOUT] =
                $checkout->format('Ymd') : $data[GeneralConstants::CHECKOUT] = null;

            $createdDate = $propertyBooking->getCreatedate();
            isset($createdDate) ? $data[GeneralConstants::CREATEDATE] =
                $createdDate->format('Ymd') : $data[GeneralConstants::CREATEDATE] = null;

            $returnData['data'] = $data;

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

    /**
     * Function to delete property booking
     *
     * @param $propertyBookingID
     *
     * @return array
     */
    public function deletePropertBookingDetails($propertyBookingID)
    {
        $returnData = array();
        try {
            $propertyBookingsRepo = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_PROPERTYBOOKINGS);
            $propertyBooking = $propertyBookingsRepo->findOneBy(array('propertybookingid' => $propertyBookingID));
            if (!$propertyBooking) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PROPERTY_BOOKING_ID);
            }
            $propertyBooking->setDeleted(1);
            $propertyBooking->setDeleteddate(new \DateTime());
            $propertyBooking->setActive(0);
            $this->entityManager->persist($propertyBooking);
            $this->entityManager->flush();
            $returnData[GeneralConstants::REASON_CODE] = 0;
            $returnData[GeneralConstants::REASON_TEXT] = GeneralConstants::PROPERTIES_BOOKING_MESSEGE['DELETED'];

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