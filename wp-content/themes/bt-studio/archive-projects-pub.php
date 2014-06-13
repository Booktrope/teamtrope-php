<?php get_header() ?>
<link rel="stylesheet" id="gforms_css-css" href="/wp-content/plugins/gravityforms/css/forms.css" type="text/css" media="all">
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">
			<h1>Book Projects</h1>
			<p>Here is a list of book projects currently in the system.</p>
<?php 

	$categories = get_terms('status', 'orderby=count&order=ASC&hide_empty=1');
	foreach( $categories as $category ): 
?>
 <br/>
 <h3><?php echo $category->name; 
 
 $posts = get_posts(array(
 'post_type' => 'projects',
 'taxonomy' => $category->taxonomy,
 'term' => $category->slug,
 'orderby' => 'title',
 'order' => 'ASC',
 'nopaging' => true,
 ));
 echo " (" . count($posts) . ")"; ?></h3>
 			<div class='gform_wrapper' id='gform_wrapper_1' >
				<div id='gf_progressbar_wrapper_7' class='gf_progressbar_wrapper'>
					<div class='gf_progressbar'>
<?php					if ( $category->name == "Manuscript (Pre-Edit)" ) { 
							$progress = '20%';
						} elseif ( $category->name == "In Editing" ) {
							$progress = '40%';
						} elseif ( $category->name == "In Proofreading" ) {
							$progress = '60%';
						} elseif ( $category->name == "Pre-publication" ) {
							$progress = '85%';
						} elseif ( $category->name == "Published" ) {
							$progress = '100%';
						}
						if ( $progress == "" ) { $progress = "5%"; } ?>
						<div class='gf_progressbar_percentage percentbar_blue percentbar_20' style='width:<?php echo $progress; ?>'><span><?php echo $progress; ?></span></div>
					</div> <!-- end of gf_progressbar -->
				</div> <!-- end of gf_progressbar_wrapper -->
			</div> <!-- end of gform_wrapper -->
			<ol>
<?php 
 foreach($posts as $post): 
 setup_postdata($post); //enables the_title(), the_content(), etc. without specifying a post ID
 ?>
<li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4></li>
<?php // the_excerpt(); ?>
 <?php endforeach; ?>
 		</ol>
<?php endforeach; ?>

<?php wp_reset_query(); ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer(); ?>
