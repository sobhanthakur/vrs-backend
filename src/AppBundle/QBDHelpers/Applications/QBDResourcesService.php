<?php

namespace AppBundle\QBDHelpers\Applications;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbdcustomers;
use AppBundle\Entity\Integrationqbdemployees;
use AppBundle\Entity\Integrationqbditems;
use AppBundle\Entity\Integrationqbdpayrollitemwages;
use AppBundle\QBDHelpers\Base\AbstractQBWCApplication;
use AppBundle\QBDHelpers\Response\ReceiveResponseXML;
use AppBundle\QBDHelpers\Response\SendRequestXML;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class QBDResourcesService
 * @package AppBundle\QBDHelpers\Applications
 */
class QBDResourcesService extends AbstractQBWCApplication
{
    /**
     * This function sends the desired XML that is to be processed by Quickbooks
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
            $ticket = $session->get(GeneralConstants::QWC_TICKET_SESSION);
            $username = $session->get(GeneralConstants::QWC_USERNAME_SESSION);
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('username' => $username));
            if ($integrationToCustomer) {
                // Append XML request as per configurations
                $qbxmlVersion = $this->_config['qbxmlVersion'];
                $xml = '<?xml version="1.0" encoding="utf-8"?>
                    <?qbxml version="' . $qbxmlVersion . '"?>
                    <QBXML>
                    <QBXMLMsgsRq onError="stopOnError">';

                // If Billing is enabled, then fetch the Customers and Items
                if ($integrationToCustomer->getQbdsyncbilling()) {
                    $requestId = $this->generateGUID();
                    $xml .= '<CustomerQueryRq requestID="' . $requestId . '" metaData="NoMetaData">
                            <ActiveStatus >All</ActiveStatus>
                            </CustomerQueryRq>
                            <ItemQueryRq requestID="' . $requestId . '" metaData="NoMetaData">
                            <ActiveStatus >All</ActiveStatus>
                            </ItemQueryRq>
                            ';
                }

                // If TimeTracking is enabled, then feth employees and payroll Item wages
                if ($integrationToCustomer->getQbdsyncpayroll()) {
                    $requestId = $this->generateGUID();
                    $xml .= '<EmployeeQueryRq requestID="' . $requestId . '" metaData="NoMetaData">
                            <ActiveStatus >All</ActiveStatus>
                            </EmployeeQueryRq>
                            <PayrollItemWageQueryRq requestID="' . $requestId . '" metaData="NoMetaData">
                            <ActiveStatus >All</ActiveStatus>
                            </PayrollItemWageQueryRq>
                            ';
                }

                $xml .= '</QBXMLMsgsRq></QBXML>';
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
        $session = new Session();
        $response = simplexml_load_string($object->response);
        // Parse the XML and Store the records accordingly
        $response = $response->QBXMLMsgsRs;
        $customerID = $this->entityManager->getRepository('AppBundle:Customers')->findOneBy(array('customerid' => $session->get(GeneralConstants::CUSTOMER_ID)));
        if (isset($response->CustomerQueryRs) && isset($response->CustomerQueryRs->CustomerRet)) {
            $incomingListIDs = [];
            $qbdListIDs = [];
            $customers = $response->CustomerQueryRs->CustomerRet;

            for ($i = 0; $i < count($customers); $i++) {
                $incomingListIDs[] = (string)$customers[$i]->ListID;
                $qbdCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->findOneBy(array('qbdcustomerlistid' => $customers[$i]->ListID));

                // If the customer is not present then create a new record or else update the customer
                if (!$qbdCustomers) {
                    $qbdCustomers = new Integrationqbdcustomers();
                }
                $qbdCustomers->setActive($customers[$i]->IsActive == "false" ? false : true);
                $qbdCustomers->setCustomerid($customerID);
                $qbdCustomers->setQbdcustomerlistid($customers[$i]->ListID);
                $qbdCustomers->setQbdcustomerfullname($customers[$i]->FullName);
                $this->entityManager->persist($qbdCustomers);
            }
            $qbdCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->GetAllCustomers($session->get(GeneralConstants::CUSTOMER_ID));
            foreach ($qbdCustomers as $val) {
                $qbdListIDs[] = $val['QBDCustomerListID'];
            }

            // Check if any record is deleted or not.
            $diffArray = array_diff($qbdListIDs, $incomingListIDs);
            foreach ($diffArray as $key => $value) {
                $qbdCustomers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->findOneBy(array('qbdcustomerlistid' => $value));
                $qbdCustomers->setActive(false);
            }
            $this->entityManager->flush();
        }

        if (isset($response->ItemQueryRs) && isset($response->ItemQueryRs->ItemNonInventoryRet)) {
            $incomingListIDs = [];
            $qbdListIDs = [];
            $items = $response->ItemQueryRs->ItemNonInventoryRet;

            for ($i = 0; $i < count($items); $i++) {
                $incomingListIDs[] = (string)$items[$i]->ListID;
                $qbdItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array('qbditemlistid' => $items[$i]->ListID));
                if (!$qbdItems) {
                    $qbdItems = new Integrationqbditems();
                }
                $qbdItems->setActive($items[$i]->IsActive == "false" ? false : true);
                $qbdItems->setCustomerid($customerID);
                $qbdItems->setQbditemlistid($items[$i]->ListID);
                $qbdItems->setQbditemfullname($items[$i]->FullName);
                $this->entityManager->persist($qbdItems);
            }
            $qbdItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->GetAllItems($session->get(GeneralConstants::CUSTOMER_ID));
            foreach ($qbdItems as $val) {
                $qbdListIDs[] = $val['QBDItemListID'];
            }
            $diffArray = array_diff($qbdListIDs, $incomingListIDs);
            foreach ($diffArray as $key => $value) {
                $qbdItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array('qbditemlistid' => $value));
                $qbdItems->setActive(false);
            }
            $this->entityManager->flush();
        }

        if (isset($response->EmployeeQueryRs) && isset($response->EmployeeQueryRs->EmployeeRet)) {
            $incomingListIDs = [];
            $qbdListIDs = [];
            $employees = $response->EmployeeQueryRs->EmployeeRet;

            for ($i = 0; $i < count($employees); $i++) {
                $incomingListIDs[] = (string)$employees[$i]->ListID;
                $qbdEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->findOneBy(array('qbdemployeelistid' => $employees[$i]->ListID));
                if (!$qbdEmployees) {
                    $qbdEmployees = new Integrationqbdemployees();
                }
                $qbdEmployees->setActive($employees[$i]->IsActive == "false" ? false : true);
                $qbdEmployees->setCustomerid($customerID);
                $qbdEmployees->setQbdemployeelistid($employees[$i]->ListID);
                $qbdEmployees->setQbdemployeefullname($employees[$i]->Name);
                $this->entityManager->persist($qbdEmployees);
            }
            $qbdEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->GetAllEmployees($session->get(GeneralConstants::CUSTOMER_ID));
            foreach ($qbdEmployees as $val) {
                $qbdListIDs[] = $val['QBDEmployeeListID'];
            }
            $diffArray = array_diff($qbdListIDs, $incomingListIDs);
            foreach ($diffArray as $key => $value) {
                $qbdEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->findOneBy(array('qbdemployeelistid' => $value));
                $qbdEmployees->setActive(false);
            }
            $this->entityManager->flush();
        }

        if (isset($response->PayrollItemWageQueryRs) && isset($response->PayrollItemWageQueryRs->PayrollItemWageRet)) {
            $incomingListIDs = [];
            $qbdListIDs = [];
            $payrollItems = $response->PayrollItemWageQueryRs->PayrollItemWageRet;

            for ($i = 0; $i < count($payrollItems); $i++) {
                $incomingListIDs[] = (string)$payrollItems[$i]->ListID;
                $qbdPayrollItems = $this->entityManager->getRepository('AppBundle:Integrationqbdpayrollitemwages')->findOneBy(array('qbdpayrollitemwagelistid' => $payrollItems[$i]->ListID));
                if (!$qbdPayrollItems) {
                    $qbdPayrollItems = new Integrationqbdpayrollitemwages();
                }
                $qbdPayrollItems->setActive($payrollItems[$i]->IsActive == "false" ? false : true);
                $qbdPayrollItems->setCustomerid($customerID);
                $qbdPayrollItems->setQbdpayrollitemwagelistid($payrollItems[$i]->ListID);
                $qbdPayrollItems->setQbdpayrollitemwagename($payrollItems[$i]->Name);
                $this->entityManager->persist($qbdPayrollItems);
            }
            $qbdPayrollItems = $this->entityManager->getRepository('AppBundle:Integrationqbdpayrollitemwages')->GetAllPayrollItemWages($session->get(GeneralConstants::CUSTOMER_ID));
            foreach ($qbdPayrollItems as $val) {
                $qbdListIDs[] = $val['QBDPayrollItemListID'];
            }

            // Check if any record is deleted or not.
            $diffArray = array_diff($qbdListIDs, $incomingListIDs);
            foreach ($diffArray as $key => $value) {
                $qbdPayrollItems = $this->entityManager->getRepository('AppBundle:Integrationqbdpayrollitemwages')->findOneBy(array('qbdpayrollitemwagelistid' => $value));
                $qbdPayrollItems->setActive(false);
            }
            $this->entityManager->flush();
        }
        // Send Response as 100% Success
        return new ReceiveResponseXML(100);
    }
}