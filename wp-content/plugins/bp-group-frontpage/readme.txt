=== Plugin Name ===
Contributors: TimCarey, Tim Carey
Tags: BuddyPress, "BuddyPress", buddypress
Requires at least: WP 3.2.1, BP 1.5.1
Tested up to: WP 3.2.1, BP 1.5.1
Stable tag: 1.2.9

Creates a Group Frontpage from a form BuddyPress Group Admins fill out. Like a book cover.

== Description ==

 Creates a bp Group Frontpage from a form BuddyPress Group Admins can fill out. Like a book cover. For instance group banner image, large description, and links. Compatible with Suffusion child theme with Suffusion buddy pack and BP compatible themes.   

== Installation ==

1.  Automatically install bp Group Frontpage from word press plug in directory.
2.  Make sure buddy press plug in is activated.
3.  Make sure bp Album+ plug in is activated.
4.  Activate the bp Group Frontpage through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= The bp group frontpage plug in is activated, but the group frontpage has stopped showing up, what do I do? =

Just deactivate and reactivate the bp group frontpage plug in.

= It's just not working correctly =

Make sure you are using the buddy press 1.5 or greater.  There were major changes to caused this plug in to not work with buddy press 1.5.  This plug in was corrected to work with that so am I not work with older versions of buddy press.  If you don't want to go to the buddy press 1.5 or greater, trying version 1.2.8 of this plug in.

= Can I or my group Admins create style sheet for individual groups =

YES - Please refer to the section called, Adding Custom Style to A Group, under Upgrade Notices for complete instructions.
 

== Changelog ==

= 1.2.9 =
* Fixed a major problem with this plug in caused by buddy press having major changes would buddy press 1.5 and above.  This major change in buddy press cause this plug in, BP Group Frontpage, to not show up as the first page that comes up when someone goes to a group.
= 1.2.8 = 
* Fixed a minor problem, which could be confusing for the correct album link.
= 1.2.7 =
* Added functionality for individualized CSS style sheets each group for advanced group Admins.
* Instructions on how to implement this in a group please refer to notes. 
= 1.2.6 =
* Fixed issue where groups that have two or more admins the banner image disappears if a different other than the one that first posted the image makes changes on the group.
* Fixed some more formatting.
= 1.2.5 =
* Fixed spelling errors in the welcome screens (was wellcome).
= 1.2.4 =
* Fixed problem with links running together with some themes.
= 1.2.3 =
* Fixed problem of extra empty lines being added on every  save settings
= 1.2.2 =
* Fixed to allow certain characters to be displayed properly
= 1.2.1 =
* Added a width limiter set to 655 pixels.  And specified in instructions that is the limit.  Images actually just reduced to that width.
= 1.2.0 =
* Add full compatibility with buddy press default theme for the current buddy press 1.2.8
* Added feature to allow the the choice of banner image to coming from the profile album of the front page editor or from an external location, like another website.
= 1.0.0 =
* This is the first version of this plug in.

== Upgrade Notice ==

Just click on update automatically in your plugin area on your dashboard on your site.

== Info Before Activation ==
 
* This plugin is designed for use with network sites or MU site in conjunction with buddypress
* Buddy Press 1.2.7 or greater should be activated prior to activation of this plug in. 
* bp Album+ should be activated prior to activation of this plug in. 
* works on most buddy press compatible themes. 
* Now fully functional with the buddy press default theme. 
* Background colors and other frontpage settings relating to the looks can be simply adjusted by changing the included CSS document.

== To Come Eventually ==

1.  The ability for group Admins to be able to add up to 4 more ad hoc areas by choosing number of ad hoc areas from a drop down..
2.  A menu under the buddy press menu in the website admin for this plug in.  There would be the ability for the site admin to set the text 2 welcome screens.  One welcome screen is for the group admin who just started the group and the other welcome screen is notifying users that the group is still being created.  
3.  The ability to rearrange frontpage items.
4.  The addition of an advanced admin menu to be able to create and edit CSS style sheets in group frontpage group admin settings.
5.  Frontpage setting to be in its own and main menu of being with another and possibly having its own creation step when a group is created.

== Adding Custom Style to A Group ==

1.  Note: Group Frontpage styles only affect the group frontpage styles and not every page of the group.
2.  Make a copy of the group frontpage default CSS document through FTP to your computer.  ( Default CSS file name is: bp-group-frontpage-css.css ).  The directory where this is stored is /wp-content/plugins/bp-group-frontpage/css.
3.  Rename this copy and based on the name of the group it is intended for.  If your group name is My Group, then your file will be bp-group-f-my-group-css.css.  Notice that there is the addition of minus signs ( - ) between each word of your group name and the entire file name is lower case.  And the word frontpage is truncated two just the letter f. 
4.  Edit the file for your group and save.  
5.  FTP your new file into the same directory as the default file.
