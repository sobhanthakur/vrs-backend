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
        if (
            $session->get(GeneralConstants::QWC_TICKET_SESSION) &&
            $session->get(GeneralConstants::QWC_USERNAME_SESSION)
        ) {
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username));
            if ($integrationToCustomer) {
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
                        $xml .= '<SalesOrderQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">
                            <RefNumber>'.$task['RefNumber'].'</RefNumber>
                            </SalesOrderQueryRq >
                            ';
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
        $response = simplexml_load_string($object->response);
        if (isset($response->QBXMLMsgsRs)) {
            $response = $response->QBXMLMsgsRs->SalesOrderQueryRs;
            for($i=0;$i<count($response);$i++) {
                $refNumber = $response[$i]->SalesOrderRet->RefNumber;
                $billingRecord = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findOneBy(array('refnumber' => (string)$refNumber));
                if($billingRecord) {
                    $txnID = $response[$i]->SalesOrderRet->TxnID;
                    $billingRecord->setTxnid($txnID);
                }
                $this->entityManager->persist($billingRecord);
            }
            $this->entityManager->flush();
        }

        return new ReceiveResponseXML(100);
    }
}