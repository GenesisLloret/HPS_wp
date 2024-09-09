<?php
/**
 * Test module 01
 * Module Name: Test module 01
 * Description: Test module 01
 */

$mod_name = "Test module 01";
$mod_version = "1.0";
$mod_description = "Test module 01";
$mod_author = "Génesis Lloret Ramos";
$mod_always_enabled = true;
$mod_has_options = ["update"];
$mod_WSForm_moderation = false;
$mod_menu = true;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('test_module_01')) {
    class test_module_01 {

        public function __construct() {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
        }

        public function add_admin_menu() {
            add_options_page(
                'test_module_01',
                'test_module_01',
                'manage_options',
                'test_module_01',
                array($this, 'create_admin_page')
            );
        }

        public function register_settings() {
            register_setting('test_module_01', 'test_module_01');
        }

        public function create_admin_page() {
            ?>
            <div class="wrap">
                <h1>Google Maps API Key Registration</h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('test_module_01');
                    do_settings_sections('test_module_01');
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Google Maps API Key</th>
                            <td>
                                <input type="text" name="test_module_01"
                                    value="<?php echo esc_attr(get_option('test_module_01')); ?>" size="50" />
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
    }

    new test_module_01();
}
