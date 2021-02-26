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
use AppBundle\Entity\Integrationqbotokens;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class QuickbooksOnlineAuthentication
 * @package AppBundle\Service
 */
class QuickbooksOnlineAuthentication extends BaseService
{
    /**
     * @param $quickbooksConfig
     * @param $request
     * @return bool
     */
    public function QBOAuthentication($quickbooksConfig, $request)
    {
        try {
            // Configure Data Service
            $dataService = $this->Configure($quickbooksConfig);
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $url = $request->server->get('QUERY_STRING');
            parse_str($url,$qsArray);
            $parseUrl = array('code' => $qsArray['code'],
                'realmId' => $qsArray['realmId']
            );

            $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);

            // Store the access tokens in the DB
            $integrationsqboTokens = $this->entityManager->getRepository('AppBundle:Integrationqbotokens')->findOneBy(array('realmID'=>$accessToken->getRealmID()));
            if(!$integrationsqboTokens) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            $integrationsqboTokens->setAccessToken($accessToken->getAccessToken());
            $integrationsqboTokens->setRefreshToken($accessToken->getRefreshToken());

            $this->entityManager->persist($integrationsqboTokens);
            $this->entityManager->flush();

            // Update the OAuth2Token
            $dataService->updateOAuth2Token($accessToken);
            return true;
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

    /**
     * @param $quickbooksConfig
     * @return DataService
     * @throws \QuickBooksOnline\API\Exception\SdkException
     */
    public function Configure($quickbooksConfig)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => $quickbooksConfig['AuthMode'],
            GeneralConstants::CLIENTID => $quickbooksConfig[GeneralConstants::CLIENTID],
            GeneralConstants::CLIENTSECRET => $quickbooksConfig[GeneralConstants::CLIENTSECRET],
            'RedirectURI' => $quickbooksConfig['RedirectURI'],
            'scope' => $quickbooksConfig['Scope'],
            'baseUrl' => $quickbooksConfig['BaseURL']
        ));

        $dataService->throwExceptionOnError(true);
        return $dataService;
    }

    /**
     * @param Integrationqbotokens $integrationQBOTokens
     * @return DataService
     * @throws \QuickBooksOnline\API\Exception\SdkException
     */
    public function Authenticate($integrationQBOTokens, $quickbooksConfig)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => $quickbooksConfig['AuthMode'],
            GeneralConstants::CLIENTID => $quickbooksConfig[GeneralConstants::CLIENTID],
            GeneralConstants::CLIENTSECRET => $quickbooksConfig[GeneralConstants::CLIENTSECRET],
            'accessTokenKey' => $integrationQBOTokens->getAccessToken(),
            'refreshTokenKey' => $integrationQBOTokens->getRefreshToken(),
            'QBORealmID' => $integrationQBOTokens->getRealmID(),
            'baseUrl' => $quickbooksConfig['BaseURL']
        ));
        $dataService->throwExceptionOnError(true);
        return $dataService;
    }

    /**
     * @param DataService $dataService
     * @param Integrationqbotokens $integrationQBOTokens
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws ServiceException
     * @throws SdkException
     */
    public function RefreshAccessToken($dataService, $integrationQBOTokens)
    {
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $refreshToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($integrationQBOTokens->getRefreshToken());

        // Find entry with RealmID
        $tokenRepo = $this->entityManager->getRepository('AppBundle:Integrationqbotokens')->findOneBy(array('realmID' => $refreshToken->getRealmID()));
        if (!$tokenRepo) {
            throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
        }
        $tokenRepo->setRefreshToken($refreshToken->getRefreshToken());
        $tokenRepo->setAccessToken($refreshToken->getAccessToken());

        $this->entityManager->persist($tokenRepo);
        $this->entityManager->flush();

        return true;
    }

}