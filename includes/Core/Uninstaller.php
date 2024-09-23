<?php

namespace HPSHUB\Includes\Core;

use HPSHUB\Controllers\Admin\ModuleController;

if (!defined('ABSPATH')) {
    exit;
}

class Uninstaller {
    public static function uninstall() {
        // Eliminar opciones y datos almacenados
        delete_option('hpshub_modules');

        // Eliminar tablas personalizadas de los módulos activos
        $active_modules = get_option('hpshub_modules', []);

        foreach ($active_modules as $module_slug) {
            $module_init_file = HPSHUB_DIR . 'Modules/' . $module_slug . '/init.php';

            if (file_exists($module_init_file)) {
                include_once $module_init_file;

                // Verificar si el módulo tiene una clase Uninstaller y llamarla
                $uninstaller_class = '\\HPSHUB\\Modules\\' . self::slug_to_class($module_slug) . '\\Uninstaller';
                if (class_exists($uninstaller_class)) {
                    $uninstaller_class::uninstall();
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
