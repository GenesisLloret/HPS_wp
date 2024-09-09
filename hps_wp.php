<?php
/*
Plugin Name: Hotel Parking Service
Description: A plugin to manage hotel parking services.
Version: 0.1.5
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
function hps_plugin_activate() {hps_load_modules();}
register_deactivation_hook(__FILE__, 'hps_plugin_deactivate');
function hps_plugin_deactivate() {
    $mods_dir = plugin_dir_path(__FILE__) . 'mods/';
    $module_files = glob($mods_dir . '*.php');
    foreach ($module_files as $module_file) {
        include $module_file;
        delete_option('hps_module_' . sanitize_title($mod_name));
    }
}
hps_load_modules();