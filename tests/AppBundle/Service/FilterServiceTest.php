<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 15/10/19
 * Time: 4:05 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Repository\EmployeeGroupsRepository;
use AppBundle\Repository\OwnersRepository;
use AppBundle\Repository\PropertiesRepository;
use AppBundle\Repository\PropertyGroupsRepository;
use AppBundle\Repository\RegionGroupsRepository;
use AppBundle\Repository\ServiceGroupRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\Constants\FiltersConstant;

class FilterServiceTest extends KernelTestCase
{
    private static $filterService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$filterService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.filter_service');
    }

    /*
     * Test Property Tags
     */
    public function testPropertyGroupsFilter()
    {
        $propertyGroupsRepository = $this->createMock(PropertyGroupsRepository::class);
        $propertyGroupsRepository->expects($this->any())
            ->method('GetPropertyGroupsRestrictions')
            ->with(1)
            ->willReturn(FiltersConstant::PROPERTY_GROUPS);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($propertyGroupsRepository);

        self::$filterService->setEntityManager($entityManager);
        $response = self::$filterService->PropertyGroupsFilter(1);
        $this->assertNotNull($response);
    }

    /*
     * Test Regions
     */
    public function testRegions()
    {
        $regionGroupsRepository = $this->createMock(RegionGroupsRepository::class);
        $regionGroupsRepository->expects($this->any())
            ->method('GetRegionGroupsRestrictions')
            ->with(1)
            ->willReturn(FiltersConstant::REGIONS_GROUPS);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($regionGroupsRepository);

        self::$filterService->setEntityManager($entityManager);
        $response = self::$filterService->RegionGroupsFilter(1);
        $this->assertNotNull($response);
    }

    /*
     * Test Owners
     */
    public function testOwners()
    {
        $ownersRepository = $this->createMock(OwnersRepository::class);
        $ownersRepository->expects($this->any())
            ->method('GetOwners')
            ->with(1)
            ->willReturn(FiltersConstant::OWNERS);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($ownersRepository);

        self::$filterService->setEntityManager($entityManager);
        $response = self::$filterService->OwnersFilter(1);
        $this->assertNotNull($response);
    }

    /*
     * Test Staff Tags
     */
    public function testStaffTags()
    {
        $employeeGroupsRepository = $this->createMock(EmployeeGroupsRepository::class);
        $employeeGroupsRepository->expects($this->any())
            ->method('GetEmployeeGroups')
            ->with(1)
            ->willReturn(FiltersConstant::STAFF_TAGS);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($employeeGroupsRepository);

        self::$filterService->setEntityManager($entityManager);
        $response = self::$filterService->StaffTagFilter(1);
        $this->assertNotNull($response);
    }

    /*
     * Test Departments
     */
    public function testDepartments()
    {
        $serviceGroupRepository = $this->createMock(ServiceGroupRepository::class);
        $serviceGroupRepository->expects($this->any())
            ->method('GetServiceGroups')
            ->with(1)
            ->willReturn(FiltersConstant::STAFF_TAGS);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($serviceGroupRepository);

        self::$filterService->setEntityManager($entityManager);
        $response = self::$filterService->DepartmentsFilter(1);
        $this->assertNotNull($response);
    }

    /*
     * Test Property Filter
     */
    public function testProperty()
    {
        $propertiesRepository = $this->createMock(PropertiesRepository::class);
        $propertiesRepository->expects($this->any())
            ->method('GetProperties')
            ->with(1)
            ->willReturn(FiltersConstant::PROPERTIES);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($propertiesRepository);

        self::$filterService->setEntityManager($entityManager);
        $response = self::$filterService->PropertyFilter(1);
        $this->assertNotNull($response);
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$filterService = null;
    }
}