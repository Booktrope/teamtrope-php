<?php
/*
Plugin Name: BuddyPress Real Names
Plugin URI: http://wordpress.org/extend/plugins/buddypress-real-names/
Description: BuddyPress Real Names allows you to change the regular nickname displayed for a user by anything you want.
Author: G.Breant
Version: 0.3.5
Author URI: http://pencil2d.org
License: GPL2
Text Domain: bprn
*/

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function bprn_plugin_init() {
    require( dirname( __FILE__ ) . '/buddypress-real-names.php' );
}

function bprn_settings_action( $links, $file ) {
    //Static so we don't call plugin_basename on every plugin row.
        static $this_plugin;


        if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

        if ( $file == $this_plugin ){
            $url = admin_url('users.php?page=bprn');
        $settings_link = '<a href="'.$url.'">' . __( 'Settings', 'oqp' ) . '</a>';
            array_unshift( $links, $settings_link ); // before other links
        }
        return $links;
} // end function si_contact_plugin_action_links
// adds "Settings" link to the plugin action page


add_action( 'bp_include', 'bprn_plugin_init' );
add_filter( 'plugin_action_links', 'bprn_settings_action',10,2);
?>