<?php
if (!defined('ABSPATH')) {exit;}
class Registro_Hoteles_Extension {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu_page']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
    }
    public static function add_menu_page() {
        add_submenu_page(
            'hps-hub',
            __('Registrar Hoteles', 'hps-hub'),
            __('Registrar Hoteles', 'hps-hub'),
            'manage_options',
            'registro-hoteles',
            [__CLASS__, 'render_page']
        );
    }
    public static function render_page() {
        $config_file = plugin_dir_path(__FILE__) . 'data.json';
        if (file_exists($config_file)) {
            $config_data = json_decode(file_get_contents($config_file), true);
            $form_id = isset($config_data['form_id']) && !empty($config_data['form_id']) ? $config_data['form_id'] : null;
        } else {$form_id = null;}
        echo '<div class="wrap">';
        echo '<h1>' . __('Registrar Hoteles', 'hps-hub') . '</h1>';
        if ($form_id) {
            echo '<button id="abrir-formulario-hotel" class="button button-primary">' . __('Abrir Formulario', 'hps-hub') . '</button>';
            echo '
            <div id="popup-formulario-hotel" style="display:none;">
                <div class="popup-content">
                    <button id="cerrar-popup" class="button">X</button>
                    ' . do_shortcode('[ws_form id="' . esc_attr($form_id) . '"]') . '
                </div>
            </div>';
        } else {echo '<p>' . __('Por favor, configure el ID del formulario en el apartado de "Extensiones".', 'hps-hub') . '</p>';}
        echo '</div>';
    }
    public static function enqueue_scripts($hook_suffix) {
        if ($hook_suffix === 'hps-hub_page_registro-hoteles') {
            wp_enqueue_script('registro-hoteles-js', plugin_dir_url(__FILE__) . 'assets/admin.js', ['jquery'], null, true);
            wp_enqueue_style('registro-hoteles-css', plugin_dir_url(__FILE__) . 'assets/admin.css');
        }
    }
}
Registro_Hoteles_Extension::init();