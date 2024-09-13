<?php

namespace HPSHUB\Models;

if (!defined('ABSPATH')) {
    exit;
}

class ModuleModel {
    public static function get_all_modules() {
        $modules_dir = HPSHUB_DIR . 'modules/';
        $modules = [];

        if (is_dir($modules_dir)) {
            $dirs = scandir($modules_dir);
            foreach ($dirs as $dir) {
                if ($dir === '.' || $dir === '..') {
                    continue;
                }
                $info = self::get_module_info($dir);
                if ($info) {
                    $modules[$dir] = $info;
                }
            }
        }

        return $modules;
    }

    public static function get_active_modules() {
        return get_option('hpshub_modules', []);
    }

    public static function activate_module($module_slug) {
        $active_modules = self::get_active_modules();
        if (!in_array($module_slug, $active_modules)) {
            $active_modules[] = $module_slug;
            update_option('hpshub_modules', $active_modules);
        }
    }

    public static function deactivate_module($module_slug) {
        $active_modules = self::get_active_modules();
        if (($key = array_search($module_slug, $active_modules)) !== false) {
            unset($active_modules[$key]);
            update_option('hpshub_modules', $active_modules);
        }
    }

    public static function delete_module($module_slug) {
        self::deactivate_module($module_slug);
        $module_dir = HPSHUB_DIR . 'modules/' . $module_slug;
        self::delete_directory($module_dir);
    }

    private static function delete_directory($dir) {
        if (!file_exists($dir)) {
            return;
        }

        if (is_file($dir) || is_link($dir)) {
            unlink($dir);
        } else {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                self::delete_directory($dir . DIRECTORY_SEPARATOR . $file);
            }
            rmdir($dir);
        }
    }

    public static function get_module_info($module_slug) {
        $info_file = HPSHUB_DIR . 'modules/' . $module_slug . '/info.json';
        if (file_exists($info_file)) {
            return json_decode(file_get_contents($info_file), true);
        }
        return null;
    }
}
