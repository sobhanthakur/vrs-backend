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
            ->select($select1.'(CASE WHEN i.laborormaterials=1 OR i.laborormaterials IS NULL THEN 1 ELSE 0 END)'.$select2);

        $result2 = $this
            ->createQueryBuilder('s')
            ->select($select1.'(CASE WHEN i.laborormaterials=0 OR i.laborormaterials IS NULL THEN 0 ELSE 1 END)'.$select2);

        switch ($matched) {
            // If status is only matched
            case 1:
                $condition = ' AND i.integrationqbditemid IS NOT NULL';
                $result
                    ->where('i.laborormaterials=1'.$condition);
                $result2
                    ->where('i.laborormaterials=0'.$condition);
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
                    ->where('i.laborormaterials=1'.$condition);
                $result2
                    ->where('i.laborormaterials=0'.$condition);
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
            $result->andWhere('s.servicegroupid IN (:Departments)')
                ->setParameter('Departments', $department);
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
}

