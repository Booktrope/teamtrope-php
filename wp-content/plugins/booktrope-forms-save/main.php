<?php
/*
Plugin Name: Booktrope Forms Save After Submission
Plugin URI: http://booktrope.com
Description: Save the Custom Fields into a project's postmeta and create a Status record
Version: 1.0.1
Author: Justin Jeffress
Author URI: http://www.booktrope.com
License: GPL2
*/

include_once(ABSPATH.'wp-admin/includes/plugin.php');
add_action("gform_after_submission", "btfs_set_post_content", 10, 2);

function btfs_set_post_content($entry, $form)
{
   // form number _## above
   $the_form_id = $entry["form_id"]; 
	$formObj = get_post($the_form_id);
	$formName = $form["title"];
	$userId = $entry["created_by"];
	$entryId = $entry["id"];
	
	//$lead = RGFormsModel::get_lead($the_form_id);
	$formMeta = GFFormsModel::get_form_meta($the_form_id);
	
	$values = array();
	foreach($formMeta['fields'] as $field)
	{
		$values[$field['label']] = $entry[$field['id']];
		
		if(isset($field['adminLabel']) && $field['adminLabel'] == "step_approved")
		{
			$values["step_approved"] = $entry[$field['id']];
		}
		else if(isset($field['adminLabel']))
		{
			$values[$field['adminLabel']] = $entry[$field['id']];
		}
		
		if($field["type"] == "checkbox")
		{
			foreach($field["inputs"] as $input)
			{
				$values[$input["label"]] =  ($entry[$input["id"]] != "") ? 1 : 0;
				
				if(isset($input['adminLabel']))
				{
					$values[$input["adminLabel"]] =  ($entry[$input["id"]] != "") ? 1 : 0;
				}
			}
		}
	}
	
	$book = get_post($values["Project ID"]);
	
	//getting the data from the process control record
	// get the next step info from the PCR, not the form
	
	$pcr_data = btfs_get_pcr_data_by_form_name($formName, $values["step_approved"]);

	//if it's a process step, we update the project to the new step.
//error_log(print_r("is_process_step", true));	
//error_log(print_r($pcr_data, true));	

	if( $pcr_data["is_process_step"] == 1 )
	{
		$pcr_name = $pcr_data["cur_pcr_name"];
		if($pcr_data['is_approval_step'] && !$values["step_approved"])
		{
			$pcr_name = $pcr_data['not_approved_go_to'];
		}
		
		btfs_set_status_values ($book, $pcr_data);
		btfs_add_or_update_meta($book->ID, 'book_pcr_step_date', date("Ymd"));
	}

   switch($formName)
   {
   case "Book Submission":
   	btfs_create_status_entry($formObj, $book, $entryId, $userId, $values["Type of update"], $pcr_data["status"], 75, $values["Status"]);
   	break;
   case "Update Project Status":
	btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 75, $cur_pcr_name);	
	btfs_add_or_update_meta($book->ID, 'book_status_update_date', date("Ymd"));		
	btfs_add_or_update_meta($book->ID, 'book_status_update', $values["Status"]);
	btfs_add_or_update_meta($book->ID, 'book_status_update_by', $values["Teamtrope ID"]);
	btfs_add_or_update_meta($book->ID, 'book_upate_type', $values["Type of update"]);
	break;

   case "Early Reader Book Feedback":
   	btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 75, $cur_pcr_name);
   	break;
   case "Project Interest Sign-Up":
   	btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 75, $cur_pcr_name);
   	break;
   case "Invite Team Member":
   	btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 75, $cur_pcr_name);
   	break;
   case "Accept Team Member":
   	btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 75, $cur_pcr_name);
		
		$member_fieldnames = array(
			'Editor' => 'book_editor',
			'Book Manager' => 'book_marketing_manager',
			'Project Manager' => 'book_project_manager',
			'Cover Designer' => 'book_cover_designer',
			'Proofreader' => 'book_proofreader',
			'other' => 'book_other'
		);
		btfs_add_or_update_meta($book->ID, $member_fieldnames[$values["Team Role"]], $values["New Team Member"]);
   	break;
	case "1099 Form Info":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 75, $cur_pcr_name);
		break;
	case "Project Revenue Allocation":
		if (is_plugin_active('asana-revenue-allocation/main.php'))
		{
			//plugin is activated	
			add_task_to_asana($the_form_id, $entry);
		}
		
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 102, $cur_pcr_name);
		
		btfs_add_or_update_meta($book->ID, 'book_author_pct', $values["Author Percentage"]);
		btfs_add_or_update_meta($book->ID, 'book_manager_pct', $values["Book Manager Percentage"]);
		btfs_add_or_update_meta($book->ID, 'book_project_manager_pct', $values["Project Manager Percentage"]);
		btfs_add_or_update_meta($book->ID, 'book_editor_pct', $values["Editor Percentage"]);
		btfs_add_or_update_meta($book->ID, 'book_designer_pct', $values["Designer Percentage"]);
		btfs_add_or_update_meta($book->ID, 'book_proofreader_pct', $values["Proofreader Percentage"]);
		btfs_add_or_update_meta($book->ID, 'book_other_pct', $values["Revenue Percentage for Other"]);
		break;
	case "Original Manuscript":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 94, $cur_pcr_name);

		btfs_save_attachment_into_book($book->ID, 'book_manuscript_original', $values["Original Manuscript"]);	   
		break;
	case "Editing Complete Date":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 23, $cur_pcr_name);
		
		btfs_add_or_update_meta($book->ID, 'book_edit_complete_date', date("Ymd", strtotime($values["Editing Complete Committed Date"])));		
		break;
	case "Submit Edited Manuscript":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 94, $cur_pcr_name);
	   
		btfs_save_attachment_into_book($book->ID, 'book_manuscript_edited', $values["Final Manuscript"]);	   
		btfs_add_or_update_meta($book->ID, 'book_childrens_book', $values["Children's book"]);
		btfs_add_or_update_meta($book->ID, 'book_color_interior', $values["Full color interior"]);
		btfs_add_or_update_meta($book->ID, 'book_has_index', $values["My book has an index"]);
		btfs_add_or_update_meta($book->ID, 'book_has_internal_illustrations', $values["My book has illustrations or images inserted throughout"]);
		break;
	case "Submit Final Proofed Document":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 94, $cur_pcr_name);
	   
		btfs_save_attachment_into_book($book->ID, 'book_manuscript_proofed', $values["Final Manuscript"]);	   
		btfs_add_or_update_meta($book->ID, 'book_has_sub-chapters', $values["Does your book contain sub-chapters?"]);
		btfs_add_or_update_meta($book->ID, 'book_special_text_treatment', $values["I have sections of text needing special treatment (optional)"]);
		btfs_add_or_update_meta($book->ID, 'book_proofed_word_count',	$values["Manuscript Word Count"]);
		btfs_add_or_update_meta($book->ID, 'book_previously_published',	$values["Was this book previously published?"]); //new
  	  	btfs_add_or_update_meta($book->ID, 'book_prev_publisher_and_date',	$values["Year and Publisher"]); //new
		break;
	case "Upload Final Document":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Approve Final Document":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Select Layout Style":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
	   btfs_add_or_update_meta($book->ID, 'book_layout_style_choice', $values["Select your Inside Book Title and Chapter font"]);
	   btfs_add_or_update_meta($book->ID, 'book_use_pen_name_on_title', $values["Are you using a pen name on the title?"]);
	   btfs_add_or_update_meta($book->ID, 'book_use_pen_name_for_copyright', $values["Are you using a pen name for the copyright?"]);
	   btfs_add_or_update_meta($book->ID, 'book_pen_name', $values["What is your pen name?"]);
	   btfs_add_or_update_meta($book->ID, 'book_exact_name_on_copyright', $values["What is the exact name you want to appear on the copyright?"]);
		break;
	case "Layout Upload":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
	   btfs_add_or_update_meta($book->ID, 'book_layout_notes', $values["Layout Info"]);
		btfs_save_attachment_into_book($book->ID, 'book_layout_upload', $values["Layout File"]);
		break;
	case "Approve Layout":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
	   btfs_add_or_update_meta($book->ID, 'book_layout_approved_date', date("Ymd", time()));
		break;
	case "Final Page Count":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);

	   btfs_add_or_update_meta($book->ID, 'book_final_page_count', $values["Page Count"]);
		break;
	case "Upload eBook Files":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Add Image":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
		btfs_save_attachment_into_book($book->ID, 'book_stock_cover_image', $values["Stock Cover Image"]);
		break;
	case "Upload Cover Concept":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);

		btfs_save_attachment_into_book($book->ID, 'book_cover_concept', $values["Cover Concept"]);
		break;
	case "Approve Cover Art":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);

		btfs_add_or_update_meta($book->ID, 'book_cover_concept_notes', $values["Cover Concept Notes"]);
		btfs_add_or_update_meta($book->ID, 'book_cover_art_approval_date', date("Ymd"));
		break;		
	case "eBook Final Review":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Upload Cover Templates":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
		btfs_save_attachment_into_book($book->ID, 'book_ebook_front_cover', $values["Front Cover File"]);
		btfs_save_attachment_into_book($book->ID, 'book_createspace_cover', $values["CreateSpace Cover Template File"]);
		btfs_save_attachment_into_book($book->ID, 'book_lightning_source_cover', $values["Lightning Source Cover Template File"]);
		btfs_save_attachment_into_book($book->ID, 'book_alternative_cover_template', $values["Alternative Template File"]);	   
		break;
	case "Publication Fact Sheet (PFS)":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
	   btfs_add_or_update_meta($book->ID, 'book_final_title', $values["Final Title"]);
	   btfs_add_or_update_meta($book->ID, 'book_series_name', $values["Series Name"]);
	   btfs_add_or_update_meta($book->ID, 'book_series_number', $values["Series Number"]);
	   btfs_add_or_update_meta($book->ID, 'book_blurb_description', $values["Back Blurb / Book Description"]);
	   btfs_add_or_update_meta($book->ID, 'book_blurb_one_line', $values["One line blurb"]);
	   btfs_add_or_update_meta($book->ID, 'book_author_bio', $values["Author Bio"]);
	   btfs_add_or_update_meta($book->ID, 'book_endorsements', $values["Endorsements"]);
	   btfs_add_or_update_meta($book->ID, 'book_print_price', $values["Print Price"]);
	   btfs_add_or_update_meta($book->ID, 'book_ebook_price', $values["ebook Price"]);
	   btfs_add_or_update_meta($book->ID, 'book_bisac_code_1', $values["BISAC Code One"]);
	   btfs_add_or_update_meta($book->ID, 'book_bisac_code_2', $values["BISAC Code Two"]);
	   btfs_add_or_update_meta($book->ID, 'book_bisac_code_3', $values["BISAC Code Three"]);
	   btfs_add_or_update_meta($book->ID, 'book_search_terms', $values["Search Terms (up to 7, separate by commas)"]);
	   btfs_add_or_update_meta($book->ID, 'book_age_range', $values["What age range is most appropriate for your book?"]);
	   btfs_add_or_update_meta($book->ID, 'book_paperback_cover_type', $values['Do you want your paperback cover to be matte or glossy?']);
		break;
	case "Apply for Rush Production":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);

	   btfs_add_or_update_meta($book->ID, 'book_production_exception', $values["Job Title"]);
	   btfs_add_or_update_meta($book->ID, 'book_production_exception_approver', $values["Exception Request by"]);
	   btfs_add_or_update_meta($book->ID, 'book_production_exception_reason', $values["Reason for Exception"]);
	   btfs_add_or_update_meta($book->ID, 'book_production_exception_date', date("Ymd", strtotime($values["Date Needed"])));
		break;
	case "Final Manuscript":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		btfs_add_or_update_meta($book->ID, 'book_publication_date', date("Ymd", strtotime($values["Publication Date"])));
		btfs_save_attachment_into_book($book->ID, 'book_final_manuscript_pdf', $values["Final PDF"]);
		btfs_save_attachment_into_book($book->ID, 'book_final_doc_file', $values["Final Word Doc"]);
		break;
	case "Publish Book":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
	   btfs_add_or_update_meta($book->ID, 'book_publication_date', date("Ymd", strtotime($values["Publication Date"])));
		btfs_save_attachment_into_book($book->ID, 'book_final_mobi', $values[".mobi File"]);
		btfs_save_attachment_into_book($book->ID, 'book_final_epub', $values[".epub File"]);
		btfs_save_attachment_into_book($book->ID, 'book_final_pdf', $values["Final PDF"]);
		break;
	case "Release Date":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
	   btfs_add_or_update_meta($book->ID, 'book_marketing_release_date', date("Ymd", strtotime($values["Planned Marketing Release Date*"])));
		break;
	case "Media Kit":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Print Corner":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Blog Tour":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		break;
	case "Free/Price Promo":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
		if(defined('WP_PARSE_API_PATH'))
		{
			$custom_fields = get_post_custom($book->ID);
		
			$asin = $custom_fields['book_asin'][0];
			$book = btfs_get_book_from_parse("teamtropeId", $book->ID);
			
			if($book === null) return;
			
			$bookObjectId = $book->objectId();
			
			$promotionType = $values["Please select the type of promotion you'd like to schedule"];
			$channels =	btfs_get_channels_from_parse($asin, $promotionType);
			$promoPrice = floatVal(str_replace("$", "", $values["Price During Promotion"]));
			btfs_save_price_change_to_parse($values["Price Promotion Start Date"], $asin, $bookObjectId, $book->title, $promoPrice, $promoPrice, $promotionType, false, $channels);
			
			if (startsWith($promotionType, "temp_"))
			{
				$price = floatVal((($promotionType == "temp_itunes_ffree") ? str_replace("$", "", $values["Price after Force Free"]) : str_replace("$", "", $values["Price After Promotion"])));
				$date = ($promotionType == "temp_itunes_ffree") ? $values["Force Free End Date"] : $values["Price Promotion End Date"];
				btfs_save_price_change_to_parse($date." 23:59:59", $asin, $bookObjectId, $book->title, $promoPrice, $price, $promotionType, true, $channels);
			}
		}
		break;
	case "Control Numbers":
		btfs_create_status_entry($formObj, $book, $entryId, $userId, $formName, $pcr_data["status"], 24, $cur_pcr_name);
		
		btfs_add_or_update_meta($book->ID, 'book_asin', $values["asin"]);
		btfs_add_or_update_meta($book->ID, 'book_apple_id', $values["apple_id"]);
		btfs_add_or_update_meta($book->ID, 'book_ebook_isbn', $values["ebook_isbn"]);
		btfs_add_or_update_meta($book->ID, 'book_hardback_isbn', $values["hardback_isbn"]);
		btfs_add_or_update_meta($book->ID, 'book_isbn', $values["paperback_isbn"]);
		
		$parseBook = null;
		$parseBook = btfs_get_book_from_parse("teamtropeId", $book->ID);
		if($parseBook == null)
		{
			$parseBook = new parseObject("Book");
		}
		
		if($values["asin"])                  { $parseBook->asin = $values["asin"]; }
		if($values["apple_id"] !== "")       { $parseBook->appleId = $values["apple_id"]; }
		if($values["hardback_isbn"] !== "")  { $parseBook->hardbackIsbn = $values["hardback_isbn"]; }
		if($values["paperback_isbn"] !== "") 
		{ 
			$paperbackISBNNoDash = str_replace("-", "", $values["paperback_isbn"]);
			$parseBook->paperbackIsbn = $values["paperback_isbn"]; 
			$parseBook->createspaceIsbn  = $paperbackISBNNoDash;
			$parseBook->lightningSource  = $paperbackISBNNoDash;
		}
		if($values["ebook_isbn"] !== "")     
		{
			$epubNoDash = str_replace("-", "", $values["ebook_isbn"]);
			$parseBook->epubIsbnItunes   = $epubNoDash;
			$parseBook->epubIsbn         = $values["ebook_isbn"];
		}
		
		$parseBook->teamtropeId = $book->ID;	
		$parseBook->update_object();
		
		btfs_add_or_update_meta($book->ID, 'book_parse_id', $parseBook->objectId());
		break;
	default: 
	   //Other forms = do nothing
	   break;
   }
}

function startsWith($haystack, $needle)
{
	return $needle === "" || strpos($haystack, $needle) === 0;
}

//***************************** Parse.com Price Change Queue ****************************/
//Getting the book id from parse.
function btfs_get_book_from_parse($fieldName, $value)
{
	$parseBook = null;
	$parse_query = new parseQuery("Book");
	$parse_query->whereEqualTo($fieldName, $value);
	$result = $parse_query->find();

	if(count($result->results) >= 1)
	{
		$parseBook = new parseObject("Book", $result->results[0]->objectId);
		foreach($result->results[0] as $key => $value)
		{
			//if($key != 'objectId') 
			{ $parseBook->$key = $value; }
		}
	}
//error_log("btfs_get_book_from_parse_end");	
	return $parseBook;
}

function btfs_get_channels_from_parse($asin, $promotionType)
{
	$parse_query = new parseQuery("SalesChannel");
	if($promotionType == "temp_itunes_ffree" || $promotionType == "perm_itunes_ffree")
	{
		$parse_query->whereEqualTo('canForceFree', true);
	}
	$results = $parse_query->find();
	
	return $results->results;
}

//Getting saving the price change into the parse.com queue.
function btfs_save_price_change_to_parse($date, $asin, $bookId, $title, $promoPrice, $price, $promotionType, $isEnd, $channels)
{
	foreach($channels as $channel)
	{
		$price_change = new parseObject("PriceChangeQueue");
		$price_change->changeDate = array('__type' => 'Date' , 'iso' => btfs_format_parse_date($date));
		$price_change->asin = $asin;
		$price_change->book         = array("__type" => "Pointer", "className" => "Book"        , "objectId" => $bookId);
		$price_change->title        = $title;
		$price_change->salesChannel = array("__type" => "Pointer", "className" => "SalesChannel", "objectId" => $channel->objectId);
		$price_change->channelName = $channel->name;
		$price_change->price = floatVal($price);
		$price_change->country = "US";
		$price_change->isEnd = $isEnd;
		$price_change->status = 0;
		$price_change->isPriceIncrease = ($isEnd && $promoPrice < $price) ? true : btfs_isPriceIncrease($channel, $price, $bookId);
		$price_change->save();
	}
}

function btfs_isPriceIncrease($channel, $price, $bookId)
{
	$result = false;
	if($channel->shouldCheckScore === true && btfs_lookup_current_price($channel->name, $bookId) < $price)
	{
		$result = true;
	}
	return $result;
}

function btfs_lookup_current_price($channel_name, $bookId)
{
	$current_price_query = new parseQuery($channel_name."ScoreBoard");
	$current_price_query->whereEqualTo('book', array("__type" => "Pointer", "className" => "Book", "objectId" => $bookId));
	$results = $current_price_query->find();
	$price = ($channel_name === "Amazon") ? "kindle_price" : "price";
	return (count($results->results) > 0) ? $results->results[0]->$price : -1;
}

//formats the data into a date format that parse.com likes.
function btfs_format_parse_date($dateStr)
{
	$timeZone = "America/Los_Angeles"; //We want these to go out by 00:00:00.000 PST 
	
	$dateFormatString = 'Y-m-d\TH:i:s.u';
	$date_time = new DateTime($dateStr, new DateTimeZone($timeZone));	
	$date_time->setTimezone(new DateTimeZone("UTC")); //converting back to UTC for parse.com
	$date = date_format($date_time, $dateFormatString);
	$date = substr($date, 0, -3).'Z';

	return $date;
}

//***************************** Create Status Entry *************************************/
function btfs_create_status_entry($formObj, $bookObj, $entryId, $userId, $formName, $statusName, $statusId, $pcrName)
{
    $custom_fields = get_post_custom($bookObj->ID);
    
    $terms_as_text = get_the_term_list($bookObj->ID, 'status', ',','');    
    $status_value = preg_split('/,/', strip_tags($terms_as_text), -1, PREG_SPLIT_NO_EMPTY);
    
    $my_post = array(
       'post_title' => $statusName,
       'post_type' => 'Statuses',
       'tax_input' => array('Status' => $status_value)
     );     
     
     $customPostId = wp_insert_post($my_post);
     
     add_post_meta($customPostId, 'project_id', $bookObj->ID, true);
     add_post_meta($customPostId, 'entry_id', $entryId, true);
     add_post_meta($customPostId, 'user_id', $userId, true);
     add_post_meta($customPostId, 'status_process_step', $pcrName, true);
     add_post_meta($customPostId, 'form_name', $formName, true);
     add_post_meta($customPostId, 'status_id', $statusId,true);
     add_post_meta($customPostId, 'status_date', date("Ymd"), true);
}

//given a PCR name, it looks it up, and loads the appropriate fields.
function btfs_get_current_pcr_data( $pcr_name )
{
	$result = array(
		'pcr_name'				=> $pcr_name,
		'is_process_step' 	=> false,
		'is_approval_step'	=> false,
		'not_approved_go_to'	=> '',
		'status'					=> ''
	);
	
	if($pcr_name === "" || is_null($pcr_name))
	{
		return $result;
	}
	
	$args = array(
		'post_type' => 'pcr',
		'limit' => 1,
		'name' => $pcr_name
	);
	$new_query = new WP_Query( $args );

	if($new_query->have_posts())
	{
	   $new_query->next_post();
	   $pcr = $new_query->post;
		$pcr_custom = get_post_custom($pcr->ID);
	
		$status_as_text = get_the_term_list($pcr->ID, 'status', ',','');
		$status_array = preg_split('/,/', strip_tags($status_as_text), -1, PREG_SPLIT_NO_EMPTY);
		
		$result["ID"] = $pcr->ID;
		$result["status"] = $status_array[0];
		if($pcr_custom["pcr_is_process_step"][0] == 1) 
		{
			$result["is_process_step"] = true;
			$result["pcr_workflow"] = $pcr_custom["pcr_workflow"][0];

		}
		if(isset($pcr_custom["pcr_is_approval_step"]) && $pcr_custom["pcr_is_approval_step"][0] == 1)
		{
			$result["is_approval_step"] = true;
		}
		if(isset($pcr_custom["pcr_not_approved_go_to"]))
		{
			$result["not_approved_go_to"] = $pcr_custom["pcr_not_approved_go_to"][0];
		}
	}
	wp_reset_postdata();
	return $result;
}

function btfs_get_pcr_data_by_form_name($formName, $approved) 
{
	wp_reset_postdata();
	// get pcr with this form name
	$args = array(
		'numberposts' => 1,
		'post_type' => 'pcr',
		'meta_key' => 'pcr_form_name',
		'meta_value' => $formName
	);
	$new_query = new WP_Query( $args );
	$pcr = $new_query->post;
	
	$pcr_custom = get_post_custom($pcr->ID);

	$result = array(
		'cur_pcr_name'		=> '',
		'next_pcr_name' 	=> '',
		'is_process_step' 	=> false,
		'is_approval_step'	=> false,
		'not_approved_go_to'	=> '',
		'pcr_workflow'		=> '',
		'status'			=> ''
	);
//error_log(print_r("pcr_custom", true));	
//error_log(print_r($pcr_custom, true));	

	// not a process step? we're done.
	if($pcr_custom["pcr_is_process_step"][0] <> 1) 
	{
		if ( isset($pcr->title) )
		{
			$result["cur_pcr_name"] = $pcr->title;
		}
		$result["is_process_step"] = 0;
		return $result;
	} else {
		$result["is_process_step"] = 1;
	}

	$the_next_step = $pcr_custom["pcr_next_step"][0]; //standard next step, step forward in the workflow

	if ( isset($approved) && !$approved ) 
	{
		$the_next_step = $pcr_custom["pcr_not_approved_go_to"][0]; //do not pass go; go back to this step
	} 
	// get next step pcr name
	$args = array(
		'post_type' => 'pcr',
		'p' => $the_next_step
	);
	$next_query = new WP_Query( $args );
	$next_pcr = $next_query->post;
	//error_log(print_r($args, true));

	$result["cur_pcr_name"] = $pcr->post_title;
	$result["next_pcr_name"] = $next_pcr->post_title;
	
	$status_as_text = get_the_term_list($pcr->ID, 'status', ',','');
	$status_array = preg_split('/,/', strip_tags($status_as_text), -1, PREG_SPLIT_NO_EMPTY);
		
	$result["ID"] = $pcr->ID;
	$result["status"] = $status_array[0];

	$status_as_text = get_the_term_list($next_pcr->ID, 'status', ',','');
	$status_array = preg_split('/,/', strip_tags($status_as_text), -1, PREG_SPLIT_NO_EMPTY);
	$result["next_status"] = $status_array[0];

	if($pcr_custom["pcr_is_process_step"][0] == 1) 
	{
		$result["is_process_step"] = true;
		$result["pcr_workflow"] = $pcr_custom["pcr_workflow"][0];
	}
	if(isset($pcr_custom["pcr_is_approval_step"]) && $pcr_custom["pcr_is_approval_step"] == 1)
	{
		$result["is_approval_step"] = true;
	}
	if(isset($pcr_custom["pcr_not_approved_go_to"]))
	{
		$result["not_approved_go_to"] = $pcr_custom["pcr_not_approved_go_to"][0];
	}
	wp_reset_postdata();

	return $result;
}

function btfs_add_or_update_meta($id, $key, $value)
{
	add_post_meta($id, $key, $value, true) || update_post_meta($id, $key, $value);
}


function btfs_save_attachment_into_book($book_id, $fieldname, $attachment_url)
{
	$attachment_id = btfs_add_post_attachements($attachment_url, $book_id);
	if($attachment_id > 0)
	{
		btfs_add_or_update_meta($book_id, $fieldname, $attachment_id);
	}
}

function btfs_add_post_attachements($file_url, $post_id) 
{
	$attachment_id = -1;

	if(!function_exists('wp_generate_attachment_metadata')) 
	{ require_once(ABSPATH . 'wp-admin/includes/image.php'); }
	
	if ($file_url !== "") 
	{
		$upload_dir = wp_upload_dir();
		$file_data = file_get_contents($file_url);
		$filename = basename($file_url);
		if(wp_mkdir_p($upload_dir['path']))
		{
			$file = $upload_dir['path'] . '/' . $filename;
		}
		else
		{
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		
		file_put_contents($file, $file_data);
		
		$supported_mime_types = array(
			'pdf'  => 'application/pdf',
			'doc'  => 'application/msword', 
			'png'  => 'image/png', 
			'jpeg' => 'image/jpeg', 
			'jpg'  => 'image/jpeg'
		);
		
		$wp_filetype = wp_check_filetype($filename, $supported_mime_types);
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => sanitize_file_name($filename),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		
		if (strpos($attachment["post_mime_type"], 'image') !== false )
		{
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}		
		return $attach_id;
	}
}

function btfs_set_status_values ($book, $pcr_data)
{
	// determine which workflow is changing status
	// pull all workflows, go through them.
  	$new_status = array();
  	$args = array(
		'post_type' => 'workflow',
	);
	$workflows = new WP_Query( $args );

	while( $workflows->have_posts() ) 
	{
		$workflow = $workflows->next_post();
		$wf_custom = get_post_custom($workflow->ID);
		$workflow_field = $wf_custom["workflow_step_field_name"][0]; 

		// set status from workflow ctrl field 
		// unless pcr_data workflow ID matches, then pull new value from pcr_data
		if ($workflow->ID == $pcr_data["pcr_workflow"] )
		{
			$new_status[] = $pcr_data["next_status"];
			// set this workflow ctrl field to the new step name
			if ( $pcr_data["next_pcr_name"] <> "" ) 
			{
				btfs_add_or_update_meta($book->ID, $workflow_field, $pcr_data["next_pcr_name"]);
			}
		}
			else 
		{
			// get the step
			// get the PCR for the current wf step
			$step_name =  $book->$workflow_field;
			$args = array(
				'post_type' => 'pcr',
				'limit' => 1,
				'name' => $step_name
			);
			$new_query = new WP_Query( $args );
			// add the associated status
			while( $new_query->have_posts() ) {
				$pcr_temp = $new_query->next_post();
				$the_terms = wp_get_post_terms($pcr_temp->ID, 'status', array("fields" => "names"));
				$new_status[] = $the_terms[0];
			}
			wp_reset_postdata();
		}
	}

	wp_set_post_terms($book->ID, $new_status, "status", false);
	wp_reset_postdata();
}