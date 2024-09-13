<?php

namespace HPS_Hub\Includes\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Assets {
    public static function init() {
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function enqueue_assets() {
        wp_enqueue_style('hps-hub-admin-css', HPS_HUB_PLUGIN_URL . 'assets/css/admin.css', [], HPS_HUB_VERSION);
        wp_enqueue_script('hps-hub-admin-js', HPS_HUB_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], HPS_HUB_VERSION, true);
    }
}
