<?php
if (!defined('ABSPATH')) {exit;}
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
                <?php if (in_array('update', $module_settings['mod_has_options'])): ?>
                    <button class="button"><?php esc_html_e('Update', 'hps_wp'); ?></button>
                <?php endif; ?>
                <?php if (in_array('activate', $module_settings['mod_has_options'])): ?>
                    <button class="button"><?php esc_html_e('Activate', 'hps_wp'); ?></button>
                <?php endif; ?>
                <?php if ($module_settings['mod_WSForm_moderation']): ?>
                    <input type="text" name="wsform_id" placeholder="<?php esc_attr_e('Enter WSForm ID', 'hps_wp'); ?>" />
                <?php endif; ?>
            </div>
            <hr>
        <?php } ?>
    </div>
    <?php
}