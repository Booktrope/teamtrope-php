<?php 

$which_parse = get_option('active_account');

if(!defined('WP_PARSE_API_APP_ID'))      define('WP_PARSE_API_APP_ID',      ($which_parse === "dev") ? get_option('dev_app_id')        : get_option('app_id'));
if(!defined('WP_PARSE_API_MASTERKEY'))   define('WP_PARSE_API_MASTERKEY',   ($which_parse === "dev") ? get_option('dev_app_masterkey') : get_option('app_masterkey'));
if(!defined('WP_PARSE_API_APP_RESTKEY')) define('WP_PARSE_API_APP_RESTKEY', ($which_parse === "dev") ? get_option('dev_app_restkey')   : get_option('app_restkey'));


class parseConfig
{
	const APPID = WP_PARSE_API_APP_ID;
	const MASTERKEY = WP_PARSE_API_MASTERKEY;
   const RESTKEY = WP_PARSE_API_APP_RESTKEY;
   const PARSEURL = 'https://api.parse.com/1/';
}
?>