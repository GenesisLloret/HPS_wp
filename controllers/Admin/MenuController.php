<?php

namespace ModuleLoader\Controllers\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class MenuController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
    }

    public static function add_admin_menu() {
        add_menu_page(
            'Module Loader',
            'Module Loader',
            'manage_options',
            'module-loader',
            [__CLASS__, 'dashboard_page'],
            'dashicons-admin-plugins',
            6
        );
    }

    public static function dashboard_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        echo '<div class="wrap">';
        echo '<h1>Module Loader</h1>';
        echo '<p>Bienvenido al cargador de módulos.</p>';
        echo '</div>';
    }
}
