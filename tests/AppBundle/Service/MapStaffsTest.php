<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/10/19
 * Time: 12:18 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Integrationqbdemployees;
use AppBundle\Entity\Integrationstocustomers;
use AppBundle\Repository\IntegrationqbdemployeesRepository;
use AppBundle\Repository\IntegrationqbdemployeestoservicersRepository;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use AppBundle\Repository\ServicersRepository;
use AppBundle\Repository\ServicerstoemployeegroupsRepository;
use AppBundle\Repository\ServicerstoservicegroupsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\AppBundle\Constants\BillingConstants;
use Tests\AppBundle\Constants\TimeTrackingConstants;

class MapStaffsTest extends KernelTestCase
{
    private static $staffMapService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$staffMapService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.map_staffs');
    }

    /*
     * Test Fetch QBD Employees
     */
    public function testFetchEmployees()
    {
        $qbdEmployees = $this->createMock(IntegrationqbdemployeesRepository::class);
        $qbdEmployees->expects($this->any())
            ->method('QBDEmployees')
            ->willReturn(TimeTrackingConstants::QBDEMPLOYEES);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($qbdEmployees);

        self::$staffMapService->setEntityManager($entityManager);
        $response = self::$staffMapService->FetchEmployees(1);
        $this->assertNotNull($response);
    }

    /*
     * Test Exception
     */
    public function testFetchEmployeesException()
    {
        try {
            $qbdEmployees = $this->createMock(IntegrationqbdemployeesRepository::class);
            $qbdEmployees->expects($this->any())
                ->method('QBDEmployees')
                ->with(Integrationqbdemployees::class)
                ->willReturn(TimeTrackingConstants::QBDEMPLOYEES);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($qbdEmployees);

            self::$staffMapService->setEntityManager($entityManager);
            $response = self::$staffMapService->FetchEmployees(1);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }

    }

    /*
     * Test Integration ID Not present
     */
    public function testIntegrationIDNotPresent()
    {
        try {
            $response = self::$staffMapService->MapStaffs(1, []);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Staff Map Inactive
     */
    public function testStaffMapInactive()
    {
        try {
            $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomers->expects($this->any())
                ->method('IsQBDSyncTimeTrackingEnabled')
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomers);

            self::$staffMapService->setEntityManager($entityManager);
            $response = self::$staffMapService->MapStaffs(1, TimeTrackingConstants::FILTERS_STAFFS);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test MapStaffs with all the Filters (Staffs Matched)
     */
    public function testMapStaffsMatched()
    {
        self::$staffMapService->setEntityManager($this->SetEntityManagers());
        $response = self::$staffMapService->MapStaffs(1, TimeTrackingConstants::FILTERS_STAFFS);
        $this->assertNotNull($response);
    }

    /*
     * Test MapStaffs with all the Filters (Staffs Not Matched)
     */
    public function testMapStaffsNotMatched()
    {
        $filters = TimeTrackingConstants::FILTERS_STAFFS;
        $filters['Filters']['Status'] = ["Not Yet Matched"];

        self::$staffMapService->setEntityManager($this->SetEntityManagers());
        $response = self::$staffMapService->MapStaffs(1, $filters);
        $this->assertNotNull($response);
    }

    /*
     * Test MapStaffs with all the Filters (Staffs Not Matched)
     */
    public function testMapStaffsException()
    {
        try {
            $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomers->expects($this->any())
                ->method('IsQBDSyncTimeTrackingEnabled')
                ->with(new Integrationstocustomers())
                ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomers);

            self::$staffMapService->setEntityManager($entityManager);
            $response = self::$staffMapService->MapStaffs(1, TimeTrackingConstants::FILTERS_STAFFS);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }
    }

    public function SetEntityManagers()
    {
        $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomers->expects($this->any())
            ->method('IsQBDSyncTimeTrackingEnabled')
            ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

        $employeeToServicers = $this->createMock(IntegrationqbdemployeestoservicersRepository::class);
        $employeeToServicers->expects($this->any())
            ->method('StaffsJoinMatched')
            ->willReturn(TimeTrackingConstants::SERVICERS_MATCHED);

        $staffTags = $this->createMock(ServicerstoemployeegroupsRepository::class);
        $staffTags->expects($this->any())
            ->method('ServicerstoEmployeeGroupsJoinMatched')
            ->willReturn(TimeTrackingConstants::MATCHED);

        $department = $this->createMock(ServicerstoservicegroupsRepository::class);
        $department->expects($this->any())
            ->method('ServicerstoServiceGroupsJoinMatched')
            ->willReturn(TimeTrackingConstants::MATCHED);

        $servicers = $this->createMock(ServicersRepository::class);
        $servicers->expects($this->any())
            ->method('SyncServicers')
            ->willReturn(TimeTrackingConstants::SERVICERS);


        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomers,$employeeToServicers, $staffTags, $department, $servicers);

        return $entityManager;
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$staffMapService = null;
    }
}