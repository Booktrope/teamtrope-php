<?php


add_filter('gform_pre_render', 'bt_populate_userdropdown');
add_filter('gform_admin_pre_render', 'bt_populate_userdropdown');
add_filter('gform_pre_submission_filter', 'bt_populate_userdropdown');
function bt_populate_userdropdown($form)
{
	if ($form['title'] === 'Accept Team Member')
	{	
		foreach($form['fields'] as &$field)
		{
			if ($field["label"] === "New Team Member")
			{
				$choices = array();
				$user_list = get_users();
				foreach($user_list as $user)
				{
					array_push($choices, array("text" => $user->display_name, "value" => $user->ID ));
				}
				$field["choices"] = $choices;
			}
		}
	}
	else if($form['title'] === 'Paperback Orders & Other Production Costs')
	{
		foreach($form['fields'] as &$field)
		{
			if($field["adminLabel"] === "team_member")
			{
				$choices = array();
				$user_list = get_users();
				foreach($user_list as $user)
				{
					array_push($choices, array("text" => $user->display_name, "value" => $user->ID ));
				}
				$field["choices"] = $choices;				
			}
		}
	}	
	else if($form['title'] === 'Control Numbers')
	{
		$fields = array();
		if(get_post_meta(get_the_ID(), "book_parse_id", true) !== "")
		{
		   $fields["asin"]          = get_post_meta(get_the_ID(), "book_asin", true);
		   $fields["appleId"]       = get_post_meta(get_the_ID(), "book_apple_id", true);
		   $fields["ebookIsbn"]     = get_post_meta(get_the_ID(), "book_ebook_isbn", true);
		   $fields["hardbackIsbn"]  = get_post_meta(get_the_ID(), "book_hardback_isbn", true);
		   $fields["paperbackIsbn"] = get_post_meta(get_the_ID(), "book_isbn", true);
		   $fields["parseId"]       = get_post_meta(get_the_ID(), "book_parse_id", true);
		   
			$form = bt_func_populate_control_numbers($form, $fields);
		}
		else
		{
			$parse_query = new parseQuery("Book");
			$parse_query->whereEqualTo("teamtropeId", intval(get_the_ID()));
			$result = $parse_query->find();
			
			if(count($result->results) >= 1)
			{
				$book = $result->results[0];
				
			   $fields["asin"]          = isset($book->asin)          ? $book->asin : "";
			   $fields["appleId"]       = isset($book->appleId)       ? $book->appleId : "";
			   $fields["ebookIsbn"]     = isset($book->epubIsbn)      ? $book->epubIsbn : "";
		   	$fields["hardbackIsbn"]  = isset($book->hardbackIsbn)  ? $book->hardbackIsbn : "";
		   	$fields["paperbackIsbn"] = isset($book->paperbackIsbn) ? $book->paperbackIsbn : "";
			   $fields["parseId"]       = $book->objectId;
			   
			   $form = bt_func_populate_control_numbers($form, $fields);
			}
		}
	}
	return $form;
}

if ( ! function_exists( 'bt_func_populate_control_numbers' ) ) :
function bt_func_populate_control_numbers($form, $fields)
{
		foreach($form['fields'] as &$field)
		{
			switch($field["adminLabel"])
			{
				case "asin":
					$field["defaultValue"]  = $fields["asin"];
					break;
				case "apple_id":
					$field["defaultValue"]  = $fields["appleId"];
					break;
				case "ebook_isbn":
					$field["defaultValue"]  = $fields["ebookIsbn"];
					break;
				case "hardback_isbn":
					$field["defaultValue"]  = $fields["hardbackIsbn"];
					break;
				case "paperback_isbn":
					$field["defaultValue"]  = $fields["paperbackIsbn"];
					break;
				case "parse_id":
					$field["defaultValue"]  = $fields["parseId"];
					break;
			}
		}
	return $form;
}
endif;

add_filter("gform_admin_pre_render", "bt_gform_edit_date_filter",20);
 
function bt_gform_edit_date_filter($form){
 
    //only populating drop down for form of your choice
    if($form["title"] != 'Editing Complete Date')
        return $form;
 
    foreach($form['fields'] as &$field){
    	if ($field["label"] === "Editing Complete Committed Date") {

 
//        if($field['type'] == 'date'){
//            if(!isset($_REQUEST['input_'.$field['id']])){
                $field['value'] = date('Y/m/d',strtotime('+1 day'));
                echo  $field['value'];
//                $input_value = $field['value'];
//                add_filter('gform_field_value_arrive', 
//                create_function("", "return '$input_value';"));
 //           }
        }
        if ($field["label"] === "Go to Project") {
        	$field['value'] = "got the project";
        }
    }
 
    return $form;
}


if ( ! function_exists( 'bt_func_get_amazon_json_for_asin' ) ) :
function bt_func_get_amazon_json_for_asin($asin)
{
	//look up in cache
	//cache hit ?  return : call parse, wrap results, save in cache, return result
	$json_result = get_transient( $asin."_amazon");
	if(empty($json_result)) 
	{
		$parse_data = bt_func_load_amazon_stats_from_parse($asin);
		$json_result = bt_func_wrap_parse_results($parse_data);

		set_transient($asin."_amazon", $json_result, HOUR_IN_SECONDS);
	}
	return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_get_amazon_sales_for_asin' ) ) :
function bt_func_get_amazon_sales_for_asin($asin)
{
	//look up in cache
	//cache hit ?  return : call parse, wrap results, save in cache, return result
	$json_sresult = get_transient( $asin."_amazon_sales");
	if(empty($json_result)) 
	{
		$parse_data = bt_func_load_amazon_sales_stats_from_parse($asin);
		$json_result = bt_func_wrap_parse_sales_results($parse_data);

		set_transient($asin."_amazon_sales", $json_result, 8 * HOUR_IN_SECONDS);
	}
	return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_wrap_parse_results' ) ) :
function bt_func_wrap_parse_results($parse_data)
{

	if($parse_data["title"] === "" && $parse_data["price"] === "" && $parse_data["sales_rank"] === "")
	{
		return "";
	}

   $json_result = '';
   $json_result .= "     {";
   $json_result .= "            chart: {";
   $json_result .= "                zoomType: 'xy'";
   $json_result .= "            },";
   $json_result .= "            title: {";
   $json_result .= "             text: '".preg_replace('/\'/', '\\\'',$parse_data["title"])."'";
   $json_result .= "            },";
   $json_result .= "            subtitle: {";
   $json_result .= "                text: 'Amazon Price and Ranking Numbers'";

  $json_result .= "            },";
   $json_result .= "            xAxis: [{";
   $json_result .= "                 type: 'datetime',";
   $json_result .= "                 dateTimeLabelFormats: {";
   $json_result .= "                    month: '%e. %b',";
   $json_result .= "                    year: '%b'";
   $json_result .= "                }";
   $json_result .= "            }],";
   $json_result .= "            yAxis: [{";
   $json_result .= "                title: {";
   $json_result .= "                    text: 'Price',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#AED991'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                min: 0,";
   $json_result .= "                labels: {";
   $json_result .= "                    format: '\${value}',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#AED991'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                opposite: true";
   $json_result .= "            },";
   $json_result .= "                {";
   $json_result .= "                labels: {";
   $json_result .= "                    format: '{value}',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#1394BB'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                min: 0,";
   $json_result .= "                title: {";
   $json_result .= "                    text: 'Ranking',";
   $json_result .= "                    style: {";
   $json_result .= "                        color: '#1394BB'";
   $json_result .= "                    }";
   $json_result .= "                },";
   $json_result .= "                reversed: true,";
   $json_result .= "            }],";
   $json_result .= "            tooltip: {";
   $json_result .= "                shared: true";
   $json_result .= "            },";
   $json_result .= "            legend: {";
   $json_result .= "                layout: 'vertical',";
   $json_result .= "                align: 'left',";
   $json_result .= "                x: 20,";
   $json_result .= "                verticalAlign: 'top',";
   $json_result .= "                y: 0,";
   $json_result .= "                floating: true,";
   $json_result .= "                backgroundColor: '#FFFFFF'";
   $json_result .= "            },";
   $json_result .= "            series: [{";
   $json_result .= "                name: 'Price',";
   $json_result .= "                color: '#AED991',";
   $json_result .= "                type: 'area',";
   $json_result .= "                data: [".$parse_data["price"]."],";
   $json_result .= "                tooltip: {";
   $json_result .= "                    valuePrefix: '$',";
   $json_result .= "                    valueDecimals: '2'";
   $json_result .= "                }";
   $json_result .= "            },{";
   $json_result .= "                name: 'Ranking',";
   $json_result .= "                color: '#1394BB',";
   $json_result .= "                type: 'spline',";
   $json_result .= "                yAxis: 1,";
   $json_result .= "                data: [".$parse_data["sales_rank"]."],";
   $json_result .= "                tooltip: {";
   $json_result .= "                    valueSuffix: ' '";
   $json_result .= "                }";
   $json_result .= "            }]";
   $json_result .= "      }";
   return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_wrap_parse_sales_results' ) ) :
function bt_func_wrap_parse_sales_results($parse_data)
{
	if($parse_data["title"] === "" && (isset($parse_data["price"]) && $parse_data["price"] === "") && $parse_data["sales_rank"] === "")
	{
		return "";
	}
	$json_result = '';
	$json_result .= "     {";
	$json_result .= "           chart: {";
	$json_result .= "                zoomType: 'xy'";
	$json_result .= "            },";
	$json_result .= "            title: {";
	$json_result .= "            	 text: '".preg_replace('/\'/', '\\\'',$parse_data["title"])."'";
	$json_result .= "            },";
	$json_result .= "            subtitle: {";
	$json_result .= "                text: 'Sales Intelligence Estimates (Amazon US Only)'";
	$json_result .= "            },";

	$json_result .= "       xAxis: [{";
	$json_result .= "            type: 'datetime',";
	$json_result .= "            dateTimeLabelFormats: {";
	$json_result .= "                month: '%e. %b',";
	$json_result .= "                year: '%b'";
	$json_result .= "            }";
	$json_result .= "        }],";
	$json_result .= "        yAxis: {";
	$json_result .= "                title: {";
	$json_result .= "                    text: 'Sales Estimate'";
	$json_result .= "                },";
	$json_result .= "                min: 0";
	$json_result .= "            },";
	$json_result .= "        tooltip: {";
	$json_result .= "            shared: true";
	$json_result .= "        },";
	$json_result .= "        series: [{";
	$json_result .= "            name: 'Sales',";
	$json_result .= "            color: '#1394BB',";
	$json_result .= "            type: 'column',";
	$json_result .= "            data: [".$parse_data["sales"]."]";
	$json_result .= "      	 }]";
	$json_result .= "      }";
	return $json_result;
}
endif;

if ( ! function_exists( 'bt_func_load_amazon_stats_from_parse' ) ) :
function bt_func_load_amazon_stats_from_parse($asin)
{

	$payload = array(
		'price'			=> '',
		'sales_rank'	=> '',
		'title'			=> '',
	);

	$cloud = new parseCloud("getCrawlDataForAsin");



	$cloud->__set("asin",$asin);
print_r($cloud, true);
	
	//TODO: add support for these paraameters
	//$cloud->__set("skip", 150);
	//$cloud->__set("limit",1000);
	// Running the cloud function
	$results = $cloud->run();
	
	$json_result = json_encode($results);

   $first = true;
   $first_price = true;
   $price_str = "";
   $sales_rank_str = "";

	$cutOffDate = date(strtotime("2014-02-07 20:00:00"));

   $payload['title'] = $results->result->title;
	foreach($results->result->crawl as $result)
	{
		if(!$first) { $sales_rank_str.=","; }
		
		$date_parts = explode('T', substr($result->crawl_date->iso, 0, strlen($result->crawl_date->iso)-5));
		
		$crawl_date = explode('-', $date_parts[0]);
		$crawl_time = explode(':', $date_parts[1]);
		
		$year = $crawl_date[0];
		$month = $crawl_date[1]-1;
		$day = $crawl_date[2];
		
		$hour = $crawl_time[0];
		$minute = $crawl_time[1];
		$second = $crawl_time[2];
		
		$price = $result->kindle_price;
		$sales_rank = $result->sales_rank;
		
		$crawlDate = strtotime("$year-$month-$day $hour:$minute:$second");
		
		if ($price > 0 
		|| (property_exists($result, 'got_price') && $result->got_price != null && $result->got_price) 
		|| (!property_exists($result, 'got_price') && ($crawlDate < $cutOffDate)))
		{
			if(!$first_price) { $price_str.= ","; }
		   $price_str.= sprintf("[Date.UTC(%d,%d,%d,%d,%d,%d), %s]", $year, $month, $day, $hour, $minute, $second, $price);
   	   $first_price = false;   	
   	}
   	      
      $sales_rank_str.= sprintf("[Date.UTC(%d,%d,%d,%d,%d,%d), %s]", $year, $month, $day, $hour, $minute, $second, $sales_rank);
		
		$first = false;
	}
	$payload['price'] = $price_str;
	$payload['sales_rank'] = $sales_rank_str;
	
	return $payload;

}
endif;

if ( ! function_exists( 'bt_func_load_amazon_sales_stats_from_parse' ) ) :
function bt_func_load_amazon_sales_stats_from_parse($asin)
{
	$payload = array(
		'sales'			=> '',
		'title'			=> '',
	);

	$cloud = new parseCloud("getSalesDataForAsin");

	$cloud->__set("asin",$asin);
	
	//TODO: add support for these paraameters
	//$cloud->__set("skip", 150);
	//$cloud->__set("limit",1000);
	// Running the cloud function
	$results = $cloud->run();
	
	$json_result = json_encode($results);

   $first = true;
   $dailySales_str = "";
   $sales_rank_str = "";

   $payload['title'] = $results->result->title;
	foreach($results->result->crawl as $result)
	{
		if(!$first) { $dailySales_str.= ","; }
		
		$date_parts = explode('T', substr($result->crawlDate->iso, 0, strlen($result->crawlDate->iso)-5));
		
		$crawl_date = explode('-', $date_parts[0]);
		$crawl_time = explode(':', $date_parts[1]);
		
		$year = $crawl_date[0];
		$month = $crawl_date[1]-1;
		$day = $crawl_date[2];
		
		$hour = $crawl_time[0];
		$minute = $crawl_time[1];
		$second = $crawl_time[2];
		
		$sales = $result->dailySales;
		
        $dailySales_str.= sprintf("[Date.UTC(%d,%d,%d,%d,%d,%d), %s]", $year, $month, $day,
      	0, 0, 0, $sales);
		
		$first = false;
	}
	$payload['sales'] = $dailySales_str;
	
	return $payload;

}
endif;

function bt_func_load_promo_ready_books_from_parse($codeset)
{
	$cloud = new parseCloud("getPromoReady");
	$cloud->__set("","");
	//$asin = "B00CY22GOU";
	//$cloud = new parseCloud("getCrawlDataForAsin");
	//$cloud->__set("asin",$asin);
	
	$results = $cloud->run();
	$payload = 
"<div id='parse-data'><table class='sortable'>
	<tr>
   		<th>No.</th>
<th>Title</th>
<th>Author</th>
<th>ASIN</th>
<th>Price</th>
<th>No. Stars</th>
<th>No. Reviews</th>

   	</tr>";
	$i = 1;
	foreach($results->result as $result)
	{
		$payload .= 	
		"<tr>
			<td>" . $i . "</td><td>" . $result->title . "</td><td>" . $result->author . "</td><td><a target='blank' href='http://amzn.com/" . $result->asin . "'>". $result->asin . "</a></td><td>" . $result->kindle_price . "</td><td>" . $result->average_stars . "</td><td>" . $result->num_of_reviews . "</td>
		</tr>";
		$i = $i + 1;
	}
	$payload .= "</table></div>";
	
	return $payload;
}

function daysInStatus($proj, $prompt, $category)
{
	global $wpdb;
	$query = "SELECT p . * 
	FROM wp_posts p
	INNER JOIN wp_postmeta pm ON pm.post_id = p.ID
	INNER JOIN wp_postmeta pm2 ON pm2.post_id = p.ID
	WHERE (p.post_title = '" . $category . "' ) AND
	
	(
	pm.meta_key =  'project_id'
	AND pm.meta_value = " . $proj->ID . "
	)
	ORDER BY p.post_date DESC
	LIMIT 1";

	$statuses = $wpdb->get_results($query);

	$status_date = "";
	foreach($statuses as $status)
	{
//		$status_date = $status->post_date;

 		$status_custom = get_post_custom($status->ID);
 		$status_date = $status_custom['status_date'][0];
		break; // just need the 1st one
	} 
	if ( $status_date == "" ) {
		//$days_in_status = '&#8734;';
		$days_in_status = '-';
	} else {
		$now = time(); 
    	$datediff = $now - strtotime($status_date);
    	$daysInStep = floor($datediff/(60*60*24));
    	if (isset($book_pcr_step)) {
	    	$PCRStepDays = getPCRDaysForStep($book_pcr_step);
	    	
	    	if ( $PCRStepDays <= $daysInStep ) {
	    		$color = "red";
				$sched = "(Behind Schedule)";

	    	} elseif ( $daysInStep >= floor(.75*$PCRStepDays) ) {
	    		$color = "rgb(197, 197, 22)";
				$sched = "(Deadline Approaching)";

	    	} else {
				$color = "green";
				$sched = "(On Schedule)";
	    	}
	    	
	    } else {
	    	$color = "black";
	    }
	    if ( $prompt ) {
	    	$sched_txt = $sched;
	    }
		$days_in_status = "<span style='color:" . $color . "' ><strong>" . $daysInStep . " </strong></span> " .  $sched_txt . "";
	}
//	wp_reset_query();
	return $days_in_status;
}

function getPCRDaysForStep ($book_pcr_step) 
{
	$args = array(	'name'=>$book_pcr_step, 
					'post_type'=>'pcr', 
					'limit'=> '1'
				);
	$pcr = new WP_Query($args);
	$i = 1;
	while ($pcr->have_posts()) : $pcr->the_post();            	
		$pcr_name = get_the_title();
		$pcr_id = get_the_ID();
		$pcr_data = get_post_custom($pcr_id);
		$i++;
	endwhile;
	$days = $pcr_data['pcr_days_to_complete_step'][0];
	wp_reset_query();
	return $days;
}

if ( ! function_exists( 'studio_setup' ) ) :
function studio_setup() {
global $themename, $shortname, $options, $options2, $options3, $bp_existed, $multi_site_on;

	load_theme_textdomain('studio', get_template_directory() . '/languages/');
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 685;
	}
	add_action( 'wp_enqueue_scripts', 'studio_load_scripts' );
	add_action( 'widgets_init', 'studio_widgets_init' );
	add_action( 'wp_enqueue_scripts', 'studio_enqueue_styles' );
	add_action( 'wp_head', 'studio_themeoptions_output' );

	require( dirname( __FILE__ ) . '/library/functions/conditional-functions.php' );
	if($bp_existed == 'true') {
		require( dirname( __FILE__ ) . '/library/functions/bp-functions.php' );
		add_filter( 'comment_form_defaults', 'wpmudev_comment_form', 10 );
	}

	$functcustomheader_on = get_option('dev_studio_customheader_on');
	if ($functcustomheader_on == "yes"){
		// turn on if using custom header
		require( dirname( __FILE__ ) . '/library/functions/custom-header.php' );
	}
	require( dirname( __FILE__ ) . '/library/functions/custom-functions.php' );
	require( dirname( __FILE__ ) . '/library/functions/option-functions.php' );
	require( dirname( __FILE__ ) . '/library/functions/loop-functions.php' );

	add_theme_support('automatic-feed-links');
}
endif;
add_action( 'after_setup_theme', 'studio_setup');

if ( ! function_exists( 'studio_enqueue_styles' ) ) :
function studio_enqueue_styles(){
global $themename, $shortname, $options, $options2, $options3, $bp_existed, $multi_site_on;
	$version = '2';

	if ($bp_existed){
	wp_enqueue_style( 'studio-buddypress', get_template_directory_uri() . '/_inc/css/studio-buddypress.css', array(), $version );
	}

	wp_enqueue_style( 'studio', get_template_directory_uri() . '/_inc/css/studio.css', array(), $version );
	wp_enqueue_style( 'studio-colours', get_template_directory_uri() . '/_inc/css/studio-colours.css', array('studio'), $version );
}
endif;

if ( ! function_exists( 'studio_load_scripts' ) ) :
function studio_load_scripts() {
	$version = '2';
	if ( !is_admin() ) {
		wp_enqueue_script("jquery");
		wp_enqueue_script( 'panel', get_template_directory_uri() . '/library/scripts/slide.js', array( 'jquery' ), $version );
		wp_enqueue_script( 'slideshow', get_template_directory_uri() . '/library/scripts/loopedslider.js', array( 'jquery' ), $version );

		if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;

if ( ! function_exists( 'studio_themeoptions_output' ) ) :
function studio_themeoptions_output(){
include (get_template_directory() . '/library/options/options.php');
		$get_current_scheme = get_option('dev_studio_custom_style');
		if($get_current_scheme == 'default.css') {
		 print "<style type='text/css' media='screen'>";
		 include (get_template_directory() . '/library/options/theme-options.php');
		 print "</style>";
		}
		if (($get_current_scheme == "") || ($get_current_scheme == 'blue.css')) { ?>
				<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/styles/blue.css" type="text/css" media="all" />
<?php
		}	elseif($get_current_scheme!='default.css') { ?>
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/styles/<?php echo $get_current_scheme; ?>" type="text/css" media="all" />
			<?php }?>
				<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/_inc/css/child.css" type="text/css" media="all" />
				<?php
}
endif;

if ( ! function_exists( 'studio_widgets_init' ) ) :
function studio_widgets_init() {
global $themename, $shortname, $options, $options2, $options3, $bp_existed, $multi_site_on;
register_sidebar(
	array(
		'name'          => __( 'Sidebar Blog', 'studio' ),
		'id'            => 'sidebar-blog',
		'description'   => 'Blog page Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Sidebar Page', 'studio' ),
		'id'            => 'sidebar-page',
		'description'   => 'Page Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Sidebar 404', 'studio' ),
		'id'            => 'sidebar-404',
		'description'   => '404 Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Sidebar Search', 'studio' ),
		'id'            => 'sidebar-search',
		'description'   => 'Search Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Sidebar Archive', 'studio' ),
		'id'            => 'sidebar-archive',
		'description'   => 'Archive Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Sidebar Single', 'studio' ),
		'id'            => 'sidebar-single',
		'description'   => 'Single Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Footer widget one', 'studio' ),
		'id'            => 'widgetone-area',
		'description'   => 'Footer widget area one',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Footer widget two', 'studio' ),
		'id'            => 'widgettwo-area',
		'description'   => 'Footer widget area two',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

register_sidebar(
	array(
		'name'          => __( 'Footer widget three', 'studio' ),
		'id'            => 'widgetthree-area',
		'description'   => 'Footer widget area three',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);

if($bp_existed == 'true') {
register_sidebar(
	array(
		'name'          => __( 'BuddyPress sidebar', 'studio' ),
		'id'            => 'sidebar-members',
		'description'   => 'BuddyPress sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	)
);
}

}
endif;

if ( !function_exists( 'wpmudev_comment_form' ) ) :
function wpmudev_comment_form( $default_labels ) {
	global $themename, $shortname, $options, $options2, $options3, $bp_existed, $multi_site_on;

	if($bp_existed == 'true') :
	global $user_identity;

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'studio' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'studio' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'studio' ) . '</label>' .
		            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$new_labels = array(
		'comment_field'  => '<p class="form-textarea"><textarea name="comment" id="comment" cols="60" rows="10" aria-required="true"></textarea></p>',
		'fields'         => apply_filters( 'comment_form_default_fields', $fields ),
		'logged_in_as'   => '',
		'must_log_in'    => '<p class="alert">' . sprintf( __( 'You must be <a href="%1$s">logged in</a> to post a comment.', 'studio' ), wp_login_url( get_permalink() ) )	. '</p>',
		'title_reply'    => __( 'Leave a reply', 'studio' )
	);

	return apply_filters( 'wpmudev_comment_form', array_merge( $default_labels, $new_labels ) );
	endif;
}
endif;

if ( !function_exists( 'wpmudev_blog_comments' ) ) :
function wpmudev_blog_comments( $comment, $args, $depth ) {
global $themename, $shortname, $options, $options2, $options3, $bp_existed, $multi_site_on;

if($bp_existed == 'true') {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type )
		return false;

	if ( 1 == $depth )
		$avatar_size = 50;
	else
		$avatar_size = 25;
	?>

	<li <?php comment_class() ?> id="comment-<?php comment_ID() ?>">
		<div class="comment-avatar-box">
			<div class="avb">
				<a href="<?php echo get_comment_author_url() ?>" rel="nofollow">
					<?php if ( $comment->user_id ) : ?>
						<?php echo bp_core_fetch_avatar( array( 'item_id' => $comment->user_id, 'width' => $avatar_size, 'height' => $avatar_size, 'email' => $comment->comment_author_email ) ) ?>
					<?php else : ?>
						<?php echo get_avatar( $comment, $avatar_size ) ?>
					<?php endif; ?>
				</a>
			</div>
		</div>

		<div class="comment-content">
			<div class="comment-meta">
				<p>
					<?php
						printf( __( '<a href="%1$s" rel="nofollow">%2$s</a> said on <a href="%3$s"><span class="time-since">%4$s</span></a>', 'studio' ), get_comment_author_url(), get_comment_author(), get_comment_link(), get_comment_date() );
					?>
				</p>
			</div>

			<div class="comment-entry">
				<?php if ( $comment->comment_approved == '0' ) : ?>
				 	<em class="moderate"><?php _e( 'Your comment is awaiting moderation.', 'studio' ); ?></em>
				<?php endif; ?>

				<?php comment_text() ?>
			</div>

			<div class="comment-options">
					<?php if ( comments_open() ) : ?>
						<?php comment_reply_link( array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ); ?>
					<?php endif; ?>

					<?php if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) : ?>
						<?php printf( '<a class="button comment-edit-link bp-secondary-action" href="%1$s" title="%2$s">%3$s</a> ', get_edit_comment_link( $comment->comment_ID ), esc_attr__( 'Edit comment', 'studio' ), __( 'Edit', 'studio' ) ) ?>
					<?php endif; ?>

			</div>

		</div>
<?php } else {

	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type )
		return false;

	if ( 1 == $depth )
		$avatar_size = 50;
	else
		$avatar_size = 25;
	?>
	<li <?php comment_class() ?> id="comment-<?php comment_ID() ?>">
		<div class="comment-avatar-box">
			<div class="avb">
				<a href="<?php echo get_comment_author_url() ?>" rel="nofollow">
					<?php if ( $comment->user_id ) : ?>
							<?php echo get_avatar( $comment, 40 ); ?>
					<?php else : ?>
						<?php echo get_avatar( $comment, $avatar_size ) ?>
					<?php endif; ?>
				</a>
			</div>
		</div>

		<div class="comment-content">
			<div class="comment-meta">
				<p>
					<?php
						printf( __( '<a href="%1$s" rel="nofollow">%2$s</a> said on <a href="%3$s"><span class="time-since">%4$s</span></a>', 'studio' ), get_comment_author_url(), get_comment_author(), get_comment_link(), get_comment_date() );
					?>
				</p>
			</div>

			<div class="comment-entry">
				<?php if ( $comment->comment_approved == '0' ) : ?>
				 	<em class="moderate"><?php _e( 'Your comment is awaiting moderation.', 'studio' ); ?></em>
				<?php endif; ?>

				<?php comment_text() ?>
			</div>

			<div class="comment-options">
					<?php if ( comments_open() ) : ?>
						<?php comment_reply_link( array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ); ?>
					<?php endif; ?>

					<?php if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) : ?>
						<?php printf( '<a class="button comment-edit-link" href="%1$s" title="%2$s">%3$s</a> ', get_edit_comment_link( $comment->comment_ID ), esc_attr__( 'Edit comment', 'studio' ), __( 'Edit', 'studio' ) ) ?>
					<?php endif; ?>

			</div>

		</div>
<?php
	}
}
endif;

///////////////////////////////////////////////////////////////////////////
/* -------------------- Update Notifications Notice -------------------- */
if ( !function_exists( 'wdp_un_check' ) ) {
  add_action( 'admin_notices', 'wdp_un_check', 5 );
  add_action( 'network_admin_notices', 'wdp_un_check', 5 );
  function wdp_un_check() {
    if ( !class_exists( 'WPMUDEV_Update_Notifications' ) && current_user_can( 'edit_users' ) )
      echo '<div class="error fade"><p>' . __('Please install the latest version of <a href="http://premium.wpmudev.org/project/update-notifications/" title="Download Now &raquo;">our free Update Notifications plugin</a> which helps you stay up-to-date with the most stable, secure versions of WPMU DEV themes and plugins. <a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">More information &raquo;</a>', 'wpmudev') . '</a></p></div>';
  }
}