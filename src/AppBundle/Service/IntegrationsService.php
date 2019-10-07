<?php
/**
 * IntegrationsService service to get the list of available + installed integrations
 * @category Service
 * @author Sobhan Thakur
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;
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
            $integrationsToCustomers = $integrationsToCustomers->GetAllIntegrations($authenticationResult['message']['CustomerID']);

            for ($i=0; $i<sizeof($integrations); $i++) {
                $installedObject = $this->InstalledIntegration($integrations[$i]->getIntegrationid(), $integrationsToCustomers);

                // Create Response Object
                $integrationDetails = null;
                $installedStatus = 0;
                if($installedObject) {
                    $installedStatus = ($installedObject['active'] === true ? 1 : 0);
                    $integrationDetails = array(
                        'QBDSyncBilling' => $installedObject['qbdsyncbilling'],
                        'QBDSyncTimeTracking' => $installedObject['qbdsyncpayroll'],
                        'CreateDate' => $installedObject['createdate'],
                        'StartDate' => $installedObject['startdate']
                    );
                }
                $integrationResponse[$i] = array(
                    'IntegrationID' => $integrations[$i]->getIntegrationid(),
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
     * @param Request $request
     * @return array
     */
    public function InstallQuickbooksDesktop(Request $request)
    {
        try {
            /*
             * Read Request object. Extract attrbutes and parameters.
             */
            $authenticationResult = $request->attributes->get('AuthPayload');
            $startDate = $request->get('StartDate');
            $qbdSyncBilling = $request->get('QBDSyncBilling');
            $qbdSyncTimeTracking = $request->get('QBDSyncTimeTracking');
            $password = $request->get('Password');
            $customerID = $authenticationResult['message']['CustomerID'];
            $integrationID = $request->get('IntegrationID');

            /*
             * Integration Object
             */
            $integration = $this->entityManager->getRepository('AppBundle:Integrations')->find($integrationID);
            if(empty($integration)) {
                throw new HttpException(404, '');
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
                throw new HttpException(404, '');
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

        } catch (HttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Unable to create new integration due To : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}