<?php

namespace HPSHUB\Modules\RegistroDeHoteles;

use HPSHUB\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class Uninstaller {
    /**
     * Eliminar la tabla personalizada al desinstalar el módulo.
     */
    public static function uninstall() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'hpshub_registro_hoteles';

        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Opcional: Eliminar otras opciones o datos relacionados
        delete_option('hpshub_registro_hoteles_table_created');
    }
}
