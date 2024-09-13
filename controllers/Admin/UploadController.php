<?php

namespace ModuleLoader\Controllers\Admin;

use ModuleLoader\Models\UploadModel;

if (!defined('ABSPATH')) {
    exit;
}

class UploadController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_upload_page']);
        add_action('admin_post_module_loader_handle_upload', [__CLASS__, 'handle_upload']);
    }

    public static function add_upload_page() {
        add_submenu_page(
            'module-loader',
            'Subir Módulos',
            'Subir Módulos',
            'manage_options',
            'module-loader-upload',
            [__CLASS__, 'upload_page']
        );
    }

    public static function upload_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        include MODULE_LOADER_DIR . 'views/admin/upload/index.php';
    }

    public static function handle_upload() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        check_admin_referer('module_loader_upload_module', 'module_loader_nonce');

        $result = UploadModel::handle_module_upload();

        if ($result['success']) {
            wp_redirect(admin_url('admin.php?page=module-loader-modules&upload=success'));
        } else {
            wp_die($result['message']);
        }
        exit;
    }
}
