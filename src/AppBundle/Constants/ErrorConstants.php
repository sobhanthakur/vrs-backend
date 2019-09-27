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
    const INVALID_AUTH_TOKEN = 'INVALIDAUTHTOKEN';
    const TOKEN_EXPIRED = 'TOKENEXPIRED';

    public static $errorCodeMap = [
        self::INVALID_AUTH_CONTENT => ['code' => 401, 'message' => 'api.response.error.invalid_auth_content'],
        self::AUTHENTICATION_EXPIRY => ['code' => 401, 'message' => 'api.response.error.invalid_authentication'],
        self::INVALID_AUTHORIZATION => ['code' => 403, 'message' => 'api.response.error.request_unauthorized'],
        self::UNPROCESSABLE_AUTH_TOKEN => ['code' => 422, 'message' => 'api.response.error.unprocessable_auth_token'],
        self::RESOURCE_NOT_FOUND => ['code' => 404, 'message' => 'api.response.error.resource_not_found'],
        self::METHOD_NOT_ALLOWED => ['code' => 405, 'message' => 'api.response.error.request_method_not_allowed'],
        self::REQ_TIME_OUT => ['code' => 408, 'message' => 'api.response.error.request_timed_out'],
        self::INTERNAL_ERR => ['code' => 500, 'message' => 'api.response.error.internal_error'],
        self::SERVICER_NOT_FOUND => ['code' => 422, 'message' => 'api.response.error.servicer_not_found'],
        self::BAD_GATEWAY => ['code' => 502, 'message' => 'api.response.error.bad_gateway'],
        self::SERVICE_UNAVAIL => ['code' => 503, 'message' => 'api.response.error.service_unavailable'],
        self::GATEWAY_TIMEOUT => ['code' => 504, 'message' => 'api.response.error.gateway_timeout'],
        self::INVALID_AUTH_TOKEN => ['code' => 401, 'message' => 'api.response.error.invalid_auth_token'],
        self::TOKEN_EXPIRED => ['code' => 401, 'message' => 'api.response.error.auth_token_expired']
    ];
}