<?php

namespace HPSHUB\Modules\RegistroDeHoteles;

use HPSHUB\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {
    /**
     * Crear la tabla personalizada para almacenar el ID del formulario.
     */
    public static function activate() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'hpshub_registro_hoteles';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            form_id varchar(50) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
