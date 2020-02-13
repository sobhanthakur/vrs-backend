<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 13/2/20
 * Time: 10:57 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use QuickBooksOnline\API\DataService\DataService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class QuickbooksOnlineAuthentication extends BaseService
{
    public function QBOAuthentication($authenticationResult, $quickbooksConfig, $request)
    {
        try {
            $customerID = $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID];

            // Configure Data Service
            $dataService = DataService::Configure(array(
                'auth_mode' => $quickbooksConfig['AuthMode'],
                'ClientID' => $quickbooksConfig['ClientID'],
                'ClientSecret' => $quickbooksConfig['ClientSecret'],
                'RedirectURI' => $quickbooksConfig['RedirectURI'],
                'scope' => $quickbooksConfig['Scope'],
                'baseUrl' => $quickbooksConfig['BaseURL']
            ));
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $url = $request->server->get('QUERY_STRING');
            parse_str($url,$qsArray);
            $parseUrl = array('code' => $qsArray['code'],
                'realmId' => $qsArray['realmId']
            );

            $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
            print_r($accessToken);die();

            // Store the access tokens in the DB
            $integrationsToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->findOneBy(array('customerid'=>$customerID));
            if(!$integrationsToCustomers) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }
            print_r($integrationsToCustomers->getIntegrationtocustomerid());
            die();

            // Update the OAuth2Token
            $dataService->updateOAuth2Token($accessToken);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

}