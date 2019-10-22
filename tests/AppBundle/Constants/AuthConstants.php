<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/9/19
 * Time: 3:46 PM
 */

namespace Tests\AppBundle\Constants;


final class AuthConstants
{
    public const AUTH_TOKEN_STAFFID_NULL = 'VRS eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6IjMxNTM2MDAwIn0.eyJDdXN0b21lcklEIjo4MjgsIkxvZ2dlZEluU3RhZmZJRCI6MCwiQ3VzdG9tZXJOYW1lIjoiQW1pdGFiaCBQYXRuYWlrIiwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDE5MDkxMzEwNTUifQ.x_emQ0uZWEAsZJyGoiHinDDXHRnu4l1w79gKKvdDzYk';
    public const AUTH_TOKEN_EXPIRED = 'VRS eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImV4cCI6IjEyMDAifQ.eyJDdXN0b21lcklEIjo4MjgsIkxvZ2dlZEluU3RhZmZJRCI6MCwiQ3VzdG9tZXJOYW1lIjoiQW1pdGFiaCBQYXRuYWlrIiwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDE5MDkxMzA5NTkifQ.hFQhBoHRmfnt-9xpvOLE6UgRaOn2s_NaD1hZ6PalAUA';
    public const AUTH_TOKEN_INVALID = 'VR eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImV4cCI6IjEyMDAifQ.eyJDdXN0b21lcklEIjo4MjgsIkxvZ2dlZEluU3RhZmZJRCI6MCwiQ3VzdG9tZXJOYW1lIjoiQW1pdGFiaCBQYXRuYWlrIiwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDE5MDkxMzA5NTkifQ.hFQhBoHRmfnt-9xpvOLE6UgRaOn2s_NaD1hZ6PalAUA';
    public const AUTH_TOKEN_INVALID_SIGNATURE = 'VRS eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6IjM2MDAifQ.eyJDdXN0b21lcklEIjo4MjgsIkxvZ2dlZEluU3RhZmZJRCI6MCwiQ3VzdG9tZXJOYW1lIjoiQW1pdGFiaCBQYXRuYWlrIiwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDE5MDkyNDEzNTMifQ.Nl3K4pdt1O7p-IWBK3h-i5RzVls8We1U1Pl15RY2vAk';
    public const AUTH_INVALID_CLAIM = 'VRS eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6IjMxNTM2MDAwIn0.eyJDdXN0b21lcklEIjo4Mjh9.vOu9hWPIODR2A-YNd85EbGdxUcuDkcikebvTr8QhAio';

    public const AUTHENTICATION_RESULT = array('message'=>array(
       'CustomerID' => 1,
       'LoggedInStaffID' => 0,
       'CustomerName' => 'John'
    ));

    public const AUTHENTICATION_RESULT_RESTRICTIONS = array(
        'status' => true,
            'message'=>array(
                'CustomerID' => 1,
                'LoggedInStaffID' => 1,
                'CustomerName' => 'John Doe'
    ));

    public const AUTHENTICATION_RESULT_RESTRICTIONS1 = array(
        'status' => true,
        'message'=>array(
            'CustomerID' => 1,
            'LoggedInStaffID' => 0,
            'CustomerName' => 'John Doe'
        ));

    public const MOCK_CUSTOMERS = array(
        'piecepay' => 1,
        'icaladdon' => 1
    );

    public const MOCK_SERVICERS = array(
        'allowadminaccess' => 0,
        'allowmanage'=> 0,
        'allowreports' => 0,
        'allowsetupaccess' => 0,
        'allowaccountaccess' => 0,
        'allowissuesaccess' => 0,
        'allowquickreports' => 0,
        'allowscheduleaccess' => 0,
        'allowmastercalendar' => 0,
        'allowtracking' => 1
    );

    public const MOCK_TIME_TRACKING = array(
      'timetracking' => 1
    );

    public const MOCK_PROPERTY_GROUPS = array(
        'PropertyGroupID' => 1,
        'PropertyGroup' => 'CCM'
    );

    public const MOCK_REGION_GROUPS = array(
        'RegionGroupID' => 1,
        'RegionGroup' => 'Leavenworth'
    );

    const CUSTOMER = [
        0 => array(
            'customerid' => 1
        )
    ];
}