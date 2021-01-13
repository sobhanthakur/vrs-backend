<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 7/1/21
 * Time: 12:38 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BookingCalenderService extends BaseService
{
    public function GetBookingCalenderDetails($servicerID,$content)
    {
        $bookings = [];
        $allTasks = [];
        try {
            $rsServicers = $this->entityManager->getRepository('AppBundle:Servicers')->BookingsCalender($servicerID);

            if (empty($rsServicers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
            }

            // Get the first array index
            $rsServicers = $rsServicers[0];

            // Get todays date in Local TimeZone
            if ($rsServicers['TimeZoneRegion'] !== '') {
                $localDate = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($rsServicers['TimeZoneRegion']);
            } else {
                $localDate = new \DateTime('now');
            }

            $thisEndDate = new \DateTime($localDate->format('Y-m-d'));
            if ((int)$rsServicers['ViewBookingsWithinDays'] === 0) {
                // Add one year to current locale date
                $thisEndDate = $thisEndDate->modify('+1 year');
            } else {
                $thisEndDate = $thisEndDate->modify('+' . $rsServicers['ViewBookingsWithinDays'].' day');
            }
            $thisEndDate->setTime(0,0,0);

            // Parse Start Date and End Date from the request query parameter
            $thisStartDate = (new \DateTime($content['StartDate']))->setTime(0,0,0);
            $endDate = (new \DateTime($content['EndDate']))->setTime(0,0,0);

            if ($thisEndDate > $endDate) {
                $thisEndDate = $endDate;
            }

            //Get Distinct Properties
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertiesForBookingCalender($servicerID);

            // Get PropertyBookings
            $propertyBookings = $this->entityManager->getRepository('AppBundle:Propertybookings')->GetBookingsForBookingCalender($servicerID,$thisEndDate->format('Y-m-d H:i:s'),$thisStartDate->format('Y-m-d H:i:s'));

            // Loop through Property Bookings
            foreach ($propertyBookings as $propertyBooking) {
                $bookingDetails['PropertyID'] = $propertyBooking['PropertyID'];
                $bookingDetails['Color'] = $propertyBooking['Color'];
                $bookingDetails['TextColor'] = '##ffffff';

                if (trim($propertyBooking['BookingColor']) !== '' &&
                    (int)$propertyBooking['PropertyID'] === (int)$propertyBooking['PropertyBookingPropertyID']
                ) {
                    $bookingDetails['Color'] = $propertyBooking['BookingColor'];
                } elseif ($propertyBooking['Color'] !== '') {
                    $bookingDetails['Color'] = '#'.$propertyBooking['Color'];
                } else {
                    $bookingDetails['Color'] = '##0275d8';
                }

                if((base_convert(substr($bookingDetails['Color'],2,2),16,10)*0.299) +
                    (base_convert(substr($bookingDetails['Color'],4,2),16,10)*0.587) +
                    (base_convert(substr($bookingDetails['Color'],-2),16,10)*0.144) > 200
                ) {
                    $bookingDetails['TextColor'] = '##000000';
                }

                $bookingDetails['CheckIn'] = $propertyBooking['CheckIn'];
                $bookingDetails['CheckInTime'] = $propertyBooking['CheckInTime'] >= 12 ? ($propertyBooking['CheckInTime'] % 12)." PM" : $propertyBooking['CheckInTime']." AM";
                $bookingDetails['CheckOut'] = $propertyBooking['CheckOut'];
                $bookingDetails['CheckOutTime'] = $propertyBooking['CheckOutTime'] >= 12 ? ($propertyBooking['CheckInTime'] % 12)." PM" : $propertyBooking['CheckOutTime']." AM";

                // GuestDetails
                (int)$rsServicers['IncludeGuestName'] ? $bookingDetails['GuestName'] = $propertyBooking['Guest'] : $bookingDetails['GuestName'] = "";
                $bookingDetails['NumberOfGuests'] = 0;
                $bookingDetails['NumberOfChildren'] = 0;
                $bookingDetails['NumberOfPets'] = 0;

                if ((int)$rsServicers['IncludeGuestNumbers']) {
                    $bookingDetails['NumberOfGuests'] = (int)$propertyBooking['NumberOfGuests'];
                    $bookingDetails['NumberOfChildren'] = (int)$propertyBooking['NumberOfChildren'];
                    $bookingDetails['NumberOfPets'] = (int)$propertyBooking['NumberOfPets'];
                }

                $bookingDetails['InGlobalNote'] = trim($propertyBooking['InGlobalNote']);
                $bookingDetails['OutGlobalNote'] = trim($propertyBooking['OutGlobalNote']);
                $bookingDetails['PropertyName'] = str_replace(array('&', '.','*','"',"'"),"",$propertyBooking['PropertyName']);
                $bookingDetails['BackToBackEnd'] = $propertyBooking['BackToBackEnd'];

                $bookings[] = $bookingDetails;
            }

            // Fetch Tasks
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForBookingCalender($servicerID,$thisStartDate,$thisEndDate,$rsServicers['TimeZoneRegion']);

            // Iterate through Tasks
            foreach ($tasks as $task) {
                $taskDetails = [];
                $taskDetails['Region'] = "";
                if ($task['Region'] !== $taskDetails['Region']) {
                    $taskDetails['Region'] = $task['Region'];
                }

                $taskDetails['TaskName'] = "Unassigned";
                if ($task['Name'] !== "") {
                    $taskDetails['TaskName'] = $task['Name'];
                }

                if ((int)$task['TaskTime'] !== 99 && $task['TaskTime'] !== "") {
                    $taskDetails['TaskTime'] = $task['TaskTime'];
                } else {
                    $taskDetails['TaskTime'] = 8;
                }

                $durationHours = floor((float)$task['MinTimeToComplete']);
                $durationMinutes = ((float)$task['MinTimeToComplete'] - floor((float)$task['MinTimeToComplete']))*60;

                if ($durationHours === 0 && $durationMinutes < 60) {
                    $durationMinutes = 60;
                }

                $taskDetails['EndTime'] = (new \DateTime($task['TaskDateTime']))->modify('+'.$durationHours.' hour'.' +'.$durationMinutes.'minute');

                if ($task['bookingcolor'] !== '' && (int)$task['PropertyID'] === (int)$task['PropertyBookingPropertyID']) {
                    $taskDetails['Color'] = trim($task['bookingcolor']);
                } elseif ($task['color'] !== '') {
                    $taskDetails['Color'] = '#'.trim($task['color']);
                } else {
                    $taskDetails['Color'] = '##0275d8';
                }

                $taskDetails['TextColor'] = '##ffffff';

                if((base_convert(substr($taskDetails['Color'],2,2),16,10)*0.299) +
                    (base_convert(substr($taskDetails['Color'],4,2),16,10)*0.587) +
                    (base_convert(substr($taskDetails['Color'],-2),16,10)*0.144) > 200
                ) {
                    $taskDetails['TextColor'] = '##000000';
                }

                if ((new \DateTime($task['TaskCompleteByDate'])) < $localDate->setTime(0,0,0) &&
                    ($task['CompleteConfirmedDate'] === "")
                ) {
                    $taskDetails['TextColor'] = '##FFA500';
                }

                $taskDetails['CompleteConfirmedDate'] = $task['CompleteConfirmedDate'];
                $taskDetails['Abbreviation'] = $task['Abbreviation'];
                $taskDetails['TaskAbbreviation'] = $task['TaskAbbreviation'];
                $taskDetails['TaskName'] = $task['TaskName'];
                $taskDetails['TaskTime'] = $task['TaskTime'];
                $taskDetails['TaskTimeMinutes'] = $task['TaskTimeMinutes'];
                $taskDetails['PropertyName'] = $task['PropertyName'];
                $taskDetails['TaskID'] = $task['TaskID'];
                $taskDetails['TaskDateTime'] = $task['TaskDateTime'];
                $taskDetails['RegionSortOrder'] = $task['RegionSortOrder'];
                $taskDetails['PropertySortOrder'] = $task['PropertySortOrder'];

                $allTasks[] = $taskDetails;
            }


            return array(
                'Properties' => $properties,
                'Bookings' => $bookings,
                'Tasks' => $allTasks
            );

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching booking calender details due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}