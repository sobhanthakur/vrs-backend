<?php
/**
 * Created by Sobhan Thakur.
 * Date: 16/9/19
 * Time: 10:11 AM
 */

namespace AppBundle\Repository;


/**
 * Class CustomersRepository
 * @package AppBundle\Repository
 */

use AppBundle\Constants\GeneralConstants;
use Doctrine\ORM\Query\Expr;

/**
 * Class CustomersRepository
 * @package AppBundle\Repository
 */
class CustomersRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function PiecePayRestrictions($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c.piecepay, c.icaladdon')
            ->where(GeneralConstants::CUSTOMER_ID_CONDITION)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function TestValidCustomer($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c.customerid')
            ->addSelect('c.useQuickbooks AS UseQuickbooks')
            ->addSelect('c.connectedStripeAccountID AS ConnectedStripeAccountID')
            ->addSelect('c.tracklabormaterials AS TrackLaborOrMaterials')
            ->addSelect('l.locale AS LocaleID')
            ->where(GeneralConstants::CUSTOMER_ID_CONDITION)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->leftJoin('AppBundle:Locale','l',Expr\Join::WITH, 'c.localeid=l.localeid')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @return mixed
     */
    public function GetTimeZone($customerID)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('t.region AS Region')
            ->innerJoin('c.timezoneid','t')
            ->where(GeneralConstants::CUSTOMER_ID_CONDITION)
            ->setParameter(GeneralConstants::CUSTOMER_ID, $customerID)
            ->getQuery()
            ->execute();
    }
}