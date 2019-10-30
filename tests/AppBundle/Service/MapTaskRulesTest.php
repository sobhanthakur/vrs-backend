<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 30/10/19
 * Time: 10:49 AM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Integrationstocustomers;
use AppBundle\Repository\IntegrationqbditemstoservicesRepository;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use AppBundle\Repository\ServicesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\AppBundle\Constants\BillingConstants;
use Tests\AppBundle\Constants\TimeTrackingConstants;

class MapTaskRulesTest extends KernelTestCase
{
    private static $taskRulesMapService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$taskRulesMapService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.map_task_rules');
    }

    /*
     * Test IntegrationID Not present
     */
    public function testIntegrationIDNotPresent()
    {
        try {
            $response = self::$taskRulesMapService->MapTaskRules(1, []);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test Empty Billing / Billling not enabled
     */
    public function testBillingNotEnabled()
    {
        try {
            $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomers->expects($this->any())
                ->method('IsQBDSyncBillingEnabled')
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomers);

            self::$taskRulesMapService->setEntityManager($entityManager);
            $response = self::$taskRulesMapService->MapTaskRules(1, BillingConstants::FILTERS);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test MapTaskRules with all the Filters (Task Rules Matched)
     */
    public function testMapTaskRulesMatched()
    {
        self::$taskRulesMapService->setEntityManager($this->SetEntityManagers());
        $response = self::$taskRulesMapService->MapTaskRules(1, TimeTrackingConstants::FILTERS_TASK_TULES);
        $this->assertNotNull($response);
    }

    /*
     * Test MapTaskRules with all the Filters (Task Rules Not Matched)
     */
    public function testMapTaskRulesNotMatched()
    {
        $filters = TimeTrackingConstants::FILTERS_TASK_TULES;
        $filters['Filters']['Status'] = ["Not Yet Matched"];

        self::$taskRulesMapService->setEntityManager($this->SetEntityManagers());
        $response = self::$taskRulesMapService->MapTaskRules(1, $filters);
        $this->assertNotNull($response);
    }

    public function SetEntityManagers()
    {
        $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomers->expects($this->any())
            ->method('IsQBDSyncBillingEnabled')
            ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

        $itemsToServices = $this->createMock(IntegrationqbditemstoservicesRepository::class);
        $itemsToServices->expects($this->any())
            ->method('ServicesJoinMatched')
            ->willReturn(TimeTrackingConstants::SERVICERS_MATCHED);

        $services = $this->createMock(ServicesRepository::class);
        $services->expects($this->any())
            ->method('SyncServices')
            ->willReturn(TimeTrackingConstants::SERVICES);


        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomers,$itemsToServices, $services);

        return $entityManager;
    }

    /*
     * Test MapTaskRules with all the Filters (Staffs Not Matched)
     */
    public function testMapTaskRulesException()
    {
        try {
            $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomers->expects($this->any())
                ->method('IsQBDSyncBillingEnabled')
                ->with(new Integrationstocustomers())
                ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomers);

            self::$taskRulesMapService->setEntityManager($entityManager);
            $response = self::$taskRulesMapService->MapTaskRules(1, TimeTrackingConstants::FILTERS_TASK_TULES);
        } catch (HttpException $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
        }
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$taskRulesMapService = null;
    }
}