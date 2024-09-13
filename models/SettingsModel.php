<?php

namespace HPS_Hub\Models;

if (!defined('ABSPATH')) {
    exit;
}

class SettingsModel {
    public static function register_settings() {
        register_setting('hps-hub-settings-group', 'hps_hub_option_1', 'sanitize_text_field');
        register_setting('hps-hub-settings-group', 'hps_hub_option_2', 'sanitize_text_field');

        add_settings_section(
            'hps_hub_main_section',
            'Configuraciones Generales',
            [__CLASS__, 'main_section_callback'],
            'hps-hub-settings'
        );

        add_settings_field(
            'hps_hub_option_1',
            'Opción 1',
            [__CLASS__, 'option_1_callback'],
            'hps-hub-settings',
            'hps_hub_main_section'
        );

        add_settings_field(
            'hps_hub_option_2',
            'Opción 2',
            [__CLASS__, 'option_2_callback'],
            'hps-hub-settings',
            'hps_hub_main_section'
        );
    }

    public static function main_section_callback() {
        echo '<p>Aquí puedes configurar las opciones generales del plugin HPS Hub.</p>';
    }

    public static function option_1_callback() {
        $value = get_option('hps_hub_option_1', '');
        echo '<input type="text" id="hps_hub_option_1" name="hps_hub_option_1" value="' . esc_attr($value) . '" />';
    }

    public static function option_2_callback() {
        $value = get_option('hps_hub_option_2', '');
        echo '<input type="text" id="hps_hub_option_2" name="hps_hub_option_2" value="' . esc_attr($value) . '" />';
    }
}
