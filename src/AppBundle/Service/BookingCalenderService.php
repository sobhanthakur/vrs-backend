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

            // Get PropertyBookings
            $propertyBookings = $this->entityManager->getRepository('AppBundle:Propertybookings')->GetBookingsForBookingCalender($servicerID,$thisEndDate->format('Y-m-d H:i:s'),$thisStartDate->format('Y-m-d H:i:s'));

            // Loop through Property Bookings
            foreach ($propertyBookings as $propertyBooking) {
                $bookingDetails['ResourceID'] = $propertyBooking['PropertyID'];
                $bookingDetails['PropertyID'] = $propertyBooking['PropertyID'];
                $bookingDetails['Color'] = $propertyBooking['Color'];
                $bookingDetails['TextColor'] = '##ffffff';
                $bookingDetails['Editable'] = 1;

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

                // Set Start Time
                $start = new \DateTime($propertyBooking['CheckIn']);
                $start->setTime((int)$propertyBooking['CheckInTime'],0);
                $bookingDetails['Start'] = $start;

                // Set End Time
                $end = new \DateTime($propertyBooking['CheckOut']);
                $end->setTime((int)$propertyBooking['CheckOutTime'],0);
                $bookingDetails['End'] = $end;

                // Description
                $description = "IN ".$propertyBooking['CheckIn'].", ".$propertyBooking['CheckInTime']."<br />";
                $description .= "OUT ".$propertyBooking['CheckOut'].", ".$propertyBooking['CheckOutTime']."<br />";

                // GuestDetails
                if ((int)$rsServicers['IncludeGuestName']) {
                    $description .= $propertyBooking['Guest']."<br />";
                }

                if ((int)$rsServicers['IncludeGuestNumbers']) {
                    $description .= (int)$propertyBooking['NumberOfGuests']."G / ".(int)$propertyBooking['NumberOfChildren']." C / ".(int)$propertyBooking['NumberOfPets']." P"."<br />";
                }

                if ($propertyBooking['GlobalNote'] !== '') {
                    $description .= "Booking Note: ".$propertyBooking['GlobalNote']." <br />";
                }

                if ($propertyBooking['InGlobalNote'] !== '') {
                    $description .= "Check In Note: ".$propertyBooking['InGlobalNote']." <br />";
                }

                if ($propertyBooking['OutGlobalNote'] !== '') {
                    $description .= "Check Out Note: ".$propertyBooking['OutGlobalNote']." <br />";
                }

                $bookingDetails['Description'] = $description;

                $bookingDetails['PropertyName'] = str_replace(array('&', '.','*','"',"'"),"",$propertyBooking['PropertyName']);
                $bookingDetails['BackToBackEnd'] = $propertyBooking['BackToBackEnd'];
                $bookingDetails['IsTask'] = 0;
                $bookingDetails['BorderColor'] = '';

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
                $taskDetails['Editable'] = 0;

                $taskDetails['TaskName'] = "Unassigned";
                $taskDetails['ResourceID'] = $task['PropertyID']." ".$task['Name'];
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

                $taskDetails['Start'] = (new \DateTime($task['TaskDateTime']))->modify('+'.$durationHours.' hour'.' +'.$durationMinutes.'minute');
                $taskDetails['End'] = null;

                $borderColor = null;
                if ((string)$task['bookingcolor'] !== '' && (int)$task['PropertyID'] === (int)$task['PropertyBookingPropertyID']) {
                    $borderColor = trim($task['bookingcolor']);
                } elseif ($task['color'] !== '') {
                    $borderColor = '#'.trim($task['color']);
                } else {
                    $borderColor = '##0275d8';
                }
                $taskDetails['BorderColor'] = $borderColor;

                $taskDetails['TextColor'] = '##ffffff';

                if((base_convert(substr($borderColor,2,2),16,10)*0.299) +
                    (base_convert(substr($borderColor,4,2),16,10)*0.587) +
                    (base_convert(substr($borderColor,-2),16,10)*0.144) > 200
                ) {
                    $taskDetails['TextColor'] = '##000000';
                }

                if ((new \DateTime($task['TaskCompleteByDate'])) < $localDate->setTime(0,0,0) &&
                    ($task['CompleteConfirmedDate'] === "")
                ) {
                    $taskDetails['TextColor'] = '##FFA500';
                }

                $taskDetails['Color'] = '';

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
                $taskDetails['IsTask'] = 1;
                $taskDetails['Description'] = "";

                $allTasks[] = $taskDetails;
            }


            return array(
                'Details' => array_merge($bookings,$allTasks)
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

    public function GetBookingCalenderProperties($servicerID)
    {
        try {
            //Get Distinct Properties
            $properties = $this->entityManager->getRepository('AppBundle:Properties')->PropertiesForBookingCalender($servicerID);
            return array(
                'Properties' => $properties
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