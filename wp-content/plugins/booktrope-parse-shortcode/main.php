<?php
/*
Plugin Name: BookTrope - Promo Ready Books
Plugin URI: http://booktrope.com/
Description: Shortcode to add the BookBub ready books to a page
Version: 0.1
Author: booktrope.com
Author URI: http://booktrope.com/
*/
/* 	num - starting number
	date - seed date (date on which the number was valid)
	increment - how many to add each time the counter fires
	pace - how long to wait until adding the increment
*/
function bt_parse_run( $atts ){
    extract( shortcode_atts( array(
		'codeset' => 'getPromoReady',
		'date' => '',
	), $atts ) );
	
	$date_format = 'Y-m-d';
	$date = trim($date);
	$time = strtotime($date);

	$output = bt_func_load_promo_ready_books_from_parse($codeset);
    
	return $output;
}

add_shortcode( 'cloudcode', 'bt_parse_run' );

