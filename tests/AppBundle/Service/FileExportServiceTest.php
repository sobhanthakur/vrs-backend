<?php
/**
 *
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 11/10/19
 * Time: 11:32 AM
 */

namespace Tests\AppBundle\Service;
use AppBundle\Repository\IntegrationsToCustomersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\AppBundle\Constants\FileExportConstants;

class FileExportServiceTest extends KernelTestCase
{
    private static $fileExportService;

    /**
     * Set Environment before Running the Test Case
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        self::$fileExportService = static::$kernel
            ->getContainer()
            ->get('vrscheduler.file_export');
    }

    /*
     * Test File Export
     */
    public function testFileExport()
    {
        $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
        $integrationToCustomersRepository->expects($this->any())
            ->method('GetSyncRecords')
            ->with(1,1)
            ->willReturn(FileExportConstants::INTEGRATION_TO_CUSTOMER);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($integrationToCustomersRepository);

        self::$fileExportService->setEntityManager($entityManager);
        $response = self::$fileExportService->DownloadQWC(1,1);
        $this->assertNotNull($response);
    }

    /*
     * Test for an invalid customer
     */
    public function testInvalidCustomer()
    {
        try {
            $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomersRepository->expects($this->any())
                ->method('GetSyncRecords')
                ->with(1,1)
                ->willReturn(null);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomersRepository);

            self::$fileExportService->setEntityManager($entityManager);
            $response = self::$fileExportService->DownloadQWC(1,1);
            $this->assertNotNull($response);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /*
     * Test if the integration is disabled
     */
    public function testIntegrationActive()
    {
        try {
            $integrationToCustomersRepository = $this->createMock(IntegrationsToCustomersRepository::class);
            $integrationToCustomersRepository->expects($this->any())
                ->method('GetSyncRecords')
                ->with(1,1)
                ->willReturn(FileExportConstants::INTEGRATION_TO_CUSTOMER1);

            $entityManager = $this->createMock(EntityManager::class);
            $entityManager->expects($this->any())
                ->method('getRepository')
                ->willReturn($integrationToCustomersRepository);

            self::$fileExportService->setEntityManager($entityManager);
            $response = self::$fileExportService->DownloadQWC(1,1);
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /**
     * Clean Environment after Running the Test Case
     */
    public static function tearDownAfterClass(): void
    {
        self::$fileExportService = null;
    }
}