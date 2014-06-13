<?php
/*
Plugin Name: oEmbed for Comments
Description: Enable oEmbed support for comments.  Requires WP 2.9+.
Author: r-a-y
Author URI: http://buddypress.org/developers/r-a-y
Version: 0.6

License: CC-GNU-GPL http://creativecommons.org/licenses/GPL/2.0/
Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KU38JAZ2DW8TW
*/

add_filter('comment_text','ray_oembed_comments', 8);

global $oembed_comments;

require_once( dirname( __FILE__ ) . '/oembed-config.php' );

// blacklist hyperlinks
$oembed_comments['blacklist'][] = '<a ';
$oembed_comments['blacklist'][] = '">';

// blacklist links from WP domain
$oembed_comments['blacklist'][] = parse_url(get_bloginfo('wpurl'), PHP_URL_HOST);

function ray_oembed_comments($content) {
	global $wp_embed, $oembed_comments;

	// WP(MU) 2.9 oEmbed check
	if( !function_exists( 'wp_oembed_get' ) )
		return $content;

	/* Regex to match URLs */
	preg_match_all('`.*?((http|https)://[\w#$&+,\/:;=?@.-]+)[^\w#$&+,\/:;=?@.-]*?`i', $content, $matches);

	/* If there are no links to parse, return content */
	if ( empty( $matches[0] ) )
		return $content;

	$links = $matches[0];

	// WP_Embed handlers = not oEmbed providers, but offer embed support via regex callback
	// sort these embed handlers by priority
	ksort( $wp_embed->handlers );

	/* Include the WP oEmbed Class */
	require_once( ABSPATH . WPINC . '/class-oembed.php' );

	/* Set up a new WP oEmbed object */
	$wp_oembed = new WP_oEmbed;

	foreach ( (array)$links as $url ) {

		// check url with whitelist, if url matches any whitelist item, skip from parsing
		foreach ($oembed_comments['blacklist'] as $blacklist_item) {
			if (strpos($url,$blacklist_item) !== false) { 
				continue 2;
			}
		}

		// check to see if url matches list of WP_Embed handlers first
		$is_wpembed_link = false;
		foreach ( $wp_embed->handlers as $priority => $handlers ) {
			foreach ( $handlers as $id => $handler ) {
				if ( preg_match( $handler['regex'], $url, $matches ) && is_callable( $handler['callback'] ) ) {
					if ( false !== $return = call_user_func( $handler['callback'], $matches, $attr, $url, $rawattr ) ) {
						$replace = apply_filters( 'embed_handler_html', $return, $url, $attr );
						$is_wpembed_link = true;
						continue 2;
					}
				}
			}			
		}

		// if url doesn't match WP_Embed handlers, let's check oEmbed!
		if(!$is_wpembed_link) {

			/* Check to see if url matches list of known WP oEmbed providers */
			$is_oembed_link = false;
			foreach ( (array)$wp_oembed->providers as $provider_matchmask => $provider ) {
				$regex = ( $is_regex = $provider[1] ) ? $provider_matchmask : '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $provider_matchmask ), '#' ) ) . '#i';
	
				if ( preg_match( $regex, $url ) )
					$is_oembed_link = true;
			}

			/* If url doesn't match a WP oEmbed provider, skip url */
			if ( !$is_oembed_link )
				continue;			

			$cachekey = '_oembed_' . md5($url);

			/* Grab oEmbed comment cache */
			$cache = get_comment_meta(get_comment_ID(), $cachekey, true);

			// cache check - yes oEmbed
			if ( !empty($cache) ) {
				$replace = apply_filters( 'embed_oembed_html', $cache, $url, $attr );
			}
			// if no cache, let's start the show!
			else {
				// process url to oEmbed
				$oembed = wp_oembed_get($url);

				$replace = apply_filters( 'embed_oembed_html', $oembed, $url, $attr );
				$replace = str_replace('
','',$replace); // fix Viddler line break in <object> tag

				/* Save oEmbed cache to comment meta */
				update_comment_meta(get_comment_ID(), $cachekey, $replace);
			}

		}

		$content = str_replace($url, $replace, $content);
	}

	return $content;
}
?>