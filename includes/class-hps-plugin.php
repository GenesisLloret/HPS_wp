<?php
if (!defined('ABSPATH')) { exit; }
class HPS_Plugin {
    public function __construct() {
        add_action('admin_menu', [$this, 'hps_add_admin_menu']);
        add_action('wp_ajax_toggle_module', [$this, 'toggle_module_ajax']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }
    public function enqueue_admin_scripts() {
        wp_enqueue_script('hps-admin-js', plugins_url('../assets/js/hps-admin.js', __FILE__), ['jquery'], null, true);
        wp_localize_script('hps-admin-js', 'hps_ajax_obj', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('hps_toggle_module_nonce')
        ]);
    }
    public function toggle_module_ajax() {
        if (!wp_verify_nonce($_POST['nonce'], 'hps_toggle_module_nonce')) {wp_send_json_error(['message' => 'Nonce verification failed.']);}
        if (!current_user_can('manage_options')) {wp_send_json_error(['message' => 'Permission denied.']);}
        global $wpdb;
        $table_name = $wpdb->prefix . 'hps_mods';
        $module_name = sanitize_text_field($_POST['module_name']);
        $module_active = intval($_POST['module_active']);
        $result = $wpdb->update(
            $table_name,
            ['active' => $module_active],
            ['mod_name' => $module_name]
        );
        if ($result === false) {wp_send_json_error(['message' => 'Database update failed.']);}
        wp_send_json_success(['message' => 'Module status updated successfully.']);
    }
    public static function activate() {
        require_once plugin_dir_path(__FILE__) . 'class-hps-db.php';
        HPS_DB::create_table();
        $modules = HPS_Plugin::load_modules();
        foreach ($modules as $module) {
            $module['active'] = isset($module['active']) ? $module['active'] : 0;
            HPS_DB::insert_or_update_module($module);
        }
    }
    public function hps_add_admin_menu() {
        add_menu_page(
            'Hotel Parking Service',
            'HPS Settings',
            'manage_options',
            'hps-settings',
            [$this, 'hps_render_admin_page'],
            'dashicons-admin-generic',
            20
        );
        $modules = HPS_Plugin::load_modules();
        foreach ($modules as $module) {
            $module['active'] = isset($module['active']) ? $module['active'] : 0;
            if ($module['mod_menu'] && $module['active']) {
                add_submenu_page(
                    'hps-settings',
                    $module['name'],
                    $module['name'],
                    'manage_options',
                    'hps-mod-' . sanitize_title($module['name']),
                    function () use ($module) {
                        echo '<div class="wrap">';
                        echo '<h1>Configuración de ' . esc_html($module['name']) . '</h1>';
                        echo '<p>' . esc_html($module['description']) . '</p>';
                        echo '</div>';
                    }
                );
            }
        }
    }
    public function hps_render_admin_page() {
        if (isset($_GET['module_updated'])) {
            echo '<div class="updated"><p>El estado del módulo ha sido actualizado con éxito.</p></div>';
        }

        echo '<div class="wrap">';
        echo '<h1>Configuración de Hotel Parking Service</h1>';
        HPS_Plugin::render_modules();
        echo '</div>';
    }    public static function load_modules() {
        require_once plugin_dir_path(__FILE__) . 'class-hps-mods.php';
        return HPS_Mods::load_modules();
    }
    public static function render_modules() {
        require_once plugin_dir_path(__FILE__) . 'class-hps-mods.php';
        HPS_Mods::render_modules();
    }
}
