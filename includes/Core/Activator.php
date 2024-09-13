<?php

namespace HPS_Hub\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {
    public static function activate() {
        $config_file = HPS_HUB_PLUGIN_DIR . 'config/config.json';
        if (!file_exists($config_file)) {
            $default_config = json_encode(['extensions' => []], JSON_PRETTY_PRINT);
            if (!file_exists(HPS_HUB_PLUGIN_DIR . 'config/')) {
                mkdir(HPS_HUB_PLUGIN_DIR . 'config/', 0755, true);
            }
            file_put_contents($config_file, $default_config);
        }
    }
}
