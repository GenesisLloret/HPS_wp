# PLUGIN

/hps-hub/
│
├── /admin/
│   ├── menu.php                // Clase para crear el menú en el panel de administración
│   ├── upload.php              // Clase para gestionar la subida de extensiones Zip
│   ├── extensions.php          // Clase para gestionar las extensiones subidas
│   ├── settings.php            // Clase para manejar la zona de configuración
│   ├── config.json             // Archivo que almacena el estado de las extensiones activas
│
├── /exts/                      // Carpeta de extensiones
│   └── /nombre-extension/      // Carpeta donde se extraen las extensiones subidas
│       ├── data.json           // Configuración de la extensión
│       └── index.php           // Funcionalidades de la extensión
│
├── /assets/
│   ├── /css/
│   │   └── admin.css           // Estilos personalizados para el panel de administración
│   └── /js/
│       └── admin.js            // Scripts personalizados para el panel de administración
│
├── hps-hub.php                 // Archivo principal del plugin
├── uninstall.php               // Código para la desinstalación del plugin
└── readme.txt                  // Descripción del plugin

---

`hps-hub.php`:
```
<?php
/*
Plugin Name: Hotel Parking Service
Description: A plugin to manage hotel parking services.
Version: 0.1.11
Author: Genesis Lloret Ramos
Text Domain: hps-hub
*/
if (!defined('ABSPATH')) {exit;}
define('HPS_HUB_VERSION', '1.0');
define('HPS_HUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HPS_HUB_PLUGIN_URL', plugin_dir_url(__FILE__));
require_once HPS_HUB_PLUGIN_DIR . 'admin/menu.php';
require_once HPS_HUB_PLUGIN_DIR . 'admin/upload.php';
require_once HPS_HUB_PLUGIN_DIR . 'admin/extensions.php';
require_once HPS_HUB_PLUGIN_DIR . 'admin/settings.php';
function hps_hub_activate() {
    $config_file = HPS_HUB_PLUGIN_DIR . 'admin/config.json';
    if (!file_exists($config_file)) {
        $default_config = json_encode(['extensions' => []], JSON_PRETTY_PRINT);
        file_put_contents($config_file, $default_config);
    }
}
register_activation_hook(__FILE__, 'hps_hub_activate');
function hps_hub_init() {
    if (is_admin()) {
        wp_enqueue_style('hps-hub-admin-css', HPS_HUB_PLUGIN_URL . 'assets/css/admin.css', [], HPS_HUB_VERSION);
        wp_enqueue_script('hps-hub-admin-js', HPS_HUB_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], HPS_HUB_VERSION, true);
    }
    if (class_exists('HPS_Hub_Menu')) {HPS_Hub_Menu::init();}
    if (class_exists('HPS_Hub_Upload')) {HPS_Hub_Upload::init();}
    if (class_exists('HPS_Hub_Extensions')) {HPS_Hub_Extensions::init();}
    if (class_exists('HPS_Hub_Settings')) {HPS_Hub_Settings::init();}
}
add_action('plugins_loaded', 'hps_hub_init');
```

`admin\menu.php`:
```
<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Hub_Menu {
    public static function init() {add_action('admin_menu', [__CLASS__, 'add_admin_menu']);}
    public static function add_admin_menu() {
        add_menu_page(
            __('HPS Hub', 'hps-hub'),
            __('HPS Hub', 'hps-hub'),
            'manage_options',
            'hps-hub',
            [__CLASS__, 'admin_page'],
            'dashicons-admin-generic',
            6
        );
        add_submenu_page(
            'hps-hub',
            __('Configuraciones', 'hps-hub'),
            __('Configuraciones', 'hps-hub'),
            'manage_options',
            'hps-hub-settings',
            [__CLASS__, 'settings_page']
        );
    }
    public static function admin_page() {
        echo '<div class="wrap">';
        echo '<h1>' . __('HPS Hub', 'hps-hub') . '</h1>';
        echo '<p>' . __('Gestión de extensiones y configuración del plugin.', 'hps-hub') . '</p>';
        echo '</div>';
    }
    public static function settings_page() {
        echo '<div class="wrap">';
        echo '<h1>' . __('Configuraciones de HPS Hub', 'hps-hub') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('hps-hub-settings-group');
        do_settings_sections('hps-hub-settings');
        submit_button();
        echo '</form>';
        echo '</div>';
    }
}
```

`admin\upload.php`:
```
<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Hub_Upload {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_upload_page']);
        add_action('admin_post_hps_hub_handle_upload', [__CLASS__, 'handle_upload']);
    }
    public static function add_upload_page() {
        add_submenu_page(
            'hps-hub',
            __('Subir Extensiones', 'hps-hub'),
            __('Subir Extensiones', 'hps-hub'),
            'manage_options',
            'hps-hub-upload',
            [__CLASS__, 'upload_page']
        );
    }
    public static function upload_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Subir Extensiones', 'hps-hub'); ?></h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="hps_hub_handle_upload">
                <?php wp_nonce_field('hps_hub_upload_nonce', 'hps_hub_upload_nonce_field'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="extension_zip"><?php _e('Subir archivo ZIP de la extensión', 'hps-hub'); ?></label></th>
                        <td><input type="file" name="extension_zip" id="extension_zip" required></td>
                    </tr>
                </table>
                <?php submit_button(__('Subir Extensión', 'hps-hub')); ?>
            </form>
        </div>
        <?php
    }
    public static function handle_upload() {
        if (!isset($_POST['hps_hub_upload_nonce_field']) || !wp_verify_nonce($_POST['hps_hub_upload_nonce_field'], 'hps_hub_upload_nonce')) {wp_die(__('Fallo de seguridad. No se pudo verificar el nonce.', 'hps-hub'));}
        if (!isset($_FILES['extension_zip']) || $_FILES['extension_zip']['error'] != UPLOAD_ERR_OK) {wp_die(__('Hubo un problema con la subida del archivo.', 'hps-hub'));}
        $uploaded_file = $_FILES['extension_zip'];
        if ($uploaded_file['type'] != 'application/zip') {wp_die(__('Solo se permiten archivos ZIP.', 'hps-hub'));}
        $upload_dir = HPS_HUB_PLUGIN_DIR . 'exts/';
        $zip_path = $upload_dir . basename($uploaded_file['name']);
        if (!move_uploaded_file($uploaded_file['tmp_name'], $zip_path)) {wp_die(__('No se pudo mover el archivo subido.', 'hps-hub'));}
        $zip = new ZipArchive;
        if ($zip->open($zip_path) === TRUE) {
            $zip->extractTo($upload_dir);
            $zip->close();
            unlink($zip_path);
        } else {wp_die(__('No se pudo descomprimir el archivo ZIP.', 'hps-hub'));}
        wp_redirect(admin_url('admin.php?page=hps-hub&upload=success'));
        exit;
    }
}
```

---

`admin\extensions.php`:
```
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
```

`admin\settings`:
```
<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Hub_Settings {
    public static function init() {add_action('admin_init', [__CLASS__, 'register_settings']);}
    public static function register_settings() {
        register_setting('hps-hub-settings-group', 'hps_hub_option_1');
        register_setting('hps-hub-settings-group', 'hps_hub_option_2');
        add_settings_section(
            'hps_hub_main_section',
            __('Configuraciones Generales', 'hps-hub'),
            [__CLASS__, 'main_section_callback'],
            'hps-hub-settings'
        );
        add_settings_field(
            'hps_hub_option_1',
            __('Opción 1', 'hps-hub'),
            [__CLASS__, 'option_1_callback'],
            'hps-hub-settings',
            'hps_hub_main_section'
        );
        add_settings_field(
            'hps_hub_option_2',
            __('Opción 2', 'hps-hub'),
            [__CLASS__, 'option_2_callback'],
            'hps-hub-settings',
            'hps_hub_main_section'
        );
    }
    public static function main_section_callback() {echo '<p>' . __('Aquí puedes configurar las opciones generales del plugin HPS Hub.', 'hps-hub') . '</p>';}
    public static function option_1_callback() {
        $value = get_option('hps_hub_option_1', '');
        echo '<input type="text" id="hps_hub_option_1" name="hps_hub_option_1" value="' . esc_attr($value) . '" />';
    }
    public static function option_2_callback() {
        $value = get_option('hps_hub_option_2', '');
        echo '<input type="text" id="hps_hub_option_2" name="hps_hub_option_2" value="' . esc_attr($value) . '" />';
    }
}
```

`admin\config.json`:
```
{
    "extensions": []
}
```

`/assets/css/admin.css`:
```
.wrap {margin: 20px;}
h1 {
    font-size: 24px;
    color: #23282d;
    margin-bottom: 20px;
}
.form-table th {
    font-weight: bold;
    padding: 10px;
}
.form-table td {padding: 10px;}
.wp-list-table th, .wp-list-table td {
    padding: 10px;
    text-align: left;
}
.wp-list-table th {background-color: #f1f1f1;}
.wp-list-table tr:nth-child(even) {background-color: #f9f9f9;}
.wp-list-table tr:nth-child(odd) {background-color: #fff;}
button.button {
    background-color: #0073aa;
    border-color: #006799;
    color: #fff;
    text-transform: uppercase;
    font-weight: bold;
}
button.button:hover {
    background-color: #005f8a;
    border-color: #004f73;
    color: #ffffff;
}
```

`/assets/js/admin.js`:
```
document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('input[name="toggle_extension"]');
    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            const action = event.target.value;
            const confirmationMessage = action === 'Activar' 
                ? '¿Estás seguro de que deseas activar esta extensión?' 
                : '¿Estás seguro de que deseas desactivar esta extensión?';
            if (!confirm(confirmationMessage)) {event.preventDefault();}
        });
    });
});
```

`uninstall.php`:
```
<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {exit;}
$config_file = plugin_dir_path(__FILE__) . 'admin/config.json';
if (file_exists($config_file)) {unlink($config_file);}
delete_option('hps_hub_option_1');
delete_option('hps_hub_option_2');
?>
```