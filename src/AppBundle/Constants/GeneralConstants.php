<?php
/**
 *  General Constants file for Storing General Message codes and Message Text for Application.
 *
 *  @category Constants
 *  @author Sobhan Thakur
 */

namespace AppBundle\Constants;


final class GeneralConstants
{
    const TOKEN_EXPIRY_TIME = '3600';
    const QWC_APP_ID = '{11A45FC0-11D6-2315-AB85-AC87A7D71230}';
    const QWC_SYNC_INFO = [
        self::OWNERID => '{90A44FB7-33D6-4815-AC85-AC86A7E71230}',
        self::FILEID => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813120}',
        self::APP_NAME => 'Push Quickbooks entities to VRS',
        self::APP_DESCRIPTION => 'Push Quickbooks entities to VRS',
        self::APP_URL => '/v1/vrs/qbdresources/sync'
    ];

    const QWC_BILLING = [
        [
            self::OWNERID => '{90A44FB7-33D6-4815-AC85-AC86A7E71231}',
            self::FILEID => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813121}',
            self::APP_NAME => 'Sync Billing With VRS',
            self::APP_DESCRIPTION => 'Create Sales Orders in Quickbooks',
            self::APP_URL => '/v1/vrs/qbdsalesorderitems'
        ],
        [
            self::OWNERID => '{90A44FB7-33D6-4815-AC85-AC86A7E71232}',
            self::FILEID => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813122}',
            self::APP_NAME => 'Map sync info for failed records',
            self::APP_DESCRIPTION => 'Use this application to fetch the Sales Orders that are failed during the sync process',
            self::APP_URL => '/v1/vrs/qbdfailedsalesorders'
        ]
    ];

    const QWC_TIMETRACKING = [
        [
            self::OWNERID => '{90A44FB7-33D6-4815-AC85-AC86A7E71233}',
            self::FILEID => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813123}',
            self::APP_NAME => 'Sync Time Tracking With VRS',
            self::APP_DESCRIPTION => 'Create Time Trackings in Quickbooks',
            self::APP_URL => '/v1/vrs/qbdtimetrackingrecords'
        ],
        [
            self::OWNERID => '{90A44FB7-33D6-4815-AC85-AC86A7E71234}',
            self::FILEID => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813124}',
            self::APP_NAME => 'Map sync info for failed records',
            self::APP_DESCRIPTION => 'Use this application to fetch the Time Tracking that are failed during the sync process',
            self::APP_URL => '/v1/vrs/qbdfailedtimetracking'
        ]
    ];

    const APP_NAME = 'AppName';
    const APP_ID = 'AppID';
    const APP_URL = 'AppURL';
    const APP_DESCRIPTION = 'AppDescription';
    const APP_SUPPORT = 'AppSupport';
    const USERNAME = 'UserName';
    const OWNERID = 'OwnerID';
    const FILEID = 'FileID';
    const ISREADONLY = 'IsReadOnly';
    const QWC_QBTYPE = 'QBFS';

    const QWC_SYNC_NAME = 'VRS-SyncInfo.qwc';

    const QWC_BILLING_SUCCESS = 'VRS-BillingBatch.qwc';
    const QWC_BILLING_FAIL = 'VRS-Failed-BillingBatch.qwc';

    const QWC_TIMETRACKING_SUCCESS = 'VRS-TimeTrackingBatch.qwc';
    const QWC_TIMETRACKING_FAIL = 'VRS-Failed-TimeTrackingBatch.qwc';

    const CUSTOMER_CONDITION = 'i.customerid= :CustomerID';
    const CUSTOMER_ID = 'CustomerID';
    const INTEGRATION_ID = 'IntegrationID';

    const REASON_CODE = 'ReasonCode';
    const REASON_TEXT = 'ReasonText';

    const MESSAGE = 'message';
    const STATUS = 'status';

    const CUSTOMER_NAME = 'CustomerName';
    const LOGGEDINSTAFFID = 'LoggedInStaffID';
    const CREATEDATETIME = 'CreateDateTime';

    const RESTRICTIONS = 'Restrictions';

    const QBWCXML = '<QBWCXML />';
    const API_HOST = 'api_host';

    const AUTH_ERROR_TEXT = 'Authentication could not be complete due to Error : ';

    const START_DATE = 'StartDate';
    const QBDSYNCBILLING = 'QBDSyncBilling';
    const QBDSYNCTT = 'QBDSyncTimeTracking';
    const PASS = 'Password';
}