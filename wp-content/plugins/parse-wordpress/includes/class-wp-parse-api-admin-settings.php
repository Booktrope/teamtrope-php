<?php
if (!defined('WP_PARSE_API_PATH')) die('.______.');

if (is_admin()){         // admin actions
        add_action('admin_menu', 'wp_parse_api_menu');
        
        function wp_parse_api_menu() {
                add_options_page('Parse Api Options', 'Parse API', 'manage_options', 'wp-parse-api-options', 'wp_parse_api_page');
                add_action('admin_init', 'wp_parse_api_admin_init');
        }
        
        function wp_parse_api_admin_init()
        {
                //register our settings
                register_setting('wp-parse-api-settings-group', 'app_id');
                register_setting('wp-parse-api-settings-group', 'app_masterkey');
                register_setting('wp-parse-api-settings-group', 'app_restkey');
                register_setting('wp-parse-api-settings-group', 'dev_app_id');
                register_setting('wp-parse-api-settings-group', 'dev_app_masterkey');
                register_setting('wp-parse-api-settings-group', 'dev_app_restkey');
                register_setting('wp-parse-api-settings-group', 'active_account');
        }
        
        function wp_parse_api_page()
        {
                require WP_PARSE_API_PATH .'includes/class-wp-parse-api-admin-settings-template.php';
        }
        

}