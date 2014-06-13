<?php
/*
Plugin Name: Booktrope Forward gforms notifications
Plugin URI: http://booktrope.com
Description: Forwards gforms email notifications
Version: 0.0.2
Author: Justin Jeffress
Author URI: http://www.booktrope.com
License: GPL2
*/

define('WP_BTFNE_PATH', plugin_dir_path(__FILE__));
require_once(WP_BTFNE_PATH.'includes/class-btfne-admin-settings.php');

add_filter("gform_notification", "forward_notification_email", 10,3);
function forward_notification_email($notification, $form, $entry)
{
	settings_fields('btfne-settings-group');
	if(get_option('is_active'))
	{
		$default_email = get_option('admin_email');
		$notification['to'] = (get_option('forwarding_email') !== "") ? get_option('forwarding_email') : $default_email;
		$notification['from'] = (get_option('from_email') !== "") ? get_option('from_email') : $default_email;
	}

	return $notification;
}