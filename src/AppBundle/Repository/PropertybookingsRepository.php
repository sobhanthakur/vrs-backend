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

        $result = $this
            ->createQueryBuilder('pb');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;
        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter[GeneralConstants::PARAMS['ACTIVE']]) ? $active = $queryParameter[GeneralConstants::PARAMS['ACTIVE']] : null);

        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('pb.' . $field);
            }
        }

        //condition to filter by property id
        if (isset($propertyBookingID)) {
            $result->andWhere('pb.propertybookingid IN (:PropertyBookingID)')
                ->setParameter('PropertyBookingID', $propertyBookingID);
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to filter by customer details
        if (isset($active)) {
            $result->andWhere('pb.active IN (:Active)')
                ->setParameter('Active', $active);
        }

        //return property booking details
        return $result
            ->innerJoin('pb.propertyid', 'p')
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
}