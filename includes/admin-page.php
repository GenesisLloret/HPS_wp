<?php
if (!defined('ABSPATH')) { exit; }

function hps_render_admin_page() {
    hps_load_modules();
    $mods = glob(plugin_dir_path(__FILE__) . '../mods/*.php');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Hotel Parking Service Settings', 'hps_wp'); ?></h1>
        <h2><?php esc_html_e('Available Modules', 'hps_wp'); ?></h2>
        <?php
        foreach ($mods as $mod) {
            include $mod;
            $module_settings = get_option('hps_module_' . sanitize_title($mod_name));
            ?>
            <div class="module-settings">
                <h3><?php echo esc_html($module_settings['mod_name']); ?> (v<?php echo esc_html($module_settings['mod_version']); ?>)</h3>
                <p><?php echo esc_html($module_settings['mod_description']); ?></p>
                <p><?php echo __('Author:', 'hps_wp') . ' ' . esc_html($module_settings['mod_author']); ?></p>

                <!-- Form for updating WSForm ID -->
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php wp_nonce_field('hps_update_wsform', 'hps_wsform_nonce'); ?>
                    <input type="hidden" name="action" value="hps_update_wsform">
                    <input type="hidden" name="module_name" value="<?php echo esc_attr($module_settings['mod_name']); ?>">
                    
                    <?php if ($module_settings['mod_WSForm_moderation']): ?>
                        <input type="text" name="wsform_id" value="<?php echo esc_attr(get_option('hps_wsform_id_' . sanitize_title($module_settings['mod_name']), '')); ?>" placeholder="<?php esc_attr_e('Enter WSForm ID', 'hps_wp'); ?>" />
                    <?php endif; ?>
                    
                    <?php if (in_array('update', $module_settings['mod_has_options'])): ?>
                        <button type="submit" class="button"><?php esc_html_e('Update', 'hps_wp'); ?></button>
                    <?php endif; ?>
                </form>
            </div>
            <hr>
        <?php } ?>
    </div>
    <?php
}
