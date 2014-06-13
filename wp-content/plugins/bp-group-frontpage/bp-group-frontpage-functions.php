<?php
// create the default subscription settings during group creation and editing
function tac_frontpage_settings_form() {
	global $bp;
	$userDomain = $bp->loggedin_user->domain;
	//$username = bp_member_name();
	//_e('.Some text ', 'bp-ass');$bp->current_item;
	//$tac_banner_info = 'Banner image address'; 
	//_e('When new users join this group, their default email notification settings will be:', 'bp-ass'); 
	
	echo '<hr/><br/><br/><hr/><hr/><table width="100%">
	
<tr><td colspan="4"><h4>Group Frontpage Setup</h4>Use the form elements below to create your group Frontpage<br/><br/></td></tr>

<tr><td colspan="4"><hr/><strong>Group Banner</strong><br/>
You can upload an image for your group banner by going to your user profile and upload the image to your user profile album there.  Then to add it to your group Frontpage you simply add the file name without path in the space provided below.<br/><br/>
<strong>Copy this link for your album: </strong> '.$userDomain.'album/<br/><br/>
 Or you can choose to have your banner image be from an external source, like a different website, where you may have an image stored.  For this image include the full address of the image.  Make sure to choose which type location your images coming from.<br/>
You may also add a link on your banner image to go to your own website or other Internet location. <br/><br/></td></tr>

<tr valign="top"><td></td><td colspan="3">Image From User Profile <input type="radio" name="tac_source" value="album"' . tac_get_form_banner_source_album() . '/> <br/>Image From External <input type="radio" name="tac_source" value="external"' . tac_get_form_banner_source_external() . '/></td></tr>

<tr valign="top"><td>Banner Image File Name:</td> <td colspan="2"><input type="text" name="tac_banner" value="'. tac_get_form_banner_data() .'" /><input type="hidden" name="tac_hidden_banner_data" value="'. tac_get_form_banner_data() .'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_banner" /></td></tr>

<tr valign="top"><td>Banner Image Width:</td> <td colspan="2"><input type="text" name="tac_banner_width" value="'. tac_get_form_banner_width() .'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_banner_width" /></td></tr>

<tr valign="top"><td>Banner Link To:</td> <td colspan="2"><input type="text" name="tac_banner_link" value="'. tac_get_form_banner_link_data() .'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_bannerlink" /></td></tr>

<tr><td colspan="4"><hr/><strong>Group Title</strong><br/>
You can add a title for your group above or below your group banner.  Or if you choose you can have a title above and below the group banner.<br/><br/></td></tr>

<tr valign="top"><td>Title Above Banner:</td> <td colspan="2"><input type="text" name="tac_titleabove_banner" value="'. tac_get_form_titleabove_banner_data() .'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_abanner" /></td></tr>


<tr valign="top"><td>Title Below Banner: </td><td colspan="2"><input type="text" name="tac_titlebelow_banner" value="'. tac_get_form_titleabelow_banner_data() .'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_bbanner" /></td></tr>

<tr><td colspan="4"><hr/><strong>Group Long Description</strong><br/>
This is a larger  description of your group than in the description you entered for your group during group creation.<br/><br/></td></tr>

<tr valign="top"><td colspan="3">Long Group Description:<br/><textarea name="tac_long_description" rows="10" wrap="on">'. tac_get_form_long_description_n_data() .'</textarea></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_descrp" /></td></tr>

<tr><td colspan="4"><br/><hr/><strong>Add or Delete Links</strong>(one at a time)<br/>
You may add up to 30 links to your group Frontpage.  In the spaces provided include the display for your links and also that web address for your link.  To delete links simply choose the link name which you would like deleted and click the save changes button.<br/><br/></td></tr>
	 
 <tr><td>Add Link Display:</td> <td><input type="text" name="tac_add_link_display" value="" /></td>
	  <td>Add Link Address:</td> <td><input type="text" name="tac_add_link_address" value="" /></td></tr>
	  
	   <tr><td colspan="2"><input type="hidden" name="tac_add_link_display_hidden" value="'. tac_get_normal_link_display_data() .'" /></td>
	  <td colspan="2"><input type="hidden" name="tac_add_link_address_hidden" value="'. tac_get_normal_link_address_data() .'" /></td></tr>
	  
<tr><td>Delete Link by Display Name: </td><td colspan="3"><select name="tac_delete_link" size="5" >
<option value="none">None</option>' . tac_get_form_normallinks_data_deleteselect() . '
<option value="all">Delete All Links</option>
</select>
</td></tr>
<tr><td colspan="4"><br/><hr/><strong>Add Contact Info<br/></strong>
To add contact info you must fill in both the contact name and contact email address.  Due to concerns about stalking there is no place for your address and phone numbers. You share your contact info at your own risk.  <br/><br/>

</td></tr>

<tr valign="top"><td>Contact Name: </td><td colspan="2"><input type="text" name="tac_contact_name" value="'.tac_get_form_contact_name_data() .'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_cname" /></td></tr>

<tr valign="top"><td>Contact Email: </td><td colspan="2"><input type="text" name="tac_contact_email" value="'.tac_get_form_contact_email_data().'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_cemail" /></td></tr>
	 
<tr><td colspan="4"><hr/><strong>Group Ad hoc Text Area</strong><br/>
This is an area where you can add additional information about your group. One of many possibilities could be events or group accomplishments.  Fill in the ad hoc name for the title of your ad hoc text area.<br/><br/></td></tr>

<tr valign="top"><td>Ad hoc Name: </td><td colspan="2"><input type="text" name="tac_hoc_name" value="'.tac_get_form_hoc_name_data().'" /></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_hocn" /></td></tr>


<tr valign="top"><td colspan="3">Group Ad hoc Text Area:<br/><textarea name="tac_hoc_area" rows="10" wrap="on">'.tac_get_form_hoc_area_n_data().'</textarea></td><td>Delete Item: <input type="checkbox" name="tac_del[]" value="del_hoca" /></td></tr>
	 
	 </table>' ;
	 
}
add_action ( 'bp_after_group_settings_admin' ,'tac_frontpage_settings_form' );
//add_action ( 'bp_after_group_settings_creation_step' ,'tac_frontpage_settings_form' );



function tac_echo_the_frontpage() {
global $wp;

groups_update_groupmeta( $group->id, 'tac_banner_from', 'album' );
/*if(is_plugin_active()) {
	echo 'Im on the front page ' ;
} */

//$grouplink =  bp_get_group_permalink();
	//$files = WP_PLUGIN_URL . '/bp-album-classes.php';
	//$Theme_data_Array =  get_theme( get_current_theme() );
	
	
	

	 	//echo  getCwd();//. $current_theme_folder_dir . $theme_group_header_loc  . 'group-header.php';//$Theme_data_Array['Title'];//get_page_template() ;//get_template_directory_uri(); //get_stylesheet_directory() ; //get_current_theme();// . plugin_basename($files);// get_current_theme();// get_theme_root_uri (); //// bloginfo("url");//$bp->root_domain;
	//ABSPATH //
	// get_current_site_name( $current_site ); 
	//echo plugins_loaded;
echo tac_bake_frontpage();

}

function tac_bake_frontpage() {
global $bp;
 $tac_theFrontpage_echo = "";

//getting the title info for above the banner.  And formatting it for echo 
$frontpage_titleabove_banner = tac_get_form_titleabove_banner_data();
if ($frontpage_titleabove_banner != "") 
{
$frontpage_titleabove_banner ='<tr><td>'.'<h1>'. $frontpage_titleabove_banner .'</h1>'.'<tr><td>';
}

//Getting banner Image width
$frontpage_imagewidth = tac_get_form_banner_width();
if($frontpage_imagewidth != "") 
{
	$frontpage_htmlwidth = ' width="'.$frontpage_imagewidth.'"';
} 
//Getting banner address info and formatting for echo.
$frontpage_banner = tac_get_form_banner_data(); 
if ($frontpage_banner != "") 
{
$frontpage_banner ='<img src="'.  tac_get_banner_for_display() .'" border="0"'.$frontpage_htmlwidth.'>';
} 

//getting the banner link to address 
$frontpage_banner_link = tac_get_form_banner_link_data();
if ($frontpage_banner_link != "") 
{
 $frontpage_banner = '<a href="'.$frontpage_banner_link.'">'. $frontpage_banner .'</a>';
} 

//getting the title info for below the banner.  And formatting it for echo
$frontpage_titlebelow_banner = tac_get_form_titleabelow_banner_data();
if ($frontpage_titlebelow_banner != "") 
{
 $frontpage_titlebelow_banner = '<h1>'. $frontpage_titlebelow_banner .'</h1>'. '</td></tr>' . '<tr><td>';
} 

//getting the long description and formatting it for echo 
$frontpage_long_description = tac_get_form_long_description_n_data();
if ($frontpage_long_description != "") 
{
	$frontpage_long_description = str_replace ("\n", "<br/>", $frontpage_long_description);
 $frontpage_long_description = '<tr><td><table class="frontpagetabledescrip" border="2">' .  '<tr><td colspan="2" class="frontpagesectiontitles">Group Description<br/></td></tr>'.'<tr><td class="frontpagedescripcell">'. $frontpage_long_description . '</td></tr>' . '</table><br/></td></tr>';
} 

//putting link display and link addresses into to together into workable links
//get the displays and addresses into variables
$frontpage_link_displays = tac_get_normal_link_display_data();
$frontpage_link_addresses = tac_get_normal_link_address_data();

$the_link_display_data_array = explode(",",$frontpage_link_displays);
$the_link_address_data_array = explode(",",$frontpage_link_addresses);
					 
$num_array_vals = count($the_link_display_data_array);
$link_area = '';

if($frontpage_link_displays != "") {

//create a for loop with an alternating index putting the links together and then in 2 variables of links
for ($i=0; $i<=$num_array_vals -1; $i++){
	if($i%2 == 0 || $i==0) {
		$left_links = $left_links . '<a href="'.$the_link_address_data_array[$i].'">'. $the_link_display_data_array[$i] .'</a><br/>';
	} else {
		$right_links = $right_links . '<a href="'.$the_link_address_data_array[$i].'">'. $the_link_display_data_array[$i] .'</a><br/>';
	}
}
$link_area = '<tr><td><table class="frontpagetablelinks" border="2">' . '<tr><td colspan="2" class="frontpagesectiontitles">Group Links<br/></td></tr>'.'<tr ><td class="frontpageleftlinks">' . $left_links . '</td><td class="frontpagerightlinks">' . $right_links . '</td></tr>'. '</table><br/></td></tr>';

} 

//getting the contact info  and formatting it for echo 
$frontpage_contact_name = tac_get_form_contact_name_data();
$frontpage_contact_email = tac_get_form_contact_email_data();
if ($frontpage_contact_name != "" && $frontpage_contact_email != "" ) 
{
 $contact_area = '<tr><td><table class="frontpagetabledescrip" border="2">' .  '<tr><td colspan="2" class="frontpagesectiontitles">Group Contact Info<br/></td></tr>'.'<tr><td class="frontpagedescripcell">'. '<strong>Name: </strong>'.$frontpage_contact_name .'<br/><strong>Email: </strong>'.  $frontpage_contact_email.'</td></tr>' . '</table><br/></td></tr>';
}
$blog_title =  get_bloginfo('name');
//$group_name = bp_group_name();

if(bp_group_is_admin()){
$frontpage__admin_settings = '<tr><td align="right"><br/><a href="../admin/group-settings">Frontpage Settings Page</a></td></tr>';
} 
//getting the ad hoc text area and ad hoc area name and formatting it for echo 
$frontpage_hoc_area  = tac_get_form_hoc_area_n_data();
$frontpage_hoc_name = tac_get_form_hoc_name_data(); 
if ($frontpage_hoc_area != "" && $frontpage_hoc_name != "") 
{
	$frontpage_hoc_area = str_replace ("\n", "<br/>", $frontpage_hoc_area);
 $frontpage_hoc_display = '<tr><td><table class="frontpagetabledescrip" border="2">' .  '<tr><td colspan="2" class="frontpagesectiontitles">'.$frontpage_hoc_name.'<br/></td></tr>'.'<tr><td class="frontpagedescripcell">'. $frontpage_hoc_area . '</td></tr>' . '</table><br/></td></tr>';
} 
//Putting all the formatted for echo pieces together 
$tac_theFrontpage_echo = 
$frontpage_titleabove_banner . $frontpage_banner . $frontpage_titlebelow_banner .
$frontpage_long_description.
$link_area .
$contact_area .
$frontpage_hoc_display;

if ($tac_theFrontpage_echo == "") {
	if (bp_group_is_admin()) {
	$tac_theFrontpage_echo = '<tr><td><br/><br/><br/><table class="frontpagetabledescrip" border="2">' .  '<tr><td colspan="2" class="frontpagesectiontitles">Group Front page Welcome<br/></td></tr>'.'<tr><td class="frontpagedescripcell">'. '
Thank you and Congratulations for starting your own group on '.$blog_title.' Groups.
<br/>There are a few more steps to customizing your group, which I will now briefly go over.<br/><br/>
It is now time to step back and think about what you would like people to see when they first come to your group.  What is your group all about? <br/><br/>
On your front page you can have a longer description of your group, but you can also have a banner photo, a title above or below your banner, up to 30 links, and some contact information.  And there is also an ad hoc area you can title whatever you like.  Each one of these items is optional through the Admin settings screen.<br/><br/>NOTE:For best results on banner image display use image 655 pixels or less.<br/><br/>
To get started customizing to make your group unique choose the Admin tab at the group menu above and then click on Group Settings.  Or for your first time click on the link below.<br/><br/>
<a href="../admin/group-settings">Frontpage Settings Page</a><br/><br/>
'.'</td></tr>' . '</table><br/><br/><br/>
</td></tr>';
	} else {
		$tac_theFrontpage_echo = '<tr><td><br/><br/><br/><table class="frontpagetabledescrip" border="2">' .  '<tr><td colspan="2" class="frontpagesectiontitles">Welcome to this Group <br/></td></tr>'.'<tr><td class="frontpagedescripcell">'. '
This group is currently being created.  Please return at a later time.   <br/><br/>
'.'</td></tr>' . '</table><br/><br/><br/></td></tr>';
	}
} 

//Into a table 
$tac_theFrontpage_echo = '<table width="100%" class="frontpagetablemain" border="1"> '.
$frontpage__admin_settings .
$tac_theFrontpage_echo .
'</table>';

return $tac_theFrontpage_echo;
}

function tac_get_form_titleabove_banner_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_titleabove_banner', TRUE);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "&#8217;", $the_data);
	//$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	return $the_data;
}

function tac_get_form_banner_source_album( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_banner_from', TRUE);
	if ($the_data == 'album') {
			$return_data = 'checked="checked"';
		return $return_data;
	} else {
			$return_data = '';
		return $return_data;
	} 
}

function tac_get_form_banner_source_external( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_banner_from', TRUE);
	if ($the_data == 'external') {
			$return_data = 'checked="checked"';
		return $return_data;
	} else {
			$return_data = '';
		return $return_data;
	} 
}

function tac_get_form_banner_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_banner', TRUE);
	$the_data = str_replace ("\n", "", $the_data);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	$the_data = str_replace ("\'", "", $the_data);
	//$the_data = str_replace ("\"", "", $the_data);
	//$the_data = str_replace ("\\", "", $the_data);
	$the_data = str_replace (" ", "", $the_data);
	return $the_data;
}

function tac_get_form_banner_width( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_banner_width', TRUE);
	$the_data = str_replace ("\n", "", $the_data);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "", $the_data);
	//$the_data = str_replace ("\"", "", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	$the_data = str_replace (" ", "", $the_data);
	return $the_data;
}

function tac_get_banner_for_display ( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		
		//Get source 
		$the_source =  groups_get_groupmeta( $group->id, 'tac_banner_from', TRUE);
		
		if ($the_source == 'album') {
			//Get path to photo in the album.  
			$the_data  =  groups_get_groupmeta( $group->id, 'path_to_photo', TRUE);
		} else {
			$the_data = '';
		} 
		
		//Get the photo file name and add it after the path to photo 
	$the_data  = $the_data . groups_get_groupmeta( $group->id, 'tac_banner', TRUE);
	
	$the_data = str_replace ("\n", "", $the_data);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "", $the_data);
	//$the_data = str_replace ("\"", "", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	$the_data = str_replace (" ", "", $the_data);
	return $the_data;
}

function tac_get_form_banner_link_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		
		//Get the photo file name
		$the_data  =groups_get_groupmeta( $group->id, 'tac_banner_link', TRUE);
		$the_data = str_replace ("\n", "", $the_data);
			$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "", $the_data);
	//$the_data = str_replace ("\"", "", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	$the_data = str_replace (" ", "", $the_data);
	return $the_data;
}

function tac_get_form_titleabelow_banner_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		$the_data  =  groups_get_groupmeta( $group->id, 'tac_titlebelow_banner', TRUE);
			$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "&#8217;", $the_data);
	//$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	return $the_data;
}
		

function tac_get_form_long_description_n_data( $group = false ) {
global $groups_template;

	if ( !$group )	
	$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_long_description', TRUE);

	$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	$the_data = str_replace ("\'", "&#8217;", $the_data);
	$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	
	return $the_data;
}

function tac_get_form_normallinks_data_deleteselect ( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		
	$the_data_display =  groups_get_groupmeta( $group->id, 'tac_add_link_display', TRUE);
		$the_data_display = str_replace ("\n", "", $the_data_display);
	$the_data_display = str_replace ("\f", "", $the_data_display);
	$the_data_display = str_replace ("\r", "", $the_data_display);
	$the_data_display = str_replace ("\'", "&#8217;", $the_data_display);
		$the_data_display = str_replace ("\"", "&#8222;", $the_data_display);
	$the_data_display = str_replace ("\\", "", $the_data_display);
	$the_data_array = explode(",", $the_data_display);
	
	$num_array_vals = count($the_data_array);
if($the_data_display != "") {

	for ($i=0; $i<=$num_array_vals -1; $i++){
     		$the_data = $the_data . '<option value="' . $the_data_array[$i] . '">' . $the_data_array[$i]. '</option>';
		}
	
} 
	return $the_data;
}

function tac_get_normal_link_address_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		
	$the_data=  groups_get_groupmeta( $group->id, 'tac_add_link_address', TRUE);
	$the_data = str_replace ("\n", "", $the_data);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "", $the_data);
	//$the_data = str_replace ("\"", "", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	

	return $the_data;
}

function tac_get_normal_link_display_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		
	$the_data=  groups_get_groupmeta( $group->id, 'tac_add_link_display', TRUE);
	$the_data = str_replace ("\n", "", $the_data);
	$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	$the_data = str_replace ("\'", "&#8217;", $the_data);
		$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	
	return $the_data;
}

function tac_get_form_contact_name_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_contact_name', TRUE);
	$the_data = str_replace ("\n", "", $the_data);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "&#8217;", $the_data);
	//$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	return $the_data;
}

function tac_get_form_contact_email_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_contact_email', TRUE);
	$the_data = str_replace ("\n", "", $the_data);
		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	//$the_data = str_replace ("\'", "", $the_data);
	//$the_data = str_replace ("\"", "", $the_data);
	$the_data = str_replace (" ", "", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	return $the_data;
}

function tac_get_form_hoc_name_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
		$the_data  =  groups_get_groupmeta( $group->id, 'tac_hoc_name', TRUE);
		$the_data = str_replace ("\n", "", $the_data);
			$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	$the_data = str_replace ("\'", "&#8217;", $the_data);
	$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);
	return $the_data;
}

function tac_get_form_hoc_area_n_data( $group = false ) {
global $groups_template;

	if ( !$group )
		$group =& $groups_template->group;
	$the_data  =  groups_get_groupmeta( $group->id, 'tac_hoc_area', TRUE);

		$the_data = str_replace ("\f", "", $the_data);
	$the_data = str_replace ("\r", "", $the_data);
	$the_data = str_replace ("\'", "&#8217;", $the_data);
	$the_data = str_replace ("\"", "&#8222;", $the_data);
	$the_data = str_replace ("\\", "", $the_data);

	return $the_data;
}

function tac_sanitize_input($val) {
	
	$val = mysql_real_escape_string($val);
	$val = stripslashes($val);
	$val = htmlentities($val);
		
	return $val;
}
function tac_sanitize_input_links($val) {
	
	$val = mysql_real_escape_string($val);
	$val = htmlentities($val);
	$val = str_replace ("\n", "", $val);
	$val = str_replace ("\f", "", $val);
	$val = str_replace ("\r", "", $val);
	$val = str_replace ("'", "&#8217;", $val);
	$val = str_replace (",", "", $val);
		
	return $val;
}

function tac_save_form_data($group) {
global $bp, $_POST;


	//Saving the banner address info.  
	if ( $postval = $_POST['tac_banner'] ) {
		$postval2 = $_POST['tac_source'];
		$storedVal = $_POST['tac_hidden_banner_data'];
		
		//Trimming leading white space 
			$postval = trim($postval);
		if ($postval2 ) {
	 		if ($postval2 == 'external') {
		 		groups_update_groupmeta( $group->id, 'tac_banner_from', 'external' );
				if (strpos($postval, "http://") !== false) {
     				//This means is found And Add it.  
					// Store the address to image 
					groups_update_groupmeta( $group->id, 'tac_banner', $postval );
	 			} 
				
			} else {
				groups_update_groupmeta( $group->id, 'tac_banner_from', 'album' );
				if ( $postval ) {
					 
				  if($storedVal != $postval) {//Unless the banner is changed Don't recreate link This will fix the problem of banner disappearing if another admin submits new form data but hasn't changed the banner 
					  $postval = str_replace (" ", "", $postval); 
					$postval = tac_sanitize_input($postval);
					if (strpos($postval, "http://") !== false) {
     			//This means is found And don't do anything.  
	 				} else	{ 
	 			//It wasn't found so add the file name  
				//Store path to photo in BuddyPress Group table 
					$path_to_photo = $bp->root_domain .'/wp-content/uploads/album/' . $bp->loggedin_user->id.'/';
					//Storing the path with user ID of Admin Posting Picture from their profile album 
					groups_update_groupmeta( $group->id, 'path_to_photo', $path_to_photo );
			
				// Store the actual image name and extension.  
					groups_update_groupmeta( $group->id, 'tac_banner', $postval );
					}
				   }
					
				} 
			} 
		}
	} 
		// Saving the banner width to the database.  After getting posted data 
	if ( $postval = $_POST['tac_banner_width'] ) {
		if ( $postval ) {
			$tac_maxwidth = 655;
			$postval = trim($postval);
			$postval = tac_sanitize_input($postval);
			if($postval > $tac_maxwidth ) 
			{
				$postval = $tac_maxwidth;
								
			} 
			groups_update_groupmeta( $group->id, 'tac_banner_width', $postval );
		} 
	} 
	
		if ( $postval = $_POST['tac_banner_link'] ) {
		if ( $postval ) {
				
			//Trimming leading and ending  white space 
			$postval = trim($postval);
			$postval = tac_sanitize_input_links($postval);

			//Adding http:// if needed
			if (strpos($postval, "http://") !== false) {
     			//This means is found And don't do anything.  
	 		} else	{ 
	 			//It wasn't found so must add it 
				$postval = 'http://' . $postval;
			}
			
			// Store the  image Link to.  
			groups_update_groupmeta( $group->id, 'tac_banner_link', $postval );
		} 
	} 
	
	// Saving the title for above the banner to the database.  After getting posted data 
	if ( $postval = $_POST['tac_titleabove_banner'] ) {
		if ( $postval ) {
			$postval = trim($postval);
			$postval = tac_sanitize_input($postval);
			groups_update_groupmeta( $group->id, 'tac_titleabove_banner', $postval );
		} 
	} 
		
	//Saving the title for below the banner. 
	if ( $postval = $_POST['tac_titlebelow_banner'] ) {
		if ( $postval ) {
			$postval = trim($postval);
			$postval = tac_sanitize_input($postval);
			groups_update_groupmeta( $group->id, 'tac_titlebelow_banner', $postval );			
		}
	}
	
	//Saving the long description info
	if ( $postval = $_POST['tac_long_description'] ) {
		if ( $postval ) {
			$postval = trim($postval);
			//$postval = str_replace ("\n", "<br/>", $postval);
			//$postval = mysql_real_escape_string($postval);
			groups_update_groupmeta( $group->id, 'tac_long_description', $postval );
		}
	}


		//adding a link 
	if ( $postval = $_POST['tac_add_link_display']) {
		
		$postval2 = $_POST['tac_add_link_address'];
		$postval3 = $_POST['tac_add_link_display_hidden'];
		$postval4 = $_POST['tac_add_link_address_hidden'];
		
		$the_link_display_data_array = explode(",",$postval3);
		$num_array_vals = count($the_link_display_data_array);
		
		if ( $postval && $postval2 &&  $num_array_vals < 30) {
			
			$postval = trim($postval);
			$postval = tac_sanitize_input_links($postval);
			$postval2 = trim($postval2);
			$postval2 = tac_sanitize_input_links($postval2);
	//Adding http:// if needed
			if (strpos($postval2, "http://") !== false) {
     			//This means is found And don't do anything.  
	 		} else	{ 
	 			//It wasn't found so must add it 
				$postval2 = 'http://' . $postval2;
			}
		//add new values to the end of the string Only if  the link display isn't there Case sensitive 
			if (strpos($postval3, $postval) !== false){
		 		//found 
	 		} else {
 			if ( $postval3 && $postval4 ) {
				$postval = $postval3 . ',' . $postval;
				$postval2 = $postval4 . ',' . $postval2;
			} else {
				$postval = $postval;
				$postval2 = $postval2;
			}
				//Saving the data as strings.
				groups_update_groupmeta( $group->id, 'tac_add_link_display', $postval);
				groups_update_groupmeta( $group->id, 'tac_add_link_address', $postval2 );

			}	
			
		}	
	}
	
	// deleting a link from normal links 
	
	if ( $postval = $_POST['tac_delete_link']) {
		$postval3 = $_POST['tac_add_link_display_hidden'];
		$postval4 = $_POST['tac_add_link_address_hidden'];
		if ( $postval && $postval3 && $postval4 && $postval != 'none') {
		
			
			if($postval !='all') {
		//Deleting  the string Only if  the link display ist there Case sensitive 
				if (strpos($the_link_display_data, $postval) !== false){
		 			//found 
	 			} else {
				
					//Get the stored strings and put them into an array 
					$the_link_display_data_array = explode(",",$postval3);
					$the_link_address_data_array = explode(",",$postval4);
				
					//Will have the same number of Displays and addresses .  Number of values in the array 
					$num_display_vals = count($the_link_display_data_array);

					$new_link_display_data_array = array();
					$new_link_address_data_array = array();
					//removes value we want removed,We only need to check for one occurrence Because we only allow only one occurrence in saving  
					for ($i=0; $i<=$num_display_vals -1; $i++){
						if ($postval != $the_link_display_data_array[$i]) {
						//Any time but when at the remove index add the array elements to a new array 
							array_push($new_link_display_data_array, $the_link_display_data_array[$i] );
							array_push($new_link_address_data_array, $the_link_address_data_array[$i] );
						} 
				
					}
					//Turn array back into String for storing 
					$postval3 = implode(",", $new_link_display_data_array);
					$postval4 = implode(",", $new_link_address_data_array);
	
					//Store the string data 
					groups_update_groupmeta( $group->id, 'tac_add_link_display', $postval3);
					groups_update_groupmeta( $group->id, 'tac_add_link_address',  $postval4);
				}
			} else {
				
				groups_delete_groupmeta( $group->id, 'tac_add_link_display');
				groups_delete_groupmeta( $group->id, 'tac_add_link_address');
			} 
			
		}
	}
	
		//Saving the contact name 
	if ( $postval = $_POST['tac_contact_name'] ) {
		if ( $postval ) {
			$postval = trim($postval);
			$postval = tac_sanitize_input($postval);
			groups_update_groupmeta( $group->id, 'tac_contact_name', $postval );
		}
	}	
	
			//Saving the contact email  
	if ( $postval = $_POST['tac_contact_email'] ) {
		if ( $postval ) {
			
			if (strpos($postval, "@") !== false) {
     			//This means is found And probably e-mail address  
				$postval = trim($postval);
				$postval = tac_sanitize_input($postval);
				groups_update_groupmeta( $group->id, 'tac_contact_email', $postval );
	 		} else	{ 
	 			//It wasn't found and definitely isn't e-mail address  
			}
			
		}
	} 
	
	//Saving the ad hoc name . 
	if ( $postval = $_POST['tac_hoc_name'] ) {
		if ( $postval ) {
			$postval = trim($postval);
			$postval = tac_sanitize_input($postval);
				groups_update_groupmeta( $group->id, 'tac_hoc_name', $postval );
		}
	}
	
	//Saving Ad hoc area 
	if ( $postval = $_POST['tac_hoc_area'] ) {
		if ( $postval ) {
			$postval = trim($postval);
			//$postval = mysql_real_escape_string($postval);
			groups_update_groupmeta( $group->id, 'tac_hoc_area', $postval );
		}
	}
	
	
	//Deleting items that were checked 
	if ( $tac_del = $_POST['tac_del'] ) {
		if ($tac_del ) {
	
	foreach($tac_del as $del_value) {
		switch ($del_value){
			
			case "del_abanner": groups_delete_groupmeta( $group->id, 'tac_titleabove_banner' );
				break;
			case "del_banner": groups_delete_groupmeta( $group->id, 'tac_banner' );
				break;
			case "del_banner_width": groups_delete_groupmeta( $group->id, 'tac_banner_width' );
				break;
			case "del_bannerlink": groups_delete_groupmeta( $group->id, 'tac_banner_link' );
				break;
			case "del_bbanner": groups_delete_groupmeta( $group->id, 'tac_titlebelow_banner' );
				break;
			case "del_descrp": groups_delete_groupmeta( $group->id, 'tac_long_description' );
				break;
			case "del_cname": groups_delete_groupmeta( $group->id, 'tac_contact_name' );
				break;
			case "del_cemail": groups_delete_groupmeta( $group->id, 'tac_contact_email' );
				break;
			case "del_hocn": groups_delete_groupmeta( $group->id, 'tac_hoc_name' );
				break;
			case "del_hoca": groups_delete_groupmeta( $group->id, 'tac_hoc_area' );
				break;
			
		} 
		 
	}
	}
	}
	
}
add_action( 'groups_group_after_save', 'tac_save_form_data' );
/*

function tac_bp_group_overrides() {
global $bp;

$bp->bp_options_nav['groups']['home']['name'] = 'Group Activity';
$bp->bp_options_nav['groups']['forum']['name'] = 'Group Forum';

}
add_action('get_header', 'tac_bp_group_overrides');
*/
function tac_redirect_to_frontpage() {
	global $bp;
	global $wp;
	$path = clean_url( $_SERVER['REQUEST_URI'] );
	
	$path = apply_filters( 'bp_uri', $path );
	
	
	if ( bp_is_group_home() && strpos( $path, $bp->bp_options_nav[$bp->groups->current_group->slug]['home']['slug'] ) === false )
	
		bp_core_redirect( $path . /*$bp->bp_options_nav[$bp->groups->current_group->slug]['frontpage']['slug']*/ 'frontpage' . '/' );
/*
bp_groups_default_extension

	if ( bp_is_group_home() && strpos( $path, $bp->bp_options_nav['groups']['home']['slug'] ) === false )
		bp_core_redirect( $path . $bp->bp_options_nav['groups']['frontpage']['slug'] . '/' );*/
}
add_action( 'wp', 'tac_redirect_to_frontpage', 1);

function tac_grouptop_filter($content) {
   global $bp;
   /*
	 get_theme_data( $theme_filename 
	 get_theme_root_uri()bloginfo("directory") 
	 register_deactivation_hook($file, $function); 
	
	$adminlink = '';
	
	$adminlink = bp_group_admin_permalink();
	
	$arrylinkelements = explode("/", $adminlink); 
	
$lastelement = count($arrylinkelements) - 1;

$tacadminname = $arrylinkelements[3];

	echo $adminlink;
	*/
	/*echo  bp_group_name();
	
	$path = clean_url( $_SERVER['REQUEST_URI'] );
	
	$path = apply_filters( 'bp_uri', $path );
	
$path = $bp->groups->current_group->slug;
	
	echo  $path;*/
	
}
add_action('bp_group_header_meta', 'tac_grouptop_filter', 1, 1);

function bp_is_group_frontpage() {
	global $bp;

	if ( BP_GROUPS_SLUG == $bp->current_component && $bp->is_single_item &&  'frontpage' == $bp->current_action )
		return true;

	return false;
}


function bp_group_frontpage_add_css() {
	global $bp,$wpdb;
//$bp->groups->current_group->id
$groupname = $bp->current_item; //bp_group_name();


$chatgroupCSS = ABSPATH . '/wp-content/plugins/bp-group-frontpage/css/bp-group-f-'.$groupname.'-css.css';

//bp-group-f-'.$groupname.'-css.css';

if(file_exists($chatgroupCSS)) {
	
	//echo $groupname.' tc';
	 wp_enqueue_style( 'bp_group_frontpage_css',WP_PLUGIN_URL .'/bp-group-frontpage/css/bp-group-f-'.$groupname.'-css.css');//$chatgroupCSS );
	 
	 } else {
//echo $groupname.' tc 2';
		wp_enqueue_style( 'bp_group_frontpage_css', WP_PLUGIN_URL .'/bp-group-frontpage/css/bp-group-frontpage-css.css' );
		
	 }
		wp_print_styles();
		//wp_head
}
add_action( 'bp_before_group_header', 'bp_group_frontpage_add_css' );

?>