<?php get_header(); 
      global $bp; ?>
	<div id="content">
		<div class="padder">
		<?php do_action( 'bp_before_blog_page' ) ?>
		<div class="page" id="blog-page" role="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h2 class="pagetitle">Book Project: <?php the_title(); ?></h2>
			<h4><strong>Status: </strong>
			<?php
			$this_id = get_the_ID();
			$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
			echo $terms_as_text ;?></h4>
			<?php
			$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
			$status_value = ( explode(',',strip_tags($terms_as_text) . ',EndOfList' ) ); 
			$custom_fields = get_post_custom( $this_id );
                        
                        if ( in_array( "In Editing",$status_value) ) {
                            if ( isset($custom_fields['proj_edit_complete_date'][0]) & $custom_fields['proj_edit_complete_date'][0] <> " " ) {
                                //echo date("Y-m-d", strtotime($custom_fields['proj_edit_complete_date'][0])) . '<br/>';
                                //echo do_shortcode('[wpc_countdown targetdate="2012-1-25"]');
                                ?> <span class="countdown_amount">Due on <?php echo date("M d Y", strtotime($custom_fields['proj_edit_complete_date'][0])); ?></span><?php
                                echo do_shortcode('[wpc_countdown targetdate="' . date("Y-m-d", strtotime($custom_fields['proj_edit_complete_date'][0]))  . '"]');
                            } else {
                                echo '<span class="countdown_amount">This team needs to commit to an <a href="#tabs-3">editing complete date</a>.</span>';
                            }
                        } ?>
                        <h4><strong>Team Needs: </strong>
			<?php
			$terms_as_text = get_the_term_list( $this_id, 'needs', '', ', ', '' ) ;
			echo $terms_as_text;
			?></h4><?php

			if ( isset($custom_fields['proj_teamroom'][0]) && $custom_fields['proj_teamroom'][0] != "" ) {
				echo "<h4>Teamroom: <a href='" . $custom_fields['proj_teamroom'][0] . "'>Link to Teamroom</a></h4>\n\r";
			} else {
					echo "<h4>Teamroom Link: </h4>\n\r";
			}
			?>
<?php //	$id = "12";
//gravity_form($id, $display_title=true, $display_description=true, $display_inactive=true, $field_values=null, $ajax=false); ?>
			<div class='gform_wrapper' id='gform_wrapper_1' >
				<div id='gf_progressbar_wrapper_7' class='gf_progressbar_wrapper'>
					<h3 class='gf_progressbar_title'>Overall Progress</h3>
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
			<div class="tabs">  <!-- top level tabs wrapper -->
				<ul>
					<li><a href="#tabs-1">Project Overview</a></li>
					<li><a href="#tabs-2">1. Team Formation</a></li>
					<li><a href="#tabs-3">2. Complete Manuscript</a></li>
					<li><a href="#tabs-4">3. Produce Book</a></li>
                    <li><a href="#tabs-5">4. Market Book</a></li>
				</ul>
				<div id="tabs-1"> <!-- tabs-1 Proj Overview -->
					<br/>
					<h3 class="pagetitle">Title</a>: <?php the_title(); ?></h3>
					<h4><strong>Genre: </strong>
					<?php $terms_as_text = get_the_term_list( $this_id, 'genres', '', ', ', '' ); echo $terms_as_text;?></h4>
					<?php
					if ( isset($custom_fields['proj_author'][0]) & $custom_fields['proj_author'][0] <> "" ) {
						echo "<h3>Author: " . $custom_fields['proj_author'][0] . "</h3>\n\r";
					} 	else {
							echo "<h3>Author: not assigned</h3>\n\r";
					}
					if ( isset($custom_fields['proj_book_manager'][0]) & $custom_fields['proj_book_manager'][0] <> "" ) {
						echo "<h4>Book Manager: " . $custom_fields['proj_book_manager'][0] . "</h4>\n\r";
		 			} else {
							echo "<h4>Book Manager: not assigned</h4>\n\r";
						}
					if ( isset($custom_fields['proj_editor'][0]) & $custom_fields['proj_editor'][0] <> "" ) {
						echo "<h4>Editor: " . $custom_fields['proj_editor'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Editor: not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_cover_designer'][0]) & $custom_fields['proj_cover_designer'][0] <> "" ) {
						echo "<h4>Cover Designer: " . $custom_fields['proj_cover_designer'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Cover Designer: not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_proofreader'][0]) & $custom_fields['proj_proofreader'][0] <> "" ) {
						echo "<h4>Proofreader: " . $custom_fields['proj_proofreader'][0] . "</h4>\n\r";
					} 
					if ( isset($custom_fields['proj_isbn'][0]) & $custom_fields['proj_isbn'][0] <> "" ) {
						echo "<h4>ISBN: " . $custom_fields['proj_isbn'][0] . "</h4>\n\r";
					} else {
						echo "<h4>ISBN Number: not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_ebook_isbn'][0]) & $custom_fields['proj_ebook_isbn'][0] <> "" ) {
						echo "<h4>eBook ISBN Number: " . $custom_fields['proj_ebook_isbn'][0] . "</h4>\n\r";
					} else {
						echo "<h4>eBook ISBN: not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_amazon_asin'][0]) & $custom_fields['proj_amazon_asin'][0] <> "" ) {
						echo "<h4>Amazon ASIN: " . $custom_fields['proj_amazon_asin'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Amazon ASIN not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_lsi_isbn'][0]) & $custom_fields['proj_lsi_isbn'][0] <> "" ) {
						echo "<h4>LSI ISBN: " . $custom_fields['proj_lsi_isbn'][0] . "</h4>\n\r";
					} else {
						echo "<h4>LSI ISBN not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_rz_id'][0]) & $custom_fields['proj_rz_id'][0] <> "" ) {
						echo "<h4>Royality Zone ID: " . $custom_fields['proj_rz_id'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Royality Zone ID not assigned</h4>\n\r";
					}
					if ( isset($custom_fields['proj_cd_milestone'][0]) & $custom_fields['proj_cd_milestone'][0] <> "" ) {
						echo "<h4>Central Desktop Project/Milestone: <a class='link' href='https://booktrope.centraldesktop.com/test/project/#milestone-" . $custom_fields['proj_cd_milestone'][0] . "' target='_blank'>Go to Milestone</a><h4>\n\r";
					} else {
						echo "<h4>Central Desktop Project/Milestone not assigned</h4>\n\r";
					}
					?>
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="entry">
							<strong>Description:</strong> <?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						</div> <!-- end of entry -->
					</div> <!-- end of post_class -->
				</div> <!-- end of detail tabs-1 project overview -->
				<div id="tabs-2"> <!-- top level tabs-2 Team Formation -->
					<br/><br/>
					<div class="tabs"> <!-- tabs-2 detail wrapper -->
						<ul>
							<li><a href="#tabs-201">1.0 Project<br/>Interest</a></li>
							<li><a href="#tabs-202">1.1 1099<br/>Form</a></li>
							<li><a href="#tabs-203">1.2 Revenue<br/>Split</a></li>
						</ul>
						<div id="tabs-201"> <!-- detail project interest form -->
							<br/>
						<?php
						$terms_as_text = get_the_term_list( $this_id, 'needs', '', ', ', '' ) ;
						if ( strip_tags($terms_as_text) != '') {
                                                    $id = "1";
                                                    gravity_form($id, $display_title=true, $display_description=true, $display_inactive=true, $field_values=null, $ajax=false); 
                                                    //echo do_shortcode('[gravityform id="1" name="Project Interest" ajax="true"]'); 
                                                    echo do_shortcode('[directory form="1" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
						} else {
                                                    echo "<h3>Team is complete. Go Team Go!</h3>";
						}	?>
						</div> <!-- end of  Project Interest -->
						<div id="tabs-202"> <!-- 1099 Form -->
							<br/>
							<?php 	$id = "4";
                                                            gravity_form($id, $display_title=true, $display_description=true, $display_inactive=true, $field_values=null, $ajax=false); ?>
							<?php// echo do_shortcode('[gravityform id="2" name="Submit Revenue" ajax="true"]'); ?>
						</div> <!-- end of 1099 -->
                                                <div id="tabs-203"> <!-- detail level tabs-3 Revenue Split -->
							<br/>
							<?php 	$id = "2"; ?>
                                                        <?php 
                                                            gravity_form($id, $display_title=true, $display_description=true, $display_inactive=true, $field_values=null, $ajax=false); 
                                                            echo do_shortcode('[directory form="2" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); ?>
						</div> <!-- end of tabs-3 Revenue Split -->
					</div> <!-- end of low level tabs under team formation -->
				</div> <!-- end of Team Formation (top tabs-1) -->
				<div id="tabs-3"> <!-- tabs-3 Complete Manuscript -->
					<br/>Finalize the manuscript and complete the editing, proofreading, layout and final approval.
					<div class="tabs"> <!-- wrapper for Complete Manuscript -->
						<ul>
							<li><a href="#tabs-300">2.0 Submit Edit<br/>Committed Date</a></li>
							<li><a href="#tabs-301">2.1 Submit Edited<br/>Manuscript</a></li>
							<li><a href="#tabs-302">2.2 Submit Proofread<br/>Manuscript</a></li>
						</ul>
						<div id="tabs-300"> <!-- tabs-1 Edited Manuscript -->
							<br/>
							<?php
                                                       // $answer = do_shortcode('[directory form="22" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                       // if (stristr($answer, 'This form does not have any entries yet'))
                                                        if ( isset($custom_fields['proj_edit_complete_date'][0]) & $custom_fields['proj_edit_complete_date'][0] <> "" ) { 
                                                            echo '<h2>The Team has set an edit completion date of: '. $custom_fields['proj_edit_complete_date'][0] . '</h2>
                                                                Questions? Contact <br/><br/><div class="generic-button" id="send-private-message"><a href="'. $bp->loggedin_user->domain . '/messages/compose/?r=jessejamesfreeman" title="Send a private message to this user." class="send-message">Private Message to Community Manager</a></div><br/>';
                                                        } else {
                                                            echo do_shortcode('[gravityform id="22" name="Edit Complete Date" ajax="true"]'); 
                                                        }
							?>
						</div> <!-- end of tabs-1 Edited Manuscript -->
						<div id="tabs-301"> <!-- tabs-1 Edited Manuscript -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="11" name="1. Edited Manuscript" ajax="false"]'); 
							echo do_shortcode('[directory form="11" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
							?>
						</div> <!-- end of tabs-1 Edited Manuscript -->
						<div id="tabs-302"> <!-- Proof Manuscript -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="17" name="2. Proofread Manuscript" ajax="true"]'); 
							echo do_shortcode('[directory form="17" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
							?>
						</div> <!-- end of Proofed Manuscript -->
                                        </div> <!-- end of wrapper for Complete Manuscript -->
				</div> <!-- end of tabs-2 top level Complete Manuscript -->
				<div id="tabs-4"> <!-- Produce book -->
					<br/>
					<div class="tabs"> <!-- wrapper for Produce Book -->
						<ul>
							<li><a href="#tabs-401">3.0 Select Layout</a></li>
							<li><a href="#tabs-402">3.1 Layout Upload</a></li>
							<li><a href="#tabs-403">3.2 Approve Layout</a></li>
							<li><a href="#tabs-404">3.3 Page Count</a></li>
							<li><a href="#tabs-405">3.4 Upload Cover</a></li>
							<li><a href="#tabs-406">3.5 Pub Fact Sheet</a></li>
						</ul>
						<div id="tabs-401"> <!-- Select Layout -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="25" name="Select Layout" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="25" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Select Layout -->
                                                <div id="tabs-402"> <!-- Layout Upload -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="15" name="Upload Layout" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="13" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>

						</div> <!-- end of Upload Layout -->
						<div id="tabs-403"> <!-- Approve Layout  -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="13" name="Approve Layout" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="15" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Approve Layout -->
						<div id="tabs-404"> <!-- Page Count-->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="24" name="Page Count" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="24" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Page Count -->

						<div id="tabs-405">
							<br/>
							<?php echo do_shortcode('[gravityform id="18" name="Upload Cover Art" ajax="true"]'); ?>
                                                        <?php
                                                        echo do_shortcode('[directory form="18" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>

						</div>
						<div id="tabs-406">
							<br/>
							<?php
							echo do_shortcode('[gravityform id="10" name="Fact Sheet" ajax="true"]'); 
							?>
                                                        <?php
                                                        echo do_shortcode('[directory form="10" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of fact sheet -->
					</div>  <!-- end of wrapper for Produce Book -->
				</div>  <!-- end of top level tabs-4 Produce book -->
				<div id="tabs-5"> <!-- Market the book -->
					<br/>
					<div class="tabs"> <!-- wrapper for Market Book -->
						<ul>
							<li><a href="#tabs-501">4.0 Release Date</a></li>
							<li><a href="#tabs-502">4.1 Media Kit</a></li>
							<li><a href="#tabs-503">4.2 Print Corner</a></li>
							<li><a href="#tabs-504">4.3 Blog Tour</a></li>
							<li><a href="#tabs-505">4.4 Free/Price Promo</a></li>
							<li><a href="#tabs-506">4.5 Test Details</a></li>
						</ul>
						<div id="tabs-501"> <!-- Release Date -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="26" name="Release Date" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="26" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Release Date -->
                                                <div id="tabs-502"> <!-- Media Kit -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="27" name="Media Kit" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="27" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>

						</div> <!-- end of Media Kit -->
						<div id="tabs-503"> <!-- Print Corner -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="28" name="Approve Layout" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="28" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Print Corner -->
						<div id="tabs-504"> <!-- Blog Tour -->
							<br/>
							<?php
							echo do_shortcode('[gravityform id="29" name="Blog Tour" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="29" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Blog Tour -->
						<div id="tabs-505"> <!-- Free Price Promo -->
							<br/>
							<?php 
                                                        echo do_shortcode('[gravityform id="30" name="Free Price Promo" ajax="true"]'); 
                                                        echo do_shortcode('[directory form="30" search="false" entry="false" entryonly="false" entryanchor="false" lightboxsettings="images,entry" showcount="false" thead="false" showrowids="false" jssearch="false" linkemail="false" linknewwindow="true" nofollowlinks="true" credit="false"]'); 
                                                        ?>
						</div> <!-- end of Free Price Promo -->
					</div>  <!-- end of wrapper for Market Book -->
				</div>  <!-- end of top level tabs-5 Market book -->
                                
                        </div>  <!-- end of top level wrapper -->
			<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

			<?php // comments_template(); ?>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer();