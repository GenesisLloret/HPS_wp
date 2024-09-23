<?php

namespace HPSHUB\Modules\RegistroDeHoteles\Models;

use HPSHUB\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class RegistroHotelesModel {
    private static $table_name;

    /**
     * Inicializar el nombre de la tabla.
     */
    public static function init() {
        global $wpdb;
        self::$table_name = $wpdb->prefix . 'hpshub_registro_hoteles';
    }

    /**
     * Obtener el último ID del formulario almacenado.
     *
     * @return string|false
     */
    public static function get_latest_form_id() {
        global $wpdb;
        $result = $wpdb->get_var(
            "SELECT form_id FROM {$wpdb->prefix}hpshub_registro_hoteles ORDER BY id DESC LIMIT 1"
        );
        return $result ? $result : false;
    }

    /**
     * Guardar el ID del formulario.
     *
     * @param string $form_id
     * @return bool|int
     */
    public static function set_form_id($form_id) {
        global $wpdb;
        $result = $wpdb->insert(
            self::$table_name,
            [
                'form_id' => $form_id,
            ],
            [
                '%s',
            ]
        );
        return $result;
    }

    /**
     * Verificar si el formulario existe en WSForms.
     *
     * @param string $form_id
     * @return bool
     */
    public static function does_form_exist($form_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'wsf_form'; // Asegúrate de que este es el nombre correcto de la tabla
        
        // Log del nombre de la tabla y el ID que se está buscando
        error_log("RegistroHotelesModel: Verificando existencia del formulario en la tabla '$table' con ID $form_id.");
        
        // Preparar la consulta para prevenir inyecciones SQL
        $query = $wpdb->prepare("SELECT id FROM $table WHERE id = %d", $form_id);
        
        // Ejecutar la consulta
        $result = $wpdb->get_var($query);
        
        if ($result) {
            error_log("RegistroHotelesModel: Formulario con ID $form_id existe.");
            return true;
        } else {
            error_log("RegistroHotelesModel: Formulario con ID $form_id no existe.");
            return false;
        }
    }
}

// Inicializar el modelo para establecer el nombre de la tabla
RegistroHotelesModel::init();
