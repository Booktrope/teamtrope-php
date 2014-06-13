<?php
/*
Plugin Name: BP Members Avatar Map
Description: Add a map display with all the members location with their avatar in buddypress.
Author: Vardi
Author URI: http://web-world.co.il
Plugin URI: http://web-world.co.il/wp-plugins/bp-members-avatar-map
Version: 1.3

License: CC-GNU-GPL http://creativecommons.org/licenses/GPL/2.0/

*/


/* Only load the plugin if BP is loaded and initialized. */
function bp_mam_init() {
	
	require( dirname( __FILE__ ) . '/mam.php' );
}
add_action( 'bp_init', 'bp_mam_init' );

function add_location_after_register(){
$user_location= xprofile_get_field_data( "Location" ,bp_core_get_userid( $_POST['signup_username'] ));	
$geourl = "http://nominatim.openstreetmap.org/search?q=".urlencode($user_location)."&format=json";
$c = curl_init();
curl_setopt($c, CURLOPT_URL, $geourl);
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$latlongJson = json_decode(curl_exec($c),true);
curl_close($c);
if($latlongJson[0]['lat']!=null && $latlongJson[0]['lon']!=null){
$latlong=$latlongJson[0]['lat'].",".$latlongJson[0]['lon'];
xprofile_set_field_data( 'Latitude and Longitude', bp_core_get_userid( $_POST['signup_username'] ), $latlong);}
	
	}
add_action( 'template_notices', 'add_location_after_register' );

function update_location_after_profile_edit(){
$user_location= xprofile_get_field_data( "Location" ,bp_displayed_user_id() );	
$geourl = "http://nominatim.openstreetmap.org/search?q=".urlencode($user_location)."&format=json";
$c = curl_init();
curl_setopt($c, CURLOPT_URL, $geourl);
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$latlongJson = json_decode(curl_exec($c),true);
curl_close($c);
if($latlongJson[0]['lat']!=null && $latlongJson[0]['lon']!=null){
$latlong=$latlongJson[0]['lat'].",".$latlongJson[0]['lon'];
xprofile_set_field_data( 'Latitude and Longitude', bp_displayed_user_id(), $latlong);}

}

add_action( 'bp_after_profile_edit_content', 'update_location_after_profile_edit' );

/* end stuff for this file */
?>