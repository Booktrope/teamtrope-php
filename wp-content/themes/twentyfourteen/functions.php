<?php
/**
 * Twenty Fourteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see twentyfourteen_content_width()
 *
 * @since Twenty Fourteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 474;
}

/**
 * Twenty Fourteen only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentyfourteen_setup' ) ) :
/**
 * Twenty Fourteen setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_setup() {

	/*
	 * Make Twenty Fourteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentyfourteen', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', twentyfourteen_font_url() ) );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'twentyfourteen-full-width', 1038, 576, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'twentyfourteen' ),
		'secondary' => __( 'Secondary menu in left sidebar', 'twentyfourteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'twentyfourteen_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'twentyfourteen_get_featured_posts',
		'max_posts' => 6,
	) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // twentyfourteen_setup
add_action( 'after_setup_theme', 'twentyfourteen_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'twentyfourteen_content_width' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return array An array of WP_Post objects.
 */
function twentyfourteen_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Twenty Fourteen.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'twentyfourteen_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return bool Whether there are featured posts.
 */
function twentyfourteen_has_featured_posts() {
	return ! is_paged() && (bool) twentyfourteen_get_featured_posts();
}

/**
 * Register three Twenty Fourteen widget areas.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_widgets_init() {
	require get_template_directory() . '/inc/widgets.php';
	register_widget( 'Twenty_Fourteen_Ephemera_Widget' );

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Content Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Additional sidebar that appears on the right.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'twentyfourteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears in the footer section of the site.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'twentyfourteen_widgets_init' );

/**
 * Register Lato Google font for Twenty Fourteen.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return string
 */
function twentyfourteen_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'twentyfourteen' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_scripts() {
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfourteen-style', get_stylesheet_uri(), array( 'genericons' ) );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentyfourteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentyfourteen-style', 'genericons' ), '20131205' );
	wp_style_add_data( 'twentyfourteen-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		wp_enqueue_script( 'twentyfourteen-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '20131205', true );
		wp_localize_script( 'twentyfourteen-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'twentyfourteen' ),
			'nextText' => __( 'Next', 'twentyfourteen' )
		) );
	}

	wp_enqueue_script( 'twentyfourteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20140319', true );
}
add_action( 'wp_enqueue_scripts', 'twentyfourteen_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_admin_fonts() {
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'twentyfourteen_admin_fonts' );

if ( ! function_exists( 'twentyfourteen_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_the_attached_image() {
	$post                = get_post();
	/**
	 * Filter the default Twenty Fourteen attachment size.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
	$attachment_size     = apply_filters( 'twentyfourteen_attachment_size', array( 810, 810 ) );
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'twentyfourteen_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="button contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'twentyfourteen' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function twentyfourteen_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} else {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( ( ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width.php' )
		|| is_page_template( 'page-templates/contributors.php' )
		|| is_attachment() ) {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'twentyfourteen_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function twentyfourteen_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'twentyfourteen_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function twentyfourteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'twentyfourteen_wp_title', 10, 2 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';
}

add_filter('gform_pre_render', 'bt_populate_userdropdown');
add_filter('gform_admin_pre_render', 'bt_populate_userdropdown');
add_filter('gform_pre_submission_filter', 'bt_populate_userdropdown');
function bt_populate_userdropdown($form)
{
	if ($form['title'] === 'Accept Team Member')
	{	
		foreach($form['fields'] as &$field)
		{
			if ($field["label"] === "New Team Member")
			{
				$choices = array();
				$user_list = get_users();
				foreach($user_list as $user)
				{
					array_push($choices, array("text" => $user->display_name, "value" => $user->ID ));
				}
				$field["choices"] = $choices;
			}
		}
	}
	else if($form['title'] === 'Paperback Orders & Other Production Costs')
	{
		foreach($form['fields'] as &$field)
		{
			if($field["adminLabel"] === "team_member")
			{
				$choices = array();
				$user_list = get_users();
				foreach($user_list as $user)
				{
					array_push($choices, array("text" => $user->display_name, "value" => $user->ID ));
				}
				$field["choices"] = $choices;				
			}
		}
	}	
	else if($form['title'] === 'Control Numbers')
	{
		$fields = array();
		if(get_post_meta(get_the_ID(), "book_parse_id", true) !== "")
		{
		   $fields["asin"]          = get_post_meta(get_the_ID(), "book_asin", true);
		   $fields["appleId"]       = get_post_meta(get_the_ID(), "book_apple_id", true);
		   $fields["ebookIsbn"]     = get_post_meta(get_the_ID(), "book_ebook_isbn", true);
		   $fields["hardbackIsbn"]  = get_post_meta(get_the_ID(), "book_hardback_isbn", true);
		   $fields["paperbackIsbn"] = get_post_meta(get_the_ID(), "book_isbn", true);
		   $fields["parseId"]       = get_post_meta(get_the_ID(), "book_parse_id", true);
		   
			$form = bt_func_populate_control_numbers($form, $fields);
		}
		else
		{
			$parse_query = new parseQuery("Book");
			$parse_query->whereEqualTo("teamtropeId", intval(get_the_ID()));
			$result = $parse_query->find();
			
			if(count($result->results) >= 1)
			{
				$book = $result->results[0];
				
			   $fields["asin"]          = isset($book->asin)          ? $book->asin : "";
			   $fields["appleId"]       = isset($book->appleId)       ? $book->appleId : "";
			   $fields["ebookIsbn"]     = isset($book->epubIsbn)      ? $book->epubIsbn : "";
		   	$fields["hardbackIsbn"]  = isset($book->hardbackIsbn)  ? $book->hardbackIsbn : "";
		   	$fields["paperbackIsbn"] = isset($book->paperbackIsbn) ? $book->paperbackIsbn : "";
			   $fields["parseId"]       = $book->objectId;
			   
			   $form = bt_func_populate_control_numbers($form, $fields);
			}
		}
	}
	return $form;
}

if ( ! function_exists( 'bt_func_populate_control_numbers' ) ) :
function bt_func_populate_control_numbers($form, $fields)
{
		foreach($form['fields'] as &$field)
		{
			switch($field["adminLabel"])
			{
				case "asin":
					$field["defaultValue"]  = $fields["asin"];
					break;
				case "apple_id":
					$field["defaultValue"]  = $fields["appleId"];
					break;
				case "ebook_isbn":
					$field["defaultValue"]  = $fields["ebookIsbn"];
					break;
				case "hardback_isbn":
					$field["defaultValue"]  = $fields["hardbackIsbn"];
					break;
				case "paperback_isbn":
					$field["defaultValue"]  = $fields["paperbackIsbn"];
					break;
				case "parse_id":
					$field["defaultValue"]  = $fields["parseId"];
					break;
			}
		}
	return $form;
}
endif;

add_filter("gform_admin_pre_render", "bt_gform_edit_date_filter",20);
 
function bt_gform_edit_date_filter($form){
 
    //only populating drop down for form of your choice
    if($form["title"] != 'Editing Complete Date')
        return $form;
 
    foreach($form['fields'] as &$field){
    	if ($field["label"] === "Editing Complete Committed Date") {

 
//        if($field['type'] == 'date'){
//            if(!isset($_REQUEST['input_'.$field['id']])){
                $field['value'] = date('Y/m/d',strtotime('+1 day'));
                echo  $field['value'];
//                $input_value = $field['value'];
//                add_filter('gform_field_value_arrive', 
//                create_function("", "return '$input_value';"));
 //           }
        }
        if ($field["label"] === "Go to Project") {
        	$field['value'] = "got the project";
        }
    }
 
    return $form;
}


if ( ! function_exists( 'bt_func_get_amazon_json_for_asin' ) ) :
function bt_func_get_amazon_json_for_asin($asin)
{
	//look up in cache
	//cache hit ?  return : call parse, wrap results, save in cache, return result
	$json_result = get_transient( $asin."_amazon");
	if(empty($json_result)) 
	{
		$parse_data = bt_func_load_amazon_stats_from_parse($asin);
		$json_result = bt_func_wrap_parse_results($parse_data);

		set_transient($asin."_amazon", $json_result, HOUR_IN_SECONDS);
	}
	return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_get_amazon_sales_for_asin' ) ) :
function bt_func_get_amazon_sales_for_asin($asin)
{
	//look up in cache
	//cache hit ?  return : call parse, wrap results, save in cache, return result
	$json_sresult = get_transient( $asin."_amazon_sales");
	if(empty($json_result)) 
	{
		$parse_data = bt_func_load_amazon_sales_stats_from_parse($asin);
		$json_result = bt_func_wrap_parse_sales_results($parse_data);

		set_transient($asin."_amazon_sales", $json_result, 8 * HOUR_IN_SECONDS);
	}
	return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_wrap_parse_results' ) ) :
function bt_func_wrap_parse_results($parse_data)
{

	if($parse_data["title"] === "" && $parse_data["price"] === "" && $parse_data["sales_rank"] === "")
	{
		return "";
	}

   $json_result = '';
   $json_result .= "     {";
   $json_result .= "            chart: {";
   $json_result .= "                zoomType: 'xy'";
   $json_result .= "            },";
   $json_result .= "            title: {";
   $json_result .= "             text: '".preg_replace('/\'/', '\\\'',$parse_data["title"])."'";
   $json_result .= "            },";
   $json_result .= "            subtitle: {";
   $json_result .= "                text: 'Amazon Price and Ranking Numbers'";

  $json_result .= "            },";
   $json_result .= "            xAxis: [{";
   $json_result .= "                 type: 'datetime',";
   $json_result .= "                 dateTimeLabelFormats: {";
   $json_result .= "                    month: '%e. %b',";
   $json_result .= "                    year: '%b'";
   $json_result .= "                }";
   $json_result .= "            }],";
   $json_result .= "            yAxis: [{";
   $json_result .= "                title: {";
   $json_result .= "                    text: 'Price',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#AED991'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                min: 0,";
   $json_result .= "                labels: {";
   $json_result .= "                    format: '\${value}',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#AED991'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                opposite: true";
   $json_result .= "            },";
   $json_result .= "                {";
   $json_result .= "                labels: {";
   $json_result .= "                    format: '{value}',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#1394BB'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                min: 0,";
   $json_result .= "                title: {";
   $json_result .= "                    text: 'Ranking',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#1394BB'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                reversed: true,";
   $json_result .= "            }],";
   $json_result .= "            tooltip: {";
   $json_result .= "                shared: true";
   $json_result .= "            },";
   $json_result .= "            legend: {";
   $json_result .= "                layout: 'vertical',";
   $json_result .= "                align: 'left',";
   $json_result .= "                x: 20,";
   $json_result .= "                verticalAlign: 'top',";
   $json_result .= "                y: 0,";
   $json_result .= "                floating: true,";
   $json_result .= "                backgroundColor: '#FFFFFF'";
   $json_result .= "            },";
   $json_result .= "            series: [{";
   $json_result .= "                name: 'Price',";
   $json_result .= "                color: '#AED991',";
   $json_result .= "                type: 'area',";
   $json_result .= "                data: [".$parse_data["price"]."],";
   $json_result .= "                tooltip: {";
   $json_result .= "                    valuePrefix: '$',";
   $json_result .= "                    valueDecimals: '2'";
   $json_result .= "                }";
   $json_result .= "            },{";
   $json_result .= "                name: 'Ranking',";
   $json_result .= "                color: '#1394BB',";
   $json_result .= "                type: 'spline',";
   $json_result .= "                yAxis: 1,";
   $json_result .= "                data: [".$parse_data["sales_rank"]."],";
   $json_result .= "                tooltip: {";
   $json_result .= "                    valueSuffix: ' '";
   $json_result .= "                }";
   $json_result .= "            }]";
   $json_result .= "      }";
   return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_wrap_parse_sales_results' ) ) :
function bt_func_wrap_parse_sales_results($parse_data)
{
	if($parse_data["title"] === "" && (isset($parse_data["price"]) && $parse_data["price"] === "") && $parse_data["sales_rank"] === "")
	{
		return "";
	}
	$json_result = '';
	$json_result .= "     {";
	$json_result .= "           chart: {";
	$json_result .= "                zoomType: 'xy'";
	$json_result .= "            },";
	$json_result .= "            title: {";
	$json_result .= "            	 text: '".preg_replace('/\'/', '\\\'',$parse_data["title"])."'";
	$json_result .= "            },";
	$json_result .= "            subtitle: {";
	$json_result .= "                text: 'Sales Intelligence Estimates (Amazon US Only)'";
	$json_result .= "            },";

	$json_result .= "       xAxis: [{";
	$json_result .= "            type: 'datetime',";
	$json_result .= "            dateTimeLabelFormats: {";
	$json_result .= "                month: '%e. %b',";
	$json_result .= "                year: '%b'";
	$json_result .= "            }";
	$json_result .= "        }],";
	$json_result .= "        yAxis: {";
	$json_result .= "                title: {";
	$json_result .= "                    text: 'Sales Estimate'";
	$json_result .= "                },";
	$json_result .= "                min: 0";
	$json_result .= "            },";
	$json_result .= "        tooltip: {";
	$json_result .= "            shared: true";
	$json_result .= "        },";
	$json_result .= "        series: [{";
	$json_result .= "            name: 'Sales',";
	$json_result .= "            color: '#1394BB',";
	$json_result .= "            type: 'column',";
	$json_result .= "            data: [".$parse_data["sales"]."]";
	$json_result .= "      	 }]";
	$json_result .= "      }";
	return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_load_amazon_stats_from_parse' ) ) :
function bt_func_load_amazon_stats_from_parse($asin)
{

	$payload = array(
		'price'			=> '',
		'sales_rank'	=> '',
		'title'			=> '',
	);

	$cloud = new parseCloud("getCrawlDataForAsin");



	$cloud->__set("asin",$asin);
print_r($cloud, true);
	
	//TODO: add support for these paraameters
	//$cloud->__set("skip", 150);
	//$cloud->__set("limit",1000);
	// Running the cloud function
	$results = $cloud->run();
	
	$json_result = json_encode($results);

   $first = true;
   $first_price = true;
   $price_str = "";
   $sales_rank_str = "";

	$cutOffDate = date(strtotime("2014-02-07 20:00:00"));

   $payload['title'] = $results->result->title;
	foreach($results->result->crawl as $result)
	{
		if(!$first) { $sales_rank_str.=","; }
		
		$date_parts = explode('T', substr($result->crawl_date->iso, 0, strlen($result->crawl_date->iso)-5));
		
		$crawl_date = explode('-', $date_parts[0]);
		$crawl_time = explode(':', $date_parts[1]);
		
		$year = $crawl_date[0];
		$month = $crawl_date[1]-1;
		$day = $crawl_date[2];
		
		$hour = $crawl_time[0];
		$minute = $crawl_time[1];
		$second = $crawl_time[2];
		
		$price = $result->kindle_price;
		$sales_rank = $result->sales_rank;
		
		$crawlDate = strtotime("$year-$month-$day $hour:$minute:$second");
		
		if ($price > 0 
		|| (property_exists($result, 'got_price') && $result->got_price != null && $result->got_price) 
		|| (!property_exists($result, 'got_price') && ($crawlDate < $cutOffDate)))
		{
			if(!$first_price) { $price_str.= ","; }
		   $price_str.= sprintf("[Date.UTC(%d,%d,%d,%d,%d,%d), %s]", $year, $month, $day, $hour, $minute, $second, $price);
   	   $first_price = false;   	
   	}
   	      
      $sales_rank_str.= sprintf("[Date.UTC(%d,%d,%d,%d,%d,%d), %s]", $year, $month, $day, $hour, $minute, $second, $sales_rank);
		
		$first = false;
	}
	$payload['price'] = $price_str;
	$payload['sales_rank'] = $sales_rank_str;
	
	return $payload;

}
endif;

if ( ! function_exists( 'bt_func_load_amazon_sales_stats_from_parse' ) ) :
function bt_func_load_amazon_sales_stats_from_parse($asin)
{
	$payload = array(
		'sales'			=> '',
		'title'			=> '',
	);

	$cloud = new parseCloud("getSalesDataForAsin");

	$cloud->__set("asin",$asin);
	
	//TODO: add support for these paraameters
	//$cloud->__set("skip", 150);
	//$cloud->__set("limit",1000);
	// Running the cloud function
	$results = $cloud->run();
	
	$json_result = json_encode($results);

   $first = true;
   $dailySales_str = "";
   $sales_rank_str = "";

   $payload['title'] = $results->result->title;
	foreach($results->result->crawl as $result)
	{
		if(!$first) { $dailySales_str.= ","; }
		
		$date_parts = explode('T', substr($result->crawlDate->iso, 0, strlen($result->crawlDate->iso)-5));
		
		$crawl_date = explode('-', $date_parts[0]);
		$crawl_time = explode(':', $date_parts[1]);
		
		$year = $crawl_date[0];
		$month = $crawl_date[1]-1;
		$day = $crawl_date[2];
		
		$hour = $crawl_time[0];
		$minute = $crawl_time[1];
		$second = $crawl_time[2];
		
		$sales = $result->dailySales;
		
        $dailySales_str.= sprintf("[Date.UTC(%d,%d,%d,%d,%d,%d), %s]", $year, $month, $day,
      	0, 0, 0, $sales);
		
		$first = false;
	}
	$payload['sales'] = $dailySales_str;
	
	return $payload;

}
endif;

function bt_func_load_promo_ready_books_from_parse($codeset)
{
	$cloud = new parseCloud("getPromoReady");
	$cloud->__set("","");
	//$asin = "B00CY22GOU";
	//$cloud = new parseCloud("getCrawlDataForAsin");
	//$cloud->__set("asin",$asin);
	
	$results = $cloud->run();
	$payload = 
"<div id='parse-data'><table class='sortable'>
	<tr>
   		<th>No.</th>
<th>Title</th>
<th>Author</th>
<th>ASIN</th>
<th>Price</th>
<th>No. Stars</th>
<th>No. Reviews</th>

   	</tr>";
	$i = 1;
	foreach($results->result as $result)
	{
		$payload .= 	
		"<tr>
			<td>" . $i . "</td><td>" . $result->title . "</td><td>" . $result->author . "</td><td><a target='blank' href='http://amzn.com/" . $result->asin . "'>". $result->asin . "</a></td><td>" . $result->kindle_price . "</td><td>" . $result->average_stars . "</td><td>" . $result->num_of_reviews . "</td>
		</tr>";
		$i = $i + 1;
	}
	$payload .= "</table></div>";
	
	return $payload;
}

function daysInStatus($proj, $prompt, $category)
{
	global $wpdb;
	$query = "SELECT p . * 
	FROM wp_posts p
	INNER JOIN wp_postmeta pm ON pm.post_id = p.ID
	INNER JOIN wp_postmeta pm2 ON pm2.post_id = p.ID
	WHERE (p.post_title = '" . $category . "' ) AND
	
	(
	pm.meta_key =  'project_id'
	AND pm.meta_value = " . $proj->ID . "
	)
	ORDER BY p.post_date DESC
	LIMIT 1";

	$statuses = $wpdb->get_results($query);

	$status_date = "";
	foreach($statuses as $status)
	{
//		$status_date = $status->post_date;

 		$status_custom = get_post_custom($status->ID);
 		$status_date = $status_custom['status_date'][0];
		break; // just need the 1st one
	} 
	if ( $status_date == "" ) {
		//$days_in_status = '&#8734;';
		$days_in_status = '-';
	} else {
		$now = time(); 
    	$datediff = $now - strtotime($status_date);
    	$daysInStep = floor($datediff/(60*60*24));
    	if (isset($book_pcr_step)) {
	    	$PCRStepDays = getPCRDaysForStep($book_pcr_step);
	    	
	    	if ( $PCRStepDays <= $daysInStep ) {
	    		$color = "red";
				$sched = "(Behind Schedule)";

	    	} elseif ( $daysInStep >= floor(.75*$PCRStepDays) ) {
	    		$color = "rgb(197, 197, 22)";
				$sched = "(Deadline Approaching)";

	    	} else {
				$color = "green";
				$sched = "(On Schedule)";
	    	}
	    	
	    } else {
	    	$color = "black";
	    }
	    if ( $prompt ) {
	    	$sched_txt = $sched;
	    }
		$days_in_status = "<span style='color:" . $color . "' ><strong>" . $daysInStep . " </strong></span> " .  $sched_txt . "";
	}
//	wp_reset_query();
	return $days_in_status;
}

function getPCRDaysForStep ($book_pcr_step) 
{
	$args = array(	'name'=>$book_pcr_step, 
					'post_type'=>'pcr', 
					'limit'=> '1'
				);
	$pcr = new WP_Query($args);
	$i = 1;
	while ($pcr->have_posts()) : $pcr->the_post();            	
		$pcr_name = get_the_title();
		$pcr_id = get_the_ID();
		$pcr_data = get_post_custom($pcr_id);
		$i++;
	endwhile;
	$days = $pcr_data['pcr_days_to_complete_step'][0];
	wp_reset_query();
	return $days;
}

function number_of_posts_on_archive($query){
    if ($query->is_archive) {
            $query->set('posts_per_page', 10000);
   }
    return $query;
}
 
add_filter('pre_get_posts', 'number_of_posts_on_archive');