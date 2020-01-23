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
     * @return SendRequestXML|mixed
     */
    public function sendRequestXML($object)
    {
        $session = new Session();
        $xml = '';
        $version = 0;
        if (
            $session->get(GeneralConstants::QWC_TICKET_SESSION) &&
            $session->get(GeneralConstants::QWC_USERNAME_SESSION)
        ) {
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username,'qbdsyncbilling'=>true));
            if ($integrationToCustomer) {
                $version = $integrationToCustomer->getVersion();
                // Set QB Version in a session
                $session->set('Version',$version);


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
                        if($version == 1) {
                            $xml .= '<EstimateQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">';
                        } else {
                            $xml .= '<SalesOrderQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">';    
                        }
                        
                        $xml .= '<RefNumber>'.$task['RefNumber'].'</RefNumber>';

                        if($version == 1) {
                            $xml .= '</EstimateQueryRq >';
                        } else {
                            $xml .= '</SalesOrderQueryRq >';    
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
        $session = new Session();
        $version = $session->get('Version');

        $response = simplexml_load_string($object->response);
        if (isset($response->QBXMLMsgsRs)) {
            if($version == 1) {
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
            } else {
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
            }
            
            $this->entityManager->flush();
        }

        return new ReceiveResponseXML(100);
    }
}