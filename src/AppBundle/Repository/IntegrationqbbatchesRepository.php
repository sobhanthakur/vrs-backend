<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 29/11/19
 * Time: 4:01 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\EntityRepository;

/**
 * Class IntegrationqbbatchesRepository
 * @package AppBundle\Repository
 */
class IntegrationqbbatchesRepository extends EntityRepository
{
    /**
     * @param $integrationToCustomerID
     * @param $completedDate
     * @param $batchType
     * @return mixed
     */
    public function CountBatches($integrationToCustomerID, $completedDate, $batchType)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('count(b1.integrationqbbatchid)')
            ->innerJoin('b1.integrationtocustomer','c1')
            ->where('c1.integrationtocustomerid = :IntegrationToCustomerID')
            ->setParameter('IntegrationToCustomerID',$integrationToCustomerID);

        if ($completedDate && !empty($completedDate['From']) && !empty($completedDate['To'])) {
            $result->andWhere('b1.createdate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ((count($batchType) === 1) && in_array(GeneralConstants::BILLING, $batchType)) {
            $result->andWhere('b1.batchtype=0');
        } elseif ((count($batchType) === 1) && in_array(GeneralConstants::TIME_TRACKING, $batchType)) {
            $result->andWhere('b1.batchtype=1');
        }

        return $result->getQuery()->execute();
    }

    /**
     * @param $integrationToCustomerID
     * @param $completedDate
     * @param $batchType
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function FetchBatches($integrationToCustomerID, $completedDate, $batchType, $limit, $offset)
    {
        $result = $this
            ->createQueryBuilder('b1')
            ->select('b1.integrationqbbatchid AS IntegrationQBBatchID, b1.batchtype AS BatchType, b1.createdate AS CreateDate')
            ->innerJoin('b1.integrationtocustomer','c1')
            ->where('c1.integrationtocustomerid = :IntegrationToCustomerID')
            ->setParameter('IntegrationToCustomerID',$integrationToCustomerID);

        if ($completedDate && !empty($completedDate['From']) && !empty($completedDate['To'])) {
            $result->andWhere('b1.createdate BETWEEN :CompletedFrom AND :CompletedTo')
                ->setParameter('CompletedFrom', $completedDate['From'])
                ->setParameter('CompletedTo', $completedDate['To']);
        }

        if ((count($batchType) === 1) && in_array(GeneralConstants::BILLING, $batchType)) {
            $result->andWhere('b1.batchtype=0');
        } elseif ((count($batchType) === 1) && in_array(GeneralConstants::TIME_TRACKING, $batchType)) {
            $result->andWhere('b1.batchtype=1');
        }

        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);

        return $result->getQuery()->execute();
    }

}