<h1>User Avatars</h1>
<?php

//stop memory limit errors from occurring as a result of loading all projects
ini_set('memory_limit', '1024M');

$results = array();

/** @var wpdb $wpdb */
global $wpdb;
$users = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users", ARRAY_A);

foreach($users as $u) {
	$user_id = $u['ID'];
	$avatar_url = bp_core_fetch_avatar(
		array(
			'item_id'   => $user_id,
			'object'    => 'user',
			'type'      => 'full',
			'no_grav'   => false, // Will return a default image if not a generic Gravatar URL
			'html'      => false // Do not encode the URL in an img tag
		)
	);
	$results[] = "{$user_id}, {$avatar_url}\n";
}

printf("Total users: %d<br />", count($results));
?>
<textarea cols="100" rows="35"><?php foreach($results as $row) { print $row; } ?></textarea>
<?php
