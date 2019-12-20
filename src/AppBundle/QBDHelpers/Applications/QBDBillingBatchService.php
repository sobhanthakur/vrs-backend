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
    /* This function sends the desired XML that is to be processed by Quickbooks
     * @param $object
     * @return SendRequestXML|mixed
     */
    /**
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
        $xml = '';
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
                    $refNUmber = $this->ReferenceNumber();
                    foreach ($tasks as $task) {
                        $response[$task['QBDCustomerListID']][] = $task['QBDItemFullName'];
                        $billingRecordID[] = $task['IntegrationQBDBillingRecordID'];
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
                                    <RefNumber >'.$refNUmber.'</RefNumber>';
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
                    $updateBillingBatch = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->UpdateBillingBatchWithRefNumber(array_unique($billingRecordID), $refNUmber, $batchID->getIntegrationqbbatchid());
                }
            }
        }
        return new SendRequestXML($xml);
    }

    /**
     * This function parses the XML that is processed by Quickbooks.
     * @param $object
     * @return ReceiveResponseXML|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function receiveResponseXML($object)
    {
        // Send Response as 100% Success
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