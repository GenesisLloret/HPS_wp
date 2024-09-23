<div class="wrap">
    <h1>Configuración de Registro de Hoteles</h1>

    <?php if (isset($_GET['message']) && $_GET['message'] == 'success') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Configuración guardada exitosamente.</p>
        </div>
    <?php elseif (isset($_GET['message']) && $_GET['message'] == 'error') : ?>
        <div class="notice notice-error is-dismissible">
            <p>El formulario con el ID proporcionado no existe.</p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php 
        wp_nonce_field('hpshub_registro_hoteles_save_config', 'hpshub_nonce'); 
        ?>
        <input type="hidden" name="action" value="hpshub_registro_hoteles_save_config">

        <table class="form-table">
            <tr>
                <th scope="row"><label for="form_id">ID del Formulario de WSForms</label></th>
                <td><input type="text" name="form_id" id="form_id" value="<?php echo esc_attr($form_id); ?>" required class="regular-text"></td>
            </tr>
        </table>

        <?php submit_button('Guardar Configuración'); ?>
    </form>
</div>
