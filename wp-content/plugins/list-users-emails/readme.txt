=== List Users Emails ===
Contributors: gutobenn
Donate link: http://www.u3b.com.br/donate
Tags: email, list, users, roles, groups, shortcode
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

List emails from users with chosen roles.

== Description ==

This plugin list emails from users of specified groups (administrators, editors, subscribers, custom roles...).

Usage: The shortcode [list-emails roles="administrator,editor"] generates a list of administrators and editors emails.

== Installation ==

1. Upload 'list-users-emails' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Paste your shortcode wherever you want

== Frequently Asked Questions ==

= Does it work with the roles I created? =

Yes, it works with any role.

= Does it hide emails from bots? =

Yes. You must add *antibot="hiddenspan"* in order to get it working. E.g [list-emails roles="administrator,editor" antibot="hiddenspan"]

== Changelog ==

= 1.0 =
* Added a simple anti-spam protection

= 1.0 =
* First Version

== Upgrade Notice ==

= 1.0 =
Added Anti-spam protection

= 1.0 =
This is the first version
