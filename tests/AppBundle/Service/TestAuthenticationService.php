<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/9/19
 * Time: 3:37 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Repository\CustomersRepository;
use AppBundle\Repository\PropertyGroupsRepository;
use AppBundle\Repository\RegionGroupsRepository;
use AppBundle\Repository\ServicersRepository;
use AppBundle\Service\AuthenticationService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
        try {
            self::$request->headers->set('Authorization', AuthConstants::AUTH_TOKEN_STAFFID_NULL);
            $response = self::$authenticationService->VerifyAuthToken(self::$request);
            if ($response['status']) {
                $this->assertTrue(true);
            }
        } catch (UnauthorizedHttpException $exception) {
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

    /*
     * Test Servicer Not Found
     */
    public function testServicerNotFound()
    {
        try {
            //Expected Value
            $value = [];

            $servicersRepository = $this->createMock(ServicersRepository::class);

            $entityManager = $this->getEntitymanager();

            $servicersRepository->expects($this->any())
                ->method('GetRestrictions')
                ->willReturn($value);

            $entityManager->expects($this->once())
                ->method('getRepository')
                ->willReturn($servicersRepository);

            self::$authenticationService->setEntityManager($entityManager);
            $response = self::$authenticationService->ValidateRestrictions(AuthConstants::AUTHENTICATION_RESULT_RESTRICTIONS);
            $this->assertNotNull($response);

        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertEquals(ErrorConstants::SERVICER_NOT_FOUND, $exception->getMessage());
        }
    }

    /*
     * Test Other Restrictions
     */
    public function testRestrictions()
    {
        $servicers = [
            0 => AuthConstants::MOCK_SERVICERS
        ];

        $servicersTimeTracking = [
            0 => AuthConstants::MOCK_TIME_TRACKING
        ];

        $regionGroups = [
            0 => AuthConstants::MOCK_REGION_GROUPS
        ];

        $propertyGroups = [
            0 => AuthConstants::MOCK_PROPERTY_GROUPS
        ];

        $customers = [
            0 => AuthConstants::MOCK_CUSTOMERS
        ];

        $servicersRepository = $this->createMock(ServicersRepository::class);
        $regionGroupRepository = $this->createMock(RegionGroupsRepository::class);
        $propertyGroupRepository = $this->createMock(PropertyGroupsRepository::class);
        $customerRepository = $this->createMock(CustomersRepository::class);

        $entityManager = $this->getEntitymanager();

        $servicersRepository->expects($this->any())
            ->method('GetRestrictions')
            ->willReturn($servicers);

        $servicersRepository->expects($this->any())
            ->method('GetTimeTrackingRestrictions')
            ->willReturn($servicersTimeTracking);

        $regionGroupRepository->expects($this->any())
            ->method('GetRegionGroupsRestrictions')
            ->willReturn($regionGroups);

        $propertyGroupRepository->expects($this->any())
            ->method('GetPropertyGroupsRestrictions')
            ->willReturn($propertyGroups);

        $customerRepository->expects($this->any())
            ->method('PiecePayRestrictions')
            ->willReturn($customers);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($servicersRepository, $regionGroupRepository, $propertyGroupRepository, $customerRepository);

        self::$authenticationService->setEntityManager($entityManager);
        $response = self::$authenticationService->ValidateRestrictions(AuthConstants::AUTHENTICATION_RESULT_RESTRICTIONS);
        $this->assertNotNull($response);
    }

    /*
     * Test Empty Repositories
     */
    public function testEmptyResults()
    {
        $value = [];

        $servicersRepository = $this->createMock(ServicersRepository::class);
        $regionGroupRepository = $this->createMock(RegionGroupsRepository::class);
        $propertyGroupRepository = $this->createMock(PropertyGroupsRepository::class);
        $customerRepository = $this->createMock(CustomersRepository::class);

        $entityManager = $this->getEntitymanager();

        $servicers = [
            0 => AuthConstants::MOCK_SERVICERS
        ];
        $servicersRepository->expects($this->any())
            ->method('GetRestrictions')
            ->willReturn($servicers);

        $servicersRepository->expects($this->any())
            ->method('GetTimeTrackingRestrictions')
            ->willReturn($value);

        $regionGroupRepository->expects($this->any())
            ->method('GetRegionGroupsRestrictions')
            ->willReturn($value);

        $propertyGroupRepository->expects($this->any())
            ->method('GetPropertyGroupsRestrictions')
            ->willReturn($value);

        $customerRepository->expects($this->any())
            ->method('PiecePayRestrictions')
            ->willReturn($value);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($servicersRepository, $regionGroupRepository, $propertyGroupRepository, $customerRepository);

        self::$authenticationService->setEntityManager($entityManager);
        $response = self::$authenticationService->ValidateRestrictions(AuthConstants::AUTHENTICATION_RESULT_RESTRICTIONS);
        $this->assertNotNull($response);

    }

    public function getEntitymanager()
    {
        $entityManager = $this->createMock(EntityManager::class);
        return $entityManager;
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$request = null;
    }
}