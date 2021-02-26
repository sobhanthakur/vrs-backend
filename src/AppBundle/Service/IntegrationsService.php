<?php
/**
 * IntegrationsService service to get the list of available + installed integrations
 * @category Service
 * @author Sobhan Thakur
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbotokens;
use AppBundle\Entity\Integrationstocustomers;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class IntegrationsService
 * @package AppBundle\Service
 */
class IntegrationsService extends BaseService
{
    /**
     * @param $authenticationResult
     * @return array
     */
    public function GetAllIntegrations($authenticationResult)
    {
        try {
            $integrationResponse = [];
            // Get Customer ID
            $customerID = $authenticationResult['message'][GeneralConstants::CUSTOMER_ID];

            // Fetch All Integrations available
            $integrations = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS)->findBy(array('active'=>1));

            // Get All Integrations based on Customer ID
            $integrationsToCustomers = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS_TO_CUSTOMERS);
            $integrationsToCustomers = $integrationsToCustomers->GetAllIntegrations($customerID);

            for ($i=0; $i<sizeof($integrations); $i++) {
                $installedObject = $this->InstalledIntegration($integrations[$i]->getIntegrationid(), $integrationsToCustomers);

                // Create Response Object
                $integrationDetails = null;
                $installedStatus = 0;
                if($installedObject) {
                    $installedStatus = ($installedObject['active'] === true ? 1 : 0);
                    $integrationDetails = array(
                        GeneralConstants::QBDSYNCBILLING => $installedObject['qbdsyncbilling'],
                        GeneralConstants::QBDSYNCTT => $installedObject['qbdsyncpayroll'],
                        GeneralConstants::CREATEDATE => $installedObject['createdate'],
                        'StartDate' => $installedObject['startdate'],
                        'Version' => $installedObject['version'],
                        'Type' => $installedObject['type'],
                        GeneralConstants::TIMETRACKING_TYPE => ($installedObject['timetrackingtype'] === true ? "1" : ($installedObject['timetrackingtype'] === null ? null : "0"))
                    );
                }
                $integrationResponse[$i] = array(
                    GeneralConstants::INTEGRATION_ID => $integrations[$i]->getIntegrationid(),
                    'Integration' => $integrations[$i]->getIntegration(),
                    'Logo' => $integrations[$i]->getLogo(),
                    'Installed' => $installedStatus,
                    'Details' => $integrationDetails
                );
            }
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'IntegrationDetailsResponse' => $integrationResponse
            );
        } catch (\Exception $exception) {
            $this->logger->error('Integration Details Cannot Be Fetched Due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $integrationId
     * @param $integrationToCustomers
     * @return null
     */
    public function InstalledIntegration($integrationId, $integrationToCustomers)
    {
        foreach ($integrationToCustomers as $key => $val) {
            if ($val[GeneralConstants::integrationid] == $integrationId) {
                return $integrationToCustomers[$key];
            }
        }
        return null;
    }


    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function InstallQuickbooksDesktop($content, $customerID)
    {
        try {
            // Validate Request
            if(!array_key_exists(GeneralConstants::START_DATE,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_START_DATE);
            }
            if(!array_key_exists(GeneralConstants::QBDSYNCBILLING,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_QBDSYNCBILLING);
            }
            if(!array_key_exists(GeneralConstants::QBDSYNCTT,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_QBDSYNCTT);
            }
            if(!array_key_exists(GeneralConstants::PASS,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_PASS);
            }
            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            /*
             * Read Request object. Extract attributes and parameters.
             */

            $startDate = $content[GeneralConstants::START_DATE];
            $qbdSyncBilling = $content[GeneralConstants::QBDSYNCBILLING];
            $qbdSyncTimeTracking = $content[GeneralConstants::QBDSYNCTT];
            $password = $content[GeneralConstants::PASS];
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            /*
             * Integration Object
             */
            $integration = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS)->find($integrationID);
            if(empty($integration)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }

            /*
             * Check if the customer has already installed the integration
             */
            $integrationToCustomers = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS_TO_CUSTOMERS)->CheckIntegration($integrationID, $customerID);
            if($integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INTEGRATION_ALREADY_PRESENT);
            }

            /*
             * Customer Object
             */
            $customer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_CUSTOMERS)->find($customerID);
            if(empty($customer)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::CUSTOMER_NOT_FOUND);
            }

            /*
             * Create new Integration
             */
            $integrationToCustomer = new Integrationstocustomers();
            $integrationToCustomer->setActive(true);
            $integrationToCustomer->setUsername('VRS'.uniqid());
            $integrationToCustomer->setQbdsyncbilling($qbdSyncBilling);
            $integrationToCustomer->setQbdsyncpayroll($qbdSyncTimeTracking);
            $integrationToCustomer->setStartdate(new \DateTime($startDate, new \DateTimeZone('UTC')));
            $integrationToCustomer->setCustomerid($customer);
            $integrationToCustomer->setIntegrationid($integration);

            // Encode the password to SHA1
            $encoder = $this->serviceContainer->get('security.password_encoder')->encodePassword($integrationToCustomer, $password);
            $integrationToCustomer->setPassword($encoder);

            $this->entityManager->persist($integrationToCustomer);
            $this->entityManager->flush();

            return $this->serviceContainer->get(GeneralConstants::RESPONSE_SERVICE)->GenericSuccessResponse();

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to create new integration due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @param $customerID
     * @return array
     */
    public function UpdateQuickbooksDesktop($content, $customerID)
    {
        try {
            $customer = null;
            /*
             * Read Request object. Extract attributes and parameters.
             */
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            // Check if the record is present or not
            $integrationToCustomer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS_TO_CUSTOMERS)->findOneBy(['customerid'=>$customerID,GeneralConstants::integrationid=>$integrationID]);

            // Create new entry
            if (!$integrationToCustomer) {
                $customer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_CUSTOMERS)->find($customerID);
                if (!$customer) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::CUSTOMER_NOT_FOUND);
                }

                $integration = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS)->find($integrationID);
                if (empty($integration)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
                }

                $integrationToCustomer = new Integrationstocustomers();

                $integrationToCustomer->setUsername('VRS' . uniqid());
                $integrationToCustomer->setCustomerid($customer);
                $integrationToCustomer->setIntegrationid($integration);
            }

            if (array_key_exists(GeneralConstants::START_DATE, $content)) {
                $integrationToCustomer->setStartdate(new \DateTime($content[GeneralConstants::START_DATE], new \DateTimeZone('UTC')));
            }

            if (array_key_exists(GeneralConstants::PASS, $content) && $content[GeneralConstants::PASS]) {
                $encoder = $this->serviceContainer->get('security.password_encoder')->encodePassword($integrationToCustomer, $content[GeneralConstants::PASS]);
                $integrationToCustomer->setPassword($encoder);
            }

            if (array_key_exists(GeneralConstants::QBDVERSION, $content)) {
                $integrationToCustomer->setVersion($content[GeneralConstants::QBDVERSION]);
            }

            if (array_key_exists(GeneralConstants::QBDSYNCBILLING, $content)) {
                $integrationToCustomer->setQbdsyncbilling($content[GeneralConstants::QBDSYNCBILLING]);
            }
            if (array_key_exists(GeneralConstants::QBDTYPE, $content)) {
                $integrationToCustomer->setType($content[GeneralConstants::QBDTYPE]);
            }

            if (array_key_exists(GeneralConstants::QBDSYNCBILLING, $content)) {
                $integrationToCustomer->setQbdsyncbilling($content[GeneralConstants::QBDSYNCBILLING]);
            }

            if (array_key_exists(GeneralConstants::QBDSYNCTT, $content)) {
                $integrationToCustomer->setQbdsyncpayroll($content[GeneralConstants::QBDSYNCTT]);
            }

            if (array_key_exists(GeneralConstants::TIMETRACKING_TYPE, $content)) {
                $type = ((int)$content[GeneralConstants::TIMETRACKING_TYPE]);
                $integrationToCustomer->setTimetrackingtype($type);
            }

            if (array_key_exists(GeneralConstants::REALMID, $content) && $content[GeneralConstants::REALMID] !== "") {
                if(!$customer) {
                    $customer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_CUSTOMERS)->find($customerID);
                }
                $tokens = new Integrationqbotokens();
                $tokens->setRealmID(preg_replace('/\s+/', '', $content[GeneralConstants::REALMID]));
                $tokens->setCustomerid($customer);
                $this->entityManager->persist($tokens);
            }

            // Set Active state to true
            $integrationToCustomer->setActive(true);


            // Persist the record in DB.
            $this->entityManager->persist($integrationToCustomer);
            $this->entityManager->flush();
            return $this->serviceContainer->get(GeneralConstants::RESPONSE_SERVICE)->GenericSuccessResponse();
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to update integration due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * On disconnection, all the mappings are removed from the tables
     * @param $customerID
     * @param $integrationID
     * @return array
     */
    public function DisconnectQBD($customerID, $content)
    {
        try {
            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PAYLOAD);
            }
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            // Remove the CustomersToProperties Mappings
//            $customersToProperties = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDCustomersToProperties FROM IntegrationQBDCustomersToProperties INNER JOIN IntegrationQBDCustomers ON IntegrationQBDCustomersToProperties.IntegrationQBDCustomerID = IntegrationQBDCustomers.IntegrationQBDCustomerID WHERE IntegrationQBDCustomers.CustomerID='.$customerID)->execute();
//            if(!$customersToProperties) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Remove the Customers Mappings
//            $customers = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDCustomers FROM IntegrationQBDCustomers WHERE IntegrationQBDCustomers.CustomerID='.$customerID)->execute();
//            if(!$customers) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Remove the Employees To Servicers Mappings
//            $employeesToServicers = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDEmployeesToServicers FROM IntegrationQBDEmployeesToServicers INNER JOIN IntegrationQBDEmployees ON IntegrationQBDEmployeesToServicers.IntegrationQBDEmployeeID = IntegrationQBDEmployees.IntegrationQBDEmployeeID WHERE IntegrationQBDEmployees.CustomerID='.$customerID)->execute();
//            if(!$employeesToServicers) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Remove the Employees Mappings
//            $employees = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDEmployees FROM IntegrationQBDEmployees WHERE IntegrationQBDEmployees.CustomerID='.$customerID)->execute();
//            if(!$employees) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Remove the Items To Services Mappings
//            $itemsToServices = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDItemsToServices FROM IntegrationQBDItemsToServices LEFT JOIN Services ON IntegrationQBDItemsToServices.ServiceID = Services.ServiceID WHERE Services.CustomerID='.$customerID)->execute();
//            if(!$itemsToServices) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Remove the Items Mappings
//            $items = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDItems FROM IntegrationQBDItems WHERE IntegrationQBDItems.CustomerID='.$customerID)->execute();
//            if(!$items) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
            $integrationqbotokens = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBOTokens FROM IntegrationQBOTokens WHERE IntegrationQBOTokens.CustomerID='.$customerID)->execute();
            if(!$integrationqbotokens) {
                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
            }

            // Remove PayrollItemwages to Customers Mapping and set active state to 0
            $integrationToCustomer = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONS_TO_CUSTOMERS)->findOneBy(['customerid'=>$customerID,GeneralConstants::integrationid=>$integrationID]);
            if(!$integrationToCustomer) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }
            $integrationToCustomer->setActive(false);
            $integrationToCustomer->setPassword(null);
            $integrationToCustomer->setQbdsyncbilling(false);
            $integrationToCustomer->setQbdsyncpayroll(false);
            $integrationToCustomer->setVersion(null);
            $integrationToCustomer->setType(null);
            $integrationToCustomer->setIntegrationqbdhourwagetypeid(null);
            $integrationToCustomer->setTimetrackingtype(null);
            $this->entityManager->persist($integrationToCustomer);
            $this->entityManager->flush();

            // Remove the Payroll Item Wages Mappings
//            $payrollItems = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDPayrollItemwages FROM IntegrationQBDPayrollItemwages WHERE IntegrationQBDPayrollItemwages.CustomerID='.$customerID)->execute();
//            if(!$payrollItems) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Delete Billing Records
//            $billingRecords = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDBillingRecords FROM IntegrationQBDBillingRecords INNER JOIN Tasks ON IntegrationQBDBillingRecords.TaskID = Tasks.TaskID INNER JOIN Properties ON Tasks.PropertyID=Properties.PropertyID WHERE Properties.CustomerID='.$customerID)->execute();
//            if(!$billingRecords) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Delete TimeTracking Records
//            $timetrackingRecords = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDTimeTrackingRecords FROM IntegrationQBDTimeTrackingRecords INNER JOIN TimeClockDays ON IntegrationQBDTimeTrackingRecords.TimeClockDaysID = TimeClockDays.TimeClockDayID INNER JOIN Servicers ON Servicers.ServicerID=Servicers.ServicerID WHERE Servicers.CustomerID='.$customerID)->execute();
//            if(!$timetrackingRecords) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            $timetrackingRecords = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDTimeTrackingRecords FROM IntegrationQBDTimeTrackingRecords INNER JOIN TimeClockTasks ON IntegrationQBDTimeTrackingRecords.TimeClockTasksID = TimeClockTasks.TimeClockTaskID INNER JOIN Servicers ON Servicers.ServicerID=Servicers.ServicerID WHERE Servicers.CustomerID='.$customerID)->execute();
//            if(!$timetrackingRecords) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            $timetrackingRecords = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBDTimeTrackingRecords FROM IntegrationQBDTimeTrackingRecords INNER JOIN TimeClockTasks ON IntegrationQBDTimeTrackingRecords.DriveTimeClockTaskID = TimeClockTasks.TimeClockTaskID INNER JOIN Servicers ON Servicers.ServicerID=Servicers.ServicerID WHERE Servicers.CustomerID='.$customerID)->execute();
//            if(!$timetrackingRecords) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }
//
//            // Delete Batch Table
//            $batch = $this->getEntityManager()->getConnection()->prepare('DELETE IntegrationQBBatches FROM IntegrationQBBatches INNER JOIN IntegrationsToCustomers ON IntegrationsToCustomers.IntegrationToCustomerID=IntegrationQBBatches.IntegrationToCustomerID WHERE IntegrationsToCustomers.CustomerID='.$customerID.' AND IntegrationsToCustomers.IntegrationID='.$integrationID)->execute();
//            if(!$batch) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::UNABLE_TO_DELETE);
//            }

            return $this->serviceContainer->get(GeneralConstants::RESPONSE_SERVICE)->GenericSuccessResponse();
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to disconnect integration due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}