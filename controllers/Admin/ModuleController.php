<?php

namespace HPSHUB\Controllers\Admin;

use HPSHUB\Models\ModuleModel;

if (!defined('ABSPATH')) {
    exit;
}

class ModuleController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_modules_page']);
        add_action('admin_post_hpshub_manage_module', [__CLASS__, 'manage_module']);
        self::load_modules();
    }

    public static function add_modules_page() {
        add_submenu_page(
            'hpshub',
            'Gestión de Módulos',
            'Módulos',
            'manage_options',
            'hpshub-modules',
            [__CLASS__, 'modules_page']
        );
    }

    public static function load_modules() {
        $active_modules = ModuleModel::get_active_modules();
        foreach ($active_modules as $module_slug) {
            // Intenta cargar 'init.php'
            $module_init_file = HPSHUB_DIR . 'Modules/' . $module_slug . '/init.php';
            if (!file_exists($module_init_file)) {
                // Si 'init.php' no existe, intenta cargar 'index.php'
                $module_init_file = HPSHUB_DIR . 'Modules/' . $module_slug . '/index.php';
            }

            if (file_exists($module_init_file)) {
                include_once $module_init_file;
            } else {
                error_log("No se encontró el archivo de inicialización para el módulo: " . $module_slug);
            }
        }
    }

    public static function modules_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        $modules = ModuleModel::get_all_modules();

        // Cargar la vista
        include HPSHUB_DIR . 'Views/Admin/Modules/index.php';
    }

    public static function manage_module() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        check_admin_referer('hpshub_manage_module', 'hpshub_nonce');

        $module_slug = isset($_POST['module_slug']) ? sanitize_text_field($_POST['module_slug']) : '';
        $action = isset($_POST['module_action']) ? sanitize_text_field($_POST['module_action']) : '';

        if ($module_slug && $action) {
            if ($action === 'activate') {
                ModuleModel::activate_module($module_slug);
                $message = 'Módulo activado correctamente.';
            } elseif ($action === 'deactivate') {
                ModuleModel::deactivate_module($module_slug);
                $message = 'Módulo desactivado correctamente.';
            } elseif ($action === 'delete') {
                ModuleModel::delete_module($module_slug);
                $message = 'Módulo eliminado correctamente.';
            }
            wp_redirect(admin_url('admin.php?page=hpshub-modules&message=' . urlencode($message)));
            exit;
        }

        wp_redirect(admin_url('admin.php?page=hpshub-modules'));
        exit;
    }
}