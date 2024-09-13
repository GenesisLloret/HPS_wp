<?php

namespace HPS_Hub\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Helpers {
    public static function get_config_data() {
        $config_file = HPS_HUB_PLUGIN_DIR . 'config/config.json';
        if (file_exists($config_file)) {
            return json_decode(file_get_contents($config_file), true);
        }
        return ['extensions' => []];
    }

    public static function save_config_data($config_data) {
        $config_file = HPS_HUB_PLUGIN_DIR . 'config/config.json';
        file_put_contents($config_file, json_encode($config_data, JSON_PRETTY_PRINT));
    }
}
