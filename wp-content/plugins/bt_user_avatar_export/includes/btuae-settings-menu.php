<?php
  if(is_admin()) {
    add_action('admin_menu', 'bt_uae_menu');

      function bt_uae_menu() {
        add_options_page(
			'Teamtrope User Avatar Export Options',
			'Teamtrope User Avatar Export',
			'manage_options',
			'bt_uae_menu',
			'bt_uae_menu_page'
        );
      }

      function bt_uae_menu_page()
      {
        require BTUAE_PATH .'includes/btuae-settings-template.php';
      }
  }
