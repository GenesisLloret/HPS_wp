<div class="wrap">
    <h1>Subir Extensiones</h1>
    <?php if (isset($_GET['message']) && $_GET['message'] == 'upload_success') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Extensión subida y descomprimida con éxito.</p>
        </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="hps_hub_handle_upload">
        <?php wp_nonce_field('hps_hub_upload_nonce', 'hps_hub_upload_nonce_field'); ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="extension_zip">Subir archivo ZIP de la extensión</label></th>
                <td><input type="file" name="extension_zip" id="extension_zip" required></td>
            </tr>
        </table>
        <?php submit_button('Subir Extensión'); ?>
    </form>
</div>
