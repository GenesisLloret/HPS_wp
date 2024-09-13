<?php
/*
Plugin Name: Hotel Parking Service
Description: Un plugin para gestionar servicios de estacionamiento en hoteles.
Version: 0.2.8
Author: Genesis Lloret Ramos
*/

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('HPSHUB_VERSION', '0.2.8');
define('HPSHUB_DIR', plugin_dir_path(__FILE__));
define('HPSHUB_URL', plugin_dir_url(__FILE__));

// Registrar función de autoload
spl_autoload_register('hpshub_autoload');
function hpshub_autoload($class) {
    if (strpos($class, 'HPSHUB\\') === 0) {
        $relative_class = substr($class, strlen('HPSHUB\\'));
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
        $class_file = HPSHUB_DIR . $class_path . '.php';
        
        if (file_exists($class_file)) {
            require_once $class_file;
        } else {
            error_log("Class file not found: " . $class_file);
        }
    }
}

// Función de activación
function hpshub_activate() {
    HPSHUB\Includes\Core\Activator::activate();
}
register_activation_hook(__FILE__, 'hpshub_activate');

// Función de desinstalación
function hpshub_uninstall() {
    HPSHUB\Includes\Core\Uninstaller::uninstall();
}
register_uninstall_hook(__FILE__, 'hpshub_uninstall');

// Inicializar el plugin
function hpshub_init() {
    if (is_admin()) {
        HPSHUB\Includes\Admin\Assets::init();
        HPSHUB\Controllers\Admin\MenuController::init();
        HPSHUB\Controllers\Admin\ModuleController::init();
        HPSHUB\Controllers\Admin\UploadController::init();
    }
}
add_action('plugins_loaded', 'hpshub_init');
