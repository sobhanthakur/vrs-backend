<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 14/10/19
 * Time: 4:30 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use AppBundle\Constants\GeneralConstants;

/**
 * Class OwnersRepository
 * @package AppBundle\Repository
 */
class OwnersRepository extends EntityRepository
{

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetOwners($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.ownerid as OwnerID, p.ownername as OwnerName')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->orderBy('p.ownername','ASC')
            ->getQuery()
            ->execute();
    }

    /**
     * Function to fetch owner details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $ownerID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function fetchOwners($customerDetails, $queryParameter, $ownerID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('o');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //Setting select query
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('o.' . $field);
            }
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('o.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return owner details
        return $result
            ->leftJoin('o.countryid', 'c')
            ->andWhere('o.active=1')
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();
    }

    /**
     * Function to fetch order details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $ownerID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $ownerID, $restriction, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all properties field
        $ownersField = GeneralConstants::OWNERS_MAPPING;

        //Get properties restrict field
        $propertiesRestrictField = GeneralConstants::OWNERS_RESTRICTION;

        //Checking restrict personal data
        $restrictionPersonalData = $restriction->restrictPersonalData;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $ownersField[$field];
            }
        } else {
            if ($restrictionPersonalData) {
                $ownersField = array_diff_key($ownersField, array_flip($propertiesRestrictField));
            }
            $query .= implode(',', $ownersField);
        }
        $query = trim($query, ',');

        return $this->fetchOwners($customerDetails, $queryParameter, $ownerID, $offset, $query, $limit);

    }

    /**
     * Function to get no. owners
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $ownerID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $ownerID, $offset)
    {
        $query = "o.ownerid";
        return $this->fetchOwners($customerDetails, $queryParameter, $ownerID, $offset, $query);

    }

    /**
     * @param $ownerID
     * @param $password
     * @return mixed
     */
    public function OwnerAuthForIssueForm($ownerID, $password)
    {
        return $this->createQueryBuilder('o')
            ->select('o.ownerid AS OwnerID, c.customerid AS CustomerID')
            ->leftJoin('o.customerid','c')
            ->where('o.ownerid= :OwnerID')
            ->andWhere('o.password= :Password')
            ->setParameter('Password', $password)
            ->setParameter('OwnerID',$ownerID)
            ->getQuery()
            ->execute();
    }
}