<?php

namespace HPS_Hub\Controllers\Admin;

use HPS_Hub\Models\UploadModel;

if (!defined('ABSPATH')) {
    exit;
}

class UploadController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_upload_page']);
        add_action('admin_post_hps_hub_handle_upload', [__CLASS__, 'handle_upload']);
    }

    public static function add_upload_page() {
        add_submenu_page(
            'hps-hub',
            'Subir Extensiones',
            'Subir Extensiones',
            'manage_options',
            'hps-hub-upload',
            [__CLASS__, 'upload_page']
        );
    }

    public static function upload_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        // Cargar la vista
        include HPS_HUB_PLUGIN_DIR . 'views/admin/upload/index.php';
    }

    public static function handle_upload() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        if (!isset($_POST['hps_hub_upload_nonce_field']) || !wp_verify_nonce($_POST['hps_hub_upload_nonce_field'], 'hps_hub_upload_nonce')) {
            wp_die('Fallo de seguridad. No se pudo verificar el nonce.');
        }

        $result = UploadModel::handle_extension_upload();

        if ($result['success']) {
            wp_redirect(admin_url('admin.php?page=hps-hub-upload&message=upload_success'));
        } else {
            wp_die($result['message']);
        }
        exit;
    }
}
