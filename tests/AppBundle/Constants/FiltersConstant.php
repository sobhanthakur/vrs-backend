<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 15/10/19
 * Time: 4:09 PM
 */

namespace Tests\AppBundle\Constants;


final class FiltersConstant
{
    const PROPERTY_GROUPS = [
        [
            "PropertyGroupID" => 7,
            "PropertyGroup" => "CCM"
        ],
        [
            "PropertyGroupID" => 20,
            "PropertyGroup" => "DLM"
        ]
    ];

    const REGIONS_GROUPS = [
        [
            "RegionGroupID" => 7,
            "RegionGroup" => "Leavenworth"
        ],
        [
            "RegionGroupID" => 8,
            "RegionGroup" => "Bend"
        ]
    ];

    const OWNERS = [
        [
            "OwnerID" => 1,
            "OwnerName" => "Destination Leavenworth"
        ],
        [
            "OwnerID" => 2,
            "OwnerName" => "Ann Love"
        ]
    ];

    const STAFF_TAGS = [
        [
            "EmployeeGroupID" => 1,
            "EmployeeGroup" => "ABC"
        ],
        [
            "EmployeeGroupID" => 2,
            "EmployeeGroup" => "XYZ"
        ]
    ];

    const DEPARTMENTS = [
        [
            "ServiceGroupID" => 1,
            "ServiceGroup" => "SG1"
        ],
        [
            "ServiceGroupID" => 2,
            "ServiceGroup" => "SG2"
        ]
    ];

    const PROPERTIES = [
        [
            "PropertyID" => 14911,
            "PropertyName" => "1058 Beach (MCA 59)"
        ],
        [
            "PropertyID" => 14912,
            "PropertyName" => "175 Treasure Cove Lane (MCA 522)"
        ]
    ];


}