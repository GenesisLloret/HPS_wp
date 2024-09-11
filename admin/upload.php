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
