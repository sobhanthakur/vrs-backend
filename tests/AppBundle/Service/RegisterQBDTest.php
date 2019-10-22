<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 8/10/19
 * Time: 4:42 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Customers;
use AppBundle\Entity\Integrations;
use AppBundle\Entity\Integrationstocustomers;
use AppBundle\Repository\CustomersRepository;
use AppBundle\Repository\IntegrationsRepository;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\AppBundle\Constants\IntegrationConstants;

class RegisterQBDTest extends KernelTestCase
{
    private static $integrationService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$integrationService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.integration_service');
    }

    /*
     * Test Failed Integration
     */
    public function testFailedIntegration()
    {
        try {
            $integrationRepository = $this->createMock(IntegrationsRepository::class);
            $integrationRepository->expects($this->any())
                ->method('find')
                ->with(null)
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->once())
                ->method('getRepository')
                ->willReturn($integrationRepository);

            self::$integrationService->setEntityManager($entityManager);
            $response = self::$integrationService->InstallQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }
    }

    /*
     * Test Integration Not Found
     */
    public function testIntegrationNotFound()
    {
        try {
            $integrationRepository = $this->createMock(IntegrationsRepository::class);
            $integrationRepository->expects($this->any())
                ->method('find')
                ->with(1)
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->once())
                ->method('getRepository')
                ->willReturn($integrationRepository);

            self::$integrationService->setEntityManager($entityManager);
            $response = self::$integrationService->InstallQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test IntegrationToCustomers Already Present
     */
    public function testIntegrationToCustomersFound()
    {
        try {
            $integrationRepository = $this->createMock(IntegrationsRepository::class);
            $integrationRepository->expects($this->any())
                ->method('find')
                ->with(1)
                ->willReturn(new Integrations());

            $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomersRepository->expects($this->any())
                ->method('CheckIntegration')
                ->with(1,1)
                ->willReturn(new Integrationstocustomers());

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationRepository, $integrationToCustomersRepository);

            self::$integrationService->setEntityManager($entityManager);
            $response = self::$integrationService->InstallQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Customers Already Present
     */
    public function testCustomersFound()
    {
        try {
            $integrationRepository = $this->createMock(IntegrationsRepository::class);
            $integrationRepository->expects($this->any())
                ->method('find')
                ->with(1)
                ->willReturn(new Integrations());

            $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomersRepository->expects($this->any())
                ->method('CheckIntegration')
                ->with(1,1)
                ->willReturn(null);

            $customersRepository = $this->createMock(CustomersRepository::class);
            $customersRepository->expects($this->any())
                ->method('find')
                ->with(1)
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationRepository, $integrationToCustomersRepository, $customersRepository);

            self::$integrationService->setEntityManager($entityManager);
            $response = self::$integrationService->InstallQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Create new Integration
     */
    public function testNewIntegration()
    {
        $integrationRepository = $this->createMock(IntegrationsRepository::class);
        $integrationRepository->expects($this->any())
            ->method('find')
            ->with(1)
            ->willReturn(new Integrations());

        $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomersRepository->expects($this->any())
            ->method('CheckIntegration')
            ->with(1,1)
            ->willReturn(null);

        $customersRepository = $this->createMock(CustomersRepository::class);
        $customersRepository->expects($this->any())
            ->method('find')
            ->with(1)
            ->willReturn(new Customers());

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository','persist','flush')
            ->willReturn($integrationRepository, $integrationToCustomersRepository, $customersRepository);

        self::$integrationService->setEntityManager($entityManager);
        $response = self::$integrationService->InstallQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        $this->assertNotNull($response);
    }

    /*
     * Test Failed Update integration Service
     */
    public function testFailedUpdate()
    {
        try {
            $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomersRepository->expects($this->any())
                ->method('findOneBy')
                ->with(['customerid'=>1,'integrationid'=>1])
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomersRepository);

            self::$integrationService->setEntityManager($entityManager);
            $response = self::$integrationService->UpdateQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Wrong format for findOneBy
     */
    public function testInternalServerErr()
    {
        try {
            $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomersRepository->expects($this->any())
                ->method('findOneBy')
                ->with(1)
                ->willReturn(new Integrationstocustomers());

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository','persist','flush')
                ->willReturn($integrationToCustomersRepository);

            self::$integrationService->setEntityManager($entityManager);
            $response = self::$integrationService->UpdateQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }
    }

    /*
     * Test Update integration Service
     */
    public function testUpdate()
    {
        $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomersRepository->expects($this->any())
            ->method('findOneBy')
            ->with(['customerid'=>1,'integrationid'=>1])
            ->willReturn(new Integrationstocustomers());

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository','persist','flush')
            ->willReturn($integrationToCustomersRepository);

        self::$integrationService->setEntityManager($entityManager);
        $response = self::$integrationService->UpdateQuickbooksDesktop(IntegrationConstants::MOCK_CONTENT, 1);
        $this->assertNotNull($response);
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$integrationService = null;
    }
}