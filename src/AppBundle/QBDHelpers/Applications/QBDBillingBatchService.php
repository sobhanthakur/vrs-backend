<?php

namespace AppBundle\QBDHelpers\Applications;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbbatches;
use AppBundle\QBDHelpers\Base\AbstractQBWCApplication;
use AppBundle\QBDHelpers\Response\ReceiveResponseXML;
use AppBundle\QBDHelpers\Response\SendRequestXML;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class QBDBillingBatchService
 * @package AppBundle\QBDHelpers\Applications
 */
class QBDBillingBatchService extends AbstractQBWCApplication
{
    /**
     * This function sends the desired XML that is to be processed by Quickbooks
     * @param $object
     * @return SendRequestXML|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sendRequestXML($object)
    {
        $session = new Session();
        $response = [];
        $billingRecordID = [];
        $referenceID = [];
        $xml = '';
        $updateBillingBatch = false;
        if (
            $session->get(GeneralConstants::QWC_TICKET_SESSION) &&
            $session->get(GeneralConstants::QWC_USERNAME_SESSION)
        ) {
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username));
            if ($integrationToCustomer) {
                $customerID = $integrationToCustomer->getCustomerid()->getCustomerid();
                $tasks = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->GetTasksForSalesOrder($customerID);
                if (!empty($tasks)) {
                    foreach ($tasks as $task) {
                        $response[$task['QBDCustomerListID']][] = $task['QBDItemFullName'];
                        $referenceID[$task['QBDCustomerListID']] = $task['IntegrationQBDBillingRecordID'].$this->ReferenceNumber();
                        $billingRecordID[$task['QBDCustomerListID']] = $task['IntegrationQBDBillingRecordID'];
                    }

                    // Create Sales Order
                    $requestId = $this->generateGUID();
                    $qbxmlVersion = $this->_config['qbxmlVersion'];
                    $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="' . $qbxmlVersion . '"?>
                    <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">';
                    foreach ($response as $key => $value) {
                        $xml .= '
                                <SalesOrderAddRq requestID="' . $requestId . '">
                                <SalesOrderAdd>
                                    <CustomerRef>
                                        <ListID>'.$key.'</ListID>
                                    </CustomerRef>
                                    <RefNumber >'.$referenceID[$key].'</RefNumber>
                                    ';

                        foreach ($value as $item) {
                            $xml .= '
                                <SalesOrderLineAdd>
                                <ItemRef>
                                <FullName >'.$item.'</FullName>
                                </ItemRef>
                                </SalesOrderLineAdd>
                            ';
                        }
                        $xml .= '
                                </SalesOrderAdd>
                                </SalesOrderAddRq>
                            ';
                    }
                    $xml .= '</QBXMLMsgsRq></QBXML>';
                    // Create new Batch
                    $batchID = new Integrationqbbatches();
                    $batchID->setBatchtype(false);
                    $batchID->setIntegrationtocustomer($integrationToCustomer);
                    $this->entityManager->persist($batchID);
                    $this->entityManager->flush();

                    // Update Billing Records rows and set reference number, batch id and sentstatus(to 1)
                    $updateBillingBatch = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->UpdateBillingBatchWithRefNumber($billingRecordID, $referenceID, $batchID->getIntegrationqbbatchid());
                }
            }
        }
        if($updateBillingBatch) {
            return new SendRequestXML($xml);
        }
        return null;
    }

    /**
     * @param $object
     * @return ReceiveResponseXML|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function receiveResponseXML($object)
    {
        $session = new Session();

        // Send Response as 100% Success
        $response = simplexml_load_string($object->response);
        if(isset($response->QBXMLMsgsRs) && isset($response->QBXMLMsgsRs->SalesOrderAddRs)) {
            $customerID = $this->entityManager->getRepository('AppBundle:Customers')->findOneBy(array('customerid' => $session->get(GeneralConstants::CUSTOMER_ID)));
            $salesOrders = $response->QBXMLMsgsRs->SalesOrderAddRs;
            for ($i=0;$i<count($salesOrders);$i++) {
                $refNumber = $salesOrders[$i]->SalesOrderRet->RefNumber;
                $billingRecord = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findOneBy(array('refnumber' => (string)$refNumber));
                if($billingRecord) {
                    $txnID = $salesOrders[$i]->SalesOrderRet->TxnID;
                    $billingRecord->setTxnid($txnID);
                }
                $this->entityManager->persist($billingRecord);
            }
            $this->entityManager->flush();
        }
        return new ReceiveResponseXML(100);
    }

    /**
     * @param string $format
     * @param null $utimestamp
     * @return false|string
     */
    public function ReferenceNumber($format = 'u', $utimestamp = null)
    {
        if (!$utimestamp) {
            $utimestamp = microtime(true);
        }

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }
}