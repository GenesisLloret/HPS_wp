<?php
/*
Plugin Name: Hotel Parking Service
Plugin URI:  https://hotelparkingservice.es/
Description: Plugin Hotel Parking Service with tracking and geolocation
Version: 0.1.1 Alpha
Author: Génesis Lloret Ramos
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-hps-plugin.php';

function hps_init_plugin() {
    new HPS_Plugin();
}

add_action('plugins_loaded', 'hps_init_plugin');
register_activation_hook(__FILE__, ['HPS_Plugin', 'activate']);
