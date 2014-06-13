<?php
/*
Plugin Name: Booktrope Process Control
Plugin URI: http://booktrope.com
Description: Create the process control post type.
Version: 0.1
Author: blahblahyaya
Author URI: http://booktrope.com
License: GPL2
*/

add_action('init', 'cptui_register_my_cpt');
function cptui_register_my_cpt() {
	register_post_type('pcr', array( 
	'label' => 'PCRs',
	'description' => 'Information to control which project steps are available.',
	'public' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'rewrite' => array('slug' => 'pcr', 'with_front' => '1'),
	'query_var' => true,
	'has_archive' => true,
	'exclude_from_search' => true,
	'supports' => array('title','editor','revisions','page-attributes','thumbnail'),
	'taxonomies' => array('status'),
	'labels' => array (
	  'name' => 'PCRs',
	  'singular_name' => 'PCR',
	  'menu_name' => 'PCRs',
	  'add_new' => 'Add PCR',
	  'add_new_item' => 'Add New PCR',
	  'edit' => 'Edit',
	  'edit_item' => 'Edit PCR',
	  'new_item' => 'New PCR',
	  'view' => 'View PCR',
	  'view_item' => 'View PCR',
	  'search_items' => 'Search PCRs',
	  'not_found' => 'No PCRs Found',
	  'not_found_in_trash' => 'No PCRs Found in Trash',
	  'parent' => 'Parent PCR',
	)
	) ); 
}

function phases_init () {
	register_taxonomy('phase','pcr',array( 
	'hierarchical' => false,
	'label' => 'Phase',
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => array('slug' => ''),'singular_label' => 'Phase') );
}
add_action( 'init', 'phases_init' );