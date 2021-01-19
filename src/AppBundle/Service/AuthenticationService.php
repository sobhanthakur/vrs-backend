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
use AppBundle\Constants\LocaleConstants;
use AppBundle\CustomClasses\TimeZoneConverter;
use AppBundle\DatabaseViews\TimeClockDays;
use AppBundle\Entity\Integrationstocustomers;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;


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
    public function VerifyAuthToken(Request $request, $restrictCustomer=null)
    {
        $authenticateResult[GeneralConstants::STATUS] = false;
        try {
            // Checking Authorization Key for validating Token.
            $authorizationParts = explode(" ", $request->headers->get(GeneralConstants::AUTHORIZATION));

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
            $customerIDToken = $token->getClaim(GeneralConstants::CUSTOMER_ID);

            // Check if restrict customer variable is set
            // And throw exception if customerid is not 70
            if ($restrictCustomer && $customerIDToken !== 70) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
            }

            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID] = $customerIDToken;
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

                // Set Locale ID
                $restrictions[GeneralConstants::LOCALEID] = $customerID[0][GeneralConstants::LOCALEID];

                // Set Region
                $restrictions[GeneralConstants::REGION] = $customerID[0][GeneralConstants::REGION];

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

                // Manage Menu Conditions
                $restrictions[GeneralConstants::RESTRICTIONS]['UseQuickbooks'] = (int)$customerID[0]['UseQuickbooks'];
                $restrictions[GeneralConstants::RESTRICTIONS]['ConnectedStripeAccountID'] = trim((string)$customerID[0]['ConnectedStripeAccountID']) !== '' ? 1 : 0;

                // Setup Menu Condition
                $integrationCompanyID = $this->serviceContainer->get('doctrine.orm.integrations_entity_manager');
                $integrationCompanyID = $integrationCompanyID->getConnection()->prepare('select IntegrationCompanyID from CustomerIntegrations where CustomerID='.$authenticationResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID]);
                $integrationCompanyID->execute();
                $integrationCompanyID = $integrationCompanyID->fetchAll();
                $companyID = 0;
                foreach ($integrationCompanyID as $item) {
                    if ((int)$item['IntegrationCompanyID'] === 12) {
                        $companyID = 1;
                        break;
                    }
                }
                $restrictions[GeneralConstants::RESTRICTIONS]['IntegrationCompanyID'] = $companyID;

                $restrictions[GeneralConstants::RESTRICTIONS]['TrackLaborOrMaterials'] = (int)$customerID[0]['TrackLaborOrMaterials'];

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
            $clockedIn = null;
            $timeZone = null;
            $timeClockResponse = null;
            $timeTaskResponse = null;

            // Check Servicer table to validate the servicerID and password
            $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->ValidateAuthentication($servicerID,$password);

            if(empty($servicer)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::WRONG_PASSWORD);
            }

            // Set TimeZone
            $timeZone = new \DateTimeZone($servicer[0]['Region']);

            // TimeTracking Information from time clock tasks and time clock days
            $timeClockTasks = $this->entityManager->getRepository('AppBundle:Timeclocktasks')->CheckOtherStartedTasks($servicerID,$servicer[0]['Region']);

            // Check If Any TimeClockDay is present for current day
            $timeClockDays = $this->entityManager->getRepository('AppBundle:Timeclockdays')->CheckTimeClockForCurrentDay($servicerID,$timeZone);
            
            if(!empty($timeClockDays)) {
                $clockedIn = new \DateTime($timeClockDays[0]['ClockIn']);
                $clockedIn->setTimezone($timeZone);
                $clockedIn = $clockedIn->format('h:i A');
            }


            $servicer[0]['TimeClockDays'] = !empty($timeClockDays) ? 1 : 0;
            $servicer[0]['TimeClockTasks'] = !empty($timeClockTasks) ? 1 : 0;
            $servicer[0]['ClockedIn'] = $clockedIn;
            $servicer[0]['TimeClockTasks'] = !empty($timeClockTasks) ? 1 : 0;
            $servicer[0]['Phone'] = trim($servicer[0]['Phone']);
            $servicer[0]['AllowCreateCompletedTask'] = $servicer[0]['AllowCreateCompletedTask'] ? 1 : 0;
            $servicer[0]['AllowAdminAccess'] = $servicer[0]['AllowAdminAccess'] ? 1 : 0;
            $servicer[0]['ActiveForDates'] = $servicer[0]['ActiveForDates'] ? 1 : 0;
            $servicer[0]['ActiveForLanguages'] = $servicer[0]['ActiveForLanguages'] ? 1 : 0;
            $servicer[0]['TimeTrackingGPS'] = (int)$servicer[0]['TimeTrackingGPS'];

            /*
             * Locale formats
             */
            $localFormat = [];
            $timeType = \IntlDateFormatter::NONE;

            // Long Format
            $dateType = \IntlDateFormatter::LONG;
            $formatter = new \IntlDateFormatter($servicer[0]['LocaleID'], $dateType,$timeType);
            $localFormat['Long'] = $formatter->getPattern();


            // Short Format
            $dateType = \IntlDateFormatter::SHORT;
            $formatter = new \IntlDateFormatter($servicer[0]['LocaleID'], $dateType,$timeType);
            $localFormat['Short'] = $formatter->getPattern();

            // Full format
            $dateType = \IntlDateFormatter::FULL;
            $formatter = new \IntlDateFormatter($servicer[0]['LocaleID'], $dateType,$timeType);
            $localFormat['Full'] = $formatter->getPattern();

            // Medium Format
            $dateType = \IntlDateFormatter::MEDIUM;
            $formatter = new \IntlDateFormatter($servicer[0]['LocaleID'], $dateType,$timeType);
            $localFormat['Medium'] = $formatter->getPattern();


//            $servicer[0]['Locale'] = $this->serviceContainer->getParameter('locale_mapping')[$servicer[0]['TranslationLocaleID']];
            $servicer[0]['LocaleFormat'] = $localFormat;

            // Create a new token
            $signer = new Sha256();
            $accessToken = (new Builder())
                ->set(GeneralConstants::SERVICERID, $servicerID)
                ->set(GeneralConstants::CUSTOMER_ID, (int)$servicer[0][GeneralConstants::CUSTOMER_ID])
                ->set(GeneralConstants::CREATEDATETIME, (new \DateTime("now", new \DateTimeZone("UTC")))->format('YmdHi'))
                ->setHeader('exp',GeneralConstants::PWA_TOKEN_EXPIRY_TIME)

                // Creating Signature.
                ->sign($signer, $this->serviceContainer->getParameter('api_secret'))
                ->getToken() // Retrieves Generated Token Object
                ->__toString(); // Converts Token into encoded String.

            // Return response
            return array(
                "AccessToken" => $accessToken,
                "Details" => $servicer[0]
            );
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
    /**
     * @param Request $request
     * @return mixed
     * This method validates the Authorization token and checks its validity for PWA APIs
     */
    public function VerifyPWAAuthentication(Request $request)
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

            // Check if the token is expired

            $exp = $token->getHeader('exp');
            $createTime = $token->getClaim(GeneralConstants::CREATEDATETIME);

            $tokenTime = new \DateTime($createTime, new \DateTimeZone("UTC"));
            $currentTime = new \DateTime("now", new \DateTimeZone("UTC"));

            // Check if the token is expired
            if ($currentTime->getTimestamp() - $tokenTime->getTimestamp() > $exp) {
                throw new UnauthorizedHttpException(null, ErrorConstants::TOKEN_EXPIRED);
            }

            // Checking That access_token must be used in API Calls.
            if (!$token->hasClaim(GeneralConstants::SERVICERID) && !$token->hasClaim(GeneralConstants::CUSTOMER_ID)) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
            }

            // Set token claims in authenticateResult array
            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::SERVICERID] = $token->getClaim(GeneralConstants::SERVICERID);
            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID] = $token->getClaim(GeneralConstants::CUSTOMER_ID);

            if ($token->hasClaim(GeneralConstants::VENDORID)) {
                $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::VENDORID] = $token->getClaim(GeneralConstants::VENDORID);
            }

            if ($token->hasClaim(GeneralConstants::OWNERID)) {
                $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::OWNERID] = $token->getClaim(GeneralConstants::OWNERID);
            }

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
     * @param Request $request
     * @return mixed
     */
    public function SetMobileHeaders($request)
    {
        $response = [];
        try {
            $response['IsMobile'] = 0;
            $userAgent = strtolower($request->headers->get('user-agent'));
            $response['UserAgent'] = $userAgent;

            // Check if the userAgent contains mobile devices
            if (strpos($userAgent,'iphone') !== false ||
                strpos($userAgent,'ipad') !== false ||
                strpos($userAgent,'android') !== false
            ) {
                $response['IsMobile'] = 1;
            }

            return $response;

        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param Request $request
     */
    public function SMSAuthentication(Request $request)
    {
        try {
            // Check if basic Authorization is present
            if (!$request->headers->has('php-auth-user') && !$request->headers->has('php-auth-pw')) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_CONTENT);
            }

            if ($request->headers->get('php-auth-user') !== $this->serviceContainer->getParameter('php-auth-user') &&
                $request->headers->get('php-auth-pw') !== $this->serviceContainer->getParameter('php-auth-password')
            ) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_CONTENT);
            }
            
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @return array
     */
    public function IssueFormAuthentication($content)
    {
        try {
            array_key_exists('OwnerID',$content) && $content['OwnerID'] !== '' ? $ownerID = $content['OwnerID'] : $ownerID = null;
            array_key_exists('VendorID',$content) && $content['VendorID'] !== '' ? $vendorID = $content['VendorID'] : $vendorID = null;
            $password = $content['Password'];
            $properties = [];

            // Set JWT Algo.
            $signer = new Sha256();
            $accessToken = (new Builder());

            if ($ownerID) {
                $servicer = $this->entityManager->getRepository('AppBundle:Owners')->OwnerAuthForIssueForm($ownerID,$password);
                if(empty($servicer)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CREDENTIALS);
                }

                // Get All Properties For the Owner
                $properties = $this->entityManager->getRepository('AppBundle:Properties')->GetProperties(null,$ownerID);

                $accessToken->set(GeneralConstants::SERVICERID, $ownerID);
                $accessToken->set(GeneralConstants::OWNERID, $ownerID);
            } else {
                $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->VendorAuthForIssueForm($vendorID,$password);
                if(empty($servicer)) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CREDENTIALS);
                }

                // Get All Properties For the Owner
                $properties = $this->entityManager->getRepository('AppBundle:Servicerstoproperties')->PropertiesForVendors($vendorID);

                // Get All Properties For the Vendor

                $accessToken->set(GeneralConstants::SERVICERID, $vendorID);
                $accessToken->set(GeneralConstants::VENDORID, $vendorID);
            }

            $accessToken->set(GeneralConstants::CUSTOMER_ID, $servicer[0][GeneralConstants::CUSTOMER_ID]);

            $servicer[0]['Properties'] = $properties;

            // Create a new token
            $accessToken = $accessToken->set(GeneralConstants::CREATEDATETIME, (new \DateTime("now", new \DateTimeZone("UTC")))->format('YmdHi'))
                ->setHeader('exp', GeneralConstants::PWA_TOKEN_EXPIRY_TIME)
                // Creating Signature.
                ->sign($signer, $this->serviceContainer->getParameter('api_secret'))
                ->getToken()// Retrieves Generated Token Object
                ->__toString(); // Converts Token into encoded String.

            // Return response
            return array(
                "AccessToken" => $accessToken,
                "Details" => $servicer[0]
            );
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}