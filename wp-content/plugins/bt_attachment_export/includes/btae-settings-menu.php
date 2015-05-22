<?php
  if(is_admin()){
    add_action('admin_menu', 'bt_ae_menu');

      function bt_ae_menu() {
        add_options_page('Teamtrope Attachment Export Options',
          'Teamtrope Attachment Export',
          'manage_options',
          'btae_menu',
          'btae_menu_page');

        //add_action('admin_init', 'wp_parse_api_admin_init');
      }

      //function wp_parse_api_admin_init() {
        //register our settings
        // register_setting('wp-parse-api-settings-group', 'app_id');
        // register_setting('wp-parse-api-settings-group', 'app_masterkey');
        // register_setting('wp-parse-api-settings-group', 'app_restkey');
        // register_setting('wp-parse-api-settings-group', 'dev_app_id');
        // register_setting('wp-parse-api-settings-group', 'dev_app_masterkey');
        // register_setting('wp-parse-api-settings-group', 'dev_app_restkey');
        // register_setting('wp-parse-api-settings-group', 'active_account');
      //}

      function btae_menu_page()
      {
        require BTAE_PATH .'includes/btae-settings-template.php';
      }
  }
?>

