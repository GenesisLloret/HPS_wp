<?php

namespace HPS_Hub\Controllers\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class MenuController {
    /**
     * Inicializa el controlador del menú.
     */
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
    }

    /**
     * Añade el menú principal del plugin en el panel de administración.
     */
    public static function add_admin_menu() {
        add_menu_page(
            'HPS Hub',
            'HPS Hub',
            'manage_options',
            'hps-hub',
            [__CLASS__, 'admin_page'],
            'dashicons-admin-generic',
            6
        );
    }

    /**
     * Muestra la página principal del plugin en el panel de administración.
     */
    public static function admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        // Aquí podrías cargar una vista si es necesario
        echo '<div class="wrap">';
        echo '<h1>HPS Hub</h1>';
        echo '<p>Gestión de extensiones y configuración del plugin.</p>';
        echo '</div>';
    }
}
