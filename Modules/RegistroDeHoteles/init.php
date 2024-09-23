<?php

namespace HPSHUB\Modules\RegistroDeHoteles;

if (!defined('ABSPATH')) {
    exit;
}

class Init {
    public static function init() {
        // Activar la tabla personalizada
        self::activate_table();

        // Inicializar el controlador principal
        Controllers\Admin\RegistroHotelesController::init();

        // Encolar assets si es necesario
        self::enqueue_assets();
    }

    /**
     * Activar la tabla personalizada mediante el activador del módulo.
     */
    private static function activate_table() {
        // Verificar si el módulo ya está activo para evitar duplicados
        if (!get_option('hpshub_registro_hoteles_table_created')) {
            Activator::activate();
            update_option('hpshub_registro_hoteles_table_created', true);
        }
    }

    public static function enqueue_assets() {
        add_action('admin_enqueue_scripts', function($hook) {
            // Encolar estilos y scripts solo en las páginas del módulo
            if (strpos($hook, 'hpshub-registro-hoteles') === false) {
                return;
            }

            wp_enqueue_style(
                'hpshub-registro-hoteles-css',
                HPSHUB_URL . 'Modules/RegistroDeHoteles/Assets/css/admin.css',
                [],
                '1.0.0'
            );

            wp_enqueue_script(
                'hpshub-registro-hoteles-js',
                HPSHUB_URL . 'Modules/RegistroDeHoteles/Assets/js/admin.js',
                ['jquery'],
                '1.0.0',
                true
            );
        });
    }
}

// Inicializar el módulo
Init::init();
