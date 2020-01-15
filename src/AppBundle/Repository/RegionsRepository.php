<?php
/**
 * Created by PhpStorr.
 * User: Sobhan
 * Date: 5/11/19
 * Time: 11:45 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use AppBundle\Constants\GeneralConstants;

class RegionsRepository extends EntityRepository
{
    /**
     * @param $region
     * @param $customerID
     * @return mixed
     */
    public function GetRegionID($region, $customerID)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('IDENTITY(r.regiongroupid)')
            ->where('r.customerid= :CustomerID')
            ->andWhere('r.regionid IN (:Regions)')
            ->setParameter('CustomerID', $customerID)
            ->setParameter('Regions', $region)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetRegionIDLoggedInStaffID0($customerID)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('IDENTITY(r.regiongroupid)')
            ->where('r.customerid= :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to get regions detail
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $regionID
     * @param $offset
     *
     * @return array
     */
    public function fetchRegions($customerDetails, $queryParameter, $regionsID, $offset)
    {
        $query = "";
        $fields = array();
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('r');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter['limit']) ? $limit = $queryParameter['limit'] : $limit = 20);

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . GeneralConstants::REGIONS_MAPPING[$field];
            }
        } else {
            $query .= implode(',', GeneralConstants::REGIONS_MAPPING);
        }
        $query = trim($query, ',');
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('r.' . $field);
            }
        }

        //condition to filter by region id
        if (isset($regionsID)) {
            $result->andWhere('r.regionid IN (:RegionsID)')
                ->setParameter('RegionsID', $regionsID);
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('r.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return region details
        return $result
            ->innerJoin('r.regiongroupid', 'rg')
            ->innerJoin('r.timezoneid', 't')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }
}