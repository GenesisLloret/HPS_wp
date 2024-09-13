<div class="wrap">
    <h1>Subir Módulos</h1>
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php 
        // Cambio de 'module_loader_upload_module' a 'hpshub_upload_module'
        wp_nonce_field('hpshub_upload_module', 'hpshub_nonce'); 
        ?>
        <input type="hidden" name="action" value="hpshub_handle_upload">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="module_zip">Subir archivo ZIP del módulo</label></th>
                <td><input type="file" name="module_zip" id="module_zip" required></td>
            </tr>
        </table>
        <?php submit_button('Subir Módulo'); ?>
    </form>
</div>
