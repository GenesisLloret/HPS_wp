<?php
if (!defined('ABSPATH')) {exit;}
add_action('admin_post_hps_deactivate_module', 'hps_deactivate_module');
function hps_deactivate_module() {
    if (!isset($_POST['hps_module_nonce']) || !wp_verify_nonce($_POST['hps_module_nonce'], 'hps_deactivate_module')) {wp_die('Invalid nonce');}
    if (!current_user_can('manage_options')) {wp_die('Unauthorized user');}
    update_option('hps_module_active', false);
    wp_redirect(admin_url('admin.php?page=hps-settings&module_deactivated=1'));
    exit;
}
