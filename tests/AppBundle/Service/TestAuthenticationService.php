<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/9/19
 * Time: 3:37 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Service\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tests\AppBundle\Constants\AuthConstants;

/**
 * Class TestAuthenticationService
 * @package Tests\AppBundle\Service
 */
class TestAuthenticationService extends KernelTestCase
{
    private static $request;
    private static $authenticationService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$authenticationService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.authentication_service');

        self::$request = new Request();
    }

    /**
     * Function to test for valid authentication token
     */
    public function testValidToken()
    {
        self::$request->headers->set('Authorization', AuthConstants::AUTH_TOKEN_STAFFID_NULL);
        $response = self::$authenticationService->VerifyAuthToken(self::$request);
        if ($response['status']) {
            $this->assertTrue(true);
        }
    }

    /**
     * Function to test for expired authentication token
     */
    public function testExpiredToken()
    {
        try {
            self::$request->headers->set('Authorization', AuthConstants::AUTH_TOKEN_EXPIRED);
            $response = self::$authenticationService->VerifyAuthToken(self::$request);
        } catch (UnauthorizedHttpException $exception) {
            $this->assertEquals(ErrorConstants::TOKEN_EXPIRED, $exception->getMessage());
        }
    }

    /**
     * Function to test for invalid authentication token
     */
    public function testInvalidToken()
    {
        try {
            self::$request->headers->set('Authorization', AuthConstants::AUTH_TOKEN_INVALID);
            $response = self::$authenticationService->VerifyAuthToken(self::$request);
        } catch (UnauthorizedHttpException $exception) {
            $this->assertEquals(ErrorConstants::INVALID_AUTH_CONTENT, $exception->getMessage());
        }
    }

    /**
     * Function to test for invalid Signature
     */
    public function testInvalidSignature()
    {
        try {
            self::$request->headers->set('Authorization', AuthConstants::AUTH_TOKEN_INVALID_SIGNATURE);
            $response = self::$authenticationService->VerifyAuthToken(self::$request);
        } catch (UnauthorizedHttpException $exception) {
            $this->assertEquals(ErrorConstants::INVALID_AUTH_TOKEN, $exception->getMessage());
        }
    }

    /**
     * Function to test for invalid Claim
     */
    public function testInvalidClaim()
    {
        try {
            self::$request->headers->set('Authorization', AuthConstants::AUTH_INVALID_CLAIM);
            $response = self::$authenticationService->VerifyAuthToken(self::$request);
        } catch (\Exception $exception) {
            $this->assertEquals(ErrorConstants::INTERNAL_ERR, $exception->getMessage());
        }
    }

    /**
     * Function to test for New token
     */
    public function testNewToken()
    {
        $response = self::$authenticationService->CreateNewToken(AuthConstants::AUTHENTICATION_RESULT);
        $this->assertNotNull($response);
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$request = null;
    }
}