<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 21/1/20
 * Time: 2:22 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Constants\GeneralConstants;

class IssueRepository extends EntityRepository
{

    /**
     * Function to fetch issues details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $issueID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchIssues($customerDetails, $queryParameter, $issueID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('i');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for closed option in query paramter
        isset($queryParameter['closed']) ? $closed = $queryParameter['closed'] : $closed = null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('i.' . $field);
            }
        }

        //Filter for closed Date,
        if (isset($closed)) {
            if ($closed == 1) {
                $result->andWhere('i.closeddate IS NOT NULL');
            } elseif ($closed == 0) {
                $result->andWhere('i.closeddate IS NULL');
            }
        }

        //condition to filter by issue id
        if ($issueID) {
            $result->andWhere('i.issueid IN (:IssueID)')
                ->setParameter('IssueID', $issueID);
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //check images and set parameter
        if (strpos($query, 'image1') !== false) {
            $result
                ->setParameter('image_url', GeneralConstants::IMAGE_URL);
        }
        if (strpos($query, 'image2') !== false) {
            $result
                ->setParameter('image_url', GeneralConstants::IMAGE_URL);
        }
        if (strpos($query, 'image3') !== false) {
            $result
                ->setParameter('image_url', GeneralConstants::IMAGE_URL);
        }

        //return issue details
        return $result
            ->innerJoin('i.propertyid', 'p')
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();
    }

    /**
     * Function to fetch issue details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $regionID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $issueID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all regions field
        $issuesField = GeneralConstants::ISSUE_MAPPING;

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : $fields;

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $issuesField[$field];
            }
        } else {
            $query .= implode(',', $issuesField);
        }
        $query = trim($query, ',');

        return $this->fetchIssues($customerDetails, $queryParameter, $issueID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of issues of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $issueID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $issueID, $offset)
    {
        $query = "i.issueid";
        return $this->fetchIssues($customerDetails, $queryParameter, $issueID, $offset, $query);

    }

}