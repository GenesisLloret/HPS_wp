<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_post_hps_update_wsform', 'hps_update_wsform');
function hps_update_wsform()
{
    // Verify nonce for security
    if (!isset($_POST['hps_wsform_nonce']) || !wp_verify_nonce($_POST['hps_wsform_nonce'], 'hps_update_wsform')) {
        wp_die('Invalid nonce');
    }

    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }

    // Get and sanitize the module name and WSForm ID
    $module_name = sanitize_text_field($_POST['module_name']);
    $wsform_id = sanitize_text_field($_POST['wsform_id']);

    // Save WSForm ID in the database
    update_option('hps_wsform_id_' . sanitize_title($module_name), $wsform_id);

    // Redirect back to the settings page with a success message
    wp_redirect(admin_url('admin.php?page=hps-settings&wsform_updated=1'));
    exit;
}
