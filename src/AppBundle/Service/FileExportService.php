<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * File Export Service to dynamically generate the desired XMLs for the quickbooks desktop integration
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use \ZipArchive as ZipArchive;

/**
 * Class FileExportService
 * @package AppBundle\Service
 */
class FileExportService extends BaseService
{

    /**
     * @param $integrationID
     * @param $customerID
     * @return Response
     */
    public function DownloadQWC($integrationID, $customerID)
    {
        $zipFileName = null;
        $zipPath = null;
        $filePath = $this->serviceContainer->getParameter('filepath');

        $syncFileName = null;

        try {
            /*
             * Fetch Username, IsActive, Billing Info and TimeTracking Info.
             */
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->GetSyncRecords($integrationID, $customerID);
            if (!$integrationToCustomers) {
                throw new HttpException(404, '');
            }

            $active = $integrationToCustomers[0]['active'];
            $username = $integrationToCustomers[0]['username'];
            $billing = $integrationToCustomers[0]['qbdsyncbilling'];
            $timeTracking = $integrationToCustomers[0]['qbdsyncpayroll'];

            /*
             * If the customer is active then generate the qwc files accordingly.
             */
            if (!$active) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INTEGRATION_NOT_ACTIVE);
            }

            /*
             * Generate XMLs for QB Sync
             */
            $syncFileName = 'Sync_' . $username . '.qwc';
            $syncXML = $this->SyncQWCFile($username, $filePath, $syncFileName);
            $zipFileName = 'QWC_' . $username . '.zip';
            $zipPath = $filePath . $zipFileName;

            // Archive ZIP. Add Files into the ZIP
            $zip = new ZipArchive();
            $zip->open($zipPath, ZipArchive::CREATE);
            $zip->addFile($filePath . $syncFileName, GeneralConstants::QWC_SYNC_NAME);

            /*
             * Create XMLs for Billing Info
             */
            if ($billing) {
                $syncXML = $this->BillingQWCFile($username, $filePath);
                $zip->addFile($filePath . $username . GeneralConstants::QWC_BILLING_SUCCESS, GeneralConstants::QWC_BILLING_SUCCESS);
                $zip->addFile($filePath . $username . GeneralConstants::QWC_BILLING_FAIL, GeneralConstants::QWC_BILLING_FAIL);
            }

            /*
             * Create XMLs for Time Tracking Info
             */
            if ($timeTracking) {
                $syncXML = $this->TimeTrackingQWCFile($username, $filePath);
                $zip->addFile($filePath . $username . GeneralConstants::QWC_TIMETRACKING_SUCCESS, GeneralConstants::QWC_TIMETRACKING_SUCCESS);
                $zip->addFile($filePath . $username . GeneralConstants::QWC_TIMETRACKING_FAIL, GeneralConstants::QWC_TIMETRACKING_FAIL);
            }

            /*
             * Close Zip Archive and delete the qwc files
             */
            $zip->close();
            if (file_exists($filePath . $syncFileName)) {
                unlink($filePath . $syncFileName);
            }

            if ($billing &&
                file_exists($filePath . $username . GeneralConstants::QWC_BILLING_SUCCESS) &&
                file_exists($filePath . $username . GeneralConstants::QWC_BILLING_FAIL)
            ) {
                unlink($filePath . $username . GeneralConstants::QWC_BILLING_SUCCESS);
                unlink($filePath . $username . GeneralConstants::QWC_BILLING_FAIL);
            }

            if ($timeTracking &&
                file_exists($filePath . $username . GeneralConstants::QWC_TIMETRACKING_SUCCESS) &&
                file_exists($filePath . $username . GeneralConstants::QWC_TIMETRACKING_FAIL)
            ) {
                unlink($filePath . $username . GeneralConstants::QWC_TIMETRACKING_SUCCESS);
                unlink($filePath . $username . GeneralConstants::QWC_TIMETRACKING_FAIL);
            }

            return $zipPath;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Downloading QWC Files due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $username
     * @param $filePath
     * @param $syncFileName
     * @return bool|int
     */
    public function SyncQWCFile($username, $filePath, $syncFileName)
    {
        $xml = new \SimpleXMLElement('<QBWCXML />');

        $xml->addChild(GeneralConstants::APP_NAME, GeneralConstants::QWC_SYNC_INFO['AppName']);
        $xml->addChild(GeneralConstants::APP_ID, GeneralConstants::QWC_APP_ID);
        $xml->addChild(GeneralConstants::APP_URL, $this->serviceContainer->getParameter('SSL') . $this->serviceContainer->getParameter('api_host') . GeneralConstants::QWC_SYNC_INFO['AppURL']);
        $xml->addChild(GeneralConstants::APP_DESCRIPTION, GeneralConstants::QWC_SYNC_INFO['AppDescription']);
        $xml->addChild(GeneralConstants::APP_SUPPORT, $this->serviceContainer->getParameter('SSL') . $this->serviceContainer->getParameter('api_host'));
        $xml->addChild(GeneralConstants::USERNAME, $username);
        $xml->addChild(GeneralConstants::OWNERID, GeneralConstants::QWC_SYNC_INFO['OwnerID']);
        $xml->addChild(GeneralConstants::FILEID, GeneralConstants::QWC_SYNC_INFO['FileID']);
        $xml->addChild(GeneralConstants::QWC_QBTYPE, GeneralConstants::QWC_QBTYPE);
        $xml->addChild(GeneralConstants::ISREADONLY, false);

        $fileName = $filePath . $syncFileName;
        $result = file_put_contents($fileName, $xml->asXML());
        return $result;
    }

    /**
     * @param $username
     * @return bool
     */
    public function BillingQWCFile($username, $filePath)
    {
        $fileName = null;
        for ($i = 0; $i < 2; $i++) {
            $xml = new \SimpleXMLElement('<QBWCXML />');

            $xml->addChild(GeneralConstants::APP_NAME, GeneralConstants::QWC_BILLING[$i]['AppName']);
            $xml->addChild(GeneralConstants::APP_ID, GeneralConstants::QWC_APP_ID);
            $xml->addChild(GeneralConstants::APP_URL, $this->serviceContainer->getParameter('SSL') . $this->serviceContainer->getParameter('api_host') . GeneralConstants::QWC_BILLING[$i]['AppURL']);
            $xml->addChild(GeneralConstants::APP_DESCRIPTION, GeneralConstants::QWC_BILLING[$i]['AppDescription']);
            $xml->addChild(GeneralConstants::APP_SUPPORT, $this->serviceContainer->getParameter('SSL') . $this->serviceContainer->getParameter('api_host'));
            $xml->addChild(GeneralConstants::USERNAME, $username);
            $xml->addChild(GeneralConstants::OWNERID, GeneralConstants::QWC_BILLING[$i]['OwnerID']);
            $xml->addChild(GeneralConstants::FILEID, GeneralConstants::QWC_BILLING[$i]['FileID']);
            $xml->addChild(GeneralConstants::QWC_QBTYPE, GeneralConstants::QWC_QBTYPE);
            $xml->addChild(GeneralConstants::ISREADONLY, false);

            switch ($i) {
                case 0:
                    $fileName = $username . GeneralConstants::QWC_BILLING_SUCCESS;
                    break;
                case 1:
                    $fileName = $username . GeneralConstants::QWC_BILLING_FAIL;
                    break;
            }
            $result = file_put_contents($filePath . $fileName, $xml->asXML());
        }
        return true;
    }

    /**
     * @param $username
     * @param $filePath
     * @return bool
     */
    public function TimeTrackingQWCFile($username, $filePath)
    {
        $fileName = null;
        for ($i = 0; $i < 2; $i++) {
            $xml = new \SimpleXMLElement('<QBWCXML />');

            $xml->addChild(GeneralConstants::APP_NAME, GeneralConstants::QWC_TIMETRACKING[$i]['AppName']);
            $xml->addChild(GeneralConstants::APP_ID, GeneralConstants::QWC_APP_ID);
            $xml->addChild(GeneralConstants::APP_URL, $this->serviceContainer->getParameter('SSL') . $this->serviceContainer->getParameter('api_host') . GeneralConstants::QWC_TIMETRACKING[$i]['AppURL']);
            $xml->addChild(GeneralConstants::APP_DESCRIPTION, GeneralConstants::QWC_BILLING[$i]['AppDescription']);
            $xml->addChild(GeneralConstants::APP_SUPPORT, $this->serviceContainer->getParameter('SSL') . $this->serviceContainer->getParameter('api_host'));
            $xml->addChild(GeneralConstants::USERNAME, $username);
            $xml->addChild(GeneralConstants::OWNERID, GeneralConstants::QWC_TIMETRACKING[$i]['OwnerID']);
            $xml->addChild(GeneralConstants::FILEID, GeneralConstants::QWC_TIMETRACKING[$i]['FileID']);
            $xml->addChild(GeneralConstants::QWC_QBTYPE, GeneralConstants::QWC_QBTYPE);
            $xml->addChild(GeneralConstants::ISREADONLY, false);

            switch ($i) {
                case 0:
                    $fileName = $username . GeneralConstants::QWC_TIMETRACKING_SUCCESS;
                    break;
                case 1:
                    $fileName = $username . GeneralConstants::QWC_TIMETRACKING_FAIL;
                    break;
            }
            $result = file_put_contents($filePath . $fileName, $xml->asXML());
        }
        return true;
    }
}