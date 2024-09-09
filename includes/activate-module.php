<?php
if (!defined('ABSPATH')) {exit;}
add_action('admin_post_hps_activate_module', 'hps_activate_module');
function hps_activate_module() {
    if (!isset($_POST['hps_module_nonce']) || !wp_verify_nonce($_POST['hps_module_nonce'], 'hps_activate_module')) {wp_die('Invalid nonce');}
    if (!current_user_can('manage_options')) {wp_die('Unauthorized user');}
    $module_file = plugin_dir_path(__FILE__) . '../mods/example-module.php';
    if (file_exists($module_file)) {include $module_file; // This will load the $mod_* variables
    }

    if (!isset($_POST['module_name']) || !isset($_POST['module_active'])) {
        wp_send_json_error(['message' => 'Invalid request']);
    }
    
    $module_name = sanitize_text_field($_POST['module_name']);
    $module_active = intval($_POST['module_active']);
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
    // Update the module's active status in the database
    update_option('hps_module_' . $module_name . '_active', $module_active);
    // Redirect after saving
    wp_redirect(admin_url('admin.php?page=hps-settings&module_activated=1'));
    exit;
    
}


