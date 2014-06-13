<?php
/*
Plugin Name: Booktrope Statuses
Plugin URI: http://booktrope.com
Description: Create the Status post type.
Version: 0.1
Author: Justin Jeffress
Author URI: http://booktrope.com
License: GPL2
*/

add_action('init', 'cptui_register_my_cpt_statuses');
function cptui_register_my_cpt_statuses() 
{
	register_post_type('statuses', array(
		'label' => 'Statuses',
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'statuses', 'with_front' => true),
		'query_var' => true,
		'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
		'taxonomies' => array('status'),
		'labels' => array (
			'name' => 'Statuses',
			'singular_name' => 'Status',
			'menu_name' => 'Statuses',
			'add_new' => 'Add Status',
			'add_new_item' => 'Add New Status',
			'edit' => 'Edit',
			'edit_item' => 'Edit Status',
			'new_item' => 'New Status',
			'view' => 'View Status',
			'view_item' => 'View Status',
			'search_items' => 'Search Statuses',
			'not_found' => 'No Statuses Found',
			'not_found_in_trash' => 'No Statuses Found in Trash',
			'parent' => 'Parent Status',
			)
		)
	); 
}