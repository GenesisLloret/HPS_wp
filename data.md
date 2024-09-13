# File: C:\xampp\htdocs\hotelparkingservice\hps-hub.php 
``` 
<?php
/*
Plugin Name: Hotel Parking Service
Description: Un plugin para gestionar servicios de estacionamiento en hoteles.
Version: 0.2.5
Author: Genesis Lloret Ramos
*/

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('HPSHUB_VERSION', '0.2.5');
define('HPSHUB_DIR', plugin_dir_path(__FILE__));
define('HPSHUB_URL', plugin_dir_url(__FILE__));

// Registrar función de autoload
spl_autoload_register('hpshub_autoload');
function hpshub_autoload($class) {
    if (strpos($class, 'HPSHUB\\') === 0) {
        $relative_class = substr($class, strlen('HPSHUB\\'));
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
        $class_file = HPSHUB_DIR . $class_path . '.php';
        
        if (file_exists($class_file)) {
            require_once $class_file;
        } else {
            error_log("Class file not found: " . $class_file);
        }
    }
}

// Función de activación
function hpshub_activate() {
    HPSHUB\Includes\Core\Activator::activate();
}
register_activation_hook(__FILE__, 'hpshub_activate');

// Función de desinstalación
function hpshub_uninstall() {
    HPSHUB\Includes\Core\Uninstaller::uninstall();
}
register_uninstall_hook(__FILE__, 'hpshub_uninstall');

// Inicializar el plugin
function hpshub_init() {
    if (is_admin()) {
        HPSHUB\Includes\Admin\Assets::init();
        HPSHUB\Controllers\Admin\MenuController::init();
        HPSHUB\Controllers\Admin\ModuleController::init();
        HPSHUB\Controllers\Admin\UploadController::init();
    }
}
add_action('plugins_loaded', 'hpshub_init');
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\uninstall.php 
``` 
<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Cargar la clase Uninstaller
require_once plugin_dir_path(__FILE__) . 'Includes/Core/Uninstaller.php';

// Ejecutar la desinstalación
ModuleLoader\Includes\Core\Uninstaller::uninstall();
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\controllers\Admin\MenuController.php 
``` 
<?php

namespace HPSHUB\Controllers\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class MenuController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
    }

    public static function add_admin_menu() {
        add_menu_page(
            'HPS Hub',
            'HPS Hub',
            'manage_options',
            'hpshub',
            [__CLASS__, 'dashboard_page'],
            'dashicons-admin-plugins',
            6
        );
    }

    public static function dashboard_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        echo '<div class="wrap">';
        echo '<h1>HPS Hub</h1>';
        echo '<p>Bienvenido al cargador de módulos.</p>';
        echo '</div>';
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\controllers\Admin\ModuleController.php 
``` 
<?php

namespace HPSHUB\Controllers\Admin;

use HPSHUB\Models\ModuleModel;

if (!defined('ABSPATH')) {
    exit;
}

class ModuleController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_modules_page']);
        add_action('admin_post_hpshub_manage_module', [__CLASS__, 'manage_module']);
        self::load_modules();
    }

    public static function add_modules_page() {
        add_submenu_page(
            'hpshub',
            'Gestión de Módulos',
            'Módulos',
            'manage_options',
            'hpshub-modules',
            [__CLASS__, 'modules_page']
        );
    }

    public static function load_modules() {
        $active_modules = ModuleModel::get_active_modules();
        foreach ($active_modules as $module_slug) {
            $module_init_file = HPSHUB_DIR . 'modules/' . $module_slug . '/init.php';
            if (file_exists($module_init_file)) {
                include_once $module_init_file;
            }
        }
    }

    public static function modules_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        $modules = ModuleModel::get_all_modules();

        // Cargar la vista
        include HPSHUB_DIR . 'Views/Admin/modules/index.php';
    }

    public static function manage_module() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        check_admin_referer('hpshub_manage_module', 'hpshub_nonce');

        $module_slug = isset($_POST['module_slug']) ? sanitize_text_field($_POST['module_slug']) : '';
        $action = isset($_POST['module_action']) ? sanitize_text_field($_POST['module_action']) : '';

        if ($module_slug && $action) {
            if ($action === 'activate') {
                ModuleModel::activate_module($module_slug);
            } elseif ($action === 'deactivate') {
                ModuleModel::deactivate_module($module_slug);
            } elseif ($action === 'delete') {
                ModuleModel::delete_module($module_slug);
            }
        }

        wp_redirect(admin_url('admin.php?page=hpshub-modules'));
        exit;
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\controllers\Admin\UploadController.php 
``` 
<?php

namespace ModuleLoader\Controllers\Admin;

use HPSHUB\Models\UploadModel;

if (!defined('ABSPATH')) {
    exit;
}

class UploadController {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_upload_page']);
        add_action('admin_post_module_loader_handle_upload', [__CLASS__, 'handle_upload']);
    }

    public static function add_upload_page() {
        add_submenu_page(
            'module-loader',
            'Subir Módulos',
            'Subir Módulos',
            'manage_options',
            'module-loader-upload',
            [__CLASS__, 'upload_page']
        );
    }

    public static function upload_page() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para acceder a esta página.');
        }

        include MODULE_LOADER_DIR . 'Views/Admin/upload/index.php';
    }

    public static function handle_upload() {
        if (!current_user_can('manage_options')) {
            wp_die('No tienes permiso para realizar esta acción.');
        }

        check_admin_referer('module_loader_upload_module', 'module_loader_nonce');

        $result = UploadModel::handle_module_upload();

        if ($result['success']) {
            wp_redirect(admin_url('admin.php?page=module-loader-modules&upload=success'));
        } else {
            wp_die($result['message']);
        }
        exit;
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\includes\Admin\Assets.php 
``` 
<?php

namespace HPSHUB\Includes\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Assets {
    public static function init() {
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function enqueue_assets() {
        wp_enqueue_style('module-loader-admin-css', MODULE_LOADER_URL . 'Assets/css/admin.css', [], MODULE_LOADER_VERSION);
        wp_enqueue_script('module-loader-admin-js', MODULE_LOADER_URL . 'Assets/js/admin.js', ['jquery'], MODULE_LOADER_VERSION, true);
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\includes\Core\Activator.php 
``` 
<?php

namespace HPSHUB\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {
    public static function activate() {
        // Crear directorios necesarios
        $modules_dir = HPSHUB_DIR . 'modules/';
        if (!file_exists($modules_dir)) {
            mkdir($modules_dir, 0755, true);
        }

        // Otras tareas de activación si son necesarias
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\includes\Core\Helper.php 
``` 
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
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\includes\Core\Uninstaller.php 
``` 
<?php

namespace HPSHUB\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Uninstaller {
    public static function uninstall() {
        // Eliminar opciones y datos almacenados
        delete_option('hpshub_modules');
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\models\ModuleModel.php 
``` 
<?php

namespace HPSHUB\Models;

use HPSHUB\Includes\Core\Helper;

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
        $active_modules = get_option('hpshub_modules', []);
        return $active_modules;
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
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\models\UploadModel.php 
``` 
<?php

namespace HPSHUB\Models;

use HPSHUB\Includes\Core\Helper;

if (!defined('ABSPATH')) {
    exit;
}

class UploadModel {
    public static function handle_module_upload() {
        if (!isset($_FILES['module_zip']) || $_FILES['module_zip']['error'] != UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Hubo un problema con la subida del archivo.'];
        }

        $uploaded_file = $_FILES['module_zip'];

        // Tipos MIME permitidos para archivos ZIP
        $allowed_mime_types = [
            'application/zip',
            'application/x-zip-compressed',
            'multipart/x-zip',
            'application/x-compressed',
        ];

        if (!in_array($uploaded_file['type'], $allowed_mime_types)) {
            return ['success' => false, 'message' => 'Solo se permiten archivos ZIP.'];
        }

        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['basedir'] . '/module-loader/';
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $zip_path = $upload_path . basename($uploaded_file['name']);

        if (!move_uploaded_file($uploaded_file['tmp_name'], $zip_path)) {
            return ['success' => false, 'message' => 'No se pudo mover el archivo subido.'];
        }

        $zip = new \ZipArchive;
        if ($zip->open($zip_path) === TRUE) {
            // Validar contenido del ZIP
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                // Verificar que no hay rutas ascendentes
                if (strpos($filename, '../') !== false) {
                    unlink($zip_path);
                    return ['success' => false, 'message' => 'El archivo ZIP contiene rutas no permitidas.'];
                }
            }

            $module_name = basename($uploaded_file['name'], '.zip');
            $extract_path = MODULE_LOADER_DIR . 'modules/' . $module_name;

            if (!file_exists($extract_path)) {
                mkdir($extract_path, 0755, true);
            }

            $zip->extractTo($extract_path);
            $zip->close();
            unlink($zip_path);

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'No se pudo descomprimir el archivo ZIP.'];
        }
    }
}
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\views\admin\modules\index.php 
``` 
<div class="wrap">
    <h1>Gestión de Módulos</h1>

    <?php if (isset($_GET['upload']) && $_GET['upload'] == 'success') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Módulo subido y descomprimido con éxito.</p>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Módulo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $slug => $module): ?>
                <tr>
                    <td><?php echo esc_html($module['name']); ?></td>
                    <td><?php echo esc_html($module['description']); ?></td>
                    <td>
                        <?php
                        $active_modules = ModuleLoader\Models\ModuleModel::get_active_modules();
                        echo in_array($slug, $active_modules) ? 'Activo' : 'Inactivo';
                        ?>
                    </td>
                    <td>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline;">
                            <?php wp_nonce_field('module_loader_manage_module', 'module_loader_nonce'); ?>
                            <input type="hidden" name="action" value="module_loader_manage_module">
                            <input type="hidden" name="module_slug" value="<?php echo esc_attr($slug); ?>">
                            <?php if (in_array($slug, $active_modules)) : ?>
                                <input type="hidden" name="module_action" value="deactivate">
                                <input type="submit" value="Desactivar" class="button">
                            <?php else: ?>
                                <input type="hidden" name="module_action" value="activate">
                                <input type="submit" value="Activar" class="button button-primary">
                            <?php endif; ?>
                        </form>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline;">
                            <?php wp_nonce_field('module_loader_manage_module', 'module_loader_nonce'); ?>
                            <input type="hidden" name="action" value="module_loader_manage_module">
                            <input type="hidden" name="module_slug" value="<?php echo esc_attr($slug); ?>">
                            <input type="hidden" name="module_action" value="delete">
                            <input type="submit" value="Eliminar" class="button button-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este módulo?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
``` 
 
# File: C:\xampp\htdocs\hotelparkingservice\views\admin\upload\index.php 
``` 
<div class="wrap">
    <h1>Subir Módulos</h1>
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('module_loader_upload_module', 'module_loader_nonce'); ?>
        <input type="hidden" name="action" value="module_loader_handle_upload">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="module_zip">Subir archivo ZIP del módulo</label></th>
                <td><input type="file" name="module_zip" id="module_zip" required></td>
            </tr>
        </table>
        <?php submit_button('Subir Módulo'); ?>
    </form>
</div>
``` 
 
