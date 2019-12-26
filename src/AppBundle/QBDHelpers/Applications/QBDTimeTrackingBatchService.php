<?php


namespace AppBundle\QBDHelpers\Applications;

use AppBundle\Constants\GeneralConstants;
use AppBundle\QBDHelpers\Base\AbstractQBWCApplication;
use AppBundle\QBDHelpers\Response\ReceiveResponseXML;
use AppBundle\QBDHelpers\Response\SendRequestXML;
use Symfony\Component\HttpFoundation\Session\Session;

class QBDTimeTrackingBatchService extends AbstractQBWCApplication
{
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
                $timeClockDays = $this->entityManager->getRepository('AppBundle:Integrationqbdtimetrackingrecords')->GetTimeTrackingRecordsToSync($customerID);
                if($timeClockDays) {
                    // Append XML request as per configurations
                    $qbxmlVersion = $this->_config['qbxmlVersion'];
                    $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="' . $qbxmlVersion . '"?>
                    <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">';
                    $requestId = $this->generateGUID();
                    foreach ($timeClockDays as $timeClockDay) {
                        $date = explode(":",gmdate('H:i:s',$timeClockDay['TimeTrackedSeconds']));
                        $date = ((int)$date[0]/1).'H'.((int)$date[1]/1).'M'.((int)$date[2]/1).'S';
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
                }
            }
        }
        return new SendRequestXML($xml);
    }

    public function receiveResponseXML($object)
    {
        return new ReceiveResponseXML(100);
    }
}