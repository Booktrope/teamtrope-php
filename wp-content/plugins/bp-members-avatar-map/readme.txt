=== BP Members Avatar map ===
Contributors: Vardi , michaelvar
Donate link: http://web-world.co.il/donate
Tags: BuddyPress, Members, maps, Avatar, location
Requires at least: WP 2.9, BuddyPress 1.2.4
Tested up to: WP 3.2.1, BuddyPress 1.3


Add a Google map display with all the members location with their avatar.
== Description ==
<b>Fix the javascript problem with 'addLoadEvent' error 17/9/2011</b>

Map plugin to show all members location and Avatars for BuddyPress sites.

Add a Google map to display the members Location. Maps are displayed in the all members page.

This is done with the users registering their address Latitude and Longitude and not by addressing the geolocation service all the time but only on registration 

by taking the Location field and requesting the Latitude and Longitude from Open Street Map Nominatim geolocation service  .This plugin will be developed more further than initial release.

The admin settings page allows the map to be assigned dimensions plus various map rendering options .

Important: You must have set up an extended profile field named 'Location'  this can be either set to in the 'Base' group in which case it will appear on the signup page or you can
create a new group and have the field display on the members profile settings. The map will only display once the member has added their location to this new field.
You must also set up a Latitude and Longitude field. !!very importent!!
You can either set the Latitude and Longitude field in the base group to show it on the sign up page or create a new group for the field name in which case it will show in the users profle setup and public display only.
You Can set up an extended profile field named 'Info' to show the member info in the map -  not a must... 			
== Installation ==

* Upload the directory '/bp-members-avatar-map/' to your WP plugins directory and activate from the Dashboard of the main blog.
* Configure the plugin at Dashboard > BuddyPress > MAM Settings.
* Presently you can set map dimensions plus map overlay options.
* Set up 3 Profile fields - 1.Location 2.Latitude and Longitude 3.Info       -the info field is not a must.

 == Frequently Asked Questions ==
 None at this time

 == Upgrade Notice ==
 1.0 to work with WP 3.1 & BuddyPress 1.3 plus code improvements.
 1.3 Add a check and remove extra " to avoid js errors
 == Screenshots ==
1. A view of a members avatars map displayed  in the members directory page.
2. A view of a members avatars map displayed  in the members directory page - more generic.

== Changelog ==

** V 1.0 **  05/09/2011
Stable initial release

** V 1.1 **  18/09/2011
Stable second release

** V 1.2 **  18/09/2011
Stable third release

** V 1.3 **  18/09/2011
Stable last release
