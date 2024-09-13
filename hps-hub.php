<?php
/*
Plugin Name: Hotel Parking Service
Description: Un plugin para gestionar servicios de estacionamiento en hoteles.
Version: 0.2.0
Author: Genesis Lloret Ramos
*/

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('HPS_HUB_VERSION', '0.2.0');
define('HPS_HUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HPS_HUB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Registrar función de autoload
spl_autoload_register('hps_hub_autoload');
function hps_hub_autoload($class) {
    if (strpos($class, 'HPS_Hub\\') === 0) {
        $relative_class = substr($class, strlen('HPS_Hub\\'));
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
        $class_file = HPS_HUB_PLUGIN_DIR . $class_path . '.php';
        if (file_exists($class_file)) {
            require_once $class_file;
        }
    }
}

// Función de activación
function hps_hub_activate() {
    HPS_Hub\Includes\Core\Activator::activate();
}
register_activation_hook(__FILE__, 'hps_hub_activate');

// Función de desinstalación
function hps_hub_uninstall() {
    HPS_Hub\Includes\Core\Uninstaller::uninstall();
}
register_uninstall_hook(__FILE__, 'hps_hub_uninstall');

// Inicializar el plugin
function hps_hub_init() {
    if (is_admin()) {
        HPS_Hub\Includes\Admin\Assets::init();
        HPS_Hub\Controllers\Admin\MenuController::init();
        HPS_Hub\Controllers\Admin\ExtensionsController::init();
        HPS_Hub\Controllers\Admin\UploadController::init();
        HPS_Hub\Controllers\Admin\SettingsController::init();
    }
}
add_action('plugins_loaded', 'hps_hub_init');

