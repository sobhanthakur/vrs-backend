<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 29/10/19
 * Time: 2:40 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class ServicesRepository
 * @package AppBundle\Repository
 */
class ServicesRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @param $department
     * @param $billable
     * @param $matched
     * @return array
     */
    public function SyncServices($customerID, $department, $billable, $matched)
    {
        $select1 = 's.serviceid AS TaskRuleID, s.servicename as TaskRuleName, ';
        $select2 = ' AS LaborOrMaterials, IDENTITY(i.integrationqbditemid) AS IntegrationQBDItemID';
        $result = $this
            ->createQueryBuilder('s')
            ->select($select1 . '(CASE WHEN i.laborormaterials=1 OR i.laborormaterials IS NULL THEN 1 ELSE 0 END)' . $select2);

        $result2 = $this
            ->createQueryBuilder('s')
            ->select($select1 . '(CASE WHEN i.laborormaterials=0 OR i.laborormaterials IS NULL THEN 0 ELSE 1 END)' . $select2);

        switch ($matched) {
            // If status is only matched
            case 1:
                $condition = ' AND i.integrationqbditemid IS NOT NULL';
                $result
                    ->where('i.laborormaterials=1' . $condition);
                $result2
                    ->where('i.laborormaterials=0' . $condition);
                break;
            case 2:
                // If status is only Not yet matched matched
                $condition = 'i.integrationqbditemid IS NULL';
                $result->where($condition);
                $result2->where($condition);
                break;
            default:
                $condition = ' OR i.laborormaterials IS NULL';
                $result
                    ->where('i.laborormaterials=1' . $condition);
                $result2
                    ->where('i.laborormaterials=0' . $condition);
        }


        $result = $this->TrimResult($result, $customerID, $department, $billable);
        $result2 = $this->TrimResult($result2, $customerID, $department, $billable);

        return array(
            'Result1' => $result->getQuery()->getSQL(),
            'Result2' => $result2->getQuery()->getSQL()
        );
    }

    /**
     * @param QueryBuilder $result
     * @param $customerID
     * @param $department
     * @param $billable
     * @return mixed
     */
    public function TrimResult($result, $customerID, $department, $billable)
    {
        $result
            ->leftJoin('AppBundle:Integrationqbditemstoservices', 'i', Expr\Join::WITH, 'i.serviceid=s.serviceid')
            ->andWhere('s.customerid=' . $customerID)
            ->andWhere('s.active=1');


        if ($department) {
            $condition = 's.servicegroupid IN (';
            $i = 0;
            for (; $i < count($department) - 1; $i++) {
                $condition .= $department[$i] . ',';
            }
            $condition .= $department[$i] . ')';
            $result->andWhere($condition);
        }

        if ($billable) {
            if (count($billable) === 1 &&
                in_array(GeneralConstants::BILLABLE, $billable)
            ) {
                $result->andWhere('s.billable=1');
            } elseif (count($billable) === 1 &&
                in_array(GeneralConstants::NOT_BILLABLE, $billable)) {
                $result->andWhere('s.billable=0');
            }
        }

        return $result;
    }

    /**
     * Function to fetch task rules details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskRulesID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchTaskRules($customerDetails, $queryParameter, $taskRulesID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('s');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check for limit option in query paramter
        (isset($queryParameter[GeneralConstants::PARAMS['ACTIVE']]) ? $active = $queryParameter[GeneralConstants::PARAMS['ACTIVE']] : null);

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                $result->orderBy('s.' . $field);
            }
        }

        //condition to filter by task rules id
        if ($taskRulesID) {
            $result->andWhere('s.serviceid IN (:TaskRuleID)')
                ->setParameter('TaskRuleID', $taskRulesID);
        }


        //condition to filter by customer details
        if ($customerDetails) {
            $result->andWhere('s.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to filter by by active status
        if (isset($active)) {
            $result->andWhere('s.active IN (:Active)')
                ->setParameter('Active', $active);
        }

        //return issue details
        return $result
            ->getQuery()
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();

    }

    /**
     * Function to fetch task rules details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskRulesID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $taskRulesID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all task rules field
        $issuesField = GeneralConstants::TASK_RULES_MAPPING;

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

        return $this->fetchTaskRules($customerDetails, $queryParameter, $taskRulesID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of task rules of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskRulesID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $taskRulesID, $offset)
    {
        $query = "count(s.serviceid)";
        return $this->fetchTaskRules($customerDetails, $queryParameter, $taskRulesID, $offset, $query);

    }
}

