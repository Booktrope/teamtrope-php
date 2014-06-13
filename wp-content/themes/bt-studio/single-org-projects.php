<?php get_header(); 
      global $bp; ?>
	<div id="content">
		<div class="padder">
		<?php do_action( 'bp_before_blog_page' ) ?>
		<div class="page" id="blog-page" role="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h4 class="pagetitle">Book Project: <?php the_title(); ?></h4>
			<h4><strong>Status: </strong>
			<?php
			$this_id = get_the_ID();
			$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
			echo $terms_as_text ;?></h4>
			<?php
			$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
			$status_value = ( explode(',',strip_tags($terms_as_text) . ',EndOfList' ) ); 
			$custom_fields = get_post_custom( $this_id );
              // book_pcr_step
        	$step = $custom_fields['book_pcr_step'][0];
        	$args = array(	'name'=>$step, 
        					'post_type'=>'pcr', 
        					'limit'=> '1'
        				);
         	$pcr = new WP_Query($args);
			$i = 1;
            while ($pcr->have_posts()) : $pcr->the_post();            	
            	echo "Last Process Step: ";
            	echo the_title() . " <br/>";
            	$pcr_id = get_the_ID();
            	$pcr_data = get_post_custom($pcr_id);
            	$i++;
            endwhile;
            
            $show_me = get_field("pcr_show_steps_when_complete");
            wp_reset_query();
                        if ( in_array( "In Editing",$status_value) ) {
                            if ( isset($custom_fields['proj_edit_complete_date'][0]) & $custom_fields['proj_edit_complete_date'][0] <> " " ) {
                            	if( strtotime($custom_fields['proj_edit_complete_date'][0]) > date("U") ){
                                	//echo date("Y-m-d", strtotime($custom_fields['proj_edit_complete_date'][0])) . '<br/>';
                                	//echo do_shortcode('[wpc_countdown targetdate="2012-1-25"]');
                                	?> <span class="countdown_amount">Due on <?php echo date("M d Y", strtotime($custom_fields['proj_edit_complete_date'][0])); ?></span><?php
                                	echo do_shortcode('[wpc_countdown targetdate="' . date("Y-m-d", strtotime($custom_fields['proj_edit_complete_date'][0]))  . '"]');
                                }
                            } else {
                                echo '<span class="countdown_amount">This team needs to commit to an <a href="#tabs-3">editing complete date</a>.</span>';
                            }
                        } ?>
                    	<h4><strong>Genre: </strong>
						<?php $terms_as_text = get_the_term_list( $this_id, 'genres', '', ', ', '' ); echo $terms_as_text;?></h4>

                        <?php
            $terms_as_text = get_the_term_list( $this_id, 'needs', '', ', ', '' ) ;            
			if ( $terms_as_text <> "" ) {                     ?>
                        <h4><strong>Team Needs: </strong>
			<?php
			
			echo $terms_as_text;
			?></h4><?php
			} else {
			echo "<h4>&nbsp;</h4>";
			}

			if ( isset($custom_fields['proj_teamroom'][0]) && $custom_fields['proj_teamroom'][0] != "" ) {
				echo "<h4><strong>Teamroom:</strong> <a href='" . $custom_fields['proj_teamroom'][0] . "'>Link to Teamroom</a></h4>\n\r";
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
						} elseif ( in_array( "In Editing",$status_value ) ) {
							$progress = '40%';
						} elseif ( in_array( "In Proofreading",$status_value ) ) {
							$progress = '60%';
						} elseif ( in_array( "Pre-publication",$status_value ) ) {
							$progress = '80%';
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
					<li><a href="#tabs-1">Overview</a></li>
					<li><a href="#tabs-2">Team Formation</a></li>
					<li><a href="#tabs-3">Complete Manuscript</a></li>
					<li><a href="#tabs-4">Produce Book</a></li>
				<?php if ( count(array_diff ( $show_me, array("04-00", "04-01","04-02","04-03", "04-04", "04-05"))) <> count($show_me) ) { ?>
                    <li><a href="#tabs-5">Market Book</a></li>
                <?php } ?>
				</ul>
				<div id="tabs-1"> <!-- tabs-1 Proj Overview -->
					<br/>
					<h3 class="pagetitle">Title</a>: <?php the_title(); ?></h3>
					<?php
					if ( isset($custom_fields['proj_author'][0]) & $custom_fields['proj_author'][0] <> "" ) {
						echo "<h3>Author: " . $custom_fields['proj_author'][0] . "</h3>\n\r";
					} 	else {
							echo "<h3>Author: </h3>\n\r";
					}
					if ( isset($custom_fields['proj_book_manager'][0]) & $custom_fields['proj_book_manager'][0] <> "" ) {
						echo "<h4>Book Manager: " . $custom_fields['proj_book_manager'][0] . "</h4>\n\r";
		 			} else {
							echo "<h4>Book Manager: </h4>\n\r";
						}
					if ( isset($custom_fields['proj_editor'][0]) & $custom_fields['proj_editor'][0] <> "" ) {
						echo "<h4>Editor: " . $custom_fields['proj_editor'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Editor: </h4>\n\r";
					}
					if ( isset($custom_fields['proj_cover_designer'][0]) & $custom_fields['proj_cover_designer'][0] <> "" ) {
						echo "<h4>Cover Designer: " . $custom_fields['proj_cover_designer'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Cover Designer: </h4>\n\r";
					}
					if ( isset($custom_fields['proj_proofreader'][0]) & $custom_fields['proj_proofreader'][0] <> "" ) {
						echo "<h4>Proofreader: " . $custom_fields['proj_proofreader'][0] . "</h4>\n\r";
					} 
					if ( isset($custom_fields['proj_isbn'][0]) & $custom_fields['proj_isbn'][0] <> "" ) {
						echo "<h4>ISBN: " . $custom_fields['proj_isbn'][0] . "</h4>\n\r";
					} else {
						echo "<h4>ISBN Number: </h4>\n\r";
					}
					if ( isset($custom_fields['proj_ebook_isbn'][0]) & $custom_fields['proj_ebook_isbn'][0] <> "" ) {
						echo "<h4>eBook ISBN Number: " . $custom_fields['proj_ebook_isbn'][0] . "</h4>\n\r";
					} else {
						echo "<h4>eBook ISBN: </h4>\n\r";
					}
					if ( isset($custom_fields['proj_amazon_asin'][0]) & $custom_fields['proj_amazon_asin'][0] <> "" ) {
						echo "<h4>Amazon ASIN: " . $custom_fields['proj_amazon_asin'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Amazon ASIN </h4>\n\r";
					}
					if ( isset($custom_fields['proj_lsi_isbn'][0]) & $custom_fields['proj_lsi_isbn'][0] <> "" ) {
						echo "<h4>LSI ISBN: " . $custom_fields['proj_lsi_isbn'][0] . "</h4>\n\r";
					} else {
						echo "<h4>LSI ISBN </h4>\n\r";
					}
					if ( isset($custom_fields['proj_rz_id'][0]) & $custom_fields['proj_rz_id'][0] <> "" ) {
						echo "<h4>Royality Zone ID: " . $custom_fields['proj_rz_id'][0] . "</h4>\n\r";
					} else {
						echo "<h4>Royality Zone ID </h4>\n\r";
					}
					if ( isset($custom_fields['proj_cd_milestone'][0]) & $custom_fields['proj_cd_milestone'][0] <> "" ) {
						echo "<h4>Central Desktop Project/Milestone: <a class='link' href='https://booktrope.centraldesktop.com/test/project/#milestone-" . $custom_fields['proj_cd_milestone'][0] . "' target='_blank'>Go to Milestone</a><h4>\n\r";
					} else {
						echo "<h4>Central Desktop Project/Milestone </h4>\n\r";
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
						<?php if (in_array ("01-00", $show_me)) { ?>
							<li><a href="#tabs-201">1.1 Project<br/>Interest</a></li>
						<?php } ?>
						<?php if (in_array ("01-01", $show_me)) { ?>
							<li><a href="#tabs-202">1.2 1099<br/>Form</a></li>
						<?php } ?>
						<?php if (in_array ("01-02", $show_me)) { ?>
							<li><a href="#tabs-203">1.3 Revenue<br/>Split</a></li>
						<?php } ?>
						</ul>
						<div id="tabs-201"> <!-- detail project interest form -->
							<br/>
						<?php
						if (in_array ("01-00", $show_me)) {
							$terms_as_text = get_the_term_list( $this_id, 'needs', '', ', ', '' );
							if ( strip_tags($terms_as_text) != '') {
								echo "This project needs: " . $terms_as_text . " (click to see all)";
								echo do_shortcode('[gravityform name="1.1 Project Interest Sign-Up" title=true description=true ajax="true"]'); 
							} else {
								echo "<h3>Team is complete. Go Team Go!</h3>";
							}	
						} ?>
						</div> <!-- end of  Project Interest -->
						<div id="tabs-202"> <!-- 1099 Form -->
							<br/>
							<?php 
							if (in_array ("01-01", $show_me)) {
								echo do_shortcode('[gravityform name="1.2 1099 Form Info" title=true description=true ajax="true"]'); 
							}?>
						</div> <!-- end of 1099 -->
                        <div id="tabs-203"> <!-- detail level tabs-3 Revenue Split -->
							<br/>
							<?php
							if (in_array ("01-02", $show_me)) {
								echo do_shortcode('[gravityform name="1.3 Project Revenue Allocation" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of tabs-3 Revenue Split -->
					</div> <!-- end of low level tabs under team formation -->
				</div> <!-- end of Team Formation (top tabs-1) -->
				<div id="tabs-3"> <!-- tabs-3 Complete Manuscript -->
					<br/>Finalize the manuscript and complete the editing, proofreading, layout and final approval.
					<div class="tabs"> <!-- wrapper for Complete Manuscript -->
						<ul>
						<?php if (in_array ("02-00", $show_me)) { ?>
							<li><a href="#tabs-300">2.0 Submit Edit<br/>Committed Date</a></li>
						<?php } ?>
						<?php if (in_array ("02-01", $show_me)) { ?>
							<li><a href="#tabs-301">2.1 Submit Edited<br/>Manuscript</a></li>
						<?php } ?>
						<?php if (in_array ("02-02", $show_me)) { ?>
							<li><a href="#tabs-302">2.2 Submit Proofread<br/>Manuscript</a></li>
						<?php } ?>
						</ul>
						<div id="tabs-300"> <!-- tabs-1 Edited Manuscript -->
							<br/>
							<?php
							if (in_array ("02-00", $show_me)) {
                                if ( isset($custom_fields['proj_edit_complete_date'][0]) & $custom_fields['proj_edit_complete_date'][0] <> "" ) { 
                                	if (strtotime($custom_fields['proj_edit_complete_date'][0]) > date("U")) {
                                		echo '<h2>The Team has set an edit completion date of: '. $custom_fields['proj_edit_complete_date'][0] . '</h2>
                                    	Questions? Contact <br/><br/><div class="generic-button" id="send-private-message"><a href="'. $bp->loggedin_user->domain . '/messages/compose/?r=jessejamesfreeman" title="Send a private message to this user." class="send-message">Private Message to Community Manager</a></div><br/>';
                                    }
                                } else {
                                	echo do_shortcode('[gravityform name="2.0 Editing Complete Date" title=true description=true ajax="true"]');
                                }
                            }
							?>
						</div> <!-- end of tabs-1 Edited Manuscript -->
						<div id="tabs-301"> <!-- tabs-1 Edited Manuscript -->
							<br/>
							<?php 
							if (in_array ("02-01", $show_me)) {
								echo do_shortcode('[gravityform name="2.1 Submit Edited Manuscript" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of tabs-1 Edited Manuscript -->
						<div id="tabs-302"> <!-- Proofreading Complete Manuscript -->
							<br/>
							<?php 
							if (in_array ("02-02", $show_me)) {
								echo do_shortcode('[gravityform name="2.2 Submit Final Proofed Document" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of Proofed Manuscript -->
                    </div> <!-- end of wrapper for Complete Manuscript -->
				</div> <!-- end of tabs-2 top level Complete Manuscript -->
				<div id="tabs-4"> <!-- Produce book -->
					<br/>
					<div class="tabs"> <!-- wrapper for Produce Book -->
						<ul>
						<?php if (in_array ("03-00", $show_me)) { ?>
							<li><a href="#tabs-401">3.0 Select Layout</a></li>
						<?php } ?>
						<?php if (in_array ("03-01", $show_me)) { ?>
							<li><a href="#tabs-402">3.1 Layout Upload</a></li>
						<?php } ?>
						<?php if (in_array ("03-02", $show_me)) { ?>
							<li><a href="#tabs-403">3.2 Approve Layout</a></li>
						<?php } ?>
						<?php if (in_array ("03-03", $show_me)) { ?>
							<li><a href="#tabs-404">3.3 Page Count</a></li>
						<?php } ?>
						<?php if (in_array ("03-04", $show_me)) { ?>
							<li><a href="#tabs-405">3.4 Upload Cover</a></li>
						<?php } ?>
						<?php if (in_array ("03-05", $show_me)) { ?>
							<li><a href="#tabs-406">3.5 Pub Fact Sheet</a></li>
						<?php } ?>
						<?php if (in_array ("03-06", $show_me)) { ?>
							<li><a href="#tabs-407">3.6 Exception Request</a></li>
						<?php } ?>
						</ul>
						<div id="tabs-401"> <!-- Select Layout 3.0 Select Layout Style-->
							<br/>
							<?php  
							if (in_array ("03-00", $show_me)) {
								echo do_shortcode('[gravityform name="3.0 Select Layout Style" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of Select Layout -->
                        <div id="tabs-402"> <!-- Layout Upload -->
							<br/>
							<?php 
							if (in_array ("03-01", $show_me)) {
								echo do_shortcode('[gravityform name="3.1 Layout Upload" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of Upload Layout -->
						<div id="tabs-403"> <!-- Approve Layout 3.2 Approve Layout -->
							<br/>
							<?php 
							if (in_array ("03-02", $show_me)) {
								echo do_shortcode('[gravityform name="3.2 Approve Layout" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of Approve Layout -->
						<div id="tabs-404"> <!-- Page Count-->
							<br/>
							<?php 
							if (in_array ("03-03", $show_me)) {
								echo do_shortcode('[gravityform name="3.3 Final Page Count" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of Page Count -->
						<div id="tabs-405">
							<br/>
							<?php 
							if (in_array ("03-04", $show_me)) {
								echo do_shortcode('[gravityform name="3.4 Upload Cover Templates" title=true description=true ajax="true"]'); 
							} ?>
						</div>
						<div id="tabs-406">
							<br/>
							<?php 
							if (in_array ("03-05", $show_me)) {
								echo do_shortcode('[gravityform name="3.5 Publication Fact Sheet (PFS)" title=true description=true ajax="true"]'); 
							} ?>
						</div> <!-- end of fact sheet -->
						<?php if (in_array ("03-06", $show_me)) { ?>
						<div id="tabs-407">
							<br/>
							<p>IMPORTANT: Assuming no unusual complications, after all editing is complete and the cover is finalized, layout approved and the book handed over to production, you should expect for it to take approximately 4 weeks for your books to be finalized, and an additional 2 weeks for standard shipping time. Please note that while we will do everything we can to work with you to meet the normal expected date, we have a queue of manuscripts going through our system at all times. Deadlines are also dependent on the creative team providing necessary feedback and deliverables (including cover templates and publication fact sheet) within two days of layout being approved.</p>
							<h3>Exception Rush Production</h3>
							<?php							
							$user_id = get_current_user_id();
							if ( current_user_can( 'manage_options' ) ) {
								echo do_shortcode("[gravityform  name='3.6 Apply for Rush Production' title=true description=true ajax='true']"); 
							    echo 'You are logged in as user '.$user_id;
							}	
							?>
						</div> <!-- end of exception request -->
						<?php } ?>
					</div>  <!-- end of wrapper for Produce Book -->
				</div>  <!-- end of top level tabs-4 Produce book -->
			<?php if ( count(array_diff ( $show_me, array("04-00", "04-01","04-02","04-03", "04-04", "04-05"))) <> count($show_me) ) { ?>
				<div id="tabs-5"> <!-- Market the book -->
					<br/>
					<div class="tabs"> <!-- wrapper for Market Book -->
						<ul>
						<?php if (in_array ("04-00", $show_me)) { ?>
							<li><a href="#tabs-501">4.0 Release Date</a></li>
						<?php } ?>
						<?php if (in_array ("04-01", $show_me)) { ?>
							<li><a href="#tabs-502">4.1 Media Kit</a></li>
						<?php } ?>
						<?php if (in_array ("04-02", $show_me)) { ?>
							<li><a href="#tabs-503">4.2 Print Corner</a></li>
						<?php } ?>
						<?php if (in_array ("04-03", $show_me)) { ?>
							<li><a href="#tabs-504">4.3 Blog Tour</a></li>
						<?php } ?>
						<?php if (in_array ("04-04", $show_me)) { ?>
							<li><a href="#tabs-505">4.4 Free/Price Promo</a></li>
						<?php } ?>
						<?php if (in_array ("04-05", $show_me)) { ?>
							<li><a href="#tabs-506">4.5 Request Image</a></li>
						<?php } ?>
						</ul>
						<div id="tabs-501"> <!-- Release Date -->
							<br/>
							<?php 
							if (in_array ("04-00", $show_me)) {
								echo do_shortcode('[gravityform name="4.0 Release Date" title=true description=true ajax="true"]');
							} ?>
						</div> <!-- end of Release Date -->
                                                <div id="tabs-502"> <!-- Media Kit -->
							<br/>
							<?php if (in_array ("04-01", $show_me)) {
								echo do_shortcode('[gravityform name="4.1 Media Kit" title=true description=true ajax="true"]');
							} ?>
						</div> <!-- end of Media Kit -->
						<div id="tabs-503"> <!-- Print Corner -->
							<br/>
							<?php if (in_array ("04-02", $show_me)) {
								echo do_shortcode('[gravityform name="4.2 Print Corner" title=true description=true ajax="true"]');
							} ?>
						</div> <!-- end of Print Corner -->
						<div id="tabs-504"> <!-- Blog Tour -->
							<br/>
							<?php if (in_array ("04-03", $show_me)) {
								echo do_shortcode('[gravityform name="4.3 Blog Tour" title=true description=true ajax="true"]');
							} ?>
						</div> <!-- end of Blog Tour -->
						<div id="tabs-505"> <!-- Free Price Promo 4.4 Free/Price Promo -->
							<br/>
							<?php if (in_array ("04-04", $show_me)) {
								echo do_shortcode('[gravityform name="4.4 Free/Price Promo" title=true description=true ajax="true"]');
							} ?>
						</div> <!-- end of Free Price Promo -->
						<div id="tabs-506"> <!-- 4.5 Request Image -->
							<br/>
							<?php if (in_array ("04-05", $show_me)) {
								echo do_shortcode('[gravityform name="4.5 Request Image" title=true description=true ajax="true"]');
							} ?>
						</div> <!-- end of Request Image -->
					</div>  <!-- end of wrapper for Market Book -->
			<?php } ?>
				</div>  <!-- end of top level tabs-5 Market book -->
                                
                        </div>  <!-- end of top level wrapper -->
			<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

			<?php // comments_template(); ?>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php // get_sidebar() ?>

<?php get_footer();