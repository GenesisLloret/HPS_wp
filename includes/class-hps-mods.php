<?php
if (!defined('ABSPATH')) {exit;}
class HPS_Mods {
    public static function load_modules() {
        $modules_dir = plugin_dir_path(__FILE__) . '../mods/';
        $modules = [];
        if (is_dir($modules_dir)) {
            foreach (glob($modules_dir . '*.php') as $module_file) {
                unset($mod_name, $mod_version, $mod_description, $mod_author, $mod_always_enabled, $mod_has_options, $mod_WSForm_moderation, $mod_menu);
                if (is_file($module_file)) {
                    include($module_file);
                    if (isset($mod_name, $mod_version, $mod_description, $mod_author, $mod_always_enabled, $mod_has_options, $mod_WSForm_moderation, $mod_menu)) {
                        $modules[] = [
                            'name' => $mod_name,
                            'version' => $mod_version,
                            'description' => $mod_description,
                            'author' => $mod_author,
                            'always_enabled' => $mod_always_enabled,
                            'has_options' => $mod_has_options,
                            'wsform_moderation' => $mod_WSForm_moderation,
                            'mod_menu' => $mod_menu,
                        ];
                    }
                }
            }
        }
        return $modules;
    }
    public static function render_modules() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hps_mods';
        $modules = self::load_modules();    
        if (empty($modules)) {
            echo '<p>No se han encontrado módulos.</p>';
            return;
        }    
        foreach ($modules as $module) {
            $db_module = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE mod_name = %s", $module['name']));
            $is_active = $db_module ? $db_module->active : 0;
            ?>
            <div class="hps-module">
                <h3><?php echo esc_html($module['name']); ?> (v<?php echo esc_html($module['version']); ?>)</h3>
                <p><?php echo esc_html($module['description']); ?></p>
                <p><strong>Autor:</strong> <?php echo esc_html($module['author']); ?></p>
                <form method="post" action="admin-post.php">
                    <input type="hidden" name="action" value="toggle_module">
                    <input type="hidden" name="module_name" value="<?php echo esc_attr($module['name']); ?>">
                    <input type="hidden" name="module_active" value="<?php echo $is_active ? 0 : 1; ?>">
                    <button type="submit" class="button">
                        <?php echo $is_active ? 'Desactivar' : 'Activar'; ?>
                    </button>
                </form>
                <hr />
            </div>
            <?php
        }
    }      
}
