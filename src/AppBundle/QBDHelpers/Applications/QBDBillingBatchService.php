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
        $description = [];
        $amount = [];
        $xml = '';
        if (
            $session->get(GeneralConstants::QWC_TICKET_SESSION) &&
            $session->get(GeneralConstants::QWC_USERNAME_SESSION)
        ) {
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username,'qbdsyncbilling'=>true));
            if ($integrationToCustomer) {
                $customerID = $integrationToCustomer->getCustomerid()->getCustomerid();
                $tasks = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->GetTasksForSalesOrder($customerID);

                if (!empty($tasks)) {
                    foreach ($tasks as $task) {
                        $ref = $task['IntegrationQBDBillingRecordID'].$this->ReferenceNumber(8-strlen($task['IntegrationQBDBillingRecordID']));
                        $response[$task['QBDCustomerListID']][] = $task['QBDItemFullName'];
                        $referenceID[$task['QBDCustomerListID']] = $ref;
                        $billingRecordID[$task['QBDCustomerListID']][] = $task['IntegrationQBDBillingRecordID'];
                        $description[$task['QBDCustomerListID']][] = $task['PropertyName'].' - '.$task['TaskName'].' - '.$task['ServiceName'].' - '.$this->TimeZoneConversion($task['CompleteConfirmedDate']->format('Y-m-d'),$task['Region']).' - '.($task['LaborOrMaterial'] === true ? "Materials":"Labor");
                        $amount[$task['QBDCustomerListID']][] = $task['Amount'];
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

                        foreach ($value as $key1=>$value1) {
                            $xml .= '
                                <SalesOrderLineAdd>
                                <ItemRef>
                                <FullName >'.$value1.'</FullName>
                                </ItemRef>
                                <Desc>'.(string)$description[$key][$key1].'</Desc>
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
                    foreach ($referenceID as $key=>$value) {
                        $billingIDs = array_unique($billingRecordID[$key]);
                        foreach ($billingIDs as $item) {
                            $billingID = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findOneBy(array('integrationqbdbillingrecordid'=>$item));
                            if($billingID) {
                                $billingID->setSentstatus(true);
                                $billingID->setIntegrationqbbatchid($batchID);
                                $billingID->setRefnumber($referenceID[$key]);
                                $this->entityManager->persist($billingID);
                            }
                        }
                    }
                    $this->entityManager->flush();
                }
            }
        }
        return new SendRequestXML($xml);
    }

    /**
     * @param $object
     * @return ReceiveResponseXML|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function receiveResponseXML($object)
    {
        // Send Response as 100% Success
        $response = simplexml_load_string($object->response);
        if(isset($response->QBXMLMsgsRs) && isset($response->QBXMLMsgsRs->SalesOrderAddRs)) {
            $salesOrders = $response->QBXMLMsgsRs->SalesOrderAddRs;
            for ($i=0;$i<count($salesOrders);$i++) {
                $refNumber = $salesOrders[$i]->SalesOrderRet->RefNumber;
                $billingRecord = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findBy(array('refnumber' => (string)$refNumber));
                foreach ($billingRecord as $billing) {
                    $txnID = $salesOrders[$i]->SalesOrderRet->TxnID;
                    $billing->setTxnid($txnID);
                    $this->entityManager->persist($billing);
                }
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
    public function ReferenceNumber($digits)
    {
        $format = 'u';
        $utimestamp = null;
        if (!$utimestamp) {
            $utimestamp = microtime(true);
        }

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * pow(10,$digits));

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    /**
     * @param $date
     * @param $region
     * @return string
     */
    public function TimeZoneConversion($date, $region)
    {
        $localTimeZone = new \DateTimeZone($region);
        $date = new \DateTime($date,$localTimeZone);

        return $date->format('Y-m-d');
    }
}