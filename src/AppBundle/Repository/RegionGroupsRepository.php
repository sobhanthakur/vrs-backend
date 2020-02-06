<?php
/**
 * Created by Sobhan Thakur.
 * Date: 13/9/19
 * Time: 6:18 PM
 */

namespace AppBundle\Repository;

use AppBundle\Constants\GeneralConstants;

class RegionGroupsRepository extends \Doctrine\ORM\EntityRepository
{
    public function GetRegionGroupsRestrictions($customerID,$region)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('r.regiongroupid as RegionGroupID, r.regiongroup as RegionGroup')
            ->where('r.customerid = (:CustomerID)')
            ->andWhere('r.regiongroupid IN (:Regions)')
            ->setParameter('CustomerID',$customerID)
            ->setParameter('Regions',$region)
            ->getQuery()
            ->execute();
    }

    public function GetRegionGroupsFilter($customerID)
    {
        return $this
            ->createQueryBuilder('r')
            ->select('r.regiongroupid as RegionGroupID, r.regiongroup as RegionGroup')
            ->where('r.customerid = (:CustomerID)')
            ->setParameter('CustomerID',$customerID)
            ->orderBy('r.regiongroup','ASC')
            ->getQuery()
            ->execute();
    }

    /**
     * Function to get region groups detail
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $regionGroupsID
     * @param $offset
     *
     * @return array
     */
    public function fetchRegionGroups($customerDetails, $queryParameter, $regionGroupsID, $offset, $query, $limit = null){
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('rg');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('rg.' . $field);
            }
        }

        //condition to filter by region id
        if (isset($regionGroupsID)) {
            $result->andWhere('rg.regiongroupid IN (:RegionGroupsID)')
                ->setParameter('RegionGroupsID', $regionGroupsID);
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('rg.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //return region groups details
        return $result
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();

    }

    /**
     * Function to fetch region details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $regiongroupID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $regiongroupID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all regions field
        $regiongroupField = GeneralConstants::REGION_GROUPS_MAPPING;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $regiongroupField[$field];
            }
        } else {
            $query .= implode(',', $regiongroupField);
        }
        $query = trim($query, ',');

        return $this->fetchRegionGroups($customerDetails, $queryParameter, $regiongroupID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of regions of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $regionID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $regiongroupID, $offset)
    {
        $query = "rg.regiongroupid";
        return $this->fetchRegionGroups($customerDetails, $queryParameter, $regiongroupID, $offset, $query);

    }
}