<?php

/*

Plugin Name: BP Group Frontpage

Plugin URI: http://www.disabilityvoice.com/dvb/groups/bp-group-frontpage/frontpage

Description: Creates a BuddyPress Group Frontpage from a form BuddyPress Group Admins can fill out. Like book cover.  For instance group banner image, large description, and links. Compatible with Suffusion child theme with Suffusion buddy pack and BP compatible themes.   

Author: Timothy A Carey

Author URI: http://www.disabilityvoice.com/dvb/groups/bp-group-frontpage/frontpage

Revision Date: October 30, 2011

Version: 1.2.9

License: GPL2

Requires at least: BuddyPress 1.5.1, bp Album+ 0.1.8.7

Tested up to: WP 3.2.1, BuddyPress 1.5.1

Contributors: TimCarey, Tim Carey

*/



/* Copyright 2011 Timothy A Carey (email : wordpress@DisabilityVoice.com) 

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation. 

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. 

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA 

*/ 



/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */

function tac_plugin_init() {

    require( dirname( __FILE__ ) . '/bp-group-frontpage-main.php' );

}

add_action( 'bp_include', 'tac_plugin_init' );

function replace_group_header_currenttheme(){
	
	 $current_theme_folder_dir = ABSPATH . '/wp-content/themes/'.  strtolower(str_replace (" ", "-", get_current_theme())) . '/';
	 
	 $theme_group_header_loc = 'groups/single/';
	 $theme_group_header_dir_loc  = $current_theme_folder_dir . $theme_group_header_loc . 'group-header.php';
	 
	 $frontpage_group_header_folder_dir = ABSPATH . '/wp-content/plugins/bp-group-frontpage/gh/';
	 $the_frontpage_group_header =  $frontpage_group_header_folder_dir . 'group-header-tac.php';
	 	 
	if (file_exists($theme_group_header_dir_loc) && file_exists($the_frontpage_group_header)) {
  
 		unlink ($theme_group_header_dir_loc);
  
  		copy ($the_frontpage_group_header, $theme_group_header_dir_loc);
  
	}

}

function replace_group_header_bpdefaulttheme(){
	
	 $current_theme_folder_dir = ABSPATH . '/wp-content/plugins/buddypress/bp-themes/bp-default/';
	 
	 $theme_group_header_loc = 'groups/single/';
	 $theme_group_header_dir_loc  = $current_theme_folder_dir . $theme_group_header_loc . 'group-header.php';
	 
	 $frontpage_group_header_folder_dir = ABSPATH . '/wp-content/plugins/bp-group-frontpage/gh/';
	 $the_frontpage_group_header =  $frontpage_group_header_folder_dir . 'group-header-tac.php';
	 
	 
	if (file_exists($theme_group_header_dir_loc) && file_exists($the_frontpage_group_header)) { 
 
 		unlink ($theme_group_header_dir_loc);
  
  		copy ($the_frontpage_group_header, $theme_group_header_dir_loc);
  
  	}

}

function bp_group_frontpage_install(){
	global $bp,$wpdb;
			define ('bp_groups_default_extension','frontpage');
	
}
register_activation_hook( __FILE__, 'bp_group_frontpage_install' );


function bp_group_frontpage_requirements_installed() {
	global $wpdb, $bp;
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

	if ( !current_user_can('install_plugins') )
		return;

	if (!defined('BP_VERSION') || version_compare(BP_VERSION, '1.2.7','<') || !is_plugin_active( 'bp-album/loader.php' )){
		add_action('admin_notices', 'bp_group_frontpage_compatibility_notices' );
		
		return;
	} else {
				replace_group_header_currenttheme();
		
				replace_group_header_bpdefaulttheme();
		
				bp_group_frontpage_install();
	} 
	
/**/
}
add_action( 'admin_menu', 'bp_group_frontpage_requirements_installed' );

function bp_group_frontpage_compatibility_notices() {
	$message = '';
	if (!defined('BP_VERSION')){
		$message = 'BP Group Frontpage needs at least BuddyPress 1.2.7  to work.';
		$message .= ' Please install Buddypress';
	}elseif(version_compare(BP_VERSION, '1.2.7','<') ){
				$message = 'BP Group Frontpage needs at least BuddyPress 1.2.7  to work.';
		$message .= ' Your current version is '.BP_VERSION.' please updrade.';
	}
	if ( !is_plugin_active( 'bp-album/loader.php' ) ) {
		$message .= ' You must have bp-album+ Installed for bp Group Frontpage Plugin to work properly.';
	}
	echo '<div class="error fade"><p>'.$message.'</p></div>';
}


function bp_group_frontpage_activate() {
	bp_group_frontpage_requirements_installed();

	do_action( 'bp_group_frontpage_activate' );
}
register_activation_hook( __FILE__, 'bp_group_frontpage_activate' );


function bp_group_frontpage_deactivate() {
	//restore_group_header_currenttheme();
	
	do_action( 'bp_group_frontpage_deactivate' );
}
register_deactivation_hook( __FILE__, 'bp_group_frontpage_deactivate' );



/* If you have code that does not need BuddyPress to run, then add it here. */

 ?>