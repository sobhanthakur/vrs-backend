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
        'OwnerID' => '{90A44FB7-33D6-4815-AC85-AC86A7E71230}',
        'FileID' => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813120}',
        'AppName' => 'Push Quickbooks entities to VRS',
        'AppDescription' => 'Push Quickbooks entities to VRS',
        'AppURL' => '/v1/vrs/qbdresources/sync'
    ];

    const QWC_BILLING = [
        [
            'OwnerID' => '{90A44FB7-33D6-4815-AC85-AC86A7E71231}',
            'FileID' => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813121}',
            'AppName' => 'Sync Billing With VRS',
            'AppDescription' => 'Create Sales Orders in Quickbooks',
            'AppURL' => '/v1/vrs/qbdsalesorderitems'
        ],
        [
            'OwnerID' => '{90A44FB7-33D6-4815-AC85-AC86A7E71232}',
            'FileID' => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813122}',
            'AppName' => 'Map sync info for failed records',
            'AppDescription' => 'Use this application to fetch the Sales Orders that are failed during the sync process',
            'AppURL' => '/v1/vrs/qbdfailedsalesorders'
        ]
    ];

    const QWC_TIMETRACKING = [
        [
            'OwnerID' => '{90A44FB7-33D6-4815-AC85-AC86A7E71233}',
            'FileID' => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813123}',
            'AppName' => 'Sync Time Tracking With VRS',
            'AppDescription' => 'Create Time Trackings in Quickbooks',
            'AppURL' => '/v1/vrs/qbdtimetrackingrecords'
        ],
        [
            'OwnerID' => '{90A44FB7-33D6-4815-AC85-AC86A7E71234}',
            'FileID' => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813124}',
            'AppName' => 'Map sync info for failed records',
            'AppDescription' => 'Use this application to fetch the Time Tracking that are failed during the sync process',
            'AppURL' => '/v1/vrs/qbdfailedtimetracking'
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

}