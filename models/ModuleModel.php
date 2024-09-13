<?php

namespace ModuleLoader\Models;

use ModuleLoader\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class ModuleModel {
    public static function get_all_modules() {
        $modules_dir = Helper::get_modules_dir();
        $modules = [];

        if (is_dir($modules_dir)) {
            $dirs = scandir($modules_dir);
            foreach ($dirs as $dir) {
                if ($dir === '.' || $dir === '..') {
                    continue;
                }
                $info = Helper::get_module_info($dir);
                if ($info) {
                    $modules[$dir] = $info;
                }
            }
        }

        return $modules;
    }

    public static function get_active_modules() {
        $active_modules = get_option('module_loader_modules', []);
        return $active_modules;
    }

    public static function activate_module($module_slug) {
        $active_modules = self::get_active_modules();
        if (!in_array($module_slug, $active_modules)) {
            $active_modules[] = $module_slug;
            update_option('module_loader_modules', $active_modules);
        }
    }

    public static function deactivate_module($module_slug) {
        $active_modules = self::get_active_modules();
        if (($key = array_search($module_slug, $active_modules)) !== false) {
            unset($active_modules[$key]);
            update_option('module_loader_modules', $active_modules);
        }
    }

    public static function delete_module($module_slug) {
        self::deactivate_module($module_slug);
        $module_dir = Helper::get_modules_dir() . $module_slug;
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
}
