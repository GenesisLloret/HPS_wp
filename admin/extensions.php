<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Hub_Extensions {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_extensions_page']);
        self::load_extensions();
    }
    public static function add_extensions_page() {
        add_submenu_page(
            'hps-hub',
            __('Extensiones', 'hps-hub'),
            __('Extensiones', 'hps-hub'),
            'manage_options',
            'hps-hub-extensions',
            [__CLASS__, 'extensions_page']
        );
    }
    public static function load_extensions() {
        $config_file = HPS_HUB_PLUGIN_DIR . 'admin/config.json';
        if (!file_exists($config_file)) {return;}
        $config_data = json_decode(file_get_contents($config_file), true);
        if (isset($config_data['extensions']) && is_array($config_data['extensions'])) {
            foreach ($config_data['extensions'] as $extension) {
                if ($extension['active']) {include_once HPS_HUB_PLUGIN_DIR . 'exts/' . $extension['slug'] . '/index.php';}
            }
        }
    }
    public static function extensions_page() {
        $config_file = HPS_HUB_PLUGIN_DIR . 'admin/config.json';
        if (file_exists($config_file)) {$config_data = json_decode(file_get_contents($config_file), true);}
        else {$config_data = ['extensions' => []];}
        ?>
        <div class="wrap">
            <h1><?php _e('Gestión de Extensiones', 'hps-hub'); ?></h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Nombre de la Extensión', 'hps-hub'); ?></th>
                        <th><?php _e('Estado', 'hps-hub'); ?></th>
                        <th><?php _e('Acciones', 'hps-hub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($config_data['extensions'] as $extension): ?>
                        <tr>
                            <td><?php echo esc_html($extension['name']); ?></td>
                            <td><?php echo $extension['active'] ? __('Activa', 'hps-hub') : __('Inactiva', 'hps-hub'); ?></td>
                            <td>
                                <form method="post">
                                    <?php wp_nonce_field('hps_hub_toggle_extension', 'hps_hub_nonce_field'); ?>
                                    <input type="hidden" name="extension_slug" value="<?php echo esc_attr($extension['slug']); ?>">
                                    <input type="submit" name="toggle_extension" value="<?php echo $extension['active'] ? __('Desactivar', 'hps-hub') : __('Activar', 'hps-hub'); ?>" class="button">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    public static function toggle_extension() {
        if (!isset($_POST['hps_hub_nonce_field']) || !wp_verify_nonce($_POST['hps_hub_nonce_field'], 'hps_hub_toggle_extension')) {wp_die(__('Fallo de seguridad. No se pudo verificar el nonce.', 'hps-hub'));}
        $config_file = HPS_HUB_PLUGIN_DIR . 'admin/config.json';
        if (!file_exists($config_file)) {return;}
        $config_data = json_decode(file_get_contents($config_file), true);
        $extension_slug = sanitize_text_field($_POST['extension_slug']);
        foreach ($config_data['extensions'] as &$extension) {
            if ($extension['slug'] === $extension_slug) {
                $extension['active'] = !$extension['active'];
                break;
            }
        }
        file_put_contents($config_file, json_encode($config_data, JSON_PRETTY_PRINT));
        wp_redirect(admin_url('admin.php?page=hps-hub-extensions'));
        exit;
    }
}
add_action('admin_post_toggle_extension', ['HPS_Hub_Extensions', 'toggle_extension']);