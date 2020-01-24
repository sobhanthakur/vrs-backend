<?php
/**
 *  General Constants file for Storing General Message codes and Message Text for Application.
 *
 * @category Constants
 * @author Sobhan Thakur
 */

namespace AppBundle\Constants;


final class GeneralConstants
{
    const TOKEN_EXPIRY_TIME = '43200';
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
    const CERTURL = 'CertURL';
    const QWC_QBFS = 'QBFS';
    const QWC_QBTYPE = 'QBType';
    const QWC_FALSE = "false";

    const QWC_SYNC_NAME = 'VRS-SyncInfo.qwc';

    const QWC_BILLING_SUCCESS = 'VRS-BillingBatch.qwc';
    const QWC_BILLING_FAIL = 'VRS-Failed-BillingBatch.qwc';

    const QWC_TIMETRACKING_SUCCESS = 'VRS-TimeTrackingBatch.qwc';
    const QWC_TIMETRACKING_FAIL = 'VRS-Failed-TimeTrackingBatch.qwc';

    const CUSTOMER_CONDITION = 'i.customerid= :CustomerID';
    const INTEGRATION_CONDITION = 'i.integrationid= :IntegrationID';
    const CUSTOMER_ID = 'CustomerID';
    const INTEGRATION_ID = 'IntegrationID';

    const REASON_CODE = 'ReasonCode';
    const REASON_TEXT = 'ReasonText';

    const MESSAGE = 'message';
    const STATUS = 'status';

    const CUSTOMER_NAME = 'CustomerName';
    const LOGGEDINSTAFFID = 'LoggedInStaffID';
    const CREATEDATETIME = 'CreateDateTime';
    const LOGGED_IN_SERVICER_PASSWORD = 'LoggedInServicerPassword';

    const RESTRICTIONS = 'Restrictions';

    const QBWCXML = '<QBWCXML />';
    const API_HOST = 'api_host';
    const QWC_CERT_RESOURCE = '/v1/vrs';

    const AUTH_ERROR_TEXT = 'Authentication could not be complete due to Error : ';
    const PROPERTY_API = 'Properties API error ';
    const OWNER_API = 'Owner API error ';
    const REGION_API = 'Region API error ';
    const REGION_GROUPS_API = 'Region groups API error ';
    const PROPERTY_BOOKING_API = 'property booking API error ';
    const ISSUES_API = 'Issues API error ';

    const START_DATE = 'StartDate';
    const QBDSYNCBILLING = 'QBDSyncBilling';
    const QBDSYNCTT = 'QBDSyncTimeTracking';
    const PASS = 'Password';
    const QBDVERSION = 'Version';

    const FILTER_MATCHED = 'Matched';
    const FILTER_NOT_MATCHED = 'Not Yet Matched';

    const PAY_BY_HOUR = 'PayByHour';
    const PAY_BY_RATE = 'PayByRate';

    const APPROVED = 'Approved';
    const NEW = 'New';
    const EXCLUDED = 'Excluded';

    const PROPERTY_ID = 'PropertyID';
    const INTEGRATION_QBD_CUSTOMER_ID = 'IntegrationQBDCustomerID';

    const STAFFID = 'StaffID';
    const INTEGRATION_QBD_EMPLOYEE_ID = 'IntegrationQBDEmployeeID';

    const TASKRULEID = 'TaskRuleID';
    const INTEGRATION_QBD_ITEM_ID = 'IntegrationQBDItemID';
    const BILLTYPE = 'BillType';

    const TASK_ID = 'TaskID';

    const BILLABLE = 'Billable';
    const NOT_BILLABLE = 'Not Billable';

    const TIME_CLOCK_DAYS_ID = 'TimeClockDaysID';

    const BILLING = 'Billing';
    const TIME_TRACKING = 'Time Tracking';

    const MONOLOG_EXCEPTION = 'monolog.logger.exception';
    const FUNCTION_LOG = ' function failed due to Error : ';
    const AUTHPAYLOAD = 'AuthPayload';
    const AUTHORIZATION = 'Authorization';
    const BILLING_STATUS_1 = 'b1.status=1';
    const BILLING_STATUS_0 = 'b1.status=0';

    const MAP_PROPERTIES_SERVICE = 'vrscheduler.map_properties';
    const MAP_TASKTULES_SERVICE = 'vrscheduler.map_task_rules';
    const FILTER_SERVICE = 'vrscheduler.filter_service';
    const MAP_STAFF_SERVICE = 'vrscheduler.map_staffs';
    const MAP_WAGE_ITEM = 'vrscheduler.map_wage_item';
    const INTEGRATION_SERVICE = 'vrscheduler.integration_service';

    const QWC_TICKET_SESSION = 'QWC_TicketID';
    const QWC_USERNAME_SESSION = 'QWC_Username';
    const QWC_BATCHID_SESSION = 'BatchID';
    const PAGINATION = 'Pagination';

    const CUSTOMER_ID_CONDITION = 'c.customerid= :CustomerID';
    const SUCCESS_TRANSLATION = 'api.response.success.message';

    /*constants used in login api*/
    const ACCESS_TOKEN = "Access_Token";
    const REFRESH_TOKEN = "Refresh_Token";
    const PUBLIC_AUTH_TOKEN = [
        'TOKEN_EXPIRY_TIME' => '3600',
        'REFRESH_TOKEN_EXPIRY_TIME' => '86400',
    ];
    const RETURN_DATA = [
        'TOKEN' => 'token',
        'CREATED' => 'Created',
        'EXPIRY' => 'Expiry',
        'RESOURCE_RESTRICTION' => 'Resource_Restrictions'
    ];
    const IMAGE_URL = "https://images.vrscheduler.com/70/";
    const USER_AGENT = "user_agent";
    const PROPERTIES = "properties";
    const PAYLOAD = [
        'CUSTOMER_ID' => 'customerID',
        'CUSTOMER_NAME' => 'customerName',
        'PROPERTIES' => 'properties'
    ];
    const PROPERTIES_MAPPING = [
        'propertyid' => 'p.propertyid as PropertyID',
        'active' => 'p.active as Active',
        'propertyname' => 'p.propertyname as PropertyName',
        'propertyabbreviation' => 'p.propertyabbreviation as PropertyAbbreviation',
        'propertynotes' => 'p.propertynotes as PropertyNotes',
        'internalnotes' => 'p.internalnotes as InternalNotes',
        'address' => 'p.address as Address',
        'lat' => 'p.lat as Lat',
        'lon' => 'p.lon as Lon',
        'doorcode' => 'p.doorcode as DoorCode',
        'defaultcheckintime' => 'p.defaultcheckintime as DefaultCheckInTime',
        'defaultcheckintimeminutes' => 'p.defaultcheckintimeminutes as DefaultCheckInTimeMinutes',
        'defaultcheckouttime' => 'p.defaultcheckouttime as DefaultCheckOutTime',
        'defaultcheckouttimeminutes' => 'p.defaultcheckouttimeminutes as DefaultCheckOutTimeMinutes',
        'ownerid' => 'o.ownerid as OwnerID',
        'regionid' => 'r.regionid as RegionID',
        'createdate' => 'p.createdate as CreateDate'
    ];

    const PROPERTIES_RESTRICTION = ['address'];

    const OWNERS_MAPPING = [
        'ownerid' => 'o.ownerid as OwnerID',
        'ownername' => 'o.ownername as OwnerName',
        'owneremail' => 'o.owneremail as OwnerEmail',
        'ownerphone' => 'o.ownerphone as OwnerPhone',
        'countryid' => 'c.countryid as CountryID',
        'createdate' => 'o.createdate as CreateDate'
    ];

    const OWNERS_RESTRICTION = ['ownername', 'owneremail', 'ownerphone'];

    const REGION_GROUPS_MAPPING = [
        'regiongroupid' => 'rg.regiongroupid as RegionGroupID',
        'regiongroup' => 'rg.regiongroup as RegionGroup',
        'createdate' => 'rg.createdate as CreateDate'
    ];

    const REGIONS_MAPPING = [
        'regionid' => 'r.regionid as RegionID',
        'regiongroupid' => 'rg.regiongroupid as RegionGroupID',
        'region' => 'r.region as Region',
        'color' => 'r.color as Color',
        'timezoneid' => 't.timezoneid as TimeZoneID',
        'createdate' => 'r.createdate as CreateDate'
    ];

    const PROPERTY_BOOKINGS_MAPPING = [
        'propertybookingid' => 'pb.propertybookingid as PropertyBookingID',
        'propertyid' => 'p.propertyid as PropertyID',
        'checkin' => 'pb.checkin as CheckIn',
        'checkintime' => 'pb.checkintime as CheckInTime',
        'checkintimeminutes' => 'pb.checkintimeminutes as CheckInTimeMinutes',
        'checkout' => 'pb.checkout as CheckOut',
        'checkouttime' => 'pb.checkouttime as CheckOutTime',
        'checkouttimeminutes' => 'pb.checkouttimeminutes as CheckOutTimeMinutes',
        'guest' => 'pb.guest as Guest',
        'guestemail' => 'pb.guestemail as GuestEmail',
        'guestphone' => 'pb.guestphone as GuestPhone',
        'numberofguests' => 'pb.numberofguests as NumberOfGuest',
        'numberofpets' => 'pb.numberofpets as NumberOfPets',
        'numberofchildren' => 'pb.numberofchildren as NumberOfChildren',
        'isowner' => 'pb.isowner as IsOwner',
        'bookingtags' => 'pb.bookingtags as BookingTags',
        'manualbookingtags' => 'pb.manualbookingtags as ManualBookingTags',
        'createdate' => 'pb.createdate as CreateDate'
    ];

    const PROPERTY_BOOKINGS_RESTRICTION = ['guest', 'guestemail', 'guestphone'];

    const ISSUE_MAPPING = [
        'issueid' => 'i.issueid as IssueID',
        'statusid' => 'i.statusid as StatusID',
        'issuetype' => 'i.issuetype as IssueType',
        'urgent' => 'i.urgent as Urgent',
        'issue' => 'i.issue as Issue',
        'notes' => 'i.notes as Notes',
        'servicernotes' => 'i.servicernotes as StaffNotes',
        'internalnotes' => 'i.internalnotes as InternalNotes',
        'image1' => "CASE WHEN (i.image1 = '') THEN i.image1 ELSE CONCAT(:image_url, i.image1) END as Image1",
        'image2' => "CASE WHEN (i.image2 = '') THEN i.image2 ELSE CONCAT(:image_url, i.image2) END as Image2",
        'image3' => "CASE WHEN (i.image3 = '') THEN i.image3 ELSE CONCAT(:image_url, i.image3) END as Image3",
        'billable' => 'i.billable as Billable',
        'propertyid' => 'p.propertyid as PropertyID',
        'closeddate' => 'i.closeddate as ClosedDate',
        'createdate' => 'i.createdate as CreateDate'
    ];

    const CHECK_API_RESTRICTION = [
        'PROPERTIES' => 'properties',
        'OWNERS' => 'owners',
        'REGION_GROUPS' => 'regiongroups',
        'REGIONS' => 'regions',
        'ISSUES' => 'issues'
    ];

    const PARAMS = [
        'OWNERID' => 'ownerid',
        'REGIONID' => 'regionid',
        'FIELDS' => 'fields',
        'SORT' => 'sort',
        'PER_PAGE' => 'per_page',
        'PAGE' => 'page'
    ];

}