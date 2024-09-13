<?php

namespace HPSHUB\Includes\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Assets {
    public static function init() {
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function enqueue_assets() {
        wp_enqueue_style('module-loader-admin-css', MODULE_LOADER_URL . 'Assets/css/admin.css', [], MODULE_LOADER_VERSION);
        wp_enqueue_script('module-loader-admin-js', MODULE_LOADER_URL . 'Assets/js/admin.js', ['jquery'], MODULE_LOADER_VERSION, true);
    }
}
