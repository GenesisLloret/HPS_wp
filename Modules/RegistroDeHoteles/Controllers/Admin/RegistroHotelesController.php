<?php

namespace HPSHUB\Modules\RegistroDeHoteles\Controllers\Admin;

use HPSHUB\Modules\RegistroDeHoteles\Models\RegistroHotelesModel;

if (!defined('ABSPATH')) {
    exit;
}

class RegistroHotelesController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_admin_menus']);
        add_action('admin_post_hpshub_registro_hoteles_save_config', [__CLASS__, 'save_config']);
    }

    /**
     * Añadir los menús al panel de administración.
     */
    public static function add_admin_menus() {
        // Añadir la opción "Registro de Hoteles" al menú principal del plugin
        add_submenu_page(
            'hpshub',                           // Slug del menú principal
            'Registro de Hoteles',              // Título de la página
            'Registro de Hoteles',              // Título del menú
            'manage_options',                   // Capacidad requerida
            'hpshub-registro-hoteles',          // Slug del submenú
            [__CLASS__, 'configuracion_page']   // Función que muestra la página
        );

        // Obtener el último ID del formulario almacenado
        $form_id = RegistroHotelesModel::get_latest_form_id();

        // Verificar si existe un form_id para añadir la opción "Formulario"
        if ($form_id) {
            add_submenu_page(
                'hpshub',                                           // Slug del menú principal
                'Formulario de Registro de Hoteles',                // Título de la página
                'Formulario',                                       // Título del menú
                'manage_options',                                   // Capacidad requerida
                'hpshub-registro-hoteles-formulario',               // Slug del submenú
                [__CLASS__, 'formulario_page']                      // Función que muestra la página
            );
        }
    }

    /**
     * Mostrar la página de configuración.
     */
    public static function configuracion_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        // Obtener el último ID del formulario almacenado
        $form_id = RegistroHotelesModel::get_latest_form_id();
        ?>
        <div class="wrap">
            <h1>Configuración de Registro de Hoteles</h1>

            <?php if (isset($_GET['message']) && $_GET['message'] == 'success') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Configuración guardada exitosamente.</p>
                </div>
            <?php elseif (isset($_GET['message']) && $_GET['message'] == 'error') : ?>
                <div class="notice notice-error is-dismissible">
                    <p>El formulario con el ID proporcionado no existe.</p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php 
                wp_nonce_field('hpshub_registro_hoteles_save_config', 'hpshub_nonce'); 
                ?>
                <input type="hidden" name="action" value="hpshub_registro_hoteles_save_config">

                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="form_id">ID del Formulario de WSForms</label></th>
                        <td><input type="text" name="form_id" id="form_id" value="<?php echo esc_attr($form_id); ?>" required class="regular-text"></td>
                    </tr>
                </table>

                <?php submit_button('Guardar Configuración'); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Procesar la acción de guardar la configuración.
     */
    public static function save_config() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        check_admin_referer('hpshub_registro_hoteles_save_config', 'hpshub_nonce');

        // Obtener y sanitizar el ID del formulario
        $form_id = isset($_POST['form_id']) ? sanitize_text_field($_POST['form_id']) : '';

        if ($form_id) {
            // Verificar si el formulario existe
            if (RegistroHotelesModel::does_form_exist($form_id)) {
                // Guardar el ID del formulario
                RegistroHotelesModel::set_form_id($form_id);

                // Redireccionar con mensaje de éxito
                wp_redirect(admin_url('admin.php?page=hpshub-registro-hoteles&message=success'));
                exit;
            } else {
                // Redireccionar con mensaje de error
                wp_redirect(admin_url('admin.php?page=hpshub-registro-hoteles&message=error'));
                exit;
            }
        }

        // Redireccionar sin cambios
        wp_redirect(admin_url('admin.php?page=hpshub-registro-hoteles'));
        exit;
    }

    /**
     * Mostrar la página del formulario.
     */
    public static function formulario_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        // Obtener el último ID del formulario almacenado
        $form_id = RegistroHotelesModel::get_latest_form_id();

        if (!$form_id) {
            wp_die('No se ha configurado un ID de formulario.');
        }

        ?>
        <div class="wrap">
            <h1>Formulario de Registro de Hoteles</h1>

            <?php echo do_shortcode('[ws_form id="' . esc_attr($form_id) . '"]'); ?>
        </div>
        <?php
    }
}
