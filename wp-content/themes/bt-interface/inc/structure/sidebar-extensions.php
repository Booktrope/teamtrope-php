<?php
/**
 * Shows the sidebar content.
 *
 * @package 		Theme Horse
 * @subpackage 		Interface
 * @since 			Interface 1.0
 * @license 		http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link 			http://themehorse.com/themes/interface
 */

/****************************************************************************************/

add_action( 'interface_left_sidebar', 'interface_display_left_sidebar', 10 );
/**
 * Show widgets of left sidebar.
 *
 * Shows all the widgets that are dragged and dropped on the left Sidebar.
 */
function interface_display_left_sidebar() {
	// Calling the left sidebar if it exists.
	dynamic_sidebar( 'interface_left_sidebar' );
}

/****************************************************************************************/

add_action( 'interface_right_sidebar', 'interface_display_right_sidebar', 10 );
/**
 * Show widgets of right sidebar.
 *
 * Shows all the widgets that are dragged and dropped on the right Sidebar.
 */
function interface_display_right_sidebar() {
	// Calling the right sidebar if it exists.
	dynamic_sidebar( 'interface_right_sidebar' );

}

/****************************************************************************************/

add_action( 'interface_contact_page_sidebar', 'interface_display_contact_page_sidebar', 10 );
/**
 * Show widgets on Contact Page Template's sidebar.
 *
 * Shows all the widgets that are dragged and dropped on the Contact Page Sidebar.
 */
function interface_display_contact_page_sidebar() {
	// Calling the conatact page sidebar if it exists.
	dynamic_sidebar( 'interface_contact_page_sidebar' );
}

/****************************************************************************************/

add_action( 'interface_footer_sidebar', 'interface_display_footer_sidebar', 10 );
/**
 * Show widgets on Footer of the theme.
 *
 * Shows all the widgets that are dragged and dropped on the Footer Sidebar.
 */
function interface_display_footer_sidebar() {
	if( is_active_sidebar( 'interface_footer_sidebar' ) || is_active_sidebar( 'interface_footer_column2' ) || is_active_sidebar( 'interface_footer_column3' ) ) {
		?>

<div class="widget-wrap">
  <div class="container">
    <div class="widget-area clearfix">
      <div class="one-third">
        <?php
						// Calling the footer column 1 sidebar if it exists.
						dynamic_sidebar( 'interface_footer_sidebar' );
						
						?>
      </div>
      <!-- .one-third -->
      
      <div class="one-third">
        <?php
						// Calling the footer column 2 sidebar if it exists.
						dynamic_sidebar( 'interface_footer_column2' );
						?>
      </div>
      <!-- .one-third -->
      
      <div class="one-third">
        <?php
						// Calling the footer column 3 sidebar if it exists.
						dynamic_sidebar( 'interface_footer_column3' );
						?>
      </div>
      <!-- .one-third --> 
    </div>
    <!-- .widget-area --> 
  </div>
  <!-- .container --> 
</div>
<!-- .widget-wrap -->
<?php
	}
}
?>
