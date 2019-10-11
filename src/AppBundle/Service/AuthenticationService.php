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
        $authenticateResult[GeneralConstants::STATUS] = false;
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
            if (!$token->hasClaim(GeneralConstants::CUSTOMER_ID) || !$token->hasClaim(GeneralConstants::LOGGEDINSTAFFID)
                || !$token->hasClaim(GeneralConstants::CUSTOMER_NAME) || !$token->hasClaim(GeneralConstants::CREATEDATETIME)
            ) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
            }

            // Set token claims in authenticateResult array
            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID] = $token->getClaim(GeneralConstants::CUSTOMER_ID);
            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_NAME] = $token->getClaim(GeneralConstants::CUSTOMER_NAME);
            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID] = $token->getClaim(GeneralConstants::LOGGEDINSTAFFID);

            //Set authenticated status to true
            $authenticateResult[GeneralConstants::STATUS] = true;
        } catch (InvalidArgumentException $ex) {
            throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
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
            if ($authenticationResult[GeneralConstants::STATUS]) {
                $restrictions[GeneralConstants::LOGGEDINSTAFFID] = $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID];
                $restrictions[GeneralConstants::RESTRICTIONS] = null;

                /*
                 * Check restrictions from the Servicers Table.
                 */
                $servicersRepo = $this->entityManager->getRepository('AppBundle:Servicers');
                $servicersResponse = $servicersRepo->GetRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID]);
                if (empty($servicersResponse)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::SERVICER_NOT_FOUND);
                }

                $servicersResponse = $servicersResponse[0]; // The query returns an array

                // Set Servicers Responses in the restrictions array.
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowAdminAccess'] = ($servicersResponse['allowadminaccess'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowManage'] = ($servicersResponse['allowmanage'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowReports'] = ($servicersResponse['allowreports'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowSetupAccess'] = ($servicersResponse['allowsetupaccess'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowAccountAccess'] = ($servicersResponse['allowaccountaccess'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowIssuesAccess'] = ($servicersResponse['allowissuesaccess'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowQuickReports'] = ($servicersResponse['allowquickreports'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowScheduleAccess'] = ($servicersResponse['allowscheduleaccess'] === true ? 1 : 0);
                $restrictions[GeneralConstants::RESTRICTIONS]['AllowMasterCalendar'] = ($servicersResponse['allowmastercalendar'] === true ? 1 : 0);

                /*
                 * Check if Servicer is active and time tracking
                 */
                $servicersTimeTracking = $servicersRepo->GetTimeTrackingRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                if (empty($servicersTimeTracking)) {
                    $restrictions[GeneralConstants::RESTRICTIONS]['TimeTracking'] = 0;
                } else {
                    $restrictions[GeneralConstants::RESTRICTIONS]['TimeTracking'] = 1;
                }

                /*
                 * Check if region Group is present or not
                 */
                $regionGroupRepo = $this->entityManager->getRepository('AppBundle:Regiongroups');
                $regionGroupResponse = $regionGroupRepo->GetRegionGroupsRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                if (empty($regionGroupResponse)) {
                    $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroup'] = 0;
                    $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroupDetails'] = null;
                } else {
                    $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroup'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroupDetails'] = $regionGroupResponse;
                }

                /*
                 * Check if property Group is present or not
                 */
                $propertyGroupRepo = $this->entityManager->getRepository('AppBundle:Propertygroups');
                $propertyGroupResponse = $propertyGroupRepo->GetPropertyGroupsRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                if (empty($propertyGroupResponse)) {
                    $restrictions[GeneralConstants::RESTRICTIONS]['PropertyGroup'] = 0;
                    $restrictions[GeneralConstants::RESTRICTIONS]['PropertyGroupDetails'] = null;
                } else {
                    $restrictions[GeneralConstants::RESTRICTIONS]['PropertyGroup'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['PropertyGroupDetails'] = $propertyGroupResponse;
                }

                /*
                 * Check if Customer is piece pay and ICalAddOn
                 */
                $customerRepo = $this->entityManager->getRepository('AppBundle:Customers');
                $customerResponse = $customerRepo->PiecePayRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                if (empty($customerResponse)) {
                    $restrictions[GeneralConstants::RESTRICTIONS]['PiecePay'] = 0;
                    $restrictions[GeneralConstants::RESTRICTIONS]['ICalAddOn'] = 0;
                } else {
                    $restrictions[GeneralConstants::RESTRICTIONS]['PiecePay'] = ($customerResponse[0]['piecepay'] === true ? 1 : 0);
                    $restrictions[GeneralConstants::RESTRICTIONS]['ICalAddOn'] = ($customerResponse[0]['icaladdon'] === true ? 1 : 0);
                }
            }
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
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
            $createTime = $token->getClaim(GeneralConstants::CREATEDATETIME);

            $tokenTime = new \DateTime($createTime, new \DateTimeZone("UTC"));
            $currentTime = new \DateTime("now", new \DateTimeZone("UTC"));

            // Check if the token is expired
            if ($currentTime->getTimestamp() - $tokenTime->getTimestamp() > $exp) {
                throw new UnauthorizedHttpException(null, ErrorConstants::TOKEN_EXPIRED);
            }
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
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
        $signer = new Sha256();
        return (new Builder())
            ->set(GeneralConstants::CUSTOMER_ID, $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID])
            ->set(GeneralConstants::LOGGEDINSTAFFID, $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID])
            ->set(GeneralConstants::CUSTOMER_NAME, $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_NAME])
            ->set(GeneralConstants::CREATEDATETIME, (new \DateTime("now", new \DateTimeZone("UTC")))->format('YmdHi'))
            ->setHeader('exp',GeneralConstants::TOKEN_EXPIRY_TIME)

            // Creating Signature.
            ->sign($signer, $this->serviceContainer->getParameter('api_secret'))
            ->getToken() // Retrieves Generated Token Object
            ->__toString(); // Converts Token into encoded String.
    }
}