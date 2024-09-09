<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {exit;}
delete_option('hps_module_active');
delete_option('hps_google_maps_api_key');
