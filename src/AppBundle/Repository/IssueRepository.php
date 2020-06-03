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

/**
 * Class IssueRepository
 * @package AppBundle\Repository
 */
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

        //check for createstartdate and createenddate in query paramter
        isset($queryParameter['createstartdate']) ? $createdStartDate = $queryParameter['createstartdate'] : $createdStartDate = null;
        isset($queryParameter['createenddate']) ? $createdEndDate = $queryParameter['createenddate'] : $createdEndDate = null;

        //check for closedstartdate and closedenddate in query paramter
        isset($queryParameter['closedstartdate']) ? $closedStartDate = $queryParameter['closedstartdate'] : $closedStartDate = null;
        isset($queryParameter['closedenddate']) ? $closedEndDate = $queryParameter['closedenddate'] : $closedEndDate = null;

        //setting status id
        if (isset($queryParameter['statusid'])) {
            switch (strtolower($queryParameter['statusid'])) {
                case "new":
                    $statusID = 0;
                    break;
                case "inprogress":
                    $statusID = 1;
                    break;
                case "onhold":
                    $statusID = 2;
                    break;
                case "cateloged":
                    $statusID = 0;
                    break;
                default:
                    $statusID = null;
            }
        }

        //setting issue type
        if (isset($queryParameter['issuetype'])) {
            switch (strtolower($queryParameter['issuetype'])) {
                case "damage":
                    $issueType = 0;
                    break;
                case "maintenance":
                    $issueType = 1;
                    break;
                case "lostandfound":
                    $issueType = 2;
                    break;
                case "supplyflag":
                    $issueType = 3;
                    break;
                case "none":
                    $issueType = -1;
                    break;
                default:
                    $issueType = null;
            }
        }

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for urgent option in query paramter
        isset($queryParameter['urgent']) ? $urgent = $queryParameter['urgent'] : $urgent = null;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for closed option in query paramter
        isset($queryParameter['closed']) ? $closed = $queryParameter['closed'] : $closed = null;

        //check for billable in query paramter
        isset($queryParameter['billable']) ? $billable = $queryParameter['billable'] : $billable = null;

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

        //condition to filter by  createdStartDate
        if ($createdStartDate) {
            $createdStartDate = date("Y-m-d", strtotime($createdStartDate));
            $result->andWhere('i.createdate >= (:CreatedDate)')
                ->setParameter('CreatedDate', $createdStartDate);
        }
        //condition to filter by  createdEndDate
        if ($createdEndDate) {
            $createdEndDate = date("Y-m-d", strtotime($createdEndDate . ' +1 day'));
            $result->andWhere('i.createdate <= (:CreatedDate)')
                ->setParameter('CreatedDate', $createdEndDate);
        }

        //condition to filter by  closedStartDate
        if ($closedStartDate) {
            $closedStartDate = date("Y-m-d", strtotime($closedStartDate));
            $result->andWhere('i.closeddate >= (:ClosedDate)')
                ->setParameter('ClosedDate', $closedStartDate);
        }
        //condition to filter by  closedEndDate
        if ($closedEndDate) {
            $closedEndDate = date("Y-m-d", strtotime($closedEndDate . ' +1 day'));
            $result->andWhere('i.closeddate <= (:ClosedDate)')
                ->setParameter('ClosedDate', $closedEndDate);
        }

        //condition to filter by issue id
        if ($issueID) {
            $result->andWhere('i.issueid IN (:IssueID)')
                ->setParameter('IssueID', $issueID);
        }

        //condition to filter by status id
        if (isset($statusID)) {
            $result->andWhere('i.statusid = (:StatusID)')
                ->setParameter('StatusID', $statusID);
        }

        //condition to filter by  issueType
        if (isset($issueType)) {
            $result->andWhere('i.issuetype = (:IssueType)')
                ->setParameter('IssueType', $issueType);
        }

        //condition to filter by  issueType
        if (isset($urgent)) {
            $result->andWhere('i.urgent = (:Urgent)')
                ->setParameter('Urgent', $urgent);
        }

        //condition to filter by  billable
        if (isset($billable)) {
            $result->andWhere('i.billable = (:Billable)')
                ->setParameter('Billable', $billable);
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
            ->leftJoin('i.propertyid', 'p')
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

    public function GetIssuesFromLastOneMinute($issueType,$issue)
    {
        $now = (new \DateTime('now'))->modify('-1 minutes');
        return $this->createQueryBuilder('i')
            ->select('i.issueid')
            ->where('i.issuetype = :IssueType')
            ->andWhere('i.issue = :Issue')
            ->andWhere('i.createdate > :CreateDate')
            ->setParameter('IssueType',$issueType)
            ->setParameter('Issue',$issue)
            ->setParameter('CreateDate',$now)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

    }
}