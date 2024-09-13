<?php

namespace HPSHUB\Controllers\Admin;

use HPSHUB\Models\UploadModel;

if (!defined('ABSPATH')) {
    exit;
}

class UploadController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_upload_page']);
        add_action('admin_post_hpshub_handle_upload', [__CLASS__, 'handle_upload']);
    }

    public static function add_upload_page() {
        add_submenu_page(
            'hpshub',                    // El slug del menú principal (HPS Hub)
            'Subir Módulos',             // Título de la página
            'Subir Módulos',             // Título del submenú
            'manage_options',            // Capacidad de usuario
            'hpshub-upload',             // Slug del submenú
            [__CLASS__, 'upload_page']    // Función que mostrará la página
        );
    }

    public static function upload_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        include HPSHUB_DIR . 'Views/Admin/Upload/index.php';
    }

    public static function handle_upload() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        check_admin_referer('hpshub_upload_module', 'hpshub_nonce');

        $result = UploadModel::handle_module_upload();

        if ($result['success']) {
            wp_redirect(admin_url('admin.php?page=hpshub-modules&upload=success'));
        } else {
            wp_die($result['message']);
        }
        exit;
    }
}
