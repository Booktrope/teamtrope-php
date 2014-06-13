<?php get_header() ?>
<div id="content">
	<div class="padder">
<div id="homepage"><!-- start #homepage-->
	<?php
	$slideshow = get_option('dev_studio_slideshow');

	if ($slideshow == "yes"){
		locate_template( array( '/library/components/slideshow.php' ), true );
	}

	if(get_option('dev_studio_homepage_latest_posts',false)=='yes'){
	?>
		<div class="padder">
			<?php if($bp_existed == 'true'){ do_action( 'bp_before_blog_home' );} ?>
			<div class="page" id="blog-latest"><!-- start #blog-latest -->
				<?php
				if( $bp_existed == 'true' ){
					do_action( 'bp_before_blog_post' );
					bp_wpmu_blogpageloop();
					do_action( 'bp_after_blog_post' );
				} else {
					wpmu_blogpageloop();
				}
				locate_template( array( '/library/components/pagination.php' ), true );
				?>
			</div><!-- end #blog-latest -->
			<?php if($bp_existed == 'true'){ do_action( 'bp_after_blog_home' ); } ?>
		</div>
	<?php
	}

	$featurecontent_on = get_option('dev_studio_feature_show');
	if ($featurecontent_on == "yes"){
		locate_template( array( '/library/components/feature-content.php' ), true );
	}
	?>
</div><!-- end #homepage -->
	</div><!-- .padder -->
</div><!-- #content -->
	<?php get_sidebar() ?>
<?php get_footer() ?>