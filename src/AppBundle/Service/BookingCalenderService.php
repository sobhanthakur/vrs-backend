<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 7/1/21
 * Time: 12:38 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
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
            if ($rsServicers[GeneralConstants::TIMEZONEREGION] !== '') {
                $localDate = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($rsServicers[GeneralConstants::TIMEZONEREGION]);
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
                $bookingDetails['ResourceID'] = $propertyBooking[GeneralConstants::PROPERTY_ID];
                $bookingDetails[GeneralConstants::PROPERTY_ID] = $propertyBooking[GeneralConstants::PROPERTY_ID];
                $bookingDetails[GeneralConstants::COLOR] = $propertyBooking[GeneralConstants::COLOR];
                $bookingDetails[GeneralConstants::TEXTCOLOR] = '##ffffff';
                $bookingDetails['Editable'] = 1;

                if (trim($propertyBooking['BookingColor']) !== '' &&
                    (int)$propertyBooking[GeneralConstants::PROPERTY_ID] === (int)$propertyBooking['PropertyBookingPropertyID']
                ) {
                    $bookingDetails[GeneralConstants::COLOR] = $propertyBooking['BookingColor'];
                } elseif ($propertyBooking[GeneralConstants::COLOR] !== '') {
                    $bookingDetails[GeneralConstants::COLOR] = '#'.$propertyBooking[GeneralConstants::COLOR];
                } else {
                    $bookingDetails[GeneralConstants::COLOR] = '##0275d8';
                }

                if((base_convert(substr($bookingDetails[GeneralConstants::COLOR],2,2),16,10)*0.299) +
                    (base_convert(substr($bookingDetails[GeneralConstants::COLOR],4,2),16,10)*0.587) +
                    (base_convert(substr($bookingDetails[GeneralConstants::COLOR],-2),16,10)*0.144) > 200
                ) {
                    $bookingDetails[GeneralConstants::TEXTCOLOR] = '##000000';
                }

                $bookingDetails[GeneralConstants::CHECKIN] = $propertyBooking[GeneralConstants::CHECKIN];
                $bookingDetails[GeneralConstants::CHECKINTIME] = $propertyBooking[GeneralConstants::CHECKINTIME] >= 12 ? ($propertyBooking[GeneralConstants::CHECKINTIME] % 12)." PM" : $propertyBooking[GeneralConstants::CHECKINTIME]." AM";
                $bookingDetails[GeneralConstants::CHECKOUT] = $propertyBooking[GeneralConstants::CHECKOUT];
                $bookingDetails[GeneralConstants::CHECKOUTTIME] = $propertyBooking[GeneralConstants::CHECKOUTTIME] >= 12 ? ($propertyBooking[GeneralConstants::CHECKINTIME] % 12)." PM" : $propertyBooking[GeneralConstants::CHECKOUTTIME]." AM";

                // Set Start Time
                $start = new \DateTime($propertyBooking[GeneralConstants::CHECKIN]);
                $start->setTime((int)$propertyBooking[GeneralConstants::CHECKINTIME],0);
                $bookingDetails['Start'] = $start;

                // Set End Time
                $end = new \DateTime($propertyBooking[GeneralConstants::CHECKOUT]);
                $end->setTime((int)$propertyBooking[GeneralConstants::CHECKOUTTIME],0);
                $bookingDetails['End'] = $end;

                // Description
                $description = "IN ".$propertyBooking[GeneralConstants::CHECKIN].", ".$propertyBooking[GeneralConstants::CHECKINTIME]."<br />";
                $description .= "OUT ".$propertyBooking[GeneralConstants::CHECKOUT].", ".$propertyBooking[GeneralConstants::CHECKOUTTIME]."<br />";

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
                $bookingDetails[GeneralConstants::PROPERTYNAME] = str_replace(array('&', '.','*','"',"'"),"",$propertyBooking[GeneralConstants::PROPERTYNAME]);
                $bookingDetails['BackToBackEnd'] = $propertyBooking['BackToBackEnd'];
                $bookingDetails['IsTask'] = 0;
                $bookingDetails['BorderColor'] = '';
                $bookings[] = $bookingDetails;
            }

            // Fetch Tasks
            $tasks = $this->entityManager->getRepository('AppBundle:Tasks')->GetTasksForBookingCalender($servicerID,$thisStartDate,$thisEndDate,$rsServicers[GeneralConstants::TIMEZONEREGION]);

            // Iterate through Tasks
            foreach ($tasks as $task) {
                $taskDetails = [];
                $taskDetails[GeneralConstants::REGION] = "";
                if ($task[GeneralConstants::REGION] !== $taskDetails[GeneralConstants::REGION]) {
                    $taskDetails[GeneralConstants::REGION] = $task[GeneralConstants::REGION];
                }
                $taskDetails['Editable'] = 0;

                $taskDetails[GeneralConstants::TASKNAME] = "Unassigned";
                $taskDetails['ResourceID'] = $task[GeneralConstants::PROPERTY_ID]." ".$task['Name'];
                if ($task['Name'] !== "") {
                    $taskDetails[GeneralConstants::TASKNAME] = $task['Name'];
                }

                if ((int)$task[GeneralConstants::TASKTIME] !== 99 && $task[GeneralConstants::TASKTIME] !== "") {
                    $taskDetails[GeneralConstants::TASKTIME] = $task[GeneralConstants::TASKTIME];
                } else {
                    $taskDetails[GeneralConstants::TASKTIME] = 8;
                }

                $durationHours = floor((float)$task[GeneralConstants::MINTIMETOCOMPLETE]);
                $durationMinutes = ((float)$task[GeneralConstants::MINTIMETOCOMPLETE] - floor((float)$task[GeneralConstants::MINTIMETOCOMPLETE]))*60;

                if ($durationHours === 0 && $durationMinutes < 60) {
                    $durationMinutes = 60;
                }

                $taskDetails['Start'] = (new \DateTime($task[GeneralConstants::TASKDATETIME]))->modify('+'.$durationHours.' hour'.' +'.$durationMinutes.'minute');
                $taskDetails['End'] = null;

                $borderColor = null;
                if ((string)$task['bookingcolor'] !== '' && (int)$task[GeneralConstants::PROPERTY_ID] === (int)$task['PropertyBookingPropertyID']) {
                    $borderColor = trim($task['bookingcolor']);
                } elseif ($task['color'] !== '') {
                    $borderColor = '#'.trim($task['color']);
                } else {
                    $borderColor = '##0275d8';
                }
                $taskDetails['BorderColor'] = $borderColor;

                $taskDetails[GeneralConstants::TEXTCOLOR] = '##ffffff';

                if((base_convert(substr($borderColor,2,2),16,10)*0.299) +
                    (base_convert(substr($borderColor,4,2),16,10)*0.587) +
                    (base_convert(substr($borderColor,-2),16,10)*0.144) > 200
                ) {
                    $taskDetails[GeneralConstants::TEXTCOLOR] = '##000000';
                }

                if ((new \DateTime($task['TaskCompleteByDate'])) < $localDate->setTime(0,0,0) &&
                    ($task[GeneralConstants::COMPLETECONFIRMEDDATE] === "")
                ) {
                    $taskDetails[GeneralConstants::TEXTCOLOR] = '##FFA500';
                }

                $taskDetails[GeneralConstants::COLOR] = '';
                $taskDetails[GeneralConstants::COMPLETECONFIRMEDDATE] = $task[GeneralConstants::COMPLETECONFIRMEDDATE];
                $taskDetails['Abbreviation'] = $task['Abbreviation'];
                $taskDetails['TaskAbbreviation'] = $task['TaskAbbreviation'];
                $taskDetails[GeneralConstants::TASKNAME] = $task[GeneralConstants::TASKNAME];
                $taskDetails[GeneralConstants::TASKTIME] = $task[GeneralConstants::TASKTIME];
                $taskDetails['TaskTimeMinutes'] = $task['TaskTimeMinutes'];
                $taskDetails[GeneralConstants::PROPERTYNAME] = $task[GeneralConstants::PROPERTYNAME];
                $taskDetails['TaskID'] = $task['TaskID'];
                $taskDetails[GeneralConstants::TASKDATETIME] = $task[GeneralConstants::TASKDATETIME];
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