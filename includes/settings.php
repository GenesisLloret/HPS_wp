<?php
if (!defined('ABSPATH')) {exit;}
add_action('admin_post_hps_save_settings', 'hps_save_settings');
function hps_save_settings() {
    if (!isset($_POST['hps_module_nonce']) || !wp_verify_nonce($_POST['hps_module_nonce'], 'hps_save_settings')) {wp_die('Invalid nonce');}
    if (!current_user_can('manage_options')) {wp_die('Unauthorized user');}
    if (isset($_POST['google_maps_api_key'])) {update_option('hps_google_maps_api_key', sanitize_text_field($_POST['google_maps_api_key']));}
    wp_redirect(admin_url('admin.php?page=hps-settings&settings_updated=1'));
    exit;
}
