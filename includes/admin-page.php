<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('hps_activate_module', 'hps_module_nonce'); ?>
    <input type="hidden" name="action" value="hps_activate_module">
    <button type="submit" class="button button-primary">Activar</button>
</form>
