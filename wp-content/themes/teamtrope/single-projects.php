<?php get_header() ?>
<script type="text/javascript">
	function unhide(divID) {
		var item = document.getElementById(divID);
		if (item) {
			item.className=(item.className=='hidden')?'unhidden':'hidden';
		}
	}
</script>
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<h2 class="pagetitle"><a href="/projects/">Project</a>: <?php the_title(); ?></h2>
				<h4><strong>Genre: </strong>
				<?php
				$this_id = get_the_ID();
				$terms_as_text = get_the_term_list( $this_id, 'genres', '', ', ', '' ) ;
				echo strip_tags($terms_as_text);
				?></h4>
				<h4><strong>Status: </strong>
				<?php
				$this_id = get_the_ID();
				$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
				echo strip_tags($terms_as_text);
				?></h4>
				
<?php 		
			$custom_fields = get_post_custom( $this_id );
			if ( isset($custom_fields['proj_author'][0]) ) {
				echo "<h3>Author: " . $custom_fields['proj_author'][0] . "</h3>\n\r";
			} 	else {
					echo "<h3>Author: N/A</h3>\n\r";
			}
			if ( isset($custom_fields['proj_book_manager'][0]) ) {
				echo "<h4>Book Manager: " . $custom_fields['proj_book_manager'][0] . "</h4>\n\r";
			} else {
					echo "<h4>Book Manager: N/A</h4>\n\r";
				}
			if ( isset($custom_fields['proj_editor'][0]) ) {
				echo "<h4>Editor: " . $custom_fields['proj_editor'][0] . "</h4>\n\r";
			} else {
				echo "<h4>Editor: N/A</h4>\n\r";
			}
			if ( isset($custom_fields['proj_cover_designer'][0]) ) {
				echo "<h4>Cover Designer: " . $custom_fields['proj_cover_designer'][0] . "</h4>\n\r";
			} else {
					echo "<h4>Cover Designer: N/A</h4>\n\r";
			}
			if ( isset($custom_fields['proj_proofreader'][0]) ) {
				echo "<h4>Proofreader: " . $custom_fields['proj_proofreader'][0] . "</h4>\n\r";
			}

			?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry">

						<strong>Description:</strong> <?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>

					</div>
					<div class="entry">
						<br/><a href="/projects/">Back to project list</a><br/>
					</div>
					<div class="entry">
						<a href="javascript:unhide('formarea');">Interested?</a>
					</div>
					<div class="entry">
						<div id="formarea" class="hidden">
							<div id="formbuffer">
								<?php echo do_shortcode('[gravityform id="1" name="Project Interest" ajax="true"]'); ?>
							</div>
						</div>
					</div>					
				</div>
			<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

			<?php // comments_template(); ?>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer(); ?>