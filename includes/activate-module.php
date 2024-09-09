<?php

add_action('admin_post_hps_activate_module', 'hps_activate_module');

function hps_activate_module() {
    if (!isset($_POST['hps_module_nonce']) || !wp_verify_nonce($_POST['hps_module_nonce'], 'hps_activate_module')) {wp_die('Invalid nonce, operation not allowed.');}
    if (!current_user_can('manage_options')) {wp_die('Unauthorized user');}
    update_option('hps_module_active', true);
    wp_redirect(admin_url('admin.php?page=hps-settings&module_activated=1'));
    exit;
}
