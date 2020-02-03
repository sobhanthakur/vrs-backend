<?php


namespace AppBundle\QBDHelpers\Applications;
use AppBundle\Constants\GeneralConstants;
use AppBundle\QBDHelpers\Base\AbstractQBWCApplication;
use AppBundle\QBDHelpers\Response\ReceiveResponseXML;
use AppBundle\QBDHelpers\Response\SendRequestXML;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class QBDFailedTimeTrackingBatchService
 * @package AppBundle\QBDHelpers\Applications
 */
class QBDFailedTimeTrackingBatchService extends AbstractQBWCApplication
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
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username,'qbdsyncpayroll'=>true,'active'=>true));
            if ($integrationToCustomer) {
                $customerID = $integrationToCustomer->getCustomerid()->getCustomerid();
                $session->set(GeneralConstants::CUSTOMER_ID,$customerID);
                $failedRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetFailedTimeTrackingRecord($customerID);
                if($failedRecords) {
                    // Append XML request as per configurations
                    $qbxmlVersion = $this->_config['qbxmlVersion'];
                    $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="' . $qbxmlVersion . '"?>
                    <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">';
                    $requestId = $this->generateGUID();
                    foreach ($failedRecords as $failedRecord) {
                        $xml .= '<TimeTrackingQueryRq  requestID="' . $requestId . '" metaData="NoMetaData">
                            <TxnDateRangeFilter>
                                <FromTxnDate>'.$failedRecord['day']->format('Y-m-d').'</FromTxnDate>
                                <ToTxnDate>'.$failedRecord['day']->format('Y-m-d').'</ToTxnDate>
                                </TxnDateRangeFilter>
                                <TimeTrackingEntityFilter>
                                    <ListID>'.$failedRecord['qbdemployeelistid'].'</ListID>
                                </TimeTrackingEntityFilter>
                            </TimeTrackingQueryRq >
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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function receiveResponseXML($object)
    {
        $this->qbLogger->debug($object->response);
        $session = new Session();
        $customerID = $session->get(GeneralConstants::CUSTOMER_ID);
        $failedRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetFailedTimeTrackingRecord($customerID);
        $response = simplexml_load_string($object->response);
        if (isset($response->QBXMLMsgsRs)) {
            $response = $response->QBXMLMsgsRs->TimeTrackingQueryRs;
            for($i=0;$i<count($response);$i++) {
                for($j=0;$j<count($failedRecords);$j++) {
                    if(
                        $response[$i]->TimeTrackingRet->TxnDate == $failedRecords[$j]['day']->format('Y-m-d') &&
                        $response[$i]->TimeTrackingRet->EntityRef->ListID == $failedRecords[$j]['qbdemployeelistid']
                    ) {
                        $txnID = $response[$i]->TimeTrackingRet->TxnID;
                        $records = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->UpdateFailedRecords($customerID,$failedRecords[$j]['day']->format('Y-m-d'),$failedRecords[$j]['qbdemployeelistid']);
                        foreach ($records as $record) {
                            $timetracking = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(array('integrationqbdtimetrackingrecords'=>$record['integrationqbdtimetrackingrecords']));
                            $timetracking->setTxnid($txnID);
                            $this->entityManager->persist($timetracking);
                        }
                    }
                }
                $this->entityManager->flush();
            }
        }
        return new ReceiveResponseXML(100);
    }
}