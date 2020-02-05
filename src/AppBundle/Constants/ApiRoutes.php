<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 26/9/19
 * Time: 4:16 PM
 */

namespace AppBundle\Constants;


final class ApiRoutes
{
    const ROUTES = array(
        "oauth_refresh_post",
        "vrs_integrations_get",
        "vrs_qwc_register_put",
        "vrs_qwc_file_export",
        "vrs_propertytags",
        "vrs_regions",
        "vrs_owners",
        "vrs_stafftags",
        "vrs_departments",
        "vrs_property",
        "vrs_properties",
        "vrs_qbdcustomers",
        "vrs_qbdemployees",
        "vrs_staffs",
        "vrs_task_rules",
        "vrs_qbditems",
        "vrs_qbd_wageitem",
        "vrs_put_wageitem",
        "vrs_get_wageitem",
        "vrs_tasks",
        "vrs_qbdcustomers_map",
        "vrs_qbdemployees_map",
        "vrs_qbditems_map",
        "vrs_qbdbilling_approve",
        "vrs_staff",
        "vrs_timeclockdays",
        "vrs_qbdtimetracking_approve",
        "vrs_synclogs",
        "vrs_synclogs_batch",
        "vrs_qwd_disconnect",
        "vrs_logs_reset"
    );

    const PUBLIC_ROUTES = array(
        "properties_get",
        "properties_get_id",
        "owners_get",
        "region_groups_get",
        "regions_get",
        "property_bookings_get",
        "property_bookings_get_id"
    );
}