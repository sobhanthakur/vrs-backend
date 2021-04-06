<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 24/12/19
 * Time: 4:20 PM
 */

namespace AppBundle\Service;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\HttpKernel\Exception\HttpException;
use  Lcobucci\JWT\Parsing\Decoder;

/**
 * Class AuthenticationPublicService
 * @package AppBundle\Service
 */
class AuthenticationPublicService extends BaseService
{
    /**
     * Function to parse the request content to a given format
     *
     * @param $content
     * @param $type
     *
     * @return array
     */
    public function parseContent($content, $type)
    {
        if ($type == "json") {
            return json_decode($content, true);
        }
        return ['status' => false, 'error' => ErrorConstants::INVALID_JSON];

    }

    /**
     * Function to parse the request content to a given format
     *
     * @param $content
     *
     * @return array
     */
    public function authDetails($content,$zapier=null)
    {
        $returnData = [];
        $authenticationResult = [];

        try {
            //Encoding apiKey and apiValue using sha256
            $apiKey = hash('sha256', $content['API_Key']);
            $apiValue = hash('sha256', $content['API_Value']);

            //Validation if user and returns error if user not present
            $apiKeysRepo = $this->entityManager->getRepository('AppBundle:Apikeys');
            $apiKeys = $apiKeysRepo->findOneBy(array('apikey' => $apiKey, 'value' => $apiValue));
            if (!$apiKeys) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CREDENTIALS);
            }

            //Getting customer object
            $customerId = $apiKeys->getCustomerid();

            //Getting customerID and customerName from CustomerRepository
            $customerRepo = $this->entityManager->getRepository('AppBundle:Customers');
            $customerDetails = $customerRepo->findOneBy(array('customerid' => $customerId));
            $customerid = $customerDetails->getCustomerid();
            $customerName = $customerDetails->getCustomername();

            //Fetching resources and its restriction for customer
            $apiKeyToPubResourseRepo = $this->entityManager->getRepository('AppBundle:Apikeystoapipublicresources');
            $restriction = $apiKeyToPubResourseRepo->fetchResource($apiKeys);
            //Setting payload for JWT token
            $authenticationResult[GeneralConstants::PAYLOAD['CUSTOMER_ID']] = $customerid;
            $authenticationResult[GeneralConstants::PAYLOAD['CUSTOMER_NAME']] = $customerName;
            $authenticationResult[GeneralConstants::PAYLOAD['PROPERTIES']] = $restriction;
            $authenticationResult[GeneralConstants::STATUS] = true;

            //create authToken
            $authToken = $this->createJWTToken($authenticationResult, GeneralConstants::PUBLIC_AUTH_TOKEN['TOKEN_EXPIRY_TIME']);

            //create refreshToken
            $refreshToken = $this->createJWTToken($authenticationResult, GeneralConstants::PUBLIC_AUTH_TOKEN['REFRESH_TOKEN_EXPIRY_TIME']);

            //Setting return data for Access Token
            $returnData[GeneralConstants::ACCESS_TOKEN][GeneralConstants::RETURN_DATA['TOKEN']] = $authToken;
            $returnData[GeneralConstants::ACCESS_TOKEN][GeneralConstants::RETURN_DATA['CREATED']] = gmdate("YmdHi");
            $returnData[GeneralConstants::ACCESS_TOKEN][GeneralConstants::RETURN_DATA['EXPIRY']] = GeneralConstants::PUBLIC_AUTH_TOKEN['TOKEN_EXPIRY_TIME'];

            //Setting return data for Refresh Token
            $returnData[GeneralConstants::REFRESH_TOKEN][GeneralConstants::RETURN_DATA['TOKEN']] = $refreshToken;
            $returnData[GeneralConstants::REFRESH_TOKEN][GeneralConstants::RETURN_DATA['CREATED']] = gmdate("YmdHi");
            $returnData[GeneralConstants::REFRESH_TOKEN][GeneralConstants::RETURN_DATA['EXPIRY']] = GeneralConstants::PUBLIC_AUTH_TOKEN['REFRESH_TOKEN_EXPIRY_TIME'];

            if ($zapier) {
                return $authenticationResult;
            }
            return $returnData;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Function to create JWT token
     *
     * @param $authenticationResult
     * @param $exp
     *
     * @return string
     */
    public function createJWTToken($authenticationResult, $exp)
    {
        $signer = new Sha256();
        return (new Builder())
            ->set(GeneralConstants::CUSTOMER_ID, $authenticationResult['customerID'])
            ->set(GeneralConstants::CUSTOMER_NAME, $authenticationResult['customerName'])
            ->set(GeneralConstants::PROPERTIES, $authenticationResult['properties'])
            ->set(GeneralConstants::CREATEDATETIME, (new \DateTime("now", new \DateTimeZone("UTC")))->format('YmdHi'))
            ->setHeader('exp', $exp)
            // Creating Signature.
            ->sign($signer, $this->serviceContainer->getParameter('api_secret'))
            ->getToken()// Retrieves Generated Token Object
            ->__toString(); // Converts Token into encoded String.
    }

    /**
     * Function to renew token using refresh token
     *
     * @param $request
     *
     * @return array
     */
    public function renewToken($request)
    {
        $authenticateResult = array();
        $returnData = array();

        try {
            //
            $authenticateResult[GeneralConstants::STATUS] = false;

            // Checking Authorization Key for validating Token.
            $authToken = $request->headers->get('Authorization');

            // Parsing String Token to JWT Token Object.
            $token = (new Parser())->parse((string)$authToken);
            $signer = new Sha256();

            // Checking If Token passed in API Request Header is valid.
            if (!$token->verify($signer, $this->serviceContainer->getParameter('api_secret'))) {
                throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
            }

            //Checking If token is expired
            if (!$this->CheckExpiry($token)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::UNPROCESSABLE_AUTH_TOKEN);
            }

            $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID] =
                $token->getClaim(GeneralConstants::CUSTOMER_ID);

            //Setting payload for JWT token
            $authenticationResult['customerID'] = $token->getClaim(GeneralConstants::CUSTOMER_ID);
            $authenticationResult['customerName'] = $token->getClaim(GeneralConstants::CUSTOMER_NAME);
            $authenticationResult['properties'] = $token->getClaim(GeneralConstants::PROPERTIES);

            //create JWT token
            $authToken = $this->createJWTToken($authenticationResult, GeneralConstants::PUBLIC_AUTH_TOKEN['TOKEN_EXPIRY_TIME']);

            //Setting return data for Access Token
            $returnData[GeneralConstants::ACCESS_TOKEN][GeneralConstants::RETURN_DATA['TOKEN']] = $authToken;
            $returnData[GeneralConstants::ACCESS_TOKEN][GeneralConstants::RETURN_DATA['CREATED']] = gmdate("YmdHi");
            $returnData[GeneralConstants::ACCESS_TOKEN][GeneralConstants::RETURN_DATA['EXPIRY']] = GeneralConstants::PUBLIC_AUTH_TOKEN['TOKEN_EXPIRY_TIME'];
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $returnData;

    }

    /**
     * Function to check epiry of refresh token
     *
     * @param $token
     *
     * @return boolean
     */
    public function CheckExpiry($token)
    {
        try {
            // Get headers and claims from the tokens
            $exp = $token->getHeader('exp');
            $createTime = $token->getClaim(GeneralConstants::CREATEDATETIME);

            //Getting token and current time respectively
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
     * Verifying the token for the user
     *
     * @param $request
     *
     * @return boolean
     */
    public function VerifyAuthToken($request)
    {
        $authenticationResult[GeneralConstants::STATUS] = false;
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

            if (!$this->CheckExpiry($token)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::UNPROCESSABLE_AUTH_TOKEN);
            }

            //Setting payload for JWT token
            $authenticationResult['customerID'] = $token->getClaim(GeneralConstants::CUSTOMER_ID);
            $authenticationResult['customerName'] = $token->getClaim(GeneralConstants::CUSTOMER_NAME);
            $authenticationResult['properties'] = $token->getClaim(GeneralConstants::PROPERTIES);

            //Set authenticated status to true
            $authenticationResult[GeneralConstants::STATUS] = true;

        }  catch (\InvalidArgumentException $ex) {
            throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (UnauthorizedHttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::AUTH_ERROR_TEXT .
                $exception->getMessage());
            throw new UnauthorizedHttpException(null, ErrorConstants::INVALID_AUTH_TOKEN);
        }
        return $authenticationResult;
    }

    /**
     * Checking the access of the user
     *
     * @param $restrictions
     * @param $baseName
     *
     * @return boolean
     */
    public function resourceRestriction($restrictions, $baseName)
    {
        foreach ($restrictions as $restriction) {
            if (gettype($restriction) === 'object' && isset($restriction->resourseName) && strtolower($restriction->resourseName) == $baseName) {
                return $restriction;
            } elseif (gettype($restriction) === 'array' && array_key_exists('resourseName',$restriction) && strtolower($restriction['resourseName']) === $baseName) {
                return json_decode(json_encode($restriction));
            }
        }
    }
}