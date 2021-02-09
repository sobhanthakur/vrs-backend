<?php

namespace AppBundle\Repository;
use AppBundle\Constants\GeneralConstants;

/**
 * PropertyStatusesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PropertyStatusesRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Function to fetch property Status details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyStatusID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $propertyStatusID, $restriction, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all properties field
        $propertiesField = GeneralConstants::PROPERTY_STATUS_MAPPING;

        //Get properties restrict field
        $propertiesRestrictField = GeneralConstants::PROPERTIES_RESTRICTION;

        //Checking restrict personal data
        $restrictionPersonalData = $restriction->restrictPersonalData;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $propertiesField[$field];
            }
        } else {
            if ($restrictionPersonalData) {
                $propertiesField = array_diff_key($propertiesField, array_flip($propertiesRestrictField));
            }
            $query .= implode(',', $propertiesField);
        }
        $query = trim($query, ',');

        return $this->fetchPropertyStatus($customerDetails, $queryParameter, $propertyStatusID, $offset, $query, $limit);

    }

    /**
     * Function to parse and fetch property status details according to query parameter
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyStatusID
     * @param $offset
     *
     * @return array
     */
    public function fetchPropertyStatus($customerDetails, $queryParameter, $propertyStatusID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('p');

        $result->select($query);

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('p.' . $field);
            }
        }

        //condition to filter by property id
        if (isset($propertyStatusID)) {
            $result->andWhere('p.propertyid IN (:PropertyID)')
                ->setParameter('PropertyID', $propertyStatusID);
        }

        //condition to filter by owner id
        if (isset($queryParameter['propertystatus'])) {
            $result->andWhere("p.propertystatus = '".$queryParameter['propertystatus']."'");
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to filter by customer details
        if (isset($limit)) {
            $result->setMaxResults($limit);
        }

        //return property details
        return $result
            ->setFirstResult(($offset - 1) * $limit)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to get no. of properties of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $propertyStatusID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $propertyStatusID, $offset)
    {
        $query = "p.propertystatusid";
        return $this->fetchPropertyStatus($customerDetails, $queryParameter, $propertyStatusID, $offset, $query);

    }
}
