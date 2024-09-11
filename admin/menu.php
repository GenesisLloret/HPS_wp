<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Hub_Menu {
    public static function init() {add_action('admin_menu', [__CLASS__, 'add_admin_menu']);}
    public static function add_admin_menu() {
        add_menu_page(
            __('HPS Hub', 'hps-hub'),
            __('HPS Hub', 'hps-hub'),
            'manage_options',
            'hps-hub',
            [__CLASS__, 'admin_page'],
            'dashicons-admin-generic',
            6
        );
        add_submenu_page(
            'hps-hub',
            __('Configuraciones', 'hps-hub'),
            __('Configuraciones', 'hps-hub'),
            'manage_options',
            'hps-hub-settings',
            [__CLASS__, 'settings_page']
        );
    }
    public static function admin_page() {
        echo '<div class="wrap">';
        echo '<h1>' . __('HPS Hub', 'hps-hub') . '</h1>';
        echo '<p>' . __('Gestión de extensiones y configuración del plugin.', 'hps-hub') . '</p>';
        echo '</div>';
    }
    public static function settings_page() {
        echo '<div class="wrap">';
        echo '<h1>' . __('Configuraciones de HPS Hub', 'hps-hub') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('hps-hub-settings-group');
        do_settings_sections('hps-hub-settings');
        submit_button();
        echo '</form>';
        echo '</div>';
    }
}