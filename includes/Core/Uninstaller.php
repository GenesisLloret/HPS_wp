<?php

namespace HPS_Hub\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Uninstaller {
    public static function uninstall() {
        $config_file = HPS_HUB_PLUGIN_DIR . 'config/config.json';
        if (file_exists($config_file)) {
            unlink($config_file);
        }

        delete_option('hps_hub_option_1');
        delete_option('hps_hub_option_2');
    }
}
