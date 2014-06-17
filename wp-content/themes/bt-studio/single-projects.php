<?php get_header(); 
      global $bp; ?>
		<div class="page">
<?php 	
	if (have_posts()) { 
		while (have_posts()) { the_post();  
			$this_id = get_the_ID(); 
		} //end while
	} // end if have posts  
	$custom_fields = get_post_custom( $this_id ); 
	$show_me = calcShowMeSteps($this_id, $custom_fields);

	outputProjectHead($custom_fields);
	$phases = getProjectPhases();
	outputProjectDetail($phases, $show_me, $custom_fields);
?>	

		</div>
<?php get_footer();

function calcShowMeSteps($this_id, $custom_fields) {
	
	$steps_to_do = array();
	// 3 workflows for this implementation TODO: pull the set of valid workflow control fields from the assigned workflow set.
	$steps_to_do = calcStepsForPCR($custom_fields['book_pcr_step'][0], $steps_to_do);
	$steps_to_do = calcStepsForPCR($custom_fields['book_pcr_step_cover_design'][0], $steps_to_do);
	$steps_to_do = calcStepsForPCR($custom_fields['book_pcr_step_mkt_info'][0], $steps_to_do);
	return $steps_to_do;
}

function calcStepsForPCR($stepName, $steps_to_do) 
{
	$args = array(	
		'name'		=> $stepName,
		'post_type'	=> 'pcr', 
		'limit'		=> '1'
        );
	$pcr = new WP_Query($args);

	// add the "enabled" steps for this workflow
	while ($pcr->have_posts()) : $pcr->the_post();
		$pcr_data = get_post_custom($pcr->id);
		$pcrs_to_do = get_field("pcr_show_steps");	
	endwhile;
	
	if ( $pcrs_to_do <> "" ) 
	{
		foreach ($pcrs_to_do as $pcr) 
		{
			array_push($steps_to_do, $pcr->post_title);
		}
	}
	wp_reset_query();
	return $steps_to_do;
}

function outputProjectHead($custom_fields) 
{
	if ( $custom_fields['book_ebook_front_cover'][0] != '' ) {
		echo '<img class="thumbalignright" src="' . wp_get_attachment_url($custom_fields['book_ebook_front_cover'][0]) . '">';
	}
	?>
	<br/><h2 class="pagetitle"><?php the_title(); ?></h2>
	<?php	
	$taxonomy = "genres";
	$sep = "";
	echo "<ul class='needs'><li>Genre: ";

	//echo "<h4>";
	foreach ( unserialize($custom_fields['book_genre'][0]) as $term_id ) {
		$term = get_term( $term_id, $taxonomy );
		echo $sep . $term->name;
		$sep = ", ";
	}
	echo "</li></ul><br/><br/>";

	$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
	$status_value = ( explode(',',strip_tags($terms_as_text) . ',EndOfList' ) ); 
	

	$needs_list = array();
	$user_info = get_userdata($custom_fields['book_marketing_manager'][0]); 
	if (!is_object($user_info)) {
		$needs_list[] = "<li class='green'>" . "Book Manager" . "</li>";
	}
	
	$user_info = get_userdata($custom_fields['book_project_manager'][0]); 
	if (!is_object($user_info)) {
		$needs_list[] = "<li class='yellow'>" . "Project Manager" . "</li>";
	}
	$user_info = get_userdata($custom_fields['book_editor'][0]); 
	if (!is_object($user_info)) {
		$needs_list[] = "<li class='red'>" . "Editor" . "</li>";
	}
	$user_info = get_userdata($custom_fields['book_proofreader'][0]); 
	if (!is_object($user_info)) {
		$needs_list[] = "<li class='red'>" . "Proofreader" . "</li>";
	}

	$user_info = get_userdata($custom_fields['book_cover_designer'][0]); 
	if (!is_object($user_info)) {
		$needs_list[] = "<li class='blue'>" . "Designer" . "</li>";
	}

	if ( count($needs_list) > 0 ) { ?>
			<ul class="needs">			
				<li>Needs: </li>					
	<?php
		foreach ( $needs_list as $need ) {
			echo $need;
		}
	?></h4><?php
		} else {
		//echo "<h4>&nbsp;</h4>";
	}   
	echo "</ul><br/>";
	if ( isset($custom_fields['book_teamroom_link'][0]) && $custom_fields['book_teamroom_link'][0] != "" ) {
		echo "<br/><a href='" . $custom_fields['book_teamroom_link'][0] . "'>Link to Teamroom</a><br/>";
	} else {
			echo "<br/><h4><a href='/groups/?tree'>Go to Teamrooms List</a></h4>\n\r";
	} 
}

function getProjectPhases() {
	$args = array(	
		'post_type'	=> 'phase', 
		'orderby'   => 'menu_order',
		'order'     => 'ASC'
        );
	$phases = new WP_Query($args);
	wp_reset_query();
	return $phases->posts;
}
function outputProjectDetail($phases, $show_me, $custom_fields) {
	$i = 1;
	foreach ($phases as $phase) {
		$phase_custom = get_post_custom( $phase->ID );
		outputPhase($i, $phase->ID, $phase->post_title, $phase_custom['phase_color_name'][0], $phase_custom['phase_icon'][0], $show_me, $custom_fields);
		$i++;
	}
}
function outputPhase($phase_count, $phase_id, $phase_title, $phase_color, $phase_icon, $show_me, $custom_fields) 
{
	//echo "<br/>*$phase_count*<br/>*$phase_title*<br/>*$phase_color*<br/>*$phase_icon*<br/>";
?>
	<!-- tabs -->
			<div class="pcss3t pcss3t-icons-left  pcss3t-height-auto pcss3t-theme-3-<?php echo $phase_color; ?>">				
				<input type="radio" name="pcss3t<?php echo '0'. $phase_count; ?>" checked id="tab1<?php echo '0'. $phase_count; ?>" class="tab-content-first">
				<label for="tab1<?php echo '0'. $phase_count; ?>"><?php echo $phase_title; ?><i class="<?php echo $phase_icon; ?>"></i></label>
<?php		
	$tasks = outputPhaseHeader($phase_id, $phase_count, $show_me);
?>								
<!--			<input type="radio" name="pcss3t<?php echo '0'. $phase_count; ?>" id="tab15<?php echo '0'. $phase_count; ?>" class="tab-content-last">
				<label for="tab15<?php echo '0'. $phase_count; ?>" class="right"><i class="icon-question-sign"></i></label>	
-->
<?php
	outputPhaseTasks($phase_id, $show_me, $tasks, $custom_fields);
?>
			</div>
			<!--/ tabs -->
<?php
}

function outputPhaseHeader($phase_id, $phase_count, $show_me) 
{
	$tasks = getPhaseTasks($phase_id);
	$j = 2;
	foreach ($tasks as $task) 
	{
		$task_custom = get_post_custom( $task->ID );

		if ( in_array ($task->post_title, $show_me) ) {
			$label_attr = ' for="tab' . $j . '0'. $phase_count . '" '; 
		} else {
			$label_attr = ' class="tab-inactive" ';
		} 
		if ( $task_custom['pcr_tab_text'][0] <> "" ) 
		{
			$tab_title = $task_custom['pcr_tab_text'][0];
		} else {
			$tab_title = $task->post_title;
		}
?>
			<input type="radio" name="pcss3t<?php echo '0'. $phase_count; ?>" id="tab<?php echo $j; ?><?php echo '0'. $phase_count; ?>" class="tab-content-<?php echo $j; ?>">
			<label <?php echo $label_attr; ?>"><?php echo $tab_title; ?><i class="<?php echo $task_custom['pcr_icon'][0]; ?>"></i></label>
<?php
		$j++;
	}
	return $tasks;
}

function getPhaseTasks($phase_id) 
{
	$args = array(
		'post_type' => 'pcr',
		'meta_key' => 'pcr_phase',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => 'pcr_phase',
				'value' => $phase_id,
				'compare' => '=',
			)
		)
	);
	$tasks = new WP_Query($args);
	wp_reset_query();
	return $tasks->posts;
}

function outputPhaseTasks($phase_id, $show_me, $tasks, $custom_fields) 
{
?>
	<ul>
<?php		$j = 2;
	foreach ($tasks as $task) 
	{
		$task_custom = get_post_custom( $task->ID );
?>
		<li class="tab-content tab-content-<?php echo $j; ?> typography">
<?php
		if (in_array ($task->post_title, $show_me))
		{
			$step_ready = true;
			if ( $task_custom['pcr_prereq_fields'][0] <> "" ) 
			{
				$step_ready = checkPrereqFields($task_custom['pcr_prereq_fields'][0],  $custom_fields);
			}
			if ( $step_ready == false ) 
			{ 
				?><h2>You must complete other tasks before this task will be unlocked.</h2><?php
				echo "<strong>System Prereq Hint:</strong> " . $task_custom['pcr_prereq_fields'][0];
			} else {
				if ($task_custom['pcr_form_name'][0] <> "" ) 
				{ 
?>
					<h1><?php echo $task_custom['pcr_form_name'][0] ?></h1>
<?php 
					echo do_shortcode('[gravityform name="'. $task_custom['pcr_form_name'][0] .'" title=false description=true ajax="true"]'); 
				} else {
					outputCustomTaskCode($task, $task_custom, $custom_fields);
				}
			}
		} 
?>
		</b></li>
<?php
		$j++;
	}
?>
<!--
	<li class="tab-content tab-content-last typography">
			<div class="typography">
				<h1>Help</h1>
				<p>&nbsp;</p>
			</div>
		</li>
-->
	</ul>
<?php	
}

function checkPrereqFields($prereq_fields, $custom_fields) 
{
	$fields_to_check = array();
	$fields_to_check = explode(',', $prereq_fields);
	
	foreach ( $fields_to_check as $field )
	{
		$field = trim($field);
		if ( $custom_fields[$field][0] == "" )
		{
			return false;
		}
	}
	return true;
}
	
function outputCustomTaskCode($task, $task_custom, $custom_fields) 
{		
	switch ($task->post_title) 
	{
		case "Project Details":
			outputProjectDetails($task, $task_custom, $custom_fields);		
			break;
		case "Assets":
			outputAssets($task, $task_custom, $custom_fields);
			break;
		case "Team":
			outputTeam($task, $task_custom, $custom_fields);
			break;
		case "Analytics":
			outputAnalytics($task, $task_custom, $custom_fields);
			break;
	}
	
}
	
function outputProjectDetails($task, $task_custom, $custom_fields) 
{

	echo "<br/><strong>Status: </strong>";
	$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
	echo $terms_as_text . "</p>";

	$terms_as_text = get_the_term_list( $this_id, 'status', '', ', ', '' ) ;
	$status_value = ( explode(',',strip_tags($terms_as_text) . ',EndOfList' ) ); 
	
	echo "<h5>Workflow Step</h5>";
	echo "<strong>Production:</strong> " . $custom_fields['book_pcr_step'][0] . " <br/>" ;
	//echo "<strong>( Days:</strong> " . daysInStatus( $post, true, $custom_fields['book_pcr_step'][0] ) . " )<br/>";

	echo "<strong>Design:</strong> " . $custom_fields['book_pcr_step_cover_design'][0] . "<br/>" ;
	echo "<strong>Marketing:</strong> " . $custom_fields['book_pcr_step_mkt_info'][0] . "<br/>" ;

	if ( in_array( "In Editing",$status_value) ) 
	{
		if ( isset($custom_fields['proj_edit_complete_date'][0]) & $custom_fields['proj_edit_complete_date'][0] <> " " ) 
		{
			if( strtotime($custom_fields['proj_edit_complete_date'][0]) > date("U") )
			{
				//echo date("Y-m-d", strtotime($custom_fields['proj_edit_complete_date'][0])) . '<br/>';
				//echo do_shortcode('[wpc_countdown targetdate="2012-1-25"]');
				?> <span class="countdown_amount">Due on <?php echo date("M d Y", strtotime($custom_fields['proj_edit_complete_date'][0])); ?></span><?php
				echo do_shortcode('[wpc_countdown targetdate="' . date("Y-m-d", strtotime($custom_fields['proj_edit_complete_date'][0]))  . '"]');
			}
		} else {
			echo '<span class="countdown_amount">This team needs to commit to an <a href="#tabs-3">editing complete date</a>.</span>';
		}
	} 
	if (  $custom_fields['book_status_update'][0] <> "" ) 
	{ 
		echo "<br/><strong>" . $custom_fields['book_upate_type'][0] . "</strong>";
		echo ' on: <strong>' . date("m-d-Y", strtotime($custom_fields['book_status_update_date'][0])) . "</strong>" . " ";
		echo ' by: <a href="/members/' . $custom_fields['book_status_update_by'][0] . '">@' . $custom_fields['book_status_update_by'][0] . "</a><br/>";
		echo $custom_fields['book_status_update'][0] . "<br/>";
	}

	echo "<br/>Original Synopsis:<br/><div class='entry'>";
	the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); 
	wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); 
	echo "</div> <!-- end of entry -->";
}
	
function outputAssets($task, $task_custom, $custom_fields) 
{
	$pcr_name = $custom_fields['book_pcr_step'][0];
	// when to show Published Files
	if ( in_array($pcr_name, array("Published","Show All")) ) 
	{ 
		echo '<h4>Published Files</h4>';
		if ( $custom_fields['book_ebook_front_cover'][0] != '' ) 
		{
			echo '<img class="alignright" width="340px" src="' . wp_get_attachment_url($custom_fields['book_ebook_front_cover'][0]) . '">';
			echo ' <strong>Cover:</strong> ' .'<a href="' . wp_get_attachment_url($custom_fields['book_ebook_front_cover'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo '<strong>Cover:</strong> N/A' . "<br/>";;
		}
		if ( $custom_fields['book_final_mobi'][0] != '' ) 
		{
			echo ' <strong>Mobi:</strong> ' .'<a href="' . wp_get_attachment_url($custom_fields['book_final_mobi'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo '<strong>Mobi:</strong> N/A' . "<br/>";;
		}
		if ( $custom_fields['book_final_epub'][0] != '' ) 
		{
			echo '<strong>ePub:</strong> ' .'<a href="' . wp_get_attachment_url($custom_fields['book_final_epub'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo '<strong>ePub:</strong> N/A' . "<br/>";;
		}
		if ( $custom_fields['book_final_pdf'][0] != '' ) 
		{
			echo '<strong>PDF:</strong> ' .'<a href="' . wp_get_attachment_url($custom_fields['book_final_pdf'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo '<strong>PDF:</strong> N/A' . "<br/>";;
		}
	}

	// when to show Final Files						
	if (  in_array($pcr_name, array("Upload Final Covers","Show All")) ) 
	{ 
		echo '<h4>Final Files</h4>';
		if ( $custom_fields['book_final_manuscript_pdf'][0] != '' ) 
		{
			echo ' <strong>Final PDF:</strong> ' .'<a href="' . wp_get_attachment_url($custom_fields['book_final_manuscript_pdf'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo '<strong>Final PDF:</strong> N/A' . "<br/>";;
		}
		if ( $custom_fields['book_final_doc_file'][0] != '' ) 
		{
			echo '<strong>Final Doc File:</strong> ' .'<a href="' . wp_get_attachment_url($custom_fields['book_final_doc_file'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo '<strong>Final Doc File:</strong> N/A' . "<br/>";;
		}
	}

	// when to show PFS Info
	if ( in_array($pcr_name, array("Publication Fact Sheet (PFS)","Published","Show All")) ) 
	{ 
		echo '<h4>Publication Fact Sheet</h4>';
		echo ' Final Title: ' . $custom_fields['book_final_title'][0] . "<br/>";
		echo ' Series Name: ' . $custom_fields['book_series_name'][0] . "<br/>";
		echo ' Series Number: ' . $custom_fields['book_series_number'][0] . "<br/>";
		echo ' Blurb: ' . $custom_fields['book_blurb_description'][0] . "<br/>";
		echo ' Blurb One Liner: ' . $custom_fields['book_blurb_one_line'][0] . "<br/>";
		echo ' Author Bio: ' . $custom_fields['book_author_bio'][0] . "<br/>";
		echo ' Endorsements: ' . $custom_fields['book_endorsements'][0] . "<br/>";
		echo ' Print Price: $' . $custom_fields['book_print_price'][0] . "<br/>";
		echo ' eBook Price: $' . $custom_fields['book_ebook_price'][0] . "<br/>";
		echo ' BISAC Code 1: ' . $custom_fields['book_bisac_code_1'][0] . "<br/>";
		echo ' BISAC Code 2: ' . $custom_fields['book_bisac_code_2'][0] . "<br/>";
		echo ' BISAC Code 3: ' . $custom_fields['book_bisac_code_3'][0] . "<br/>";
		echo ' Search Terms: ' . $custom_fields['book_search_terms'][0] . "<br/>";
		echo ' Age Range: ' . $custom_fields['book_age_range'][0] . "<br/>";
		echo ' Cover Type: ' . $custom_fields['book_paperback_cover_type'][0] . "<br/>";
	}
	// when to show Cover Files
	if ( in_array($pcr_name, array("Upload Cover Templates", "Publication Fact Sheet (PFS)", "Published", "Show All")) ) 
	{ 
		echo '<h4>Cover Files</h4>';
		if ( $custom_fields['book_ebook_front_cover'][0] != '' ) 
		{
			if ( $pcr_name <> "Published" ) 
			{
				echo '<img class="alignright" width="340px" src="' . wp_get_attachment_url($custom_fields['book_ebook_front_cover'][0]) . '">';
			}
			echo ' eBook Front Cover: ' .'<a href="' . wp_get_attachment_url($custom_fields['book_ebook_front_cover'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo 'N/A' . "<br/>";;
		}
		if ( $custom_fields['book_createspace_cover'][0] != '' ) 
		{
			echo ' Createspace Cover: ' .'<a href="' . wp_get_attachment_url($custom_fields['book_createspace_cover'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo 'N/A' . "<br/>";;
		}
		if ( $custom_fields['book_lightning_source_cover'][0] != '' ) 
		{
			echo ' Lightning Source Cover: ' .'<a href="' . wp_get_attachment_url($custom_fields['book_lightning_source_cover'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo 'N/A' . "<br/>";;
		}
		if ( $custom_fields['book_alternative_cover_template'][0] != '' ) 
		{
			echo ' Alternative Cover: ' .'<a href="' . wp_get_attachment_url($custom_fields['book_alternative_cover_template'][0]) . '">DOWNLOAD</a>' . "<br/>";
		} else {
			echo 'N/A' . "<br/>";;
		}
	}
	// when to show stock image(s) -- whenever we have one
	if ( $custom_fields['book_stock_cover_image'][0] != '' ) 
	{
		echo '<h4>Requesed Cover Image(s)</h4>';
		echo 'Requested Image(s): ' .'<a href="' . wp_get_attachment_url($custom_fields['book_stock_cover_image'][0]) . '">DOWNLOAD</a>' . "<br/>";
	}
	// when to show requested image
	if ( in_array($pcr_name, array("Approve Cover Art", "Upload Cover Templates", "Show All")) ) 
	{ 
		echo '<h4>Stock Image Request</h4>';
		if ( $pcr_name <> "Published" ) {
			echo '<img class="alignright" width="340px" src="' . wp_get_attachment_url($custom_fields['book_stock_image_request_link'][0]) . '">';
		}
		echo ' Image Request: ' . $custom_fields['book_stock_image_request_link'][0] . "<br/>";
	}
	// when to show Cover Concept Notes
	if ( in_array($pcr_name, array("Upload Cover Concept", "Show All")) ) 
	{ 
		echo '<h4>Cover Concept Notes</h4>';
		echo ' Cover Concept Notes: ' . $custom_fields['book_cover_concept_notes'][0] . "<br/>";
	}
	// when to show Cover Concept
	if ( in_array($pcr_name, array("Upload Cover Concept", "Approve Cover Art", "Upload Cover Templates", "Show All")) ) 
	{ 
		echo '<h4>Cover Concept</h4>';
		if ( $custom_fields['book_cover_concept'][0] != '' ) 
		{
			if ( $pcr_name <> "Published" ) 
			{
				echo '<img class="alignright" width="340px" src="' . wp_get_attachment_url($custom_fields['book_cover_concept'][0]) . '">';
			}
			echo ' Latest Cover Concept: ' .'<a href="' . wp_get_attachment_url($custom_fields['book_cover_concept'][0]) . '">DOWNLOAD</a>';
		} else {
			echo 'N/A' . "<br/>";;
		}
	}
	// when to show Page Count
	if ( in_array($pcr_name, array("Upload Cover Concept", "Upload Cover Templates", "Request Image", "Upload Cover Templates", "Final Manuscript", "Published", "Show All")) ) 
	{ 
		echo '<h4>Page Count</h4>';
		if ( $custom_fields['book_final_page_count'][0] < 1 ) 
		{
			echo ' Final Page Count: N/A<br/>';
		} else {
			echo ' Final Page Count: ' . $custom_fields['book_final_page_count'][0] . "<br/>";
		}
	
	}
	// when to show Layout Sample
	if ( $pcr_name === "Approve Layout" ) 
	{ 
		echo '<h4>Proposed Layout</h4>';
		if ( $custom_fields['book_layout_upload'][0] != '' ) 
		{
			echo ' Layout Sample File: <a href="' . wp_get_attachment_url($custom_fields['book_layout_upload'][0]) . '">DOWNLOAD</a><br/>';
		} else {
			echo 'N/A' . "<br/>";;
		}
		echo ' Layout Notes: ' . $custom_fields['book_layout_notes'][0] . "<br/>";
	} 
	// when to show layout options
	if ( in_array($pcr_name, array("Upload Layout", "Approve Layout", "Final Page Count", "Upload Cover Concept", "Approve Cover Art", "Upload Cover Templates", "Publication Fact Sheet (PFS)", "Final Manuscript", "Show All")) ) 
	{ 
		echo '<h4>Layout Options</h4>';
		echo ' Layout Style: ' . $custom_fields['book_layout_style_choice'][0] . "<br/>";
		if ( $custom_fields['book_use_pen_name_on_title'][0] == TRUE ) 
		{
			$answer = "Yes";
		} else {
			$answer = "No";
		}
		echo ' Use Pen Name?: ' . $answer . "<br/>";
		if ( $answer == "Yes" ) 
		{
			echo ' Pen Name: ' . $custom_fields['book_pen_name'][0] . "<br/>";
		}
		if ( $custom_fields['book_use_pen_name_for_copyright'][0] == TRUE ) 
		{
			$answer = "Yes";
		} else {
			$answer = "No";
		}
		echo ' Use Pen Name for Copyright?: ' . $answer . "<br/>";
		if ( $answer == "Yes" ) 
		{
			echo ' Copyright Pen Name: ' . $custom_fields['book_exact_name_on_copyright'][0] . "<br/>";
		}
	} 
	// when to show Proofed Document
	if ( in_array($pcr_name, array("Choose Style", "Upload Layout", "Approve Layout", "Final Page Count", "Upload Cover Concept", "Approve Cover Art", "Upload Cover Templates", "Publication Fact Sheet (PFS)", "Show All")) ) 
	{ 
		echo '<h4>Proofed Document</h4>';
		if ( $custom_fields['book_has_sub-chapters'][0] == TRUE ) 
		{
			$answer = "Yes";
		} else {
			$answer = "No";
		}
		echo ' Has Sub-Chapters? ' . $answer . "<br/>";
		echo ' Special Text Treatment: ' . $custom_fields['book_special_text_treatment'][0] . "<br/>";
		if ( $custom_fields['book_proofed_word_count'][0] < 1 ) 
		{
			echo ' Word Count: N/A<br/>';
		} else {
			echo ' Word Count: ' . $custom_fields['book_proofed_word_count'][0] . "<br/>";
		}
		if ( $custom_fields['book_previously_published'][0] == TRUE ) 
		{
			$answer = "Yes";
		} else {
			$answer = "No";
		}
		echo ' Previously Published?: ' . $answer . "<br/>";
		if ( $answer == "Yes" ) 
		{
			echo ' Previous Publish Info: ' . $custom_fields['book_prev_publisher_and_date'][0] . "<br/>";
		}
		echo '<h4>Manuscript</h4>';
		if ( $custom_fields['book_manuscript_proofed'][0] != '' ) 
		{
			echo ' Proofed Manuscript: <a href="' . wp_get_attachment_url($custom_fields['book_manuscript_proofed'][0]) . '">DOWNLOAD</a>';
		} else {
			echo 'N/A' . "<br/>";;
		}
	}
	if ( $pcr_name === "Submit Final Proofed Document" ) 
	{ 
		echo '<h4>Manuscript</h4>';
		if ( $custom_fields['book_manuscript_edited'][0] != '' ) {
			echo ' Edited Manuscript: <a href="' . wp_get_attachment_url($custom_fields['book_manuscript_edited'][0]) . '">DOWNLOAD</a>';
		} else {
			echo 'N/A' . "<br/>";;
		}
	} 
	// when to show edit complete date
	if ( in_array($pcr_name, array("Submit Edit Complete Date", "Submit Edited", "Show All")) ) 
	{
		echo ' Edit Complete Date: ' . date('m-d-Y', strtotime($custom_fields['book_edit_complete_date'][0])) . "<br/>";
	} 
	if ( in_array($pcr_name, array("New Manuscript", "Edit Complete Date", "Submit Edited", "Show All")) ) 
	{ 	// when to show Original Manuscript
		echo '<h4>Manuscript</h4>';
		if ( $custom_fields['book_manuscript_original'][0] != '' ) 
		{
			echo ' Original Manuscript: <a href="' . wp_get_attachment_url($custom_fields['book_manuscript_original'][0]) . '">DOWNLOAD</a>';
		} else {
			echo 'N/A' . "<br/>";;
		}
	}
}
	
function outputTeam($task, $task_custom, $custom_fields) 
{
?>
	<h4>Our Team</h4>
	<?php $authors = unserialize($custom_fields['book_author'][0]);
?>
<div id="members-dir-list" class="members dir-list">
<ul id="members-list" class="item-list" role="main">
<?php
	$size = "180";
	$roles = array();
	$actors = array();
// load roles and actors
	foreach ( $authors as $author ) 
	{
		$user_info = get_userdata($author); 
		$roles[] = "Author";
		$actors[] = $user_info->id;
	}
	$roles[] = "Book Manager";
	$actors[] = $custom_fields['book_marketing_manager'][0];

	$roles[] = "Project Manager";
	$actors[] = $custom_fields['book_project_manager'][0];
	
	$roles[] = "Editor";
	$actors[] = $custom_fields['book_editor'][0];

	$roles[] = "Proofreader";
	$actors[] = $custom_fields['book_proofreader'][0];

	$roles[] = "Cover Designer";
	$actors[] = $custom_fields['book_cover_designer'][0];
	
	outputTeamMembers($roles, $actors); 
?>
</ul>
</div>
<?		
}
		
function outputTeamMembers($roles, $actors) 
{
	$roles_taken = array();
	$my_roles = "";
	$i = 0;
	foreach ($actors as $actor) 
	{
		if ( $actor > 0 ) 
		{
			$my_roles = "";
			if ( in_array($roles[$i], $roles_taken) ) 
			{
				// skip this one
			} else {
				// look through the rest of the array for same actor ID
				for ( $j = $i + 1; $j <= count($actors); $j++ ) 
				{
					if ( $actors[$j] == $actor ) 
					{
						$roles_taken[] = $roles[$j];
						if ( $my_roles == "" ) 
						{
							$my_roles = $roles[$i] . ", " . $roles[$j];
						} else {
							$my_roles .= ", " . $roles[$j];
						}
					} 
				}
				if ($my_roles == "") 
				{ 
					$my_roles = $roles[$i];
				}
				
				$user_info = get_userdata($actor); 
				$user_home = bp_core_get_user_domain( $user_info->id );			
?>
				<li>
					<div class="item-avatar">
						<a href="<?php echo $user_home; ?>"><?php echo bp_core_fetch_avatar( 'item_id='. $user_info->id .'&type=full&width=180&height=180' . ' alt="Profile picture of '. $user_info->display_name . '"' ); ?></a>
					</div>
					<div class="item">
						<div class="item-title">
							<a href="<?php echo $user_home; ?>"><?php echo $user_info->display_name; ?></a><br>
							<?php echo $my_roles; ?><br>
						</div>
						<div class="item-meta">
						</div>
					</div>
				</li>
<?php
				$i++;
			}
		}
	}
}	
	
function outputAnalytics($task, $task_custom, $custom_fields) 
{
	if(isset($custom_fields['book_asin'][0]) && $custom_fields['book_asin'][0] !== "")
	{ ?>
		<h1>Rankings and Analytics</h1>
		<?php
		$asin = $custom_fields['book_asin'][0];
		$json_result = bt_func_get_amazon_json_for_asin($asin);
		$json_sales_result = bt_func_get_amazon_sales_for_asin($asin);	
	//	echo "***" . $json_sales_result . "***";
		if($json_result || $json_sales_result)
		{ ?>
			<script src="//code.highcharts.com/highcharts.js"></script>
			<script src="//code.highcharts.com/modules/exporting.js"></script>
		<?php
		if($json_sales_result) {
	?>
	<script type='text/javascript'>//<![CDATA[ 
	jQuery(document).ready(function ($) {
			$('#daily-sales-container').highcharts(<?php echo $json_sales_result; ?>);
		});
	//]]>  
	</script>
			<div id="daily-sales-container" style="min-width: 910px; height: 400px; margin: 0 auto"></div>
	<?php }
		if($json_result) {
	?>
	<script type='text/javascript'>//<![CDATA[ 
	jQuery(document).ready(function ($) {
			$('#price-ranking-container').highcharts(<?php echo $json_result; ?>);
		});
	//]]>  
	</script>
			<div id="price-ranking-container" style="min-width: 910px; height: 400px; margin: 0 auto"></div>
	<?php }
		}
	}
}