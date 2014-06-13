<?php
/**
 * Interface defining constants, adding files and WordPress core functionality.
 *
 * Defining some constants, loading all the required files and Adding some core functionality.
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menu() To add support for navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @package Theme Horse
 * @subpackage Interface
 * @since Interface 1.0
 */

//booktrope additions

add_filter('gform_pre_render', 'bt_populate_userdropdown');
add_filter('gform_admin_pre_render', 'bt_populate_userdropdown');
add_filter('gform_pre_submission_filter', 'bt_populate_userdropdown');
function bt_populate_userdropdown($form)
{
	if ($form['title'] === '1.15 Accept Team Member')
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
	return $form;
}

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
	if($parse_data["title"] === "" && $parse_data["price"] === "" && $parse_data["sales_rank"] === "")
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

function daysInStatus($proj, $prompt, $book_pcr_step)
{
	global $wpdb;
	$query = "SELECT p . * 
	FROM wp_posts p
	INNER JOIN wp_postmeta pm ON pm.post_id = p.ID
	INNER JOIN wp_postmeta pm2 ON pm2.post_id = p.ID
	WHERE (
	pm.meta_key =  'project_id'
	AND pm.meta_value = " . $proj->ID . "
	)
	AND (
	pm2.meta_key =  'status_process_step'
	AND pm2.meta_value = '" . $book_pcr_step . "'
	)
	ORDER BY p.post_date DESC";

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
