<?php
/**
 *  AuthenticateAuthorize Service to handle Authentication and Authorization
 *  Related tasks.
 *
 * @category Service
 * @author Sobhan Thakur
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Repository\ServicersRepository;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\Servicers;
use AppBundle\Entity\Regiongroups;
use AppBundle\Entity\Propertygroups;


/**
 * Class AuthenticationService
 * @package AppBundle\Service
 */
class AuthenticationService extends BaseService
{
    /**
     * @param Request $request
     * @return mixed
     * This method validates the Authorization token and checks its validity
     */
    public function VerifyAuthToken(Request $request)
    {
        $authenticateResult['status'] = false;
        $restrictions = [];
        try {
            // Checking Authorization Key for validating Token.
            $authorizationParts = explode(" ", $request->headers->get('Authorization'));

            if (
                count($authorizationParts) !== 2 || 'VRS' !== $authorizationParts[0]
                || empty(trim($authorizationParts[1]))
            ) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_CONTENT);
            }

            // Parsing String Token to JWT Token Object.
            $token = (new Parser())->parse((string)$authorizationParts[1]);
            $signer = new Sha256();

            // Checking If Token passed in API Request Header is valid OR not.
            if (!$token->verify($signer, $this->serviceContainer->getParameter('api_secret'))) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
            }

            if(!$this->CheckExpiry($token)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::UNPROCESSABLE_AUTH_TOKEN);
            }
            // Checking That access_token must be used in API Calls.
            if (!$token->hasClaim('CustomerID') || !$token->hasClaim('LoggedInStaffID')
                || !$token->hasClaim('CustomerName') || !$token->hasClaim('CreateDateTime')
            ) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
            }

            // Set token claims in authenticateResult array
            $authenticateResult['message']['CustomerID'] = $token->getClaim('CustomerID');
            $authenticateResult['message']['CustomerName'] = $token->getClaim('CustomerName');
            $authenticateResult['message']['LoggedInStaffID'] = $token->getClaim('LoggedInStaffID');

            //Set authenticated status to true
            $authenticateResult['status'] = true;
        } catch (InvalidArgumentException $ex) {
            throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Authentication could not be complete due to Error : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $authenticateResult;
    }

    /**
     * This method checks all the restrictions/permissions allowed for the loggedIn Customer
     * @param $authenticationResult
     * @return array
     * Checks the restrictions for navigation access.
     */
    public function ValidateRestrictions($authenticationResult)
    {
        $restrictions = [];
        try {
            if ($authenticationResult['status']) {
                $restrictions['LoggedInStaffID'] = $authenticationResult['message']['LoggedInStaffID'];
                $restrictions['Restrictions'] = null;
                if ($authenticationResult['message']['LoggedInStaffID'] !== 0) {

                    /*
                     * Check restrictions from the Servicers Table.
                     */
                    $servicersRepo = $this->entityManager->getRepository('AppBundle:Servicers');
                    $servicersResponse = $servicersRepo->GetRestrictions($authenticationResult['message']['LoggedInStaffID']);
                    if(empty($servicersResponse)) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::SERVICER_NOT_FOUND);
                    }

                    $servicersResponse = $servicersResponse[0]; // The query returns an array

                    // Set Servicers Responses in the restrictions array.
                    $restrictions['Restrictions']['AllowAdminAccess'] = ($servicersResponse['allowadminaccess'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowManage'] = ($servicersResponse['allowmanage'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowReports'] = ($servicersResponse['allowreports'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowSetupAccess'] = ($servicersResponse['allowsetupaccess'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowAccountAccess'] = ($servicersResponse['allowaccountaccess'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowIssuesAccess'] = ($servicersResponse['allowissuesaccess'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowQuickReports'] = ($servicersResponse['allowquickreports'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowScheduleAccess'] = ($servicersResponse['allowscheduleaccess'] === true ? 1 : 0);
                    $restrictions['Restrictions']['AllowMasterCalendar'] = ($servicersResponse['allowmastercalendar'] === true ? 1 : 0);

                    /*
                     * Check if Servicer is active and time tracking
                     */
                    $servicersTimeTracking = $servicersRepo->GetTimeTrackingRestrictions($authenticationResult['message']['CustomerID']);
                    if(empty($servicersTimeTracking)) {
                        $restrictions['Restrictions']['TimeTracking'] = 0;
                    } else {
                        $restrictions['Restrictions']['TimeTracking'] = 1;
                    }

                    /*
                     * Check if region Group is present or not
                     */
                    $regionGroupRepo = $this->entityManager->getRepository('AppBundle:Regiongroups');
                    $regionGroupResponse = $regionGroupRepo->GetRegionGroupsRestrictions($authenticationResult['message']['CustomerID']);
                    if(empty($regionGroupResponse)) {
                        $restrictions['Restrictions']['RegionGroup'] = 0;
                    } else {
                        $restrictions['Restrictions']['RegionGroup'] = 1;
                    }

                    /*
                     * Check if property Group is present or not
                     */
                    $propertyGroupRepo = $this->entityManager->getRepository('AppBundle:Propertygroups');
                    $propertyGroupResponse = $propertyGroupRepo->GetPropertyGroupsRestrictions($authenticationResult['message']['CustomerID']);
                    if(empty($propertyGroupResponse)) {
                        $restrictions['Restrictions']['PropertyGroup'] = 0;
                    } else {
                        $restrictions['Restrictions']['PropertyGroup'] = 1;
                    }

                    /*
                     * Check if Customer is piece pay and ICalAddOn
                     */
                    $customerRepo = $this->entityManager->getRepository('AppBundle:Customers');
                    $customerResponse = $customerRepo->PiecePayRestrictions($authenticationResult['message']['CustomerID']);
                    if(empty($customerResponse)) {
                        $restrictions['Restrictions']['PiecePay'] = 0;
                        $restrictions['Restrictions']['ICalAddOn'] = 0;
                    } else {
                        $restrictions['Restrictions']['PiecePay'] = ($customerResponse[0]['piecepay'] === true ? 1 : 0);
                        $restrictions['Restrictions']['ICalAddOn'] = ($customerResponse[0]['icaladdon'] === true ? 1 : 0);
                    }
                }
            }
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Authentication could not be complete due to Error : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $restrictions;
    }

    /**
     * @param $token
     * @return bool
     * Checks the expiration time of token
     */
    Public function CheckExpiry($token)
    {
        try {
            // Get headers and claims from the tokens
            $exp = $token->getHeader('exp');
            $createTime = $token->getClaim('CreateDateTime');
            $loggedInStaffID = $token->getClaim('LoggedInStaffID');
            $customerID = $token->getClaim('CustomerID');
            $customerName = $token->getClaim('CustomerName');

            $tokenTime = new \DateTime($createTime, new \DateTimeZone("UTC"));
            $currentTime = new \DateTime("now", new \DateTimeZone("UTC"));

            // Check if the token is expired
            if ($currentTime->getTimestamp() - $tokenTime->getTimestamp() > $exp) {
                throw new UnauthorizedHttpException(null, ErrorConstants::TOKEN_EXPIRED);
            }
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Authentication could not be complete due to Error : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return true;
    }

    /**
     * @param $authenticationResult
     * @return string
     * This method creates a new token
     */
    public function CreateNewToken($authenticationResult)
    {
        $accessToken = null;
        $signer = new Sha256();
        $token = (new Builder())
            ->set('CustomerID', $authenticationResult['message']['CustomerID'])
            ->set('LoggedInStaffID', $authenticationResult['message']['LoggedInStaffID'])
            ->set('CustomerName', $authenticationResult['message']['CustomerName'])
            ->set('CreateDateTime', (new \DateTime("now", new \DateTimeZone("UTC")))->format('YmdHi'))
            ->setHeader('exp',GeneralConstants::TOKEN_EXPIRY_TIME)

            // Creating Signature.
            ->sign($signer, $this->serviceContainer->getParameter('api_secret'))
            ->getToken() // Retrieves Generated Token Object
            ->__toString(); // Converts Token into encoded String.
        return $token;
    }
}