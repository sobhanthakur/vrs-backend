<?php


namespace AppBundle\QBDHelpers\Applications;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbbatches;
use AppBundle\QBDHelpers\Base\AbstractQBWCApplication;
use AppBundle\QBDHelpers\Response\ReceiveResponseXML;
use AppBundle\QBDHelpers\Response\SendRequestXML;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class QBDTimeTrackingBatchService
 * @package AppBundle\QBDHelpers\Applications
 */
class QBDTimeTrackingBatchService extends AbstractQBWCApplication
{
    /**
     * @param $object
     * @return SendRequestXML|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sendRequestXML($object)
    {
        $session = new Session();
        $xml = '';
        $updateTimeTrackingBatch = null;
        if (
            $session->get(GeneralConstants::QWC_TICKET_SESSION) &&
            $session->get(GeneralConstants::QWC_USERNAME_SESSION)
        ) {
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username,'qbdsyncpayroll'=>true));
            if ($integrationToCustomer) {
                $customerID = $integrationToCustomer->getCustomerid()->getCustomerid();
                $timeClockDays = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetTimeTrackingRecordsToSync($customerID);
                if($timeClockDays) {
                    // Append XML request as per configurations
                    $qbxmlVersion = $this->_config['qbxmlVersion'];
                    $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="' . $qbxmlVersion . '"?>
                    <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">';
                    foreach ($timeClockDays as $timeClockDay) {
                        $date = explode(":",gmdate('H:i:s',$timeClockDay['TimeTrackedSeconds']));
                        $date = ((int)$date[0]/1).'H'.((int)$date[1]/1).'M'.((int)$date[2]/1).'S';
                        $requestId = $this->generateGUID();
                        $xml .= '
                                <TimeTrackingAddRq requestID="' . $requestId . '">
                                <TimeTrackingAdd>
                                    <TxnDate >'.$timeClockDay['Date']->format('Y-m-d').'</TxnDate>
                                    <EntityRef>
                                        <FullName>'.$timeClockDay['QBDEmployeeName'].'</FullName>
                                    </EntityRef>
                                    <Duration>PT'.$date.'</Duration>
                                    <PayrollItemWageRef>
                                        <FullName>'.$integrationToCustomer->getIntegrationqbdhourwagetypeid()->getQbdpayrollitemwagename().'</FullName>
                                    </PayrollItemWageRef>
                                    <BillableStatus >NotBillable</BillableStatus>
                                </TimeTrackingAdd>
                            </TimeTrackingAddRq>';
                    }
                    $xml .= '</QBXMLMsgsRq></QBXML>';
                    $batchID = new Integrationqbbatches();
                    $batchID->setIntegrationtocustomer($integrationToCustomer);
                    $batchID->setBatchtype(true);
                    $this->entityManager->persist($batchID);
                    $this->entityManager->flush();

                    $integrationQBDTimetrackingID = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetUnsycedTimeTrackingBatch($customerID);
                    $session->set(GeneralConstants::QWC_BATCHID_SESSION,$batchID->getIntegrationqbbatchid());
                    $updateTimeTrackingBatch = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->UpdateTimeTrackingBatches($batchID->getIntegrationqbbatchid(),$integrationQBDTimetrackingID);
                }
            }
        }
        if($updateTimeTrackingBatch) {
            return new SendRequestXML($xml);
        }
        return null;
    }

    /**
     * @param $object
     * @return ReceiveResponseXML|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function receiveResponseXML($object)
    {
        $this->qbLogger->debug($object->response);
        $session = new Session();
        $batchID = $session->get(GeneralConstants::QWC_BATCHID_SESSION);
        $response = simplexml_load_string($object->response);
        if(isset($response->QBXMLMsgsRs) && isset($response->QBXMLMsgsRs->TimeTrackingAddRs)) {
            $timeTracking = $response->QBXMLMsgsRs->TimeTrackingAddRs;
            for ($i=0;$i<count($timeTracking);$i++) {
                $timeTrackingRet = $timeTracking[$i]->TimeTrackingRet;
                $txnID = $timeTrackingRet->TxnID;
                $txnDate = (string)$timeTrackingRet->TxnDate;
                $listID = (string)$timeTrackingRet->EntityRef->ListID;
                $timeTrackingIDs = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->UpdateSuccessTxnID($batchID,$txnDate,$listID,$txnID);
                foreach ($timeTrackingIDs as $items) {
                    $txnTimeTracking = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->findOneBy(array('integrationqbdtimetrackingrecords' => $items['integrationqbdtimetrackingrecords']));
                    $txnTimeTracking->setTxnid($txnID);
                    $this->entityManager->persist($txnTimeTracking);
                }
                $this->entityManager->flush();
            }
        }
        return new ReceiveResponseXML(100);
    }
}