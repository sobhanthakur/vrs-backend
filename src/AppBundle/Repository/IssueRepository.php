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
     *
     * @return array
     */
    public function fetchIssues($customerDetails, $queryParameter, $issueID, $offset)
    {
        $query = "";
        $fields = array();
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('i');

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : $fields;

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter['limit']) ? $limit = $queryParameter['limit'] : $limit = 20);

        //condition to set query for all or some required fields
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . GeneralConstants::ISSUE_MAPPING[$field];
            }
        } else {
            $query .= implode(',', GeneralConstants::ISSUE_MAPPING);
        }
        $query = trim($query, ',');
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('i.' . $field);
            }
        }

        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //check for image url
        if (empty($fields) || in_array('image1', $fields)) {
            $result
                ->setParameter('image_url', GeneralConstants::IMAGE_URL);
        }
        if (empty($fields) || in_array('image2', $fields)) {
            $result
                ->setParameter('image_url', GeneralConstants::IMAGE_URL);
        }
        if (empty($fields) || in_array('image3', $fields)) {
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

}