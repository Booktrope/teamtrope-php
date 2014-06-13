<?php
/*
 * Plugin Name: BuddyPress Admin Access Activity
 * Version: 1.0
 * Plugin URI: http://andylroberts.com/plugins
 * Description: Simply allows admins to go directly to any activity they link to. Stops the problem of 'You do not have access to this activity' in BuddyPress
 * Author: Andy Roberts
 * Author URI: http://andylroberts.com
 */
 
// filter bp_activity_permalink_access ($has_access, &$activity) to allow admins access to activities they are mentioned in
function bp_allow_admin_activity_access($args) {
	global $bp;
	
	if ($args[0] == FALSE && is_admin() ) {
		return TRUE;
	}
}

add_filter("bp_activity_permalink_access", "bp_allow_admin_activity_access");