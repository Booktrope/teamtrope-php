<?php
/*
Plugin Name: Parse.com Api
Plugin URI: http://booktrope.com
Description: Bridge between parse.com api and wordpress
Version: 0.0.1
Author: Justin Jeffress
Author URI: 

*/

define('WP_PARSE_API_PATH', plugin_dir_path(__FILE__));

require_once WP_PARSE_API_PATH . 'lib/parse-php-library/parse.php';
//require_once WP_PARSE_API_PATH . 'includes/class-wp-parse-api-helpers.php';
require_once WP_PARSE_API_PATH . 'includes/class-wp-parse-api-admin-settings.php';