<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 18/10/19
 * Time: 2:38 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Repository\IntegrationqbdcustomersRepository;
use AppBundle\Repository\IntegrationqbdcustomerstopropertiesRepository;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use AppBundle\Repository\PropertiesRepository;
use AppBundle\Repository\PropertiestopropertygroupsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\AppBundle\Constants\BillingConstants;

class MapPropertiesServiceTest extends KernelTestCase
{
    private static $billingMapProperties;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$billingMapProperties = static::$kernel
            ->getContainer()
            ->get('vrscheduler.map_properties');
    }

    /*
     * Test IntegrationID Not present
     */
    public function testIntegrationIDNotPresent()
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

            self::$billingMapProperties->setEntityManager($entityManager);
            $response = self::$billingMapProperties->MapProperties(1, []);
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

            self::$billingMapProperties->setEntityManager($entityManager);
            $response = self::$billingMapProperties->MapProperties(1, BillingConstants::FILTERS);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test MapProperties with Empty Filter
     */
    public function testMapPropertiesEmpty()
    {
        $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomers->expects($this->any())
            ->method('IsQBDSyncBillingEnabled')
            ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

        $propertiesRepository = $this->createMock(PropertiesRepository::class);
        $propertiesRepository->expects($this->any())
            ->method('SyncProperties')
            ->willReturn(BillingConstants::PROPERTIES);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomers, $propertiesRepository);

        self::$billingMapProperties->setEntityManager($entityManager);
        $response = self::$billingMapProperties->MapProperties(1, BillingConstants::INTEGRATION_ID);
        $this->assertNotNull($response);
    }

    /*
     * Test MapProperties with all the Filters (Properties Not Matched)
     */
    public function testMapProperties()
    {
        $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomers->expects($this->any())
            ->method('IsQBDSyncBillingEnabled')
            ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

        $propertiesToPropertyGroups = $this->createMock(PropertiestopropertygroupsRepository::class);
        $propertiesToPropertyGroups->expects($this->any())
            ->method('PropertiestoPropertyGroupsJoinMatched')
            ->willReturn(BillingConstants::PROPERTIES_TO_PROPERTY_GROUPS);

        $customersToProperties = $this->createMock(IntegrationqbdcustomerstopropertiesRepository::class);
        $customersToProperties->expects($this->any())
            ->method('PropertiesJoinMatched')
            ->willReturn(BillingConstants::PROPERTIES_MATCH);

        $propertiesRepository = $this->createMock(PropertiesRepository::class);
        $propertiesRepository->expects($this->any())
            ->method('SyncProperties')
            ->willReturn(BillingConstants::PROPERTIES);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomers,$customersToProperties,$propertiesToPropertyGroups,$propertiesRepository);

        self::$billingMapProperties->setEntityManager($entityManager);
        $response = self::$billingMapProperties->MapProperties(1, BillingConstants::FILTERS);
        $this->assertNotNull($response);
    }

    /*
     * Test MapProperties with all the Filters (Properties Matched)
     */
    public function testMapPropertiesMatched()
    {
        $integrationToCustomers = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomers->expects($this->any())
            ->method('IsQBDSyncBillingEnabled')
            ->willReturn(BillingConstants::INTEGRATIONTOCUSTOMERS);

        $propertiesToPropertyGroups = $this->createMock(PropertiestopropertygroupsRepository::class);
        $propertiesToPropertyGroups->expects($this->any())
            ->method('PropertiestoPropertyGroupsJoinMatched')
            ->willReturn(BillingConstants::PROPERTIES_TO_PROPERTY_GROUPS);

        $customersToProperties = $this->createMock(IntegrationqbdcustomerstopropertiesRepository::class);
        $customersToProperties->expects($this->any())
            ->method('PropertiesJoinMatched')
            ->willReturn(BillingConstants::PROPERTIES_MATCH);

        $propertiesRepository = $this->createMock(PropertiesRepository::class);
        $propertiesRepository->expects($this->any())
            ->method('SyncProperties')
            ->willReturn(BillingConstants::PROPERTIES);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomers,$customersToProperties,$propertiesToPropertyGroups,$propertiesRepository);

        $filters = BillingConstants::FILTERS;
        $filters['Filters']['Status'][0] = 'Matched';
        self::$billingMapProperties->setEntityManager($entityManager);
        $response = self::$billingMapProperties->MapProperties(1, $filters);
        $this->assertNotNull($response);
    }

    /*
     * Test Fetch QBD Customers
     */
    public function testQBDCustomers()
    {
        $qbdCustomers = $this->createMock(IntegrationqbdcustomersRepository::class);
        $qbdCustomers->expects($this->any())
            ->method('QBDCustomers')
            ->willReturn(BillingConstants::QBDCUSTOMERS);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($qbdCustomers);

        self::$billingMapProperties->setEntityManager($entityManager);
        $response = self::$billingMapProperties->FetchCustomers(1);
        $this->assertNotNull($response);
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$billingMapProperties = null;
    }
}