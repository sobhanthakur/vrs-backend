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
    public function fetchRegionGroups($customerDetails, $queryParameter, $regionGroupsID, $offset){
        $query = "";
        $fields = array();
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('rg');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter[GeneralConstants::PARAMS['PER_PAGE']]) ? $limit = $queryParameter[GeneralConstants::PARAMS['PER_PAGE']] : $limit = 20);

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . GeneralConstants::REGION_GROUPS_MAPPING[$field];
            }
        } else {
            $query .= implode(',', GeneralConstants::REGION_GROUPS_MAPPING);
        }
        $query = trim($query, ',');
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
}