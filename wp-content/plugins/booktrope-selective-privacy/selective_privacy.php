<?php
/*
 * Plugin Name: Booktrope Selective Privacy
 * Plugin URI: http://booktrope.com
 * Description: Selectively disable the Private Buddypress forced login
 * Version: 0.1
 * Author: Brian Ronald
 * Author URI: http://booktrope.com
 *
 * This plugin adds some work-arounds for Absolute Privacy and Private BuddyPress so that the
 * OAuth2 plugin will work.  It can also be used to disable forced-logins for certain URLs,
 * to fix issues where a redirect-url has its' paramters stripped, and to add domains to the
 * safe-redirect list.
 */

class Selective_Privacy
{
	/**
	 * @var Selective_Privacy
	 */
	private static $instance;

	private $noAuthUrls = array(
		// URL (partial) => Offset
		'/oauth/token' => 0, // API call which does not require an authenticated user
		'/oauth/me' => 0,    // API call which does not require an authenticated user
	);

	private $fullRequestRedirectUrls = array(
		// URL (partial) => Offset
		'/oauth/authorize' => 0, // API call which does not require an authenticated user
	);

	private $safeRedirectHosts = array(
		'brianr.mlinks.net'
	);

	/**
	 * Singleton
	 *
	 * @return Selective_Privacy
	 */
	public static function getInstance()
	{
		if(! self::$instance instanceof Selective_Privacy) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Configures the appropriate filters - Safe to run multiple times
	 */
	public function configureFilters() {
		// Add this filter regardless
		add_filter( 'pbp_login_required_check', array( $this, 'checkAllowedUrl' ), 1 );

		// Fix redirect URL to use full params for certain paths
		add_filter( 'login_redirect', array( $this, 'fullRedirect' ), 1 );

		// If this is a no-auth URL, disable AbsolutePrivacy before it has a chance to run
		if ( $this->isCurrentRequestInCollection( $this->noAuthUrls ) ) {
			remove_filter( 'template_redirect', 'abpr_lockDown' );
		}

		// Add filter that'll add safe redirect domains
		add_filter('allowed_redirect_hosts', array($this, 'allowedRedirectHosts'));
	}

	/**
	 * Checks the current URL against a list of safe URLs
	 *
	 * This is intended to be a dual-purpose method.  If called from configureFilters, it will
	 * merely remove the template_direct filter from AbsolutePrivacy which runs early on.  If
	 * called from Private Buddy Press's allowedUrl filter, it will match the URL and optionally
	 * return a false if it's one of the configured safe URLs, otherwise will return whatever
	 * value was passed in from a previous filter.
	 *
	 * @param bool $currentVisibility
	 *
	 * @return bool
	 */
	public function checkAllowedUrl($currentVisibility)
	{
		if(empty($_SERVER['REQUEST_URI']) || empty($this->noAuthUrls)) {
			return $currentVisibility;
		}

		if($this->isCurrentRequestInCollection($this->noAuthUrls)) {
			return false;
		}

		return $currentVisibility;
	}

	/**
	 * Wordpress strips off the additional parameters, so if this is one of our allowed URL's
	 * recreate the redirect URL based on the request
	 *
	 * @param string $redirect_to
	 * @param string $redirect_to_raw
	 * @param $user
	 *
	 * @return string
	 */
	public function fullRedirect( $redirect_to, $redirect_to_raw = null, $user = null )
	{
		if(empty($_REQUEST) || ! isset($_REQUEST['redirect_to'])) {
			return $redirect_to;
		}

		if($this->isCurrentRequestInCollection($this->fullRequestRedirectUrls)) {
			$params = array();
			foreach($_REQUEST as $key => $val) {
				if($key == 'redirect_to') {
					$params[] = $val;
				} else {
					$params[] = "{$key}={$val}";
				}
			}

			$redirect_to = implode('&', $params);
		}

		return $redirect_to;
	}

	public function isCurrentRequestInCollection($urlCollection)
	{
		foreach($urlCollection as $url => $offset) {
			if(stripos($_SERVER['REQUEST_URI'], $url, $offset) !== FALSE) {
				return true;
			}
		}

		return false;
	}

	public function allowedRedirectHosts($hosts)
	{
		if (empty($hosts)) {
			$hosts = array();
		}

		return array_unique(array_merge($hosts, $this->safeRedirectHosts));
	}
}

$selectivePrivacy = Selective_Privacy::getInstance();
$selectivePrivacy->configureFilters();
