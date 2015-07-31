<?php
/*
Plugin Name: BookTrope - Team Projects
Plugin URI: http://booktrope.com
Description: Custom Post Type Plugin to manage projects
Version: 1.1.2
Author: Andy Roberts 
Author URI: http://booktrope.com/
*/


/* Only load the plugin functions if BuddyPress is loaded and initialized. */
function bp_projects_init() {
	require( dirname( __FILE__ ) . '/bp-projects.php' );
}
add_action( 'bp_init', 'bp_projects_init' );

/**
 * Display the metabox
 */
function url_custom_metabox() {
	global $post;
	$urllink = get_post_meta( $post->ID, 'urllink', true );
	$urldesc = get_post_meta( $post->ID, 'urldesc', true );

	if ( !preg_match( "/http(s?):\/\//", $urllink )) {
		$errors = 'Url not valid';
		$urllink = 'http://';
	} 

	// output invlid url message and add the http:// to the input field
	if( $errors ) { echo $errors; } ?>

	<p><label for="siteurl">Url:<br />
		<input id="siteurl" size="37" name="siteurl" value="<?php if( $urllink ) { echo $urllink; } ?>" /></label></p>
	<p><label for="urldesc">Description:<br />
		<textarea id="urldesc" name="urldesc" cols="45" rows="4"><?php if( $urldesc ) { echo $urldesc; } ?></textarea></label></p>
<?php
}

/**
 * Get and return the values for the URL and description
 */
function get_url_desc_box() {
	global $post;
	$urllink = get_post_meta( $post->ID, 'urllink', true );
	$urldesc = get_post_meta( $post->ID, 'urldesc', true );

	return array( $urllink, $urldesc );
}

add_filter( 'groups_custom_group_fields_editable', 'group_details_markup' );
add_action( 'groups_group_details_edited', 'group_details_save' );
add_action( 'groups_created_group',  'group_details_save' );

function group_details_markup() {
global $bp;
	
	$group_text = groups_get_groupmeta( $bp->groups->current_group->id, 'e2e_text' );
	
	?>	
<h2>Details</h2>
	<?php $url = groups_get_groupmeta( $bp->groups->current_group->id, 'project_url' ); ?>
	<label for="group-url">Book Project URL</label>
	<input type="text" name="group-url" id="group-url" value="<?php echo $url ?>" />
	<span class='description'><?php _e('Be sure to include http://');?></span>
	<?php

	return;
}

function group_details_save( $group_id ) {
	global $bp, $wpdb;
	$key = 'group-url';
	if ( isset( $_POST[$key] ) ) {
		$value = $_POST[$key];
		groups_update_groupmeta( $group_id, 'project_url' , $value );
	}
}

function extra_fields_output(){
	global $bp;
	$img_path = plugin_dir_url( __FILE__ );
  	$group_url = groups_get_groupmeta( $bp->groups->current_group->id, 'project_url' ); 
	$projectarray = explode(",", $group_url);
	if (is_array($projectarray) && count($projectarray) > 1 ) { 
		echo '<h4><strong style="color:#777;">Projects: </strong><br/></h4>';
		foreach ($projectarray as $url ) {
			$url = trim($url);
			$postid = url_to_postid( $url );
			$cover_asset = get_post_meta($postid, "book_ebook_front_cover");
			if ( $postid <> 0 ) {
				$project_name = get_the_title($postid);
			} else {
				$project_name = "go to: " . $url;
			}
			$cover_image = wp_get_attachment_image_src($cover_asset[0]);
			if ( $cover_image[0] == "" ) { 
				$cover_image[0] = $img_path . "comingsoon.png";
			}
	?>
	<a href="<?php echo $url; ?>"><img src="<?php echo $cover_image[0]; ?>" height="75" width="58" title="<?php echo $project_name; ?>" alt="<?php echo $project_name; ?>" ></a>
<?php
		}
	}
	else { 
		echo '<strong style="color:#777;">Go to Author Project Grid: <br/></strong>';
		$url = trim($group_url);
		$postid = url_to_postid( $url );
		$cover_asset = get_post_meta($postid, "book_ebook_front_cover");
		if ( $postid <> 0 ) {
			$project_name = get_the_title($postid);
		} else {
			$project_name = "go to: " . $url;
		}
		$cover_image = wp_get_attachment_image_src($cover_asset[0]);
		if ( $cover_image[0] == "" ) { 
			$cover_image[0] = $img_path . "comingsoon.png";
		}
		?>
		<a href="<?php echo $url; ?>"><img src="<?php echo $cover_image[0]; ?>" height="75" width="58" title="<?php echo $project_name; ?>" alt="<?php echo $project_name; ?>" ></a>
<?php
	}
}
add_action( 'bp_group_header_meta', 'extra_fields_output'  );

// filter for Gravity Forms Directory Plugin
add_filter('kws_gf_directory_lead_filter','directory_context_filter');
                    
// return only array entries for the current page
function filter_dir($value) {
    $permalink = get_permalink();
    if ($value["source_url"] === $permalink) {
        return true;
    }
    return false;
}
 
// filter for directory displays
function directory_context_filter($content = false) {
    $content = array_filter($content,"filter_dir");
    return $content;
}

if ( ! defined( 'BP_DEFAULT_COMPONENT' ) )
  define( 'BP_DEFAULT_COMPONENT', 'profile' );