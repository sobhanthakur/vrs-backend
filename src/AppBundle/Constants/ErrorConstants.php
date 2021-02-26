<?php
/**
 *  Error Constants file for Storing Error Message codes and Message Text for Application.
 *
 *  @category Constants
 *  @author Sobhan Thakur
 */

namespace AppBundle\Constants;

final class ErrorConstants
{
    const MESSAGE = 'message';
    const INCOMPLETE_REQ = 'INCOMPLETEREQ';
    const INTERNAL_ERR = 'INTERNALERR';
    const INVALID_CONTENT_TYPE = 'INVALIDCONTENTTYPE';
    const INVALID_CONTENT_LENGTH = 'INVALIDCONTENTLEN';
    const INVALID_REQ_DATA = 'INVALIDREQDATA';
    const INVALID_AUTH_CONTENT = 'INVALIDAUTHCONTENT';
    const UNPROCESSABLE_AUTH_TOKEN = 'UNPROCESSABLEAUTHTOKEN';
    const RESOURCE_NOT_FOUND = 'NORESOURCEFOUND';
    const INVALID_AUTHENTICATION = 'INVALIDAUTHENTICATION';
    const INVALID_AUTHORIZATION = 'INVALIDAUTHORIZATION';
    const METHOD_NOT_ALLOWED = 'METHODNOTALLOWED';
    const SERVICER_NOT_FOUND='SERVICERNOTFOUND';
    const INTEGRATION_ALREADY_PRESENT='INTEGRATIONALREADYPRESENT';
    const EMPTY_START_DATE='EMPTYSTARTDATE';
    const EMPTY_QBDSYNCBILLING='EMPTYQBDSYNCBILLING';
    const EMPTY_QBDSYNCTT='EMPTYQBDSYNCTT';
    const EMPTY_PASS='EMPTYPASS';
    const EMPTY_INTEGRATION_ID='EMPTYINTEGRATIONID';
    const BILLING_NOT_ENABLED = 'BILLINGNOTENABLED';
    const CUSTOMER_NOT_FOUND = 'CUSTOMERNOTFOUND';
    const INTEGRATION_NOT_PRESENT = 'INTEGRATIONNOTPRESENT';
    const INACTIVE = 'INACTIVE';
    const INVALID_WAGE_ITEM_ID = 'INVALIDWAGEITEMID';
    const EMPTY_DATA = 'EMPTYDATA';
    const INVALID_PROPERTY_ID = 'INVALIDPROPERTYID';
    const INVALID_INTEGRATIONQBDCUSTOMERID = 'INVALIDINTEGRATIONQBDCUSTOMERID';
    const INVALID_STAFF_ID = 'INVALIDSTAFFID';
    const INVALID_INTEGRATIONQBDEMPLOYEEID = 'INVALIDINTEGRATIONQBDEMPLOYEEID';
    const INVALID_TASKRULE_ID = 'INVALIDTASKRULEID';
    const INVALID_INTEGRATIONQBDITEMID = 'INVALIDINTEGRATIONQBDITEMID';
    const INVALID_TASKID = 'INVALIDTASKID';
    const INVALID_STATUS = 'INVALIDSTATUS';
    const INVALID_TIMEZONE = 'INVALIDTIMEZONE';
    const INVALID_TIMECLOCKDAYSID = 'INVALIDTIMECLOCKDAYSID';
    const INVALID_PAYLOAD='INVALIDPAYLOAD';
    const UNABLE_TO_DELETE = 'UNABLETODELETE';
    const INVALID_AUTHENTICATION_BODY = 'INVALIDAUTHENTICATIONBODY';
    const UNABLE_TO_RESET_BATCH = 'UNABLETORESETBATCH';
    const OAUTH_FAILED = 'OAUTHFAILED';
    const QBO_CONNECTION_ERROR = 'QBOCONNECTIONERROR';

    const REQ_TIME_OUT = 'REQTIMEOUT';
    const SERVICE_UNAVAIL = 'SERVICEUNAVAIL';
    const INVALID_CONTENTMD5 = 'INVALIDCONTENTMD5';
    const INVALID_DATE_TIME = 'INVALIDDATETIME';
    const EMPTY_AUTH_HEADER = 'EMPTYAUTHHEAD';
    const MISSING_AUTH_FIELD = 'MISSINGAUTHFIELD';
    const GATEWAY_TIMEOUT = 'GATEWAYTIMEOUT';
    const BAD_GATEWAY = 'BADGATEWAY';
    const AUTHENTICATION_EXPIRY = 'AUTHENTICATIONEXPIRY';
    const SERVICE_ACCESS_NOT_ALLOWED = 'SERVICENOTALLOWED';
    const INVALID_EMAIL = 'INVALIDEMAIL';
    const INVALID_USERNAME = 'INVALIDUSERNAME';
    const INVALID_AUTH_TOKEN = 'INVALIDAUTHTOKEN';
    const TOKEN_EXPIRED = 'TOKENEXPIRED';
    const INTEGRATION_NOT_ACTIVE = 'INTEGRATIONNOTACTIVE';
    const INVALID_INTEGRATION = 'INVALIDINTEGRATION';
    const INVALID_JSON = 'INVALID_JSON';
    const INVALID_REQUEST = 'INVALID_REQUEST';
    const INVALID_CHECKOUT = 'INVALID_CHECKOUT';
    const LIMIT_EXHAUST = 'LIMIT_EXHAUST';
    const TRY1MINLATER = 'TRY_1_MIN_LATER';

    const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
    const NOTHING_TO_MAP = 'NOTHINGTOMAP';
    const INVALID_PROPERTY_BOOKING_ID = 'INVALID_PROPERTY_BOOKING_ID';
    const INVALID_TIMELOGIN_DETAILS = 'INVALID_TIMELOGIN_DETAILS';



    public static $errorCodeMap = [
        self::INVALID_AUTH_CONTENT => ['code' => 401, self::MESSAGE => 'api.response.error.invalid_auth_content'],
        self::AUTHENTICATION_EXPIRY => ['code' => 401, self::MESSAGE => 'api.response.error.invalid_authentication'],
        self::INVALID_AUTHORIZATION => ['code' => 403, self::MESSAGE => 'api.response.error.request_unauthorized'],
        self::UNPROCESSABLE_AUTH_TOKEN => ['code' => 422, self::MESSAGE => 'api.response.error.unprocessable_auth_token'],
        self::RESOURCE_NOT_FOUND => ['code' => 404, self::MESSAGE => 'api.response.error.resource_not_found'],
        self::METHOD_NOT_ALLOWED => ['code' => 405, self::MESSAGE => 'api.response.error.request_method_not_allowed'],
        self::REQ_TIME_OUT => ['code' => 408, self::MESSAGE => 'api.response.error.request_timed_out'],
        self::INTERNAL_ERR => ['code' => 500, self::MESSAGE => 'api.response.error.internal_error'],
        self::SERVICER_NOT_FOUND => ['code' => 422, self::MESSAGE => 'api.response.error.servicer_not_found'],
        self::BAD_GATEWAY => ['code' => 502, self::MESSAGE => 'api.response.error.bad_gateway'],
        self::SERVICE_UNAVAIL => ['code' => 503, self::MESSAGE => 'api.response.error.service_unavailable'],
        self::GATEWAY_TIMEOUT => ['code' => 504, self::MESSAGE => 'api.response.error.gateway_timeout'],
        self::INVALID_AUTH_TOKEN => ['code' => 401, self::MESSAGE => 'api.response.error.invalid_auth_token'],
        self::TOKEN_EXPIRED => ['code' => 401, self::MESSAGE => 'api.response.error.auth_token_expired'],
        self::INTEGRATION_ALREADY_PRESENT => ['code' => 422, self::MESSAGE => 'api.response.error.integration_already_present'],
        self::INTEGRATION_NOT_ACTIVE => ['code' => 422, self::MESSAGE => 'api.response.error.integration_not_active'],
        self::EMPTY_START_DATE => ['code' => 422, self::MESSAGE => 'api.response.error.empty_start_date'],
        self::EMPTY_QBDSYNCBILLING => ['code' => 422, self::MESSAGE => 'api.response.error.empty_qbd_sync_billing'],
        self::EMPTY_QBDSYNCTT => ['code' => 422, self::MESSAGE => 'api.response.error.empty_qbd_sync_tt'],
        self::EMPTY_PASS => ['code' => 422, self::MESSAGE => 'api.response.error.empty_qbd_password'],
        self::EMPTY_INTEGRATION_ID => ['code' => 422, self::MESSAGE => 'api.response.error.empty_integration_id'],
        self::BILLING_NOT_ENABLED => ['code' => 422, self::MESSAGE => 'api.response.error.billing_not_enabled'],
        self::INVALID_INTEGRATION => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_integration'],
        self::CUSTOMER_NOT_FOUND => ['code' => 422, self::MESSAGE => 'api.response.error.customer_not_found'],
        self::INTEGRATION_NOT_PRESENT => ['code' => 422, self::MESSAGE => 'api.response.error.integration_not_present'],
        self::INACTIVE => ['code' => 1001, self::MESSAGE => 'api.response.error.inactive'],
        self::INVALID_WAGE_ITEM_ID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_wage_item_id'],
        self::EMPTY_DATA => ['code' => 422, self::MESSAGE => 'api.response.error.empty_data'],
        self::INVALID_PROPERTY_ID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_property_id'],
        self::INVALID_INTEGRATIONQBDCUSTOMERID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_integration_qbd_customer_id'],
        self::INVALID_STAFF_ID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_staff_id'],
        self::INVALID_INTEGRATIONQBDEMPLOYEEID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_integration_qbd_employee_id'],
        self::INVALID_TASKRULE_ID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_taskrule_id'],
        self::INVALID_INTEGRATIONQBDITEMID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_integration_qbd_item_id'],
        self::INVALID_TASKID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_task_id'],
        self::INVALID_STATUS => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_status'],
        self::INVALID_TIMEZONE => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_timezone'],
        self::INVALID_TIMECLOCKDAYSID => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_timeclockdaysid'],
        self::INVALID_PAYLOAD => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_payload'],
        self::INVALID_JSON => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_json'],
        self::INVALID_REQUEST => ['code' => 400, self::MESSAGE => 'api.response.error.invalid_request'],
        self::INVALID_CREDENTIALS => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_credential'],
        self::LIMIT_EXHAUST => ['code' => 429, self::MESSAGE => 'api.response.error.limit_exhaust'],
        self::UNABLE_TO_DELETE => ['code' => 422, self::MESSAGE => 'api.response.error.unable_to_delete'],
        self::UNABLE_TO_RESET_BATCH => ['code' => 422, self::MESSAGE => 'api.response.error.unable_to_reset_batch'],
        self::UNABLE_TO_DELETE => ['code' => 422, self::MESSAGE => 'api.response.error.unable_to_delete'],
        self::LIMIT_EXHAUST => ['code' => 429, self::MESSAGE => 'api.response.error.limit_exhaust'],
        self::INVALID_AUTHENTICATION_BODY => ['code' => 401, self::MESSAGE => 'api.response.error.invalid_auth_body'],
        self::OAUTH_FAILED => ['code' => 1002, self::MESSAGE => 'api.response.error.oauth_failed'],
        self::NOTHING_TO_MAP => ['code' => 422, self::MESSAGE => 'api.response.error.nothing_to_map'],
        self::QBO_CONNECTION_ERROR => ['code' => 1002, self::MESSAGE => 'api.response.error.qbo_connection_error'],
        self::NOTHING_TO_MAP => ['code' => 422, self::MESSAGE => 'api.response.error.nothing_to_map'],
        self::UNABLE_TO_RESET_BATCH => ['code' => 422, self::MESSAGE => 'api.response.error.unable_to_reset_batch'],
        self::INVALID_PROPERTY_BOOKING_ID  => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_property_booking'],
        self::INVALID_CHECKOUT  => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_checkout'],
        self::INVALID_TIMELOGIN_DETAILS  => ['code' => 422, self::MESSAGE => 'api.response.error.invalid_timelogin_details'],
        self::TRY1MINLATER  => ['code' => 422, self::MESSAGE => 'api.response.error.try_1_min_later']
    ];
}