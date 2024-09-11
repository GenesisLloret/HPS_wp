<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {exit;}
$config_file = plugin_dir_path(__FILE__) . 'admin/config.json';
if (file_exists($config_file)) {unlink($config_file);}
delete_option('hps_hub_option_1');
delete_option('hps_hub_option_2');
?>
