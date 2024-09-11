<?php
/*
Plugin Name: Hotel Parking Service
Description: A plugin to manage hotel parking services.
Version: 0.1.13
Author: Genesis Lloret Ramos
Text Domain: hps-hub
*/
if (!defined('ABSPATH')) {exit;}
define('HPS_HUB_VERSION', '1.0');
define('HPS_HUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HPS_HUB_PLUGIN_URL', plugin_dir_url(__FILE__));
require_once HPS_HUB_PLUGIN_DIR . 'admin/menu.php';
require_once HPS_HUB_PLUGIN_DIR . 'admin/upload.php';
require_once HPS_HUB_PLUGIN_DIR . 'admin/extensions.php';
require_once HPS_HUB_PLUGIN_DIR . 'admin/settings.php';
function hps_hub_activate() {
    $config_file = HPS_HUB_PLUGIN_DIR . 'admin/config.json';
    if (!file_exists($config_file)) {
        $default_config = json_encode(['extensions' => []], JSON_PRETTY_PRINT);
        file_put_contents($config_file, $default_config);
    }
}
register_activation_hook(__FILE__, 'hps_hub_activate');
function hps_hub_init() {
    if (is_admin()) {
        wp_enqueue_style('hps-hub-admin-css', HPS_HUB_PLUGIN_URL . 'assets/css/admin.css', [], HPS_HUB_VERSION);
        wp_enqueue_script('hps-hub-admin-js', HPS_HUB_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], HPS_HUB_VERSION, true);
    }
    if (class_exists('HPS_Hub_Menu')) {HPS_Hub_Menu::init();}
    if (class_exists('HPS_Hub_Upload')) {HPS_Hub_Upload::init();}
    if (class_exists('HPS_Hub_Extensions')) {HPS_Hub_Extensions::init();}
    if (class_exists('HPS_Hub_Settings')) {HPS_Hub_Settings::init();}
}
add_action('plugins_loaded', 'hps_hub_init');