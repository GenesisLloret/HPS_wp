<?php

namespace HPSHUB\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {
    public static function activate() {
        // Crear directorios necesarios
        $modules_dir = HPSHUB_DIR . 'modules/';
        if (!file_exists($modules_dir)) {
            mkdir($modules_dir, 0755, true);
        }

        // Otras tareas de activación si son necesarias
    }
}
