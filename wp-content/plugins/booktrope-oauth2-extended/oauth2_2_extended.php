<?php
/*
 * Plugin Name: Booktrope OAuth2 Extended
 * Plugin URI: http://booktrope.com
 * Description: Provides extensions for the OAuth2 Extension
 * Version: 0.1
 * Author: Brian Ronald
 * Author URI: http://booktrope.com
 *
 * This plugin will extend the /oauth/me method so that it can provide additional information about
 * the user, such as the Avatar URL.
 */

add_filter('wo_endpoints', 'wo_extend_resource_api', 2);

function wo_extend_resource_api ($methods)
{
	$methods['me'] = array('func'=>'_bt_wo_me');
	return $methods;
}

/**
 * Replaces the default me endpoint
 * @param string|null $token [description]
 */
function _bt_wo_me ($token = null)
{
	/**
	 * Added 3.0.2 to handle access tokens not assigned to user
	 */
	if (empty($token) || !isset($token['user_id']) || $token['user_id'] == 0) {
		$response = new OAuth2\Response();
		$response->setError(400, 'invalid_request', 'Missing or invalid access token');
		$response->send();
		exit;
	}

	$user_id = &$token['user_id'];

	/** @var wpdb $wpdb */
	global $wpdb;
	$me_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE ID = $user_id", ARRAY_A);

	// Grab the user's Teamtrope Role(s)
	$sql = "SELECT wp_bp_xprofile_data.value
			FROM wp_bp_xprofile_data
			INNER JOIN wp_bp_xprofile_fields ON wp_bp_xprofile_fields.id = wp_bp_xprofile_data.field_id
			WHERE wp_bp_xprofile_fields.name = 'Role(s)'
			AND wp_bp_xprofile_data.user_id = {$user_id}";
	$roles_serialized = $wpdb->get_row($sql, ARRAY_A);

	// Convert from Serialized PHP to JSON
	if(! empty($roles_serialized)) {

		$roles_array = unserialize($roles_serialized['value']);

		if(! empty($roles_array)) {
			$me_data['roles'] = json_encode($roles_array);
		} else {
			$me_data['roles'] = array();
		}
	}

	// Remove sensitive data
	unset($me_data['user_pass']);
	unset($me_data['user_activation_key']);
	unset($me_data['user_url']);

	// Grab the Avatar from BuddyPress
	$me_data['avatar_url'] = bp_core_fetch_avatar(
		array(
			'item_id'   => $user_id,
			'object'    => 'user',
			'type'      => 'full',
			'no_grav'   => false, // Will return a default image if not a generic Gravatar URL
			'html'      => false // Do not encode the URL in an img tag
		)
	);

// in case we want to determine if it's a gravatar url
//	$isGravatarUrl = (stripos($avatarUrl, 'gravatar.com', 0) !== FALSE);

	$response = new OAuth2\Response($me_data);
	$response->send();
	exit;
}