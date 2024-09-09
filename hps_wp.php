<?php
/*
Plugin Name: Hotel Parking Service
Description: A plugin to manage hotel parking services.
Version: 0.1.10
Author: Genesis Lloret Ramos
*/
if (!defined('ABSPATH')) { exit; }
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/activate-module.php';
require_once plugin_dir_path(__FILE__) . 'includes/deactivate-module.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
add_action('admin_menu', 'hps_register_admin_page');
function hps_register_admin_page() {
    add_menu_page(
        __('HPS Settings', 'hps_wp'),
        __('HPS Settings', 'hps_wp'),
        'manage_options',
        'hps-settings',
        'hps_render_admin_page',
        'dashicons-admin-tools'
    );
}
function hps_load_modules() {
    $mods_dir = plugin_dir_path(__FILE__) . 'mods/';
    $module_files = glob($mods_dir . '*.php');
    foreach ($module_files as $module_file) {
        include $module_file;
        error_log('Loading module: ' . $mod_name);
        $existing_settings = get_option('hps_module_' . sanitize_title($mod_name), false);
        if (!$existing_settings) {
            $module_settings = [
                'mod_name' => $mod_name,
                'mod_version' => $mod_version,
                'mod_description' => $mod_description,
                'mod_author' => $mod_author,
                'mod_always_enabled' => $mod_always_enabled,
                'mod_menu' => $mod_menu,
                'mod_has_options' => $mod_has_options,
                'mod_WSForm_moderation' => $mod_WSForm_moderation,
            ];
            update_option('hps_module_' . sanitize_title($mod_name), $module_settings);
        }
    }
}
register_activation_hook(__FILE__, 'hps_plugin_activate');
function hps_plugin_activate() {
    hps_create_module_table();
    hps_load_modules();
}
register_deactivation_hook(__FILE__, 'hps_plugin_deactivate');
function hps_plugin_deactivate() {
    $mods_dir = plugin_dir_path(__FILE__) . 'mods/';
    $module_files = glob($mods_dir . '*.php');
    foreach ($module_files as $module_file) {
        include $module_file;
        delete_option('hps_module_' . sanitize_title($mod_name));
    }
}
register_activation_hook(__FILE__, 'hps_create_module_table');

function hps_create_module_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'hps_modules';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        mod_name varchar(100) NOT NULL,
        mod_version varchar(10) NOT NULL,
        mod_description text NOT NULL,
        mod_author varchar(100) NOT NULL,
        mod_always_enabled tinyint(1) NOT NULL,
        mod_menu tinyint(1) NOT NULL,
        mod_has_options text NOT NULL,
        mod_WSForm_moderation tinyint(1) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
hps_load_modules();