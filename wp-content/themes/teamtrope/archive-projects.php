<?php get_header() ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">
			<h1>Book Projects</h1>
			<p>Here is a list of our in-progress books available to the community. Learn about the books below and reach out to a Booktrope staff member to express interest in a project.</p>
<?php 
	$num_posts = 1;
	global $query_string;
	query_posts($query_string . "&post_type=projects&post_status=publish&&order=ASC&posts_per_page=10");
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<h3><?php echo $num_posts++;?>. <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
<strong>Status: </strong>
<?php
	$this_id = get_the_ID();
	$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
	echo strip_tags($terms_as_text);
?>
<?php the_excerpt(); ?>


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
