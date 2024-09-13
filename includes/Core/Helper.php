<?php

namespace HPSHUB\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Helper {
    public static function get_modules_dir() {
        return HPSHUB_DIR . 'modules/';
    }

    public static function get_module_info($module_slug) {
        $info_file = self::get_modules_dir() . $module_slug . '/info.json';
        if (file_exists($info_file)) {
            return json_decode(file_get_contents($info_file), true);
        }
        return null;
    }
}
