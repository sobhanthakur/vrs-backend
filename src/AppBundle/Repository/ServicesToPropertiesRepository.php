<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 26/2/21
 * Time: 1:51 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class ServicesToPropertiesRepository
 * @package AppBundle\Repository
 */
class ServicesToPropertiesRepository extends EntityRepository
{
    /**
     * function to check whether the Entered TaskRuleID is valid or not
     * @param $taskRuleID
     * @param $propertyID
     * @param $authDetails
     * @return mixed
     */
    public function CheckValidTaskRule($taskRuleID, $propertyID, $authDetails)
    {
        return $this->createQueryBuilder('stp')
            ->select('stp.servicetopropertyid AS ServiceToPropertyID')
            ->addSelect('IDENTITY(stp.defaultservicerid) AS DefaultServicerID')
            ->addSelect('servicerid.backupservicerid1 AS BackupServicerID1')
            ->addSelect('servicerid.backupservicerid2 AS BackupServicerID2')
            ->addSelect('servicerid.backupservicerid3 AS BackupServicerID3')
            ->addSelect('servicerid.backupservicerid4 AS BackupServicerID4')
            ->addSelect('servicerid.backupservicerid5 AS BackupServicerID5')
            ->addSelect('servicerid.backupservicerid6 AS BackupServicerID6')
            ->addSelect('servicerid.backupservicerid7 AS BackupServicerID7')
            ->addSelect('servicerid.workdays AS WorkDays')
            ->addSelect('stp.mintimetocomplete AS MinTimeToComplete')
            ->addSelect('stp.maxtimetocomplete AS MaxTimeToComplete')
            ->addSelect('stp.numberofservicers AS NumberOfServicers')
            ->addSelect('s.includedamage AS IncludeDamage')
            ->addSelect('s.includemaintenance AS IncludeMaintenance')
            ->addSelect('s.includelostandfound AS IncludeLostAndFound')
            ->addSelect('s.includesupplyflag AS IncludeSupplyFlag')
            ->addSelect('s.includeservicernote AS IncludeServicerNote')
            ->addSelect('s.notifycustomeroncompletion AS NotifyCustomerOnCompletion')
            ->addSelect('s.notifycustomeronoverdue AS NotifyCustomerOnOverdue')
            ->addSelect('s.notifycustomerondamage AS NotifyCustomerOnDamage')
            ->addSelect('s.notifycustomeronmaintenance AS NotifyCustomerOnMaintenance')
            ->addSelect('s.notifycustomeronservicernote AS NotifyCustomerOnServicerNote')
            ->addSelect('s.notifycustomeronlostandfound AS NotifyCustomerOnLostAndFound')
            ->addSelect('s.notifycustomeronsupplyflag AS NotifyCustomerOnSupplyFlag')
            ->addSelect('s.includetoownernote AS IncludeToOwnerNote')
            ->addSelect('s.defaulttoownernote AS DefaultToOwnerNote')
            ->addSelect('s.notifyowneroncompletion AS NotifyOwnerOnCompletion')
            ->addSelect('s.allowshareimageswithowners AS AllowShareImagesWithOwners')
            ->addSelect('s.notifyserviceronoverdue AS NotifyServicerOnOverdue')
            ->addSelect('s.notifycustomeronnotyetdone AS NotifyCustomerOnNotYetDone')
            ->addSelect('s.notifyserviceronnotyetdone AS NotifyServicerOnNotYetDone')
            ->addSelect('s.billable AS Billable')
            ->addSelect('s.amount AS Amount')
            ->addSelect('s.expenseamount AS ExpenseAmount')
            ->addSelect('stp.piecepay AS PiecePay')
            ->addSelect('s.paytype AS PayType')
            ->where('stp.serviceid='.(int)$taskRuleID)
            ->andWhere('stp.propertyid='.(int)$propertyID)
            ->innerJoin('stp.propertyid','p')
            ->innerJoin('stp.serviceid','s')
            ->innerJoin('stp.defaultservicerid','servicerid')
            ->andWhere('p.customerid='.(int)$authDetails['customerID'])
            ->andWhere('s.tasktype=9')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}