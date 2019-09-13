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
    const INVALID_PASS = 'INVALIDPASS';
    const INVALID_CRED = 'INVALIDCRED';
    const INVALID_AUTH_TOKEN = 'INVALIDAUTHTOKEN';
    const TOKEN_EXPIRED = 'TOKENEXPIRED';
    const USERNAME_EXISTS = 'USERNAMEPREEXIST';
    const INVALID_REFRESH_TOKEN = 'INVALIDREFRESHTOKEN';
    const EXPIRED_REFRESH_TOKEN = 'EXPIREDREFRESHTOKEN';

    public static $errorCodeMap = [
        self::INVALID_AUTH_CONTENT => ['code' => '401', 'message' => 'api.response.error.invalid_auth_content'],
        self::AUTHENTICATION_EXPIRY => ['code' => '401', 'message' => 'api.response.error.invalid_authentication'],
        self::INVALID_AUTHORIZATION => ['code' => '403', 'message' => 'api.response.error.request_unauthorized'],
        self::UNPROCESSABLE_AUTH_TOKEN => ['code' => '422', 'message' => 'api.response.error.unprocessable_auth_token'],
        self::RESOURCE_NOT_FOUND => ['code' => '404', 'message' => 'api.response.error.resource_not_found'],
        self::METHOD_NOT_ALLOWED => ['code' => '405', 'message' => 'api.response.error.request_method_not_allowed'],
        self::REQ_TIME_OUT => ['code' => '408', 'message' => 'api.response.error.request_timed_out'],
        self::INTERNAL_ERR => ['code' => '500', 'message' => 'api.response.error.internal_error'],
        self::SERVICER_NOT_FOUND => ['code' => '422', 'message' => 'api.response.error.servicer_not_found'],
        self::BAD_GATEWAY => ['code' => '502', 'message' => 'api.response.error.bad_gateway'],
        self::SERVICE_UNAVAIL => ['code' => '503', 'message' => 'api.response.error.service_unavailable'],
        self::GATEWAY_TIMEOUT => ['code' => '504', 'message' => 'api.response.error.gateway_timeout'],
        self::INVALID_AUTHENTICATION => ['code' => '1001', 'message' => 'api.response.error.invalid_auth_fields'],
        self::INCOMPLETE_REQ => ['code' => '1002', 'message' => 'api.response.error.incomplete_req'],
        self::INVALID_REQ_DATA => ['code' => '1003', 'message' => 'api.response.error.invalid_request_data'],
        self::INVALID_DATE_TIME => ['code' => '1007', 'message' => 'api.response.error.invalid_date_time'],
        self::INVALID_CONTENT_TYPE => ['code' => '1008', 'message' => 'api.response.error.invalid_content_type'],
        self::INVALID_CONTENT_LENGTH => ['code' => '1009', 'message' => 'api.response.error.invalid_content_length'],
        self::EMPTY_AUTH_HEADER => ['code' => '1011', 'message' => 'api.response.error.empty_auth_header'],
        self::MISSING_AUTH_FIELD => ['code' => '1013', 'message' => 'api.response.error.empty_auth_fields'],
        self::SERVICE_ACCESS_NOT_ALLOWED => ['code' => '1019', 'message' => 'api.response.error.service_not_allowed'],
        self::INVALID_EMAIL => ['code' => '1024', 'message' => 'api.response.error.invalid_email'],
        self::INVALID_USERNAME => ['code' => '1034', 'message' => 'api.response.error.invalid_username'],
        self::INVALID_CRED => ['code' => '1035', 'message' => 'api.response.error.invalid_credentials'],
        self::INVALID_AUTH_TOKEN => ['code' => '401', 'message' => 'api.response.error.invalid_auth_token'],
        self::TOKEN_EXPIRED => ['code' => '401', 'message' => 'api.response.error.auth_token_expired'],
        self::USERNAME_EXISTS => ['code' => '1040', 'message' => 'api.response.error.username_exists'],
        self::INVALID_REFRESH_TOKEN => ['code' => '1042', 'message' => 'api.response.error.invalid_refresh_token'],
        self::EXPIRED_REFRESH_TOKEN => ['code' => '1043', 'message' => 'api.response.error.expired_refresh_token'],
    ];
}