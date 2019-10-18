<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 18/10/19
 * Time: 2:43 PM
 */

namespace Tests\AppBundle\Constants;

final class BillingConstants
{
    public const PROPERTIES_MATCH = [1,2,3,4,5];

    public const FILTERS = array (
        'Filters' =>
            array (
                'Status' =>
                    array (
                        0 => 'Not Yet Matched',
                    ),
                'PropertyTag' =>
                    array (
                        0 => 1,
                        1 => 2,
                        2 => 3
                    ),
                'Region' =>
                    array (
                        0 => 10,
                        1 => 12
                    ),
                'Owner' =>
                    array (
                        0 => 20,
                        1 => 11
                    ),
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

    public const PROPERTIES = [
        [
            "PropertyID" => 2,
            "PropertyName" => "DL Ahren's Haus",
            "PropertyAbbreviation" => "DLAH",
            "RegionName" => "Leavenworth",
            "OwnerName" => "Destination Leavenworth"
        ],
        [
            "PropertyID" => 3,
            "PropertyName" => "DL Arrowhead Lodge",
            "PropertyAbbreviation" => "DLAL",
            "RegionName" => "Plain",
            "OwnerName" => "Destination Leavenworth"
        ]
    ];
}