<?php

namespace HPSHUB\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {
    public static function activate() {
        // Crear directorios necesarios
        $modules_dir = HPSHUB_DIR . 'Modules/';
        if (!file_exists($modules_dir)) {
            mkdir($modules_dir, 0755, true);
        }

        // Registrar y activar módulos activos
        self::activate_active_modules();
    }

    /**
     * Activar los módulos que estén activos.
     */
    private static function activate_active_modules() {
        $active_modules = get_option('hpshub_modules', []);

        foreach ($active_modules as $module_slug) {
            $module_init_file = HPSHUB_DIR . 'Modules/' . $module_slug . '/init.php';

            if (file_exists($module_init_file)) {
                include_once $module_init_file;

                // Verificar si el módulo tiene una clase Activator y llamarla
                $activator_class = '\\HPSHUB\\Modules\\' . self::slug_to_class($module_slug) . '\\Activator';
                if (class_exists($activator_class)) {
                    $activator_class::activate();
                }
            }
        }
    }

    /**
     * Convertir el slug del módulo a formato de clase (CamelCase).
     *
     * @param string $slug
     * @return string
     */
    private static function slug_to_class($slug) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $slug)));
    }
}
