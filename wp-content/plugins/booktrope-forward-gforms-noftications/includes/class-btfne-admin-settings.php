<?php
if (!defined('WP_BTFNE_PATH')) die('.______.');

if (is_admin())
{         // admin actions
	add_action('admin_menu', 'wp_btfne_menu');
	
	function wp_btfne_menu()
	{
		add_options_page('Booktrope Forward Gforms Notification Email Options', 'BTFNE Options', 'manage_options', 'btfne-options', 'btfne_page');
		add_action('admin_init', 'btfne_admin_init');
	}
	
	function btfne_admin_init()
	{
		//register our settings
		register_setting('btfne-settings-group', 'is_active');
		register_setting('btfne-settings-group', 'forwarding_email');
		register_setting('btfne-settings-group', 'from_email');	
	}
	
	function btfne_page()
	{
		require WP_BTFNE_PATH .'includes/class-btfne-admin-settings-template.php';
	}
}