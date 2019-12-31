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
     * @param $createDate
     * @param $limit
     * @param $offset
     * @param $unmatched
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function SyncServices($customerID, $department, $billable, $createDate, $limit, $offset, $unmatched)
    {
        $select1 = 's.serviceid AS TaskRuleID, s.servicename as TaskRuleName, ';
        $select2 = ' AS LaborOrMaterials, IDENTITY(i.integrationqbditemid) AS IntegrationQBDItemID';
        $result = $this
            ->createQueryBuilder('s')
            ->select($select1.'(CASE WHEN i.laborormaterials=1 OR i.laborormaterials IS NULL THEN 1 ELSE 0 END)'.$select2);

        $result2 = $this
            ->createQueryBuilder('s')
            ->select($select1.'(CASE WHEN i.laborormaterials=0 OR i.laborormaterials IS NULL THEN 0 ELSE 1 END)'.$select2);

        if ($unmatched) {
            $condition = 'i.integrationqbditemid IS NULL';
            $result->where($condition);
            $result2->where($condition);
        } else {
            $condition = ' OR i.laborormaterials IS NULL';
            $result
                ->andWhere('i.laborormaterials=1'.$condition);
            $result2
                ->andWhere('i.laborormaterials=0'.$condition);
        }

        $result = $this->TrimResult($result, $customerID, $department, $billable);
        $result2 = $this->TrimResult($result2, $customerID, $department, $billable);

        $sql = $this->getEntityManager()->getConnection()->prepare($result->getQuery()->getSQL() . ' UNION ALL ' . $result2->getQuery()->getSQL() . ' ORDER BY s0_.ServiceID OFFSET ' . (($offset - 1) * $limit) . ' ROWS FETCH NEXT ' . $limit . ' ROWS ONLY');
        $sql->execute();
        return $sql->fetchAll();
    }

    /**
     * @param $customerID
     * @param $department
     * @param $billable
     * @param $unmatched
     * @return int|null
     */
    public function CountSyncServices($customerID, $department, $billable, $unmatched)
    {
        $select1 = 'count(s.serviceid)';
        $result = $this
            ->createQueryBuilder('s')
            ->select($select1);

        $result2 = $this
            ->createQueryBuilder('s')
            ->select($select1);

        if ($unmatched) {
            $condition = 'i.integrationqbditemid IS NULL';
            $result->where($condition);
            $result2->where($condition);
        } else {
            $condition = ' OR i.laborormaterials IS NULL';
            $result
                ->andWhere('i.laborormaterials=1'.$condition);
            $result2
                ->andWhere('i.laborormaterials=0'.$condition);
        }

        $result = $this->TrimResult($result, $customerID, $department, $billable)->getQuery()->execute();
        $result2 = $this->TrimResult($result2, $customerID, $department, $billable)->getQuery()->execute();

        $count = null;
        if($result) {
            $count = (int)$result[0][1];
        }
        if($result2) {
            $count += (int)$result2[0][1];
        }
        return $count;

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

