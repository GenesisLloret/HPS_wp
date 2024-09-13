<?php

namespace HPS_Hub\Controllers\Admin;

use HPS_Hub\Models\SettingsModel;

if (!defined('ABSPATH')) {
    exit;
}

class SettingsController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_settings_page() {
        add_submenu_page(
            'hps-hub',
            'Configuraciones',
            'Configuraciones',
            'manage_options',
            'hps-hub-settings',
            [__CLASS__, 'settings_page']
        );
    }

    public static function settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        // Cargar la vista
        include HPS_HUB_PLUGIN_DIR . 'views/admin/settings/index.php';
    }

    public static function register_settings() {
        SettingsModel::register_settings();
    }
}
