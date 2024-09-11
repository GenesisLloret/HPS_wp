<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Hub_Settings {
    public static function init() {add_action('admin_init', [__CLASS__, 'register_settings']);}
    public static function register_settings() {
        register_setting('hps-hub-settings-group', 'hps_hub_option_1');
        register_setting('hps-hub-settings-group', 'hps_hub_option_2');
        add_settings_section(
            'hps_hub_main_section',
            __('Configuraciones Generales', 'hps-hub'),
            [__CLASS__, 'main_section_callback'],
            'hps-hub-settings'
        );
        add_settings_field(
            'hps_hub_option_1',
            __('Opción 1', 'hps-hub'),
            [__CLASS__, 'option_1_callback'],
            'hps-hub-settings',
            'hps_hub_main_section'
        );
        add_settings_field(
            'hps_hub_option_2',
            __('Opción 2', 'hps-hub'),
            [__CLASS__, 'option_2_callback'],
            'hps-hub-settings',
            'hps_hub_main_section'
        );
    }
    public static function main_section_callback() {echo '<p>' . __('Aquí puedes configurar las opciones generales del plugin HPS Hub.', 'hps-hub') . '</p>';}
    public static function option_1_callback() {
        $value = get_option('hps_hub_option_1', '');
        echo '<input type="text" id="hps_hub_option_1" name="hps_hub_option_1" value="' . esc_attr($value) . '" />';
    }
    public static function option_2_callback() {
        $value = get_option('hps_hub_option_2', '');
        echo '<input type="text" id="hps_hub_option_2" name="hps_hub_option_2" value="' . esc_attr($value) . '" />';
    }
}
