<?php
/**
 * GetIntegrations service to get the list of available + installed integrations
 * @category Service
 * @author Sobhan Thakur
 */

namespace AppBundle\Service;
use AppBundle\Constants\ErrorConstants;

/**
 * Class GetIntegrations
 * @package AppBundle\Service
 */
class GetIntegrations extends BaseService
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
}