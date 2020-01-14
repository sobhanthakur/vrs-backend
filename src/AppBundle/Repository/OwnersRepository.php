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

class OwnersRepository extends EntityRepository
{

    public function GetOwners($customerID)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.ownerid as OwnerID, p.ownername as OwnerName')
            ->where('p.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
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
     *
     * @return array
     */
    public function fetchOwners($customerDetails, $queryParameter, $ownerID, $offset)
    {
        $query = "";
        $fields = array();
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('o');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter['limit']) ? $limit = $queryParameter['limit'] : $limit = 20);

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . GeneralConstants::OWNERS_MAPPING[$field];
            }
        } else {
            $query .= implode(',', GeneralConstants::OWNERS_MAPPING);
        }

        $query = trim($query, ',');
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
            ->innerJoin('o.countryid', 'c')
            ->where('o.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerDetails)
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();
    }
}