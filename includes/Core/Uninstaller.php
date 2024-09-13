<?php

namespace HPSHUB\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Uninstaller {
    public static function uninstall() {
        // Eliminar opciones y datos almacenados
        delete_option('hpshub_modules');
    }
}
