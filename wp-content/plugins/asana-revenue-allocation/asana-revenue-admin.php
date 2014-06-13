<?php
/**
* Teamtrope Asana settings!
*
*/

function teamtrope_asana_menu() {
	add_options_page( 'Teamtrope Settings', 'TT Asana', 'manage_options', 'tt-asana-options', 'teamtrope_asana_options' );
}

function teamtrope_asana_options() {
	// variables for the field and option names 
    $opt_name = 'asana_api_key';
	$wkspace_name = 'asana_selected_workspace';
	$revenue_project_name = 'asana_revenue_project';
    $hidden_field_name = 'tt_options_submit_hidden';
    $data_field_name = 'asana_api_key';
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">'; 	
	global $wpdb;
	
	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		// Read their posted value
		$opt_val = $_POST[ $data_field_name ];
		$wkspace_val = $_POST[ $wkspace_name ];	
		$revenue_project_val = $_POST[ $revenue_project_name ];	
		// Save the posted value in the database
		update_option( $opt_name, $opt_val );
		update_option( $wkspace_name, $wkspace_val );
		update_option( $revenue_project_name, $revenue_project_val );

		// Put an settings updated message on the screen
		?>
	<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php
	}
	// Read in existing option value from database
	$opt_val = get_option( $opt_name );
	$wkspace_val = get_option( $wkspace_name );
	$revenue_project_val = get_option( $revenue_project_name );
	
	?>	
	<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<h3><?php _e('Teamtrope to Asana workflow settings');?></h3>
		<table class="form-table">
		<tr>
		<th><label for="asana_api_key"><?php _e('Asana API key') ?></label></th>
		<td>
		<input name="asana_api_key" id="asana_api_key" value="<?php echo esc_attr( $opt_val );?>">
		<span class="description"><?php _e('Your Asana API key');?></span>
		</td>
		</tr>
		</table>
		<table class="form-table">
		<tr>
		<th><label for="asana_selected_workspace"><?php _e('Selected Asana Workspace') ?></label></th>
		<td>
		<?php
		if ( $opt_val != ""){
			$workspace_url = "workspaces"; 		
			$api_key = $opt_val.":"; //note API key must have colon added to it for basic auth to work
			$workspaces=get_asana_info($workspace_url, $api_key);
		?>	
			<select name='asana_selected_workspace' id='asana_selected_workspace'>";
			<?php if( $wkspace_val != ""){
				echo "<option value='".esc_attr( $wkspace_val )."'>". substr(strrchr(esc_attr( $wkspace_val ), ";"), 1)."</option>";
			}
			else
			{
				echo "<option value=''>Choose a workspace</option>";
			}
			foreach ($workspaces as $ws) {
			echo "<option value='".$ws->id."&".$ws->name."'>".$ws->name."</option>";
			}
			echo "</select>";
			?>
			<span class='description'><?php _e('Please select an Asana workspace to use for the site');?></span>
			<?php
		}
		else
		{
			echo "Please enter an API key and save your options, then come back to select a workspace";
		}?>

		</td>
		</tr>
<?php
		if ( $wkspace_val != ""){
			$projects_url = "workspaces/".substr($wkspace_val, 0, strpos($wkspace_val, "&"))."/projects";
//			$projects_url = "workspaces/803972355135/projects";
			$projects=get_asana_info($projects_url, $api_key);
?>
		<tr>
		<td>Revenue Split Project</td>
		<td><?php
			echo "<select name='".$revenue_project_name."' id='".$revenue_project_name."'>";
			if( $wkspace_val == "") {
				echo "<option value=''>Choose a project</option>";
			}
			foreach ($projects as $p) {
				$p_name = substr($p->name,0,30);
				if ( strlen($p->name)>29 ) {
					$p_name = $p_name . "...";
				}
				if (esc_attr( $revenue_project_val ) == $p->id) { $select_val = "selected"; } 
				else
				{ $select_val = ""; }
				echo "<option value='" . $p->id . "' " . $select_val . ">" .$p_name . "</option>";
			}
			echo "</select>";
			?>
			<span class='description'><?php _e('Please select the project to send revenue split requests');?></span>
			<?php
			
		}
		else
		{
			echo "Please choose the workspace and save your options, then come back to select your projects";
		}
		?></td>
		<tr>
		<td>
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
		</td>
		</tr>
		</table>	
	</form>
<?php
	echo '</div>';
}
 