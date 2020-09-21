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
            self::DESCRIPTION1 => 'Create',
            self::DESCRIPTION2 => 'in Quickbooks',
            self::APP_URL => '/v1/vrs/qbdsalesorderitems'
        ],
        [
            self::OWNERID => '{90A44FB7-33D6-4815-AC85-AC86A7E71232}',
            self::FILEID => '{57F3B9B6-86F6-4FCC-B1FF-967DE1813122}',
            self::APP_NAME => 'Sync Billing for failed records',
            self::DESCRIPTION1 => 'Use this application to fetch the',
            self::DESCRIPTION2 => 'that are failed during the sync process',
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
            self::APP_NAME => 'Sync Time Tracking for failed records',
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
    const DESCRIPTION1 = 'Description1';
    const DESCRIPTION2 = 'Description2';

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
    const SUCCESS = 'Success';

    const CUSTOMER_NAME = 'CustomerName';
    const LOGGEDINSTAFFID = 'LoggedInStaffID';
    const CREATEDATETIME = 'CreateDateTime';
    const LOGGED_IN_SERVICER_PASSWORD = 'LoggedInServicerPassword';
    const SERVICERID = 'ServicerID';
    const SERVICERNAME = 'ServicerName';
    const TIMETRACKING = 'TimeTracking';
    const MILEAGE = 'Mileage';
    const STARTEARLY = 'StartEarly';
    const CHANGEDATE = 'ChangeDate';


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
    const STAFF_API = 'staff API error ';
    const TASKS_API = 'tasks API error ';
    const STAFF_DAY_TIMES_API = 'staff day times API error ';
    const STAFF_TASK_API = 'staff Task API error ';

    const START_DATE = 'StartDate';
    const QBDSYNCBILLING = 'QBDSyncBilling';
    const QBDSYNCTT = 'QBDSyncTimeTracking';
    const PASS = 'Password';
    const QBDVERSION = 'Version';
    const QBDTYPE = 'Type';
    const REALMID = 'RealmID';
    const TIMETRACKING_TYPE = 'TimeTrackingType';

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
    const TIME_CLOCK_TASKS_ID = 'TimeClockTasksID';
    const INTEGRATIONQBDTIMETRACKINGRECORDID = 'IntegrationQBDTimeTrackingRecordID';

    const BILLING = 'Billing';
    const TIME_TRACKING = 'Time Tracking';

    const MONOLOG_EXCEPTION = 'monolog.logger.exception';
    const MONOLOG_API = 'monolog.logger.api';
    const MONOLOG_QB = 'monolog.logger.qb';
    const FUNCTION_LOG = ' function failed due to Error : ';
    const AUTHPAYLOAD = 'AuthPayload';
    const MOBILE_HEADERS = 'MOBILE_HEADERS';
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

    /*constants used in API request limiting*/
    const LIMIT = 500;
    const PERIOD = 60;

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

    const PROPERTIES_BOOKING_MESSEGE = [
        'INSERT' => 'Data is succesfully inserted',
        'UPDATE' => 'Data is succesfully updated',
        'DELETED' => 'Data is succesfully deleted'
    ];

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
        'createdate' => 'pb.createdate as CreateDate',
        'active' => 'CASE WHEN pb.active = 1 THEN 1 ELSE 0 END as Active'
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

    const TASK_RULES_MAPPING = [
        'serviceid' => 's.serviceid as TaskRuleID',
        'active' => 's.active as Active',
        'servicename' => 's.servicename as TaskRule',
        'abbreviation' => 's.abbreviation as Abbreviation',
        'createdate' => 's.createdate as CreateDate'
    ];

    const STAFF_MAPPING = [
        'servicerid' => 's.servicerid as StaffID',
        'name' => 's.name as Name',
        'servicerabbreviation' => 's.servicerabbreviation as Abbreviation',
        'email' => 's.email as Email',
        'phone' => 's.phone as Phone',
        'countryid' => 's.countryid as CountryID',
        'active' => 's.active as Active',
        'createdate' => 's.createdate as CreateDate'
    ];

    const TASKS_MAPPING = [
        'taskid' => 't.taskid as TaskID',
        'serviceid' => 't.serviceid as TaskRuleID',
        'propertybookingid' => 'pb.propertybookingid as PropertyBookingID',
        'propertyid' => 'p.propertyid as PropertyID',
        'taskname' => 't.taskname as TaskName',
        'taskdescription' => 't.taskdescription as TaskDescription',
        'approved' => 't.approved as Approved',
        'approveddate' => 't.approveddate as ApprovedDate',
        'completed' => '(CASE WHEN t.completeconfirmeddate IS NOT NULL THEN 1 ELSE 0 END) as Completed',
        'billable' => 't.billable as Billable',
        'amount' => 't.amount as LaborAmount',
        'expenseamount' => 't.expenseamount as MaterialsAmount',
        'taskdate' => 't.taskdate as TaskDate',
        'completeconfirmeddate' => 't.completeconfirmeddate as CompleteConfirmedDate',
        'created' => 't.createdate as CreateDate'
    ];

    const STAFF_TASKS_MAPPING = [
        'tasktoservicerid' => 'st.tasktoservicerid as StaffTaskID',
        'taskid' => 't.taskid as TaskID',
        'task' => '\'\' as TaskName',
        'serviceid' => 't.serviceid as TaskRuleID',
        'servicerid' => 'sr.servicerid as StaffID',
        'staffname' => 'sr.name as StaffName',
        'paytype' => 'st.paytype as PayType',
        'payrate' => 'st.payrate as PayRate',
        'piecepay' => 'st.piecepay as PiecePay',
        'TimeTracked' => '\'\' as TimeTracked',
        'completed' => '(CASE WHEN st.completeconfirmeddate IS NOT NULL THEN 1 ELSE 0 END) as Completed',
        'completeconfirmeddate' => 'st.completeconfirmeddate as CompleteConfirmedDate',
        'Pay' => '\'\' as Pay',
        'servicerpayrate' => 'sr.payrate as ServicerPayRate',
        'clockin' => 'tct.clockin as ClockIn',
        'clockout' => 'tct.clockout as ClockOut',
        'taskshortname' => 't.taskname as TaskShortName',
        'servicename' => 's.servicename as ServiceName',
        'approved' => 'CASE WHEN st.piecepaystatus != 0 THEN 1 ELSE 0 END as Approved',
        'approvedDate' => 'st.approvedDate as ApprovedDate',
    ];

    const STAFF_TASKS_TIMES_MAPPING = [
        'timeclocktaskid' => 'tct.timeclocktaskid as StaffTaskTimeID',
        'servicerid' => 'sr.servicerid as StaffID',
        'taskid' => 't.taskid as TaskID',
        'clockin' => 'tct.clockin as ClockIn',
        'clockout' => 'tct.clockout as ClockOut',
        'inlat' => 'tct.inlat as InLat',
        'InLon' => 'tct.inlon as InLon',
        'OutLat' => 'tct.outlat as OutLat',
        'OutLon' => 'tct.outlon as OutLon',
        'note' => 'tct.note as Note',
        'autologoutflag' => 'tct.autologoutflag as AutoLogOutFlag'
    ];

    const STAFF_DAY_TIMES_MAPPING = [
        'timeclockdayid' => 'tcd.timeclockdayid as StaffDayTimeID',
        'servicerid' => 'sr.servicerid as StaffID',
        'clockin' => 'tcd.clockin as ClockIn',
        'clockout' => 'tcd.clockout as ClockOut',
        'inlat' => 'tcd.inlat as InLat',
        'inlon' => 'tcd.inlon as InLon',
        'outlat' => 'tcd.outlat as OutLat',
        'outlon' => 'tcd.outlon as OutLon',
        'mileagein' => 'tcd.mileagein as MileageIn',
        'mileageout' => 'tcd.mileageout as MileageOut',
        'autologoutflag' => 'tcd.autologoutflag as AutoLogOutFlag'
    ];

    const CHECK_API_RESTRICTION = [
        'PROPERTIES' => 'properties',
        'OWNERS' => 'owners',
        'REGION_GROUPS' => 'regiongroups',
        'REGIONS' => 'regions',
        'ISSUES' => 'issues',
        'PROPERTY_BOOKINGS' => 'propertybookings',
        'TASK_RULES' => 'taskrules',
        'STAFF' => 'staff',
        'TASKS' => 'tasks',
        'STAFF_TASKS' => 'stafftasks',
        'STAFF_TASK_TIMES' => 'stafftasktimes',
        'STAFF_DAY_TIMES' => 'staffdaytimes'
    ];

    const PARAMS = [
        'OWNERID' => 'ownerid',
        'ACTIVE' => 'active',
        'REGIONID' => 'regionid',
        'FIELDS' => 'fields',
        'SORT' => 'sort',
        'PER_PAGE' => 'per_page',
        'PAGE' => 'page',
        'PROPERTYID' => 'propertyid',
        'CHECKINSTARTDATE' => 'checkinstartdate',
        'CHECKINENDDATE' => 'checkinenddate',
        'CHECKOUTSTARTDATE' => 'checkoutstartdate',
        'CHECKOUTENDDATE' => 'checkoutenddate',
        'CLOSED' => 'closed',
        'APPROVED' => 'approved',
        'APPROVEDSTARTDATE' => 'approvedstartdate',
        'APPROVEDENDDATE' => 'approvedenddate',
        'COMPLETEDSTARTDATE' => 'completedstartdate',
        'COMPLETEDENDDATE' => 'completedenddate',
        'TASKSTARTDATE' => 'taskstartdate',
        'TASKENDDATE' => 'taskenddate',
        'BILLABLE' => 'billable',
        'COMPLETED' => 'completed',
        'STATUSID' => 'statusid',
        'ISSUETYPE' => 'issuetype',
        'URGENT' => 'urgent',
        'CREATESTARTDATE' => 'createstartdate',
        'CREATEENDDATE' => 'createenddate',
        'CLOSEDSTARTDATE' => 'closedstartdate',
        'CLOSEDENDDATE' => 'closedenddate',
        'TASDRULEID' => 'taskruleid',
        'PROPERTYBOOKINGID' => 'propertybookingid',
        'TASKID' => 'taskid',
        'PAYTYPE' => 'paytype',
        'STAFFID' => 'staffid',
        'STARTDATE' => 'startdate',
        'ENDDATE' => 'enddate',
        'TASKRULEID' => 'taskruleid'
    ];

    const RATE_LIMIT = 300;
    const RATE_LIMIT_TTL = 60;

    const LOCALE = [
        0 => 'eng',
        1 => 'spa',
        2 => 'por',
        3 => 'fre',
        4 => 'jap',
        5 => 'chi'
    ];

    const DAYOFWEEK = [
        1 => 2,
        2 => 3,
        3 => 4,
        4 => 5,
        5 => 6,
        6 => 7,
        7 => 1
    ];
}