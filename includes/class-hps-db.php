<?php
if (!defined('ABSPATH')) {
    exit;
}

class HPS_DB {

    public static function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hps_mods';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            mod_name varchar(255) NOT NULL,
            mod_version varchar(50) NOT NULL,
            mod_description text NOT NULL,
            mod_author varchar(255) NOT NULL,
            mod_always_enabled tinyint(1) DEFAULT 0 NOT NULL,
            mod_has_options text DEFAULT '' NOT NULL,
            mod_wsform_moderation tinyint(1) DEFAULT 0 NOT NULL,
            mod_menu tinyint(1) DEFAULT 0 NOT NULL,
            active tinyint(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function insert_or_update_module($module) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hps_mods';

        if (empty($module['name'])) {
            return;
        }

        $existing_module = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE mod_name = %s",
            $module['name']
        ));

        if ($existing_module) {
            $wpdb->update(
                $table_name,
                [
                    'mod_version' => $module['version'],
                    'mod_description' => $module['description'],
                    'mod_author' => $module['author'],
                    'mod_always_enabled' => $module['always_enabled'],
                    'mod_has_options' => maybe_serialize($module['has_options']),
                    'mod_wsform_moderation' => $module['wsform_moderation'],
                    'mod_menu' => $module['mod_menu'],
                    'active' => $module['always_enabled'] ? 1 : $module['active'],
                ],
                ['id' => $existing_module->id]
            );
        } else {
            $wpdb->insert(
                $table_name,
                [
                    'mod_name' => $module['name'],
                    'mod_version' => $module['version'],
                    'mod_description' => $module['description'],
                    'mod_author' => $module['author'],
                    'mod_always_enabled' => $module['always_enabled'],
                    'mod_has_options' => maybe_serialize($module['has_options']),
                    'mod_wsform_moderation' => $module['wsform_moderation'],
                    'mod_menu' => $module['mod_menu'],
                    'active' => $module['always_enabled'] ? 1 : $module['active'],
                ]
            );
        }
    }
}
