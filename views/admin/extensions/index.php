<div class="wrap">
    <h1>Gestión de Extensiones</h1>

    <?php if (isset($_GET['message']) && $_GET['message'] == 'extension_toggled') : ?>
        <div class="notice notice-success is-dismissible">
            <p>Extensión actualizada con éxito.</p>
        </div>
    <?php endif; ?>

    <h2>Extensiones Activas/Inactivas</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Nombre de la Extensión</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($extensions as $extension): ?>
                <tr>
                    <td><?php echo esc_html($extension['name']); ?></td>
                    <td><?php echo isset($extension['active']) && $extension['active'] ? 'Activa' : 'Inactiva'; ?></td>
                    <td>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <?php wp_nonce_field('hps_hub_toggle_extension', 'hps_hub_nonce_field'); ?>
                            <input type="hidden" name="action" value="hps_hub_toggle_extension">
                            <input type="hidden" name="extension_slug" value="<?php echo esc_attr($extension['slug']); ?>">
                            <input type="submit" name="toggle_extension" value="<?php echo isset($extension['active']) && $extension['active'] ? 'Desactivar' : 'Activar'; ?>" class="button">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
