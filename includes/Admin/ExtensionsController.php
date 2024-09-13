<?php

namespace HPS_Hub\Controllers\Admin;

use HPS_Hub\Models\ExtensionModel;
use HPS_Hub\Includes\Core\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

class ExtensionsController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_extensions_page']);
        add_action('admin_post_hps_hub_toggle_extension', [__CLASS__, 'toggle_extension']);
        self::load_extensions();
    }

    public static function add_extensions_page() {
        add_submenu_page(
            'hps-hub',
            'Extensiones',
            'Extensiones',
            'manage_options',
            'hps-hub-extensions',
            [__CLASS__, 'extensions_page']
        );
    }

    public static function load_extensions() {
        $extensions = ExtensionModel::get_active_extensions();
        foreach ($extensions as $extension) {
            $extension_path = HPS_HUB_PLUGIN_DIR . 'exts/' . $extension['slug'] . '/index.php';
            if (file_exists($extension_path)) {
                include_once $extension_path;
            }
        }
    }

    public static function extensions_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        $extensions = ExtensionModel::get_all_extensions();

        // Cargar la vista
        include HPS_HUB_PLUGIN_DIR . 'views/admin/extensions/index.php';
    }

    public static function toggle_extension() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        if (!isset($_POST['hps_hub_nonce_field']) || !wp_verify_nonce($_POST['hps_hub_nonce_field'], 'hps_hub_toggle_extension')) {
            wp_die('Fallo de seguridad. No se pudo verificar el nonce.');
        }

        $extension_slug = isset($_POST['extension_slug']) ? sanitize_text_field($_POST['extension_slug']) : '';

        ExtensionModel::toggle_extension($extension_slug);

        wp_redirect(admin_url('admin.php?page=hps-hub-extensions&message=extension_toggled'));
        exit;
    }
}
