<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 11/10/19
 * Time: 11:37 AM
 */

namespace Tests\AppBundle\Constants;


final class FileExportConstants
{
    public const INTEGRATION_TO_CUSTOMER = array(
        0 => array(
            'active' => 1,
            'username' => 'VRS5d9dd0b9237e6',
            'qbdsyncbilling' => 1,
            'qbdsyncpayroll' => 1
        )
    );

    public const INTEGRATION_TO_CUSTOMER1 = array(
        0 => array(
            'active' => 0,
            'username' => 'VRS5d9dd0b9237e6',
            'qbdsyncbilling' => 1,
            'qbdsyncpayroll' => 1
        )
    );
}