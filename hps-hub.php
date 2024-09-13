<?php
/*
Plugin Name: Hotel Parking Service
Description: Un plugin para gestionar servicios de estacionamiento en hoteles.
Version: 0.2.1
Author: Genesis Lloret Ramos
*/
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('MODULE_LOADER_VERSION', '0.2.1');
define('MODULE_LOADER_DIR', plugin_dir_path(__FILE__));
define('MODULE_LOADER_URL', plugin_dir_url(__FILE__));

// Registrar función de autoload
spl_autoload_register('module_loader_autoload');
function module_loader_autoload($class) {
    if (strpos($class, 'ModuleLoader\\') === 0) {
        $relative_class = substr($class, strlen('ModuleLoader\\'));
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
        $class_file = MODULE_LOADER_DIR . $class_path . '.php';
        if (file_exists($class_file)) {
            require_once $class_file;
        }
    }
}

// Función de activación
function module_loader_activate() {
    ModuleLoader\Includes\Core\Activator::activate();
}
register_activation_hook(__FILE__, 'module_loader_activate');

// Función de desinstalación
function module_loader_uninstall() {
    ModuleLoader\Includes\Core\Uninstaller::uninstall();
}
register_uninstall_hook(__FILE__, 'module_loader_uninstall');

// Inicializar el plugin
function module_loader_init() {
    if (is_admin()) {
        ModuleLoader\Includes\Admin\Assets::init();
        ModuleLoader\Controllers\Admin\MenuController::init();
        ModuleLoader\Controllers\Admin\ModuleController::init();
        ModuleLoader\Controllers\Admin\UploadController::init();
    }
}
add_action('plugins_loaded', 'module_loader_init');