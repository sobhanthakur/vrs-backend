<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 24/10/19
 * Time: 12:20 PM
 */

namespace Tests\AppBundle\Constants;


class TimeTrackingConstants
{
    public const QBDEMPLOYEES = [
        [
            'IntegrationQBDEmployeeID' => 1,
            'QBDEmployeeFullName' => "Employee1"
        ]
    ];

    public const FILTERS = array (
        'IntegrationID' => 1,
        'Filters' =>
            array (
                'StaffTag' => [1],
                'Department' => [1],
                'Status' => ["Matched"],
                'CreateDate' =>
                    array (
                        'From' => '2018-09-09',
                        'To' => '2018-09-09',
                    ),
            ),
        'Pagination' =>
            array (
                'Offset' => 1,
                'Limit' => 10,
            ),
    );

    public const SERVICERS_MATCHED = array
    (
        0 => array(
            1 => 1
        )
    );

    public const MATCHED = array
    (
        0 => array
        (
            1 => 1
        ),

        1 => array
        (
            1 => 594
        )
    );

    public const SERVICERS = [
        [
            "StaffID" => 1,
            "StaffName" => "Jeb Butler",
            "ServicerAbbreviation" => "JB"
        ]
    ];

}