<?php
require_once( WP_PLUGIN_DIR.'/bp-group-frontpage/bp-group-frontpage-functions.php' );


class Group_Tac_Frontpage extends BP_Group_Extension {	
		
	function group_tac_frontpage() {
		global $bp;
		$this->name = 'Group Front Page';
		$this->slug = 'frontpage';
		//define ('bp_groups_default_extension','frontpage');
		
		// Only enable the notifications nav item if the user is a member of the group
		/*if ( groups_is_user_member( $bp->loggedin_user->id , $bp->groups->current_group->id )  ) {
			$this->enable_nav_item = true;
		} else {
			$this->enable_nav_item = false;
		}*/
$this->enable_nav_item = true;
		$this->nav_item_position = 1;
		$this->enable_create_step = false;
		$this->enable_edit_item = false;
	}
	
	// Display the notification settings form
	function display() {
		tac_echo_the_frontpage(); 
	}
	
	// The remaining group API functions aren't used for this plugin but have to be overriden or api won't work
	
	function create_screen() {
		return false;
	}

	function create_screen_save() {
		return false;
	}

	function edit_screen() {
		// if ass-admin-can-send-email = no this won't show
		//ass_admin_notice_form();
		return false;
	}

	function edit_screen_save() {
		return false;
	}

	function widget_display() { 
		return false;
	}
		
}

//bp_register_group_extension( 'Group_Activity_Subscription' );
/*
function tac_activate_extension() {
	$extension = new Group_Tac_Frontpage;
	add_action( "wp", array( &$extension, "_register" ), 2 );
}
add_action( 'init', 'group_tac_frontpage' );
*/
bp_register_group_extension( 'Group_Tac_Frontpage' );

?>
