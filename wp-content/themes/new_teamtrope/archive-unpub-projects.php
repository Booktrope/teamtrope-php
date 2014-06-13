<?php get_header() ?>
<link rel="stylesheet" id="gforms_css-css" href="/wp-content/plugins/gravityforms/css/forms.css" type="text/css" media="all">
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">
			<h1>Unpublished Book Projects</h1>
			<p>Here is a list of book that have not been published yet.</p>
<?php 
	//$num_posts = 1;
	global $query_string;
//	query_posts($query_string . "&post_type=projects&post_status=publish&&order=DESC&posts_per_page=-1");
	$query_string = $query_string . "&post_type=projects&post_status=publish&&order=DESC&posts_per_page=2000";

	//global $wp_query; 
	$myquery = wp_parse_args($query_string);
	$myquery['posts_per_page'] = '-1';
	$myquery['tax_query'] = array(
    	array(
        	'taxonomy' => 'status',
       		'terms' => array('published'),
        	'field' => 'slug',
        	'operator' => 'NOT IN',
        	'nopaging' => true
    	),
	);
	query_posts($myquery);

	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
<h4>Status: 
<?php
	$this_id = get_the_ID();
	$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
	$status_value = ( explode(',',strip_tags($terms_as_text) . ',EndOfList' ) ); 
	echo strip_tags($terms_as_text);
?></h4>
			<div class='gform_wrapper' id='gform_wrapper_1' >
				<div id='gf_progressbar_wrapper_7' class='gf_progressbar_wrapper'>
					<div class='gf_progressbar'>
<?php					if ( in_array( "Manuscript (Pre-Edit)",$status_value ) ) { 
							$progress = '20%';
						} elseif ( in_array( "In-editing",$status_value ) ) {
							$progress = '40%';
						} elseif ( in_array( "In Proofreading",$status_value ) ) {
							$progress = '60%';
						} elseif ( in_array( "Pre-publication",$status_value ) ) {
							$progress = '85%';
						} elseif ( in_array( "Published",$status_value ) ) {
							$progress = '100%';
						}
						if ( $progress == "" ) { $progress = "5%"; } ?>
						<div class='gf_progressbar_percentage percentbar_blue percentbar_20' style='width:<?php echo $progress; ?>'><span><?php echo $progress; ?></span></div>
					</div> <!-- end of gf_progressbar -->
				</div> <!-- end of gf_progressbar_wrapper -->
			</div> <!-- end of gform_wrapper -->
<?php // the_excerpt(); ?>
<?php endwhile; 
endif; ?>
<div class="navigation">
	<div class="alignleft"><?php next_posts_link('Previous entries') ?></div>
	<div class="alignright"><?php previous_posts_link('Next entries') ?></div>
</div>
<?php wp_reset_query(); ?>
		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer(); ?>
