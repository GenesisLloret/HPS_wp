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
        wp_enqueue_style('hpshub-admin-css', HPSHUB_URL . 'Assets/css/admin.css', [], HPSHUB_VERSION);
        wp_enqueue_script('hpshub-admin-js', HPSHUB_URL . 'Assets/js/admin.js', ['jquery'], HPSHUB_VERSION, true);
    }
}
