<?php

/**
 * bp_mam_admin()
 * Checks for form submission, saves component settings and outputs admin screen HTML.
 */

add_action( 'admin_init', 'bp_mam_settings' );
function bp_mam_settings() {
 register_setting ( 'bp_wp_mam-settings-group', 'mam_width' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_height' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_map_zoom' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_display_where' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_navigation_display' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_navigation_size' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_max_number_of_members' );
 register_setting ( 'bp_wp_mam-settings-group', 'mam_default_location' );
 }
 add_option( 'mam_width', '' );
 add_option( 'mam_height', '' );
 add_option( 'mam_map_zoom', '' );
 add_option( 'mam_display_where', '0' );
 add_option( 'mam_navigation_display', '' );
 add_option( 'mam_navigation_size', '' );
 add_option( 'mam_max_number_of_members', '' );
 add_option( 'mam_default_location', '' );

 function bp_mam_admin() {
	global $bp;

	/* If the form has been submitted and the admin referrer checks out, save the settings */
	if ( isset( $_POST['submit'] ) && check_admin_referer('update_mam_settings', 'mam_settings') ) {
		update_option( 'mam_width', $_POST['width'] );
		update_option( 'mam_height', $_POST['height'] );
  update_option( 'mam_display_where', $_POST['mam_display_where'] );
  update_option( 'mam_map_zoom', $_POST['mam_map_zoom'] );
  update_option( 'mam_navigation_display', $_POST['mam_navigation_display'] );
  update_option( 'mam_navigation_size', $_POST['mam_navigation_size'] );
  update_option( 'mam_map_type_control', $_POST['mam_map_type_control'] );
  update_option( 'mam_max_number_of_members', $_POST['mam_max_number_of_members'] );
  update_option( 'mam_default_location', $_POST['mam_default_location'] );

		$updated = true;
	}


 $map_width = get_option( 'mam_width' );
 $map_height = get_option( 'mam_height' );
 $map_zoom   =  get_option('mam_map_zoom' );
 $navigation_display   =  get_option( 'mam_navigation_display' );
 $navigation_size = get_option( 'mam_navigation_size' ); 
 $map_type_control = get_option( 'mam_map_type_control' );
 $map_max_members = get_option('mam_max_number_of_members');
 $map_default_location = get_option('mam_default_location');
   
?>
	<div class="wrap">
		<h2><?php _e( 'Members Avatar Map Configuration&nbsp;&nbsp;<img src="'.plugins_url().'/bp-members-avatar-map/images/mam_icon.png"/>', 'bp-mam' ) ?></h2>
		

		<?php if ( isset($updated) ) : ?><?php echo "<div id='message' class='updated fade'><p>" . __( 'Settings Updated.', 'bp-mam' ) . "</p></div>" ?><?php endif; ?>
     <p><?php _e('To use Members Avatar Map you must have first set up a new custom xprofile field called <b>Location</b> This is important as the map script looks for this field and value to obtain map location data.', 'bp_mam') ?></p>
     <p><?php _e("If you have not yet set up a location field please visit the <a href=\"" .  $path_to ."?page=bp-profile-setup\">Profile Field Setup</a> page.", "bp_mam") ?></p>
     <p><?php _e('You can either set the location field in the base group to show it on the sign up page or create a new group for the field name in which case it will show in the users profle setup and public display only.', 'bp_mam') ?></p>
	<p><?php _e("You must also set up a <b>Latitude and Longitude</b> field please visit the <a href=\"" .  $path_to ."?page=bp-profile-setup\">Profile Field Setup</a> page.", "bp_mam") ?></p>
     <p><?php _e('You can either set the <b>Latitude and Longitude</b> field in the base group to show it on the sign up page or create a new group for the field name in which case it will show in the users profle setup and public display only.', 'bp_mam') ?></p>
	<p><?php _e('You can also set a <b>Info</b> field in the base group to show it on the sign up page or create a new group for the field name in which case it will show in the users profle setup and public display only.This field is for displaying member info on the info window that open by clicking on member avatar marker on the map. This field is not a must', 'bp_mam') ?></p>
			
		<form action="<?php echo  $path_to  . '?page=bp-mam-settings' ?>" name="mam-settings-form" id="mam-settings-form" method="post">
   
   <h3><?php _e('Map configuration options', 'bp-mam') ?></h3>
 
     <p><b><?php _e('Set map dimensions - Note: you may set the height by pixels but the width is set by % of element.', 'bp_mam') ?> </b></p>
     <table class="form-table">
		    <colgroup>
       <col span="1" width="30%" />
       <col span="1" width="70%" />
      </colgroup>	
	  <tr valign="top">
					  <th scope="row"><label for="mam-default"><?php _e( 'Map default location(put Latitude and Longitude of the desired default location)', 'bp-mam' ) ?></label></th>
					  <td>
						 <input name="mam_default_location" type="text" id="mam_default_location"  value="<?php echo attribute_escape( $map_default_location ); ?>" size="20" />
					  </td>
				  </tr>
      <tr valign="top">
					  <th scope="row"><label for="mam-width"><?php _e( 'Map width (e.g 70 to  = 70%)', 'bp-mam' ) ?></label></th>
					  <td>
						 <input name="width" type="text" id="mam-width"  value="<?php echo attribute_escape( $map_width ); ?>" size="20" />
					  </td>
				  </tr>
      <tr>
					  <th scope="row"><label for="mam-height"><?php _e( 'Map height', 'bp-mam' ) ?></label></th>
					  <td>
						  <input name="height" type="text" id="mam-height" value="<?php echo attribute_escape( $map_height ); ?>" size="20" />
					  </td>
				  </tr>
      <tr><td colspan="2"><?php _e('If no map dimensions are set here the map will default to display at a width and height of 200px', 'bp-mam') ?></td></tr>
      <tr valign="top">
					  <th scope="row"><label for="mam-zoom"><?php _e( 'Map Zoom', 'bp-mam' ) ?></label></th>
					  <td>
						 <input name="mam_map_zoom" type="text" id="mam-zoom" maxlength="2" value="<?php echo attribute_escape( $map_zoom ); ?>" size="5" />
					  </td>
      <tr valign="top">
       <td colspan="2"><?php _e('Map zoom sets the level of detail shown on the map, the higher the value the more you zoom into the location. Values between 8 &amp; 16 work well. If no value set Zoom value defaults to 11.', 'bp-mam') ?></td></tr>       
      </tr>
      <tr>
       <th scope="row"><label for="map-navigation"><?php _e( 'Show the map navigation overlay controls. ', 'bp-mam' ) ?></label></th>
       <td><input  name="mam_navigation_display" type="checkbox" id="map-navigation" <?php if( $navigation_display == true){?> checked="checked" <?php } ?>  value= true /></p></td>
      </tr>
      <tr>
       <td colspan="2"><?php _e('If you want to display a set of navigation controls, i.e zoom, pan etc then check the box above to  show these controls on the maps.', 'bp-mam');  ?></td>
      </tr>
      <tr>
       <th scope="row" valign="top"><label for="navigationSmall"><?php _e( 'Set the map navigation control size to small. ', 'bp-mam' ) ?></label></th>
       <td><input  name="mam_navigation_size" type="checkbox" id="navigationSmall" <?php if( $navigation_size == 'small'){?> checked="checked" <?php } ?>  value= 'small' /></p></td>
      </tr>
      <tr valign="top">
       <td colspan="2"><?php _e('Google maps adjusts certain map controls according to the maps dimensions  I.E the larger the map the more complex and larger the controls will be. If you wish to keep the navigation controls fixed as small check the box above.', 'bp-mam');  ?></td>
      </tr>
      <tr>
       <th scope="row" valign="top"><label for="map-type-control"><?php _e( 'Set map type control as a dropdown', 'bp-mam' ) ?></label></th>
       <td><input  name="mam_map_type_control" type="checkbox" id="map-type-control" <?php if( $map_type_control == 'dropdown'){?> checked="checked" <?php } ?>  value= 'dropdown' /></p></td>
      </tr>
      <tr valign="top">
       <td colspan="2"><?php _e('Map type controls select the map view I.E. Map, Satellite, Hybrid, these selections default to dropdown for small maps and inline boxes for larger maps. If you would like to display as a dropdown only then check the box above', 'bp-mam');  ?></td>
      </tr>
     </table>
    
    <h3><?php _e('Page display options', 'bp-mam') ?></h3>
    <p><b><?php _e('Select the number of  members  you would like to display on the map', 'bp_mam') ?></b></p>

				<p><label for="mam_max_number_of_members"><?php _e( 'Number of  members ', 'bp-mam' ) ?></label>
					
						<input name="mam_max_number_of_members" type="text" id="mam_max_number_of_members" value="<?php echo attribute_escape( $map_max_members ); ?>"  /></p>
               
    <p><b><?php _e('Select the area of the members account /profile page you would like to display the map in N.B defaults to no region', 'bp_mam') ?></b></p>

				<p><label for="mam-region-d"><?php _e( 'bp_before_directory_members_content <i>Displays on the members main index.</i>', 'bp-mam' ) ?></label>
					
						<input name="mam_display_where" type="radio" id="mam-region-d"   value="4" checked="checked"/></p>
			  
     
     <p class="submit">
				<input type="submit" name="submit" value="<?php _e( 'Save Settings', 'bp-mam' ) ?>"/>
			</p>

			<?php
			/* This is very important, don't leave it out. */
			wp_nonce_field( 'update_mam_settings', 'mam_settings' );
			?>
		</form>
		<p>Another great plugin from <a href="http://web-world.co.il">Web-World</a>. Have questions or suggestions? go to <a href="http://web-world.co.il/Contact">Contact</a></p>
		<p>Find this plugin useful? Consider a donation : <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/IL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
	</div>
<?php
}
/* pointless comment to force a new revision and commit */
?>