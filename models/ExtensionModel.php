<?php

namespace HPS_Hub\Models;

use HPS_Hub\Includes\Core\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

class ExtensionModel {
    public static function get_all_extensions() {
        $config_data = Helpers::get_config_data();
        return $config_data['extensions'];
    }

    public static function get_active_extensions() {
        $extensions = self::get_all_extensions();
        return array_filter($extensions, function($extension) {
            return isset($extension['active']) && $extension['active'];
        });
    }

    public static function toggle_extension($slug) {
        $config_data = Helpers::get_config_data();
        foreach ($config_data['extensions'] as &$extension) {
            if ($extension['slug'] === $slug) {
                $extension['active'] = !isset($extension['active']) || !$extension['active'];
                break;
            }
        }
        Helpers::save_config_data($config_data);
    }
}
