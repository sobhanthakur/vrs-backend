<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/10/19
 * Time: 12:18 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Integrationqbdemployees;
use AppBundle\Repository\IntegrationqbdemployeesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    public function testStaffMap()
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
    public function testException()
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

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$staffMapService = null;
    }
}