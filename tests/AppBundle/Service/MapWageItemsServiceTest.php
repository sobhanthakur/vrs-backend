<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 1/11/19
 * Time: 5:14 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Customers;
use AppBundle\Entity\Integrationqbdpayrollitemwages;
use AppBundle\Entity\Integrationstocustomers;
use AppBundle\Repository\IntegrationqbdpayrollitemwagesRepository;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\AppBundle\Constants\TimeTrackingConstants;

class MapWageItemsServiceTest extends KernelTestCase
{
    private static $wageItemMapService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$wageItemMapService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.map_wage_item');
    }

    /*
     * Test FetchQBDWageItems
     */
    public function testFetchQBDWageItems()
    {
        $integrationqbdpayrollitemwages = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
        $integrationqbdpayrollitemwages->expects($this->any())
            ->method('QBDPayrollItemWages')
            ->willReturn(TimeTrackingConstants::QBD_PAYROLL_ITEM_WAGES);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationqbdpayrollitemwages);

        self::$wageItemMapService->setEntityManager($entityManager);
        $response = self::$wageItemMapService->FetchQBDWageItems(1);
        $this->assertNotNull($response);
    }

    /*
     * Test FetchQBDWageItems Exception
     */
    public function testFetchQBDWageItemsException()
    {
        try {
            $integrationqbdpayrollitemwages = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
            $integrationqbdpayrollitemwages->expects($this->any())
                ->method('QBDPayrollItemWages')
                ->with(new Integrationqbdpayrollitemwages())
                ->willReturn(TimeTrackingConstants::QBD_PAYROLL_ITEM_WAGES);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationqbdpayrollitemwages);

            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->FetchQBDWageItems(1);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }
    }

    /*
     * Test UpdatePayrollMapping with Empty Content
     */
    public function testUpdatePayrollMappingIntegrationIDNotPresent()
    {
        try {
            $response = self::$wageItemMapService->UpdatePayrollMapping(1,[]);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test UpdatePayrollMapping Integration Not Present
     */
    public function testUpdatePayrollMappingIntegrationNotPresent()
    {
        try {
            $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomer->expects($this->any())
                ->method('findOneBy')
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomer);
            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->UpdatePayrollMapping(1,TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test UpdatePayrollMapping With Alll info
     */
    public function testUpdatePayrollMappingAll()
    {
        $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomer->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new Integrationstocustomers());

        $wageType = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
        $wageType->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new Integrationqbdpayrollitemwages());

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomer,$wageType,$wageType);
        self::$wageItemMapService->setEntityManager($entityManager);
        $response = self::$wageItemMapService->UpdatePayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        $this->assertNotNull($response);
    }

    /*
     * Test UpdatePayrollMapping With Exception
     */
    public function testUpdatePayrollException()
    {
        try {
            $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomer->expects($this->any())
                ->method('findOneBy')
                ->with(new Integrationstocustomers())
                ->willReturn(new Integrationstocustomers());

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomer);
            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->UpdatePayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        } catch (HttpException $exception) {
            $this->assertEquals(500,$exception->getStatusCode());
        }
    }

    /*
     * Test UpdatePayrollMapping With Exception Incorrect QBD Item Wage
     */
    public function testUpdatePayrollException1()
    {
        try {
            $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomer->expects($this->any())
                ->method('findOneBy')
                ->willReturn(new Integrationstocustomers());

            $wageType = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
            $wageType->expects($this->any())
                ->method('findOneBy')
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomer,$wageType,$wageType);
            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->UpdatePayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        } catch (HttpException $exception) {
            $this->assertEquals(422,$exception->getStatusCode());
        }
    }

    /*
     * Test UpdatePayrollMapping With Exception Incorrect QBD Item Wage
     */
    public function testUpdatePayrollException2()
    {
        try {
            $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomer->expects($this->any())
                ->method('findOneBy')
                ->willReturn(new Integrationstocustomers());

            $wageType = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
            $wageType->expects($this->any())
                ->method('findOneBy')
                ->willReturn(new Integrationqbdpayrollitemwages());

            $wageType1 = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
            $wageType1->expects($this->any())
                ->method('findOneBy')
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomer,$wageType,$wageType1);
            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->UpdatePayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        } catch (HttpException $exception) {
            $this->assertEquals(422,$exception->getStatusCode());
        }
    }

    /*
     * Test GetPayrollMapping
     */
    public function testGetPayrollMapping()
    {
        $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomer->expects($this->any())
            ->method('findOneBy')
            ->willReturn(
                (new Integrationstocustomers())
                    ->setIntegrationqbdratewagetypeid(new Integrationqbdpayrollitemwages())
                    ->setIntegrationqbdhourwagetypeid(new Integrationqbdpayrollitemwages())
            );

        $wageType = $this->createMock(IntegrationqbdpayrollitemwagesRepository::class);
        $wageType->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new Integrationqbdpayrollitemwages());

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomer, $wageType, $wageType);
        self::$wageItemMapService->setEntityManager($entityManager);
        $response = self::$wageItemMapService->GetPayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        $this->assertNotNull($response);
    }

    /*
     * Test GetPayrollMapping Exception Integration ID not present
     */
    public function testGetPayrollMappingIntegrationIDNotPresent()
    {
        try {
            $response = self::$wageItemMapService->GetPayrollMapping(1, []);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Integration Not Active
     */
    public function testGetPayrollMappingIntegrationNotActive()
    {
        try {
            $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomer->expects($this->any())
                ->method('findOneBy')
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomer);
            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->GetPayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Get Payroll Mapping Exception
     */
    public function testGetPayrollMappingException500()
    {
        try {
            $integrationToCustomer = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomer->expects($this->any())
                ->method('findOneBy')
                ->with(new Integrationstocustomers())
                ->willReturn(new Integrationstocustomers());

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomer);
            self::$wageItemMapService->setEntityManager($entityManager);
            $response = self::$wageItemMapService->GetPayrollMapping(1, TimeTrackingConstants::UPDATE_PAYROLL_MAPPING);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$wageItemMapService = null;
    }
}