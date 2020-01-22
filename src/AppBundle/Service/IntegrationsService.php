<?php
/**
 * IntegrationsService service to get the list of available + installed integrations
 * @category Service
 * @author Sobhan Thakur
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationstocustomers;
use Symfony\Component\HttpFoundation\Request;
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
            $customerID = $authenticationResult['message']['CustomerID'];

            // Fetch All Integrations available
            $integrations = $this->entityManager->getRepository('AppBundle:Integrations')->findBy(array('active'=>1));

            // Get All Integrations based on Customer ID
            $integrationsToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers');
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
                        'CreateDate' => $installedObject['createdate'],
                        'StartDate' => $installedObject['startdate']
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
            if ($val['integrationid'] == $integrationId) {
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
            $integration = $this->entityManager->getRepository('AppBundle:Integrations')->find($integrationID);
            if(empty($integration)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }

            /*
             * Check if the customer has already installed the integration
             */
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->CheckIntegration($integrationID, $customerID);
            if($integrationToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INTEGRATION_ALREADY_PRESENT);
            }

            /*
             * Customer Object
             */
            $customer = $this->entityManager->getRepository('AppBundle:Customers')->find($customerID);
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

            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();

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
            /*
             * Read Request object. Extract attributes and parameters.
             */
            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            // Check if the record is present or not
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(['customerid'=>$customerID,'integrationid'=>$integrationID]);

            // Create new entry
            if(!$integrationToCustomer) {
                $customer = $this->entityManager->getRepository('AppBundle:Customers')->find($customerID);
                if(!$customer) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::CUSTOMER_NOT_FOUND);
                }

                $integration = $this->entityManager->getRepository('AppBundle:Integrations')->find($integrationID);
                if(empty($integration)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
                }

                $integrationToCustomer = new Integrationstocustomers();
                $integrationToCustomer->setActive(true);
                $integrationToCustomer->setUsername('VRS'.uniqid());
                $integrationToCustomer->setQbdsyncbilling($content[GeneralConstants::QBDSYNCBILLING]);
                $integrationToCustomer->setVersion($content[GeneralConstants::QBDVERSION]);
                $integrationToCustomer->setQbdsyncpayroll($content[GeneralConstants::QBDSYNCTT]);
                $integrationToCustomer->setStartdate(new \DateTime($content[GeneralConstants::START_DATE], new \DateTimeZone('UTC')));
                $integrationToCustomer->setCustomerid($customer);
                $integrationToCustomer->setIntegrationid($integration);

                // Encode the password to SHA1
                $encoder = $this->serviceContainer->get('security.password_encoder')->encodePassword($integrationToCustomer, $content[GeneralConstants::PASS]);
                $integrationToCustomer->setPassword($encoder);

            } else {
                if(array_key_exists(GeneralConstants::START_DATE,$content)) {
                    $integrationToCustomer->setStartdate(new \DateTime($content[GeneralConstants::START_DATE], new \DateTimeZone('UTC')));
                }

                if(array_key_exists(GeneralConstants::PASS,$content)) {
                    $encoder = $this->serviceContainer->get('security.password_encoder')->encodePassword($integrationToCustomer, $content[GeneralConstants::PASS]);
                    $integrationToCustomer->setPassword($encoder);
                }

                if(array_key_exists(GeneralConstants::QBDVERSION,$content)) {
                    $integrationToCustomer->setVersion($content[GeneralConstants::QBDVERSION]);
                }

                if(array_key_exists(GeneralConstants::QBDSYNCBILLING,$content)) {
                    $integrationToCustomer->setQbdsyncbilling($content[GeneralConstants::QBDSYNCBILLING]);
                }

                if(array_key_exists(GeneralConstants::QBDSYNCTT,$content)) {
                    $integrationToCustomer->setQbdsyncpayroll($content[GeneralConstants::QBDSYNCTT]);
                }
                $integrationToCustomer->setActive(true);
            }

            // Persist the record in DB.
            $this->entityManager->persist($integrationToCustomer);
            $this->entityManager->flush();
            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to update integration due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
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
            $integrationToCustomer = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(['customerid'=>$customerID,'integrationid'=>$integrationID]);
            if(!$integrationToCustomer) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATION);
            }
            $integrationToCustomer->setActive(false);
            $this->entityManager->persist($integrationToCustomer);
            $this->entityManager->flush();
            return $this->serviceContainer->get('vrscheduler.api_response_service')->GenericSuccessResponse();
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to disconnect integration due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}