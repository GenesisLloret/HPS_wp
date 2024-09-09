<?php
/**
 * Google Maps API Key Registration
 * Module Name: Google Maps API Key Registration
 * Description: A module to register and store the Google Maps API Key.
 */

$mod_name = "Google Maps API Key Registration";
$mod_version = "1.0";
$mod_description = "A module to register and store the Google Maps API Key.";
$mod_author = "Génesis Lloret Ramos";
$mod_always_enabled = false;
$mod_has_options = ["update", "activate"];
$mod_WSForm_moderation = true;
$mod_menu = true;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Google_Maps_API_Module')) {
    class Google_Maps_API_Module {

        public function __construct() {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
        }

        public function add_admin_menu() {
            add_options_page(
                'Google Maps API Key',
                'Google Maps API Key',
                'manage_options',
                'google-maps-api-module',
                array($this, 'create_admin_page')
            );
        }

        public function register_settings() {
            register_setting('google_maps_api_group', 'google_maps_api_key');
        }

        public function create_admin_page() {
            ?>
            <div class="wrap">
                <h1>Google Maps API Key Registration</h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('google_maps_api_group');
                    do_settings_sections('google_maps_api_group');
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Google Maps API Key</th>
                            <td>
                                <input type="text" name="google_maps_api_key"
                                    value="<?php echo esc_attr(get_option('google_maps_api_key')); ?>" size="50" />
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
    }

    new Google_Maps_API_Module();
}
