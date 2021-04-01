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
        "vrs_qbo_authenticate",
        "vrs_qwd_disconnect",
        "vrs_logs_reset",
        "vrs_qbo_syncresources",
        "vrs_qbo_syncbilling",
        "vrs_qbo_synctimetracking",
        "vrs_staffs_drivetime"
    );

    const TRANSLATION_ROUTES = array(
        "vrs_pwa_translation",
        "vrs_pwa_translation_post",
        "vrs_pwa_translation_get_english",
        "vrs_pwa_translation_locales",
        "vrs_pwa_translation_get",
        "vrs_pwa_translation_put",
        "vrs_pwa_translation_locales_post",
        "vrs_pwa_translation_post_english",
    );

    const PUBLIC_ROUTES = array(
        "properties_get",
        "properties_get_id",
        "owners_get",
        "region_groups_get",
        "regions_get",
        "property_bookings_get",
        "property_bookings_get_id",
        "issues_get",
        "issues_get_id",
        "taskrules_get",
        "task_rules_get_id",
        "staff_get",
        "staff_get_id",
        "tasks_get",
        "tasks_get_id",
        "staff_tasks_get",
        "staff_get_id",
        "staff_tasks_get_id",
        "staff_task_times_get",
        "staff_day_times",
        "property_bookings_post",
        "property_bookings_put",
        "property_bookings_delete",
        "property_statuses_get",
        "tasks_post",
        "property_post",
        "owner_post"
    );

    const PWA_ROUTES = array(
        "vrs_pwa_tasks",
        "vrs_pwa_starttask",
        "vrs_pwa_tabs_log",
        "vrs_pwa_tabs_info",
        "vrs_pwa_tabs_booking",
        "vrs_pwa_tabs_images",
        "vrs_pwa_tabs_assignments",
        "vrs_pwa_tabs_manage",
        "vrs_pwa_issue_post",
        "vrs_pwa_starttask",
        "vrs_pwa_clockinout",
        "vrs_pwa_manage_save",
        "vrs_pwa_task_accept_decline",
        "vrs_pwa_task_changedate",
        "vrs_pwa_manage_submit",
        "vrs_pwa_unscheduled_properties",
        "vrs_pwa_unscheduled_tabs_property",
        "vrs_pwa_unscheduled_tabs_Image",
        "vrs_pwa_unscheduled_tasks",
        "vrs_pwa_unscheduled_tasks_complete",
        "vrs_pwa_upload_image_post",
        "vrs_pwa_issue_post_vendor_owner",
        "vrs_pwa_manage_delete",
        "vrs_pwa_calender_feed_booking_get",
        "vrs_pwa_calender_properties_get"
    );

    const LOCATION_ROUTES = array (
        "vrs_pwa_starttask",
        "vrs_pwa_manage_submit",
        "vrs_pwa_unscheduled_tasks_complete"
    );

    const NO_ERROR_ROUTES = array(
        "vrs_pwa_authenticate",
        "oauth_refresh_get",
        "oauth_login_post",
        "oauth_refresh_post",
        "oauth_validate_post",
        "vrs_pwa_authenticate_issue_form"
    );

    const RESTRICT_ADMIN_ROUTES_MAIL = array(
        "vrs_pwa_unscheduled_tasks_complete",
        "vrs_pwa_upload_image_post",
        "vrs_pwa_manage_submit",
        "vrs_pwa_issue_post"
    );

    const RESTRICT_RESPONSE_LOGS = array(
        "vrs_pwa_tasks",
        "vrs_pwa_tabs_manage"
    );

    const SMS_ROUTES = array(
        "vrs_aws_sns"
    );

    const NO_ERROR_MESSAGES = array(
        "NOTHINGTOMAP",
        "INACTIVE"
    );
}