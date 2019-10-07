<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 6:08 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Integrations;
use AppBundle\Repository\IntegrationsRepository;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\Constants\IntegrationConstants;

class IntegrationsTest extends KernelTestCase
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
     * Test GetAllIntegrations functionality
     */
    public function testGetAllIntegrations()
    {
        // Integration Object
        $integrations = new Integrations();
        $integrations->setIntegration(IntegrationConstants::MOCK_INTEGRATIONS['Integration']);
        $integrations->setLogo(IntegrationConstants::MOCK_INTEGRATIONS['Logo']);
        $integrations->setActive(1);

        $value = [
            0 => $integrations
        ];

        // Integration TO Customers object
        $integrationsToCustomers = [
            0 => IntegrationConstants::MOCK_INTEGRATIONS_TO_CUSTOMERS
        ];

        // Now, mock the repository so it returns the mock of the Integrations
        $integrationRepository = $this->createMock(IntegrationsRepository::class);

        $integrationRepository->expects($this->any())
            ->method('findBy')
            ->with(array('active' => 1))
            ->willReturn($value);

        $response = $this->setEntityManager($integrationRepository, $integrationsToCustomers);
        $this->assertNotNull($response);

        // Test Another scenario with Installed = 0
        $response = $this->setEntityManager($integrationRepository, []);
        $this->assertNotNull($response);
    }

    /*
     * Set mock Repositories for the tests
     */
    public function setEntityManager($integrationRepository, $integrationsToCustomers)
    {
        $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
        $entityManager = $this->createMock(EntityManager::class);

        $integrationToCustomersRepository->expects($this->any())
            ->method('GetAllIntegrations')
            ->willReturn($integrationsToCustomers);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationRepository, $integrationToCustomersRepository);
        self::$integrationService->setEntityManager($entityManager);
        $response = self::$integrationService->GetAllIntegrations(IntegrationConstants::AUTHENTICATION_RESULT);
        return $response;
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$integrationService = null;
    }
}