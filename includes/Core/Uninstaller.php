<?php

namespace ModuleLoader\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Uninstaller {
    public static function uninstall() {
        // Eliminar opciones y datos almacenados
        delete_option('module_loader_modules');
    }
}
