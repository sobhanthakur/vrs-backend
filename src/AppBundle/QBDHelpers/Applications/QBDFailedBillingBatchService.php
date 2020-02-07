<?php


namespace AppBundle\QBDHelpers\Applications;

use AppBundle\Constants\GeneralConstants;
use AppBundle\QBDHelpers\Base\AbstractQBWCApplication;
use AppBundle\QBDHelpers\Response\ReceiveResponseXML;
use AppBundle\QBDHelpers\Response\SendRequestXML;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class QBDFailedBillingBatchService
 * @package AppBundle\QBDHelpers\Applications
 */
class QBDFailedBillingBatchService extends AbstractQBWCApplication
{
    /**
     * @param $object
     * @throws \Exception
     * @return SendRequestXML|mixed
     */
    public function sendRequestXML($object)
    {
        $session = new Session();
        $xml = '';
        if (
            $session->get(GeneralConstants::QWC_TICKET_SESSION) &&
            $session->get(GeneralConstants::QWC_USERNAME_SESSION)
        ) {
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username,'qbdsyncbilling'=>true,'active'=>true));
            if ($integrationToCustomer) {
                $version = $integrationToCustomer->getVersion();
                $type = $integrationToCustomer->getType();

                if($version >=2) {
                    throw new \Exception("Invalid Version");
                }

                $customerID = $integrationToCustomer->getCustomerid()->getCustomerid();
                $tasks = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->GetFailedBillingRecords($customerID);
                if ($tasks) {
                    // Append XML request as per configurations
                    $qbxmlVersion = $this->_config['qbxmlVersion'];
                    $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="' . $qbxmlVersion . '"?>
                    <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">';
                    $requestId = $this->generateGUID();
                    foreach ($tasks as $task) {
                        if ($type == 2) {
                            // Query for Invoice
                            $xml .= '<InvoiceQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">
                                     <RefNumber>'.$task['RefNumber'].'</RefNumber>
                                     </InvoiceQueryRq >';
                        } elseif ($version == 0 && $type == 0) {
                            // Query for SalesOrder
                            $xml .= '<SalesOrderQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">
                                     <RefNumber>'.$task['RefNumber'].'</RefNumber>
                                     </SalesOrderQueryRq >';
                        } else {
                            // Query for Estimate
                            $xml .= '<EstimateQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">
                                     <RefNumber>'.$task['RefNumber'].'</RefNumber>
                                     </EstimateQueryRq >';
                        }
                    }
                    $xml .= '</QBXMLMsgsRq></QBXML>';
                }
            }
        }
        return new SendRequestXML($xml);
    }

    /**
     * @param $object
     * @return ReceiveResponseXML|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function receiveResponseXML($object)
    {
        $this->qbLogger->debug($object->response);
        $response = simplexml_load_string($object->response);
        if (isset($response->QBXMLMsgsRs)) {
            if(isset($response->QBXMLMsgsRs->EstimateQueryRs)) {
                $response = $response->QBXMLMsgsRs->EstimateQueryRs;
                for($i=0;$i<count($response);$i++) {
                    $refNumber = $response[$i]->EstimateRet->RefNumber;
                    $billingRecord = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findBy(array('refnumber' => (string)$refNumber));
                    foreach ($billingRecord as $billing) {
                        $txnID = $response[$i]->EstimateRet->TxnID;
                        $billing->setTxnid($txnID);
                        $this->entityManager->persist($billing);
                    }
                }
            } elseif(isset($response->QBXMLMsgsRs->SalesOrderQueryRs)) {
                $response = $response->QBXMLMsgsRs->SalesOrderQueryRs;
                for($i=0;$i<count($response);$i++) {
                    $refNumber = $response[$i]->SalesOrderRet->RefNumber;
                    $billingRecord = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findBy(array('refnumber' => (string)$refNumber));
                    foreach ($billingRecord as $billing) {
                        $txnID = $response[$i]->SalesOrderRet->TxnID;
                        $billing->setTxnid($txnID);
                        $this->entityManager->persist($billing);
                    }
                }
            } else {
                $response = $response->QBXMLMsgsRs->InvoiceQueryRs;
                for($i=0;$i<count($response);$i++) {
                    $refNumber = $response[$i]->InvoiceRet->RefNumber;
                    $billingRecord = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findBy(array('refnumber' => (string)$refNumber));
                    foreach ($billingRecord as $billing) {
                        $txnID = $response[$i]->InvoiceRet->TxnID;
                        $billing->setTxnid($txnID);
                        $this->entityManager->persist($billing);
                    }
                }
            }

            // Persist the entity Manager
            $this->entityManager->flush();
        }

        return new ReceiveResponseXML(100);
    }
}