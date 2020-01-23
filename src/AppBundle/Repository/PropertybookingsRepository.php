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
    public function fetchPropertyBooking($customerDetails, $queryParameter, $propertyBookingID, $restriction, $offset)
    {
        $query = "";
        $fields = array();
        $sortOrder = array();

        //Get all owners field
        $propertyBookingField = GeneralConstants::PROPERTY_BOOKINGS_MAPPING;

        //Get owners restrict field
        $propertyBookingRestrictField = GeneralConstants::PROPERTY_BOOKINGS_RESTRICTION;

        $result = $this
            ->createQueryBuilder('pb');

        //Checking restrict personal data
        $restrictionPersonalData = $restriction->restrictPersonalData;

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter['limit']) ? $limit = $queryParameter['limit'] : $limit = 20);

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $propertyBookingField[$field];
            }
        } else {
            if ($restrictionPersonalData) {
                $propertyBookingField = array_diff_key($propertyBookingField, array_flip($propertyBookingRestrictField));
            }
            $query .= implode(',', $propertyBookingField);
        }

        $query = trim($query, ',');
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

        //return owner details
        return $result
            ->innerJoin('pb.propertyid', 'p')
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();
    }
}