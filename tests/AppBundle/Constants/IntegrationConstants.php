<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 6:11 PM
 */

namespace Tests\AppBundle\Constants;


use AppBundle\Constants\GeneralConstants;

class IntegrationConstants
{
    public const AUTHENTICATION_RESULT = array(
        'message' => array(
            'CustomerID' => 1,
            'LoggedInStaffID' => 1,
            'CustomerName' => 'John Doe'
        )
    );

    public const MOCK_INTEGRATIONS = array(
        'Integration' => 'QuickBooks Enterprise',
        'Logo' => 'https://example.com/abc.jpg'
    );

    public const MOCK_INTEGRATIONS_TO_CUSTOMERS = array(
        'active' => 1,
        'createdate' => '2019-09-27T07:05:16+05:30',
        'qbdsyncbilling' => 1,
        'qbdsyncpayroll' => 0,
        'integrationid' => null,
        'startdate' => '2019-09-27T07:05:16+05:30'
    );

    public const MOCK_CONTENT = array(
        GeneralConstants::START_DATE => "2019-12-30",
        GeneralConstants::PASS => "Sobhan",
        GeneralConstants::QBDSYNCBILLING => true,
        GeneralConstants::QBDSYNCTT => true,
        GeneralConstants::INTEGRATION_ID => 1
    );

    public const START_DATE_MISSING = array(
        GeneralConstants::PASS => "John",
        GeneralConstants::QBDSYNCBILLING => true,
        GeneralConstants::QBDSYNCTT => true,
        GeneralConstants::INTEGRATION_ID => 1
    );

    public const SYNC_BILLING_MISSING = array(
        GeneralConstants::START_DATE => "2019-12-30",
        GeneralConstants::PASS => "Sobhan",
        GeneralConstants::QBDSYNCTT => true,
        GeneralConstants::INTEGRATION_ID => 1
    );

    public const SYNC_TIMETRACKING_MISSING = array(
        GeneralConstants::START_DATE => "2019-12-30",
        GeneralConstants::PASS => "Sobhan",
        GeneralConstants::QBDSYNCBILLING => true,
        GeneralConstants::INTEGRATION_ID => 1
    );

    public const PASSWORD_MISSING = array(
        GeneralConstants::START_DATE => "2019-12-30",
        GeneralConstants::QBDSYNCBILLING => true,
        GeneralConstants::QBDSYNCTT => true,
        GeneralConstants::INTEGRATION_ID => 1
    );

    public const INTEGRATIONID_MISSING = array(
        GeneralConstants::START_DATE => "2019-12-30",
        GeneralConstants::PASS => "Sobhan",
        GeneralConstants::QBDSYNCBILLING => true,
        GeneralConstants::QBDSYNCTT => true
    );



}