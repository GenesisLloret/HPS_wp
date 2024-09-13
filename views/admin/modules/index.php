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
            <?php
            // Obtener módulos activos una sola vez antes del loop
            $active_modules = HPSHUB\Models\ModuleModel::get_active_modules();

            if (!empty($modules)) :
                foreach ($modules as $slug => $module) : ?>
                    <tr>
                        <td><?php echo esc_html($module['name']); ?></td>
                        <td><?php echo esc_html($module['description']); ?></td>
                        <td>
                            <?php
                            echo in_array($slug, $active_modules) ? 'Activo' : 'Inactivo';
                            ?>
                        </td>
                        <td>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline;">
                                <?php 
                                wp_nonce_field('hpshub_manage_module', 'hpshub_nonce'); 
                                ?>
                                <input type="hidden" name="action" value="hpshub_manage_module">
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
                                <?php 
                                wp_nonce_field('hpshub_manage_module', 'hpshub_nonce'); 
                                ?>
                                <input type="hidden" name="action" value="hpshub_manage_module">
                                <input type="hidden" name="module_slug" value="<?php echo esc_attr($slug); ?>">
                                <input type="hidden" name="module_action" value="delete">
                                <input type="submit" value="Eliminar" class="button button-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este módulo?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach;
            else : ?>
                <tr>
                    <td colspan="4">No se encontraron módulos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
