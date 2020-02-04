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
        } catch (\InvalidArgumentException $ex) {
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
        $managersToProperties = null;
        $properties = null;
        $regions = null;
        try {
            if ($authenticationResult[GeneralConstants::STATUS]) {
                $restrictions[GeneralConstants::LOGGEDINSTAFFID] = $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID];
                $restrictions[GeneralConstants::RESTRICTIONS] = null;

                $customerRepo = $this->entityManager->getRepository('AppBundle:Customers');
                $customerID = $customerRepo->TestValidCustomer($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                if (empty($customerID)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::CUSTOMER_NOT_FOUND);
                }

                // Set Username
                $restrictions[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_NAME] = $authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_NAME];
                $restrictions[GeneralConstants::LOGGED_IN_SERVICER_PASSWORD] = null;

                // Region Groups and Regions Repository
                $regionGroupRepo = $this->entityManager->getRepository('AppBundle:Regiongroups');
                $regionsRepo = $this->entityManager->getRepository('AppBundle:Regions');

                /*
                 * Check restrictions from the Servicers Table.
                 */
                $servicersRepo = $this->entityManager->getRepository('AppBundle:Servicers');
                if($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID] === 0) {
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowAdminAccess'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowManage'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowReports'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowSetupAccess'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowAccountAccess'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowIssuesAccess'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowQuickReports'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowScheduleAccess'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowMasterCalendar'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowTracking'] = 1;
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowEditBookings'] = 1;

                    // Region Group Restrictions
                    $regions = $regionsRepo->GetRegionIDLoggedInStaffID0($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                    $regionGroupResponse = $regionGroupRepo->GetRegionGroupsRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID],$regions);
                    if(!empty($regionGroupResponse)) {
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroup'] = 1;
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroupDetails'] = $regionGroupResponse;
                    } else {
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroup'] = 0;
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroupDetails'] = null;
                    }
                } else {
                    $servicersResponse = $servicersRepo->GetRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID]);
                    if (empty($servicersResponse)) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::SERVICER_NOT_FOUND);
                    }

                    $servicersResponse = $servicersResponse[0]; // The query returns an array

                    // Set Username and password
                    $restrictions[GeneralConstants::LOGGED_IN_SERVICER_PASSWORD] = trim($servicersResponse['password2']);
                    $restrictions[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_NAME] = $servicersResponse['name'];

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
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowTracking'] = ($servicersResponse['allowtracking'] === true ? 1 : 0);
                    $restrictions[GeneralConstants::RESTRICTIONS]['AllowEditBookings'] = ($servicersResponse['alloweditbookings'] === true ? 1 : 0);

                    // Fetch Property ID from ManagersToProperties Table.
                    $managersToPropertiesRepo = $this->entityManager->getRepository('AppBundle:Managerstoproperties');
                    $managersToProperties = $managersToPropertiesRepo->GetPropertyID($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::LOGGEDINSTAFFID]);

                    // Fetch RegionID from properties
                    $propertiesRepo = $this->entityManager->getRepository('AppBundle:Properties');
                    $properties = $propertiesRepo->GetRegionByID($managersToProperties);

                    // Fetch RegionID from Regions
                    $regions = $regionsRepo->GetRegionID($properties,$authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);

                    // Region Group Restrictions
                    $regionGroupResponse = $regionGroupRepo->GetRegionGroupsRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID],$regions);
                    if(!empty($regionGroupResponse)) {
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroup'] = 1;
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroupDetails'] = $regionGroupResponse;
                    } else {
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroup'] = 0;
                        $restrictions[GeneralConstants::RESTRICTIONS]['RegionGroupDetails'] = null;
                    }
                }

                /*
                 * Check if Servicer is active and time tracking
                 */
                $servicersTimeTracking = $servicersRepo->GetTimeTrackingRestrictions($authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                $restrictions[GeneralConstants::RESTRICTIONS]['TimeTracking'] = $servicersTimeTracking[0]['timetracking'] === true ? 1 : 0;


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
        } catch (HttpException $exception) {
            throw $exception;
        }  catch (\Exception $exception) {
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

    /**
     * @param $content
     * @return array
     */
    public function PWAAutheticate($content)
    {
        try {
            $servicerID = $content[GeneralConstants::SERVICERID];
            $password = $content[GeneralConstants::PASS];

            // Check Servicer table to validate the servicerID and password
            $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->ValidateAuthentication($servicerID,$password);

            if(empty($servicer)) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTHENTICATION_BODY);
            }

            // Create a new token
            $signer = new Sha256();
            $accessToken = (new Builder())
                ->set(GeneralConstants::SERVICERID, $servicerID)
                ->set(GeneralConstants::CREATEDATETIME, (new \DateTime("now", new \DateTimeZone("UTC")))->format('YmdHi'))
                ->setHeader('exp',GeneralConstants::TOKEN_EXPIRY_TIME)

                // Creating Signature.
                ->sign($signer, $this->serviceContainer->getParameter('api_secret'))
                ->getToken() // Retrieves Generated Token Object
                ->__toString(); // Converts Token into encoded String.

            // Return response
            return array(
                "AccessToken" => $accessToken,
                GeneralConstants::SERVICERID => $servicer[0][GeneralConstants::SERVICERID],
                GeneralConstants::SERVICERNAME => $servicer[0][GeneralConstants::SERVICERNAME],
                GeneralConstants::TIMETRACKING => ($servicer[0][GeneralConstants::TIMETRACKING] ? 1 : 0),
                GeneralConstants::MILEAGE => ($servicer[0][GeneralConstants::MILEAGE] ? 1 : 0),
                GeneralConstants::STARTEARLY => ($servicer[0][GeneralConstants::STARTEARLY] ? 1 : 0),
                GeneralConstants::CHANGEDATE => ($servicer[0][GeneralConstants::CHANGEDATE] ? 1 : 0)

            );
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}