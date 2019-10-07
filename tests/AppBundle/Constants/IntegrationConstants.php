<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 27/9/19
 * Time: 6:11 PM
 */

namespace Tests\AppBundle\Constants;


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
}