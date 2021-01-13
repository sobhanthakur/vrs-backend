<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 15/1/20
 * Time: 9:10 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Constants\GeneralConstants;

/**
 * Class PropertybookingsRepository
 * @package AppBundle\Repository
 */
class PropertybookingsRepository extends EntityRepository
{
    /**
     * Function to fetch property bookings details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyBookingID
     * @param $offset
     *
     * @return array
     */
    public function fetchPropertyBooking($customerDetails, $queryParameter, $propertyBookingID, $offset, $query, $limit = null)
    {
        $sortOrder = array();
        $propertyID = null;

        $result = $this
            ->createQueryBuilder('pb');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for propertyid in query paramter
        isset($queryParameter['propertyid']) ? $propertyID = explode(',', $queryParameter['propertyid']) : null;

        //check for checkinstartdate and checkinendtdate in query paramter
        isset($queryParameter['checkinstartdate']) ? $checkInStartDate = explode(',', $queryParameter['checkinstartdate']) : $checkInStartDate = null;
        isset($queryParameter['checkinenddate']) ? $checkInEndDate = explode(',', $queryParameter['checkinenddate']) : $checkInEndDate = null;

        //check for checkoutstartdate and checkoutenddate in query paramter
        isset($queryParameter['checkoutstartdate']) ? $checkOutStartDate = explode(',', $queryParameter['checkoutstartdate']) : $checkOutStartDate = null;
        isset($queryParameter['checkoutenddate']) ? $checkOutEndDate = explode(',', $queryParameter['checkoutenddate']) : $checkOutEndDate = null;


        //check for limit option in query paramter
        (isset($queryParameter[GeneralConstants::PARAMS['ACTIVE']]) ? $active = $queryParameter[GeneralConstants::PARAMS['ACTIVE']] : null);

        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('pb.' . $field);
            }
        }

        //condition to filter by property booking id
        if (isset($propertyBookingID)) {
            $result->andWhere('pb.propertybookingid IN (:PropertyBookingID)')
                ->setParameter('PropertyBookingID', $propertyBookingID);
        }

        //condition to filter by property  id
        if (isset($propertyID)) {
            $result->andWhere('p.propertyid IN (:PropertyID)')
                ->setParameter('PropertyID', $propertyID);
        }

        //condition to filter by  checkinstartdate
        if ($checkInStartDate) {
            $checkInStartDate = date("Y-m-d", strtotime($checkInStartDate[0]));
            $result->andWhere('pb.checkin >= (:CheckInStartDate)')
                ->setParameter('CheckInStartDate', $checkInStartDate);
        }

        //condition to filter by  checkinenddate
        if ($checkInEndDate) {
            $checkInEndDate = date("Y-m-d", strtotime($checkInEndDate[0]));
            $result->andWhere('pb.checkin <= (:CheckInEndDate)')
                ->setParameter('CheckInEndDate', $checkInEndDate);
        }

        //condition to filter by  checkoutstartdate
        if ($checkOutStartDate) {
            $checkOutStartDate = date("Y-m-d", strtotime($checkOutStartDate[0]));
            $result->andWhere('pb.checkout >= (:CheckOutStartDate)')
                ->setParameter('CheckOutStartDate', $checkOutStartDate);
        }

        //condition to filter by  checkoutenddate
        if ($checkOutEndDate) {
            $checkOutEndDate = date("Y-m-d", strtotime($checkOutEndDate[0]));
            $result->andWhere('pb.checkout <= (:CheckOutEndDate)')
                ->setParameter('CheckOutEndDate', $checkOutEndDate);
        }

        //condition to filter by by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to filter by by active status
        if (isset($active)) {
            $result->andWhere('pb.active IN (:Active)')
                ->setParameter('Active', $active);
        }

        //return property booking details
        return $result
            ->innerJoin('pb.propertyid', 'p')
            ->andWhere('pb.deleted = 0')
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();

    }

    /**
     * Function to fetch property booking details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $$propertyBookingID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $propertyBookingID, $restriction, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all properties field
        $propertyBookingField = GeneralConstants::PROPERTY_BOOKINGS_MAPPING;

        //Get properties restrict field
        $propertiesRestrictField = GeneralConstants::PROPERTY_BOOKINGS_RESTRICTION;

        //Checking restrict personal data
        $restrictionPersonalData = $restriction->restrictPersonalData;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $propertyBookingField[$field];
            }
        } else {
            if ($restrictionPersonalData) {
                $propertyBookingField = array_diff_key($propertyBookingField, array_flip($propertiesRestrictField));
            }
            $query .= implode(',', $propertyBookingField);
        }
        $query = trim($query, ',');

        return $this->fetchPropertyBooking($customerDetails, $queryParameter, $propertyBookingID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of property booking of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyBookingID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $propertyBookingID, $offset)
    {
        $query = "pb.propertybookingid ";
        return $this->fetchPropertyBooking($customerDetails, $queryParameter, $propertyBookingID, $offset, $query);

    }

    /**
     * @param $servicerID
     * @param $endDate
     * @param $startDate
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function GetBookingsForBookingCalender($servicerID, $endDate, $startDate)
    {
        $query = "
                    SELECT 
            Distinct Regions.SortOrder as RegionSortOrder,
            CheckIn,CheckOut,CheckInTime,CheckOutTime,PropertyBookings.CreateDate as BookingCreateDate,PropertyName,Region,NumberOfGuests,NumberOfChildren,NumberOfPets,Guest,GuestEmail,GuestPhone,IsOwner,PropertyBookingID,Properties.PropertyID,InternalNote,GlobalNote,InGlobalNote,OutGlobalNote,Regions.Color,IsManuallyEntered,Propertybookings.color as BookingColor,BackToBackEnd ,ImportBookingID,PropertyBookings.PropertyID as PropertyBookingPropertyID
            FROM PropertyBookings
            LEFT JOIN Properties ON PropertyBookings.PropertyID = Properties.PropertyID
            LEFT JOIN Customers ON Properties.CustomerID = Customers.CustomerID
            LEFT JOIN ServicersToProperties ON Properties.PropertyID = ServicersToProperties.PropertyID
            LEFT JOIN Regions ON Properties.RegionID = Regions.RegionID
            WHERE ServicerID = ". $servicerID."
            AND PropertyBookings.Active = 1
            AND Properties.Active = 1
            AND CheckIn <= '".$endDate."'
            AND CheckOut >= '".$startDate."'
            AND (GoLiveDate IS NULL OR CheckOut > GoLiveDate) 
            UNION
            SELECT 
            Distinct Regions.SortOrder as RegionSortOrder,
            CheckIn,CheckOut,CheckInTime,CheckOutTime,PropertyBookings.CreateDate as BookingCreateDate,PropertyName,Region,NumberOfGuests,NumberOfChildren,NumberOfPets,Guest,GuestEmail,GuestPhone,IsOwner,PropertyBookingID,Properties.PropertyID,InternalNote,GlobalNote,InGlobalNote,OutGlobalNote,Regions.Color,IsManuallyEntered,Propertybookings.color as BookingColor,BackToBackEnd ,ImportBookingID,PropertyBookings.PropertyID as PropertyBookingPropertyID
            FROM PropertyBookings
            LEFT JOIN Properties ON PropertyBookings.PropertyID = Properties.LinkedPropertyID
            LEFT JOIN Customers ON Properties.CustomerID = Customers.CustomerID
            LEFT JOIN ServicersToProperties ON Properties.PropertyID = ServicersToProperties.PropertyID
            LEFT JOIN Regions ON Properties.RegionID = Regions.RegionID
            WHERE ServicerID = ".$servicerID."
            AND PropertyBookings.Active = 1
            AND Properties.Active = 1
            AND CheckIn <= '".$endDate."'
            AND CheckOut >= '".$startDate."'
            AND (GoLiveDate IS NULL OR CheckOut > GoLiveDate) 
            ORDER BY  CheckIn
        ";

        $result = $this->getEntityManager()->getConnection()->prepare($query);

        $result->execute();
        return $result->fetchAll();
    }
}