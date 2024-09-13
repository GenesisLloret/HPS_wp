<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Cargar la clase Uninstaller
require_once plugin_dir_path(__FILE__) . 'includes/Core/Uninstaller.php';

// Ejecutar la desinstalación
ModuleLoader\Includes\Core\Uninstaller::uninstall();
