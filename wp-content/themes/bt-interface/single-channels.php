<?php get_header(); 

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'channel-thumb', 9999, 75 );
}
      global $bp; ?>
	<div id="content">
		<div class="padder">
		<?php //do_action( 'bp_before_blog_page' ) ?>
		<div class="page" id="blog-page" role="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<a href="../">back to channel list</a><br/><br/>
			<h2 class="pagetitle">Channel Details - <?php the_title(); ?></h2>
			<?php
			$custom_fields = get_post_custom( $this_id );
			the_post_thumbnail( 'channel-thumb' ); ?>
			<div id="author-box">
				<br/><h4>Channel Champion</h4>
			<?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_email(), '50' ); }?>
			<?php the_author_posts_link(); ?>
			</div>
<?php		
			echo '<br/><br/><h4>Channel Positioning</h4>';     
        	the_content(); 
			
			echo '<br/><h4>Booktrope Channel Since</h4>';
			echo date("Y-m-d", strtotime($custom_fields['chan_start_date'][0])) . "\n";

//		if (is_admin()) {
			echo '<br/><br/>';     
			echo '<h4>Revenue Goal</h4> $' . $custom_fields['chan_projected_revenue'][0] . ' per '; 
			echo $custom_fields['chan_revenue_per'][0] . ' by '; 
			echo date("Y-m-d", strtotime($custom_fields['chan_revenue_by'][0])) . "\n";
//		}
			echo '<br/><br/><h4>Revenue Contribution</h4>';     
			echo $custom_fields['chan_percentage_of_revenue'][0] . '%';


			echo '<br/><br/><h4>Target Market</h4>';     
			echo $custom_fields['chan_target_market'][0];

			echo '<br/><br/><h4>Success Metrics</h4>';     
			echo $custom_fields['chan_success_metrics'][0];

			echo '<br/><br/><h4>Operational Overhead</h4>';     
			echo $custom_fields['chan_operational_overhead'][0];
			echo '<br/><br/>';     

			echo '<br/><br/><h4>Revenue Reporting Requirements</h4>';     
			echo $custom_fields['chan_revenue_reporting_requirements'][0];
			echo '<br/><br/>';     

			edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>
			<?php endwhile; endif; ?>
		</div><!-- .page -->
		<?php //do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer();