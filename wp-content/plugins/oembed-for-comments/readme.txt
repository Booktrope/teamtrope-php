=== oEmbed for Comments ===
Contributors: r-a-y
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V864MRMV6DTKN
Tags: oembed, embed, comment, comments
Requires at least: WP 2.9
Tested up to: WP 2.9.2
Stable tag: 0.6

Enable oEmbed support for comments.  Requires WP 2.9+.

== Description ==

It's the same oEmbed functionality you know and love from your WP 2.9 post editor, now enabled for comments!

Your comments can now embed content from the following sites:

* YouTube
* Blip.tv
* Vimeo
* DailyMotion
* Flickr
* Hulu
* Viddler
* Qik
* Revision3
* Photobucket
* Scribd
* Wordpress.tv

How do you use the plugin?  Simple!  Input **any** URL from one of the listed sites above into a comment.

When the comment is posted and approved, the URL automagically transforms into the embedded content.


== Installation ==

#### This plugin requires at least Wordpress 2.9 ####

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
1. Login to the Wordpress dashboard and navigate to "Plugins > Installed".  Activate the "oEmbed for Comments" plugin.


== Frequently Asked Questions ==

#### What is oEmbed ####

[oEmbed](http://www.oembed.com/) is a simple API that allows a website to display embedded content (such as photos or videos) when a user posts a link to that resource.  It was designed to avoid copying and pasting HTML from the media you wish to embed.


#### What is oEmbed for Comments? ####

oEmbed for Comments utilizes [Wordpress' own oEmbed class](http://codex.wordpress.org/Embeds), so by default, you can share content from the following sites in your comments:

* YouTube
* Blip.tv
* Vimeo
* DailyMotion
* Flickr
* Hulu
* Viddler
* Qik
* Revision3
* Photobucket
* Scribd
* Wordpress.tv

The plugin allows you to input **any** URL from one of the listed sites above into a comment.

When the comment is posted and approved, the URL automagically transforms into the embedded content.  There is no GUI.


#### How do I extend Wordpress' oEmbed provider list? ####

By default, you can only embed content from websites listed on Wordpress' internal whitelist. This is to prevent the embedding of malicious content from untrustworthy websites.

To add an oEmbed provider, read the following article for more info:
http://codex.wordpress.org/Embeds#Adding_Support_For_An_oEmbed-Enabled_Site

The other option is you can override Wordpress' internal whitelist and enable *any* site that is oEmbeddable by downloading and activating Viper007Bond's Enable oEmbed Discovery plugin.
**You should only activate the oEmbed Discovery plugin if you trust your user base. You've been warned.**


== Blacklist feature ==

This feature allows you to blacklist websites from being processed by oEmbed.

For example, say I wanted to block YouTube links from being embedded.

Open `oembed-config.php` in a text editor and add the following line to the end:

`$oembed_comments['blacklist'][] = 'youtube.com';`

This will blacklist all links from YouTube.com from being parsed.

== Known issues ==

* Hyperlinking an oEmbeddable link and inputting the same link in plain text will show the oEmbeddable item three times (two times if using anchor text) (not many people will do this)


== Special thanks ==

* [Viper007Bond](http://www.viper007bond.com/) - for creating the WP_oEmbed class
* [apeatling](http://buddypress.org/developers/apeatling/) - for performance enhancements

== Donate! ==

There are a couple of ways you can choose to support me:

* [Fund my work soundtrack!](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V864MRMV6DTKN)  Music helps me help you!  A dollar lets me buy a new tune off Amazon MP3, Amie Street or emusic.com!  Or if you're feeling generous, you can help me buy a whole CD!  If you choose to donate, let me know what songs or which CD you want me to listen to! :)
* Rate this plugin

== Changelog ==

= 0.6 =
* First version!
* Basically a port of my oEmbed for BuddyPress plugin ;)