<?php get_header() ?>
<link rel="stylesheet" id="gforms_css-css" href="/wp-content/plugins/gravityforms/css/forms.css" type="text/css" media="all">
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">
			<h1>Book Projects by Need</h1>
			<p>Unpublished book projects that need one or more team members.</p>
<?php 
	$statuses = get_terms('status', 'orderby=status&order=ASC&hide_empty=1');
	$needs = get_terms('needs', 'orderby=needs&order=ASC&hide_empty=1');
	foreach( $needs as $need ): 
	
?>
 <br/>
 <h3><?php echo $need->name; 
 
 /*
 $posts = get_posts(array(
 'post_type' => 'projects',
 'taxonomy' => $need->taxonomy,
 'term' => $need->slug,
 'orderby' => 'title',
 'order' => 'ASC',
 'nopaging' => true,
 ));
*/ 
 	//$myquery = wp_parse_args($query_string);
 	$myquery = array(
 		'post_type' => 'projects',
 		'taxonomy' => $need->taxonomy,
 		'term' => $need->slug,
 		'orderby' => 'title',
 		'order' => 'ASC',
 		'nopaging' => true,
 	);
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
$posts = get_posts(	$myquery );
//	query_posts($myquery);

 echo " (" . count($posts) . ")"; ?></h3>
			<ol>
<?php 
	$cat_posts = array();
	$count = 0;
	foreach($posts as $post): 
		setup_postdata($post); //enables the_title(), the_content(), etc. without specifying a post ID
		$terms_as_text = get_the_term_list( $post->ID, 'status', '', ', ', '' ) ;
		//$post['workflow'] = $terms_as_text;

 ?>
<li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> (<?php echo $terms_as_text; ?>)</h4></li>
<?php // the_excerpt(); 
	$cat_posts[$post->post_title] = $terms_as_text;
?>
 <?php endforeach; ?>
 </ol>
 By Status:
 <ol>
 <?php 
 	asort($cat_posts);
 	foreach ($cat_posts as $key => $val) {
	    echo "$key ( $val )<br/>";
}
 ?>
 		</ol>
<?php endforeach; ?>

<?php wp_reset_query(); ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer(); ?>
