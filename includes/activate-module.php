<?php
if (!defined('ABSPATH')) {exit;}
add_action('admin_post_hps_activate_module', 'hps_activate_module');
function hps_activate_module() {
    if (!isset($_POST['hps_module_nonce']) || !wp_verify_nonce($_POST['hps_module_nonce'], 'hps_activate_module')) {
        wp_die('Invalid nonce');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }

    // Load module settings from the module file
    $module_file = plugin_dir_path(__FILE__) . '../mods/example-module.php';
    if (file_exists($module_file)) {
        include $module_file; // This will load the $mod_* variables
    }

    // Save module settings to the database, overriding defaults if they exist
    $module_settings = [
        'mod_name' => $mod_name,
        'mod_version' => $mod_version,
        'mod_description' => $mod_description,
        'mod_author' => $mod_author,
        'mod_always_enabled' => $mod_always_enabled,
        'mod_menu' => $mod_menu,
        'mod_has_options' => $mod_has_options,
        'mod_WSForm_moderation' => $mod_WSForm_moderation
    ];

    update_option('hps_module_' . sanitize_title($mod_name), $module_settings);

    // Redirect after saving
    wp_redirect(admin_url('admin.php?page=hps-settings&module_activated=1'));
    exit;
}


