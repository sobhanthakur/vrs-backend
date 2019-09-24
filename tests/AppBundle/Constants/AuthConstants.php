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
    public const AUTH_INVALID_CLAIM = 'VRS eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6IjM2MDAifQ.eyJDdXN0b21lcklEIjo4MjgsIkxvZ2dlZEluU3RhZmZJRCI6MCwiQ3JlYXRlRGF0ZVRpbWUiOiIyMDE5MDkyNDEzNTcifQ._VG5u0izOdb0KwdjiEr1hoGkaQYq8Y9qUTyRUB9ut6c';

    public const AUTHENTICATION_RESULT = array('message'=>array(
       'CustomerID' => 1,
       'LoggedInStaffID' => 0,
       'CustomerName' => 'John'
    ));
}