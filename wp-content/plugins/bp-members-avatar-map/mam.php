<?php 
global $bp;
define ( 'BP_MAM_DB_VERSION', '1.2.1' );
/**
* create url paths for multisite network admin screens or plain vanills admin screen
* workaround for adding links to various other site admin pages e.g linking to
* the BP extended profile screen.
* this is  likely a bad hack  with a better solution to be implemented given time.
*/
if( is_multisite() && function_exists( 'is_network_admin' ) ){
$path_to = site_url() . "/wp-admin/network/admin.php";
} else {
$path_to = site_url() . "/wp-admin/admin.php";
}

require ( dirname( __FILE__ ) . '/mam-classes.php' );

function mam_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->mam->id = 'mam';

	/* Register this in the active components array */
	//$bp->active_components[$bp->mam->slug] = $bp->mam->id;
}

add_action( 'bp_setup_globals', 'bp_mam_setup_globals' );

 /* network_admin_menu */
add_action( 'wp', 'mam_setup_globals', 2 );
if( is_multisite() && function_exists( 'is_network_admin' ) ):
add_action( 'network_admin_menu', 'mam_setup_globals', 2 );
else:
add_action( 'admin_menu', 'mam_setup_globals', 2 );
endif;

################## create the Admin dashboard settings ############
/** create WP admin settings ***/
function bp_mam_menu() {
	global $bp;

  if ( true == $bp->loggedin_user->is_super_admin ):
   $user_is_admin = true;
  elseif (true == $bp->loggedin_user->is_site_admin ):
   $user_is_admin = true;
  else:
   $user_is_admin = false;
  endif;
    
	if ( !$user_is_admin )
		return false;

    require_once( dirname( __FILE__ ) . '/admin/mam-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Members Avatar Map Setup', 'bp-mam' ), __( '<img src="'.plugins_url().'/bp-members-avatar-map/images/mam_icon.png"/>Members Avatar Map Setup', 'bp-mam' ), 'manage_options', 'bp-mam-settings', 'bp_mam_admin' );
}
if( is_multisite() && function_exists( 'is_network_admin' ) ):
add_action( 'network_admin_menu', 'bp_mam_menu' );
else:
add_action( 'admin_menu', 'bp_mam_menu' );
endif;

// Fetch values for map page location & members directory - currently unused.
$where = get_option( 'mam_display_where' );
//$members_listing = get_option( 'mam_members_directory' );
  

#### Profile location maps - google V3 api no api key required ####
?>
<?php function members_avatar_Map(){  
 
  global $bp, $wpdb;

 ?> 
 <?php 
 
	if ( get_option('mam_default_location')) {
	   $map_default_location = get_option( 'mam_default_location' );
	} elseif ( empty($map_default_location) ) {
	   $map_default_location = '32.69801,35.14132';
	}
	if ( get_option('mam_width')&&get_option('mam_width')<100 ) {
	   $map_width = get_option( 'mam_width' );
	} elseif ( empty($map_width) ) {
	   $map_width = '80';
	}
	if ( get_option('mam_height')  ) {
	   $map_height = get_option( 'mam_height' );
	} elseif ( empty($map_height) ) {
	   $map_height = '200';
	}
 if ( get_option('mam_map_zoom') ){
    $map_zoom = get_option( 'mam_map_zoom' ); 
 } elseif ( empty($map_zoom) ){
    $map_zoom = 11;
 } 
 // Create a class for parent map div $where variable sets additional tokens
 $where = get_option('mam_display_where');
 switch($where){
 case "4":
 $whereClass = ' bp_before_directory_members_content';
 default:
 $whereClass = ' bp_before_directory_members_content';
 }
 $map_tokens = 'map-display' . $whereClass;
 
 $map_navigation = get_option( 'mam_navigation_display' );
 $navigation_size = get_option( 'mam_navigation_size' );
 $map_type_control = get_option( 'mam_map_type_control' ) ;
 $map_max_members = get_option('mam_max_number_of_members');
 if($map_max_members>300)$map_max_members=300;
 ?>
  <!--div class="<?php echo $map_tokens; ?>"-->
  <script type="text/javascript">

function addLoadEvent(func) {

var oldonload = window.onload;

if (typeof window.onload != 'function') {

window.onload = func;

} else {

window.onload = function() {

if (oldonload) {

oldonload();

}

func();

}

}

}

</script>

  <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
  <script type="text/javascript">
      
  var geocoder;
  var map;
  function initialize() {
       var latlng = new google.maps.LatLng(<?php echo $map_default_location; ?>);
       var myOptions = {
       zoom: <?php echo $map_zoom; ?>,
       center: latlng,
       <?php if ( true == $map_navigation  ) {?>
       navigationControl: true,
       <?php   } else { ?>
       navigationControl: false,
       <?php  } ?>
       <?php if ('small' == $navigation_size): ?>
       navigationControlOptions: {
       style: google.maps.NavigationControlStyle.SMALL
       },
       <?php endif; ?> 
       <?php if ('dropdown' == $map_type_control): ?>
       mapTypeControl: true,
       mapTypeControlOptions: {
       style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
       },
       <?php endif; ?> 
       mapTypeId: google.maps.MapTypeId.HYBRID
       }
     map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  }                
//***************************************************************************************************
function showAddress(lik,det,cords) {
if(cords=="")return;
var info=document.getElementById(det).getElementsByTagName("div")[0].innerHTML;
var avat=document.getElementById(det).getElementsByTagName("img")[0].src;
var arr = cords.split(",");
if (arr.length!=2)return;
    var lat=parseFloat(arr[0]);
	var lng=parseFloat(arr[1]);
	var latlngadress = new google.maps.LatLng(lat,lng);
	var icon_avatar = new google.maps.MarkerImage(avat, null, null, new google.maps.Point(10,31),new google.maps.Size(20, 20));
    var marker = new google.maps.Marker({
            map: map, 
            position: latlngadress,
			animation: google.maps.Animation.DROP,
			icon:icon_avatar,shadow:'<?php echo plugins_url() ?>/bp-members-avatar-map/images/shadow.png'
        });
				var infowindow = new google.maps.InfoWindow(
      { content: "<table><tr><td><div style='width:44px;height:44px;background:blue;'><img src='"+avat+"' style='height:40px;width:40px;margin:2px;'/></div><a href='"+lik+"'>"+det+"</a></td><td><p>"+info+"</p></td></tr></table>",
        size: new google.maps.Size(50,50)
      });
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });
  }
function showAddress_online(lik,det,cords) {
if(cords=="")return;
var info=document.getElementById(det).getElementsByTagName("div")[0].innerHTML;
var avat=document.getElementById(det).getElementsByTagName("img")[0].src;
var arr = cords.split(",");
if (arr.length!=2)return;
    var lat=parseFloat(arr[0]);
	var lng=parseFloat(arr[1]);
	var latlngadress = new google.maps.LatLng(lat,lng);
	var icon_avatar = new google.maps.MarkerImage(avat, null, null, new google.maps.Point(10,32),new google.maps.Size(20, 20));
    var marker = new google.maps.Marker({
            map: map,
			animation: google.maps.Animation.DROP,
            position: latlngadress,
			icon:icon_avatar,shadow:'<?php echo plugins_url() ?>/bp-members-avatar-map/images/shadow_green.png'
        });
				var infowindow = new google.maps.InfoWindow(
      { content: "<table><tr><td><div style='width:44px;height:44px;background:green;'><img src='"+avat+"' style='height:40px;width:40px;margin:2px;'/></div><a href='"+lik+"'>"+det+"</a></td><td><p>"+info+"</p></td></tr></table>",
        size: new google.maps.Size(50,50)
      });
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });
  }  
addLoadEvent(function() {
jQuery(document).ready( function() { initialize(); } );
<?php if ( bp_has_members('type=active&max='.$map_max_members.'&per_page='.$map_max_members.'') ) : ?>
					<?php while ( bp_members() ) : bp_the_member(); ?>   
 try{jQuery(document).ready( function() {showAddress("<?php bp_member_permalink() ?>","<?php bp_member_name() ?>","<?php bp_member_profile_data('field=Latitude and Longitude') ?>");} );}
catch(err){}
<?php endwhile; ?>
<?php endif; ?>
<?php if ( bp_has_members( 'type=online' ) ) : ?>
					<?php while ( bp_members() ) : bp_the_member(); ?>   
try{jQuery(document).ready( function() {showAddress_online("<?php bp_member_permalink() ?>","<?php bp_member_name() ?>","<?php bp_member_profile_data('field=Latitude and Longitude') ?>");} );}
catch(err){}
<?php endwhile; ?>
<?php endif; ?>
}) 
  </script>
  <?php if ( bp_has_members('type=active&max='.$map_max_members.'&per_page='.$map_max_members.'') ) : ?>
					<?php while ( bp_members() ) : bp_the_member();?>			
 <div style="display:none;" id="<?php bp_member_name() ?>"><?php bp_member_avatar() ?><div><?php bp_member_profile_data('field=Info') ?></div></div>
<?php endwhile; ?>
<?php endif; ?>
  <div id="map_canvas" style="height:<?php echo $map_height; ?>px;width:<?php echo $map_width; ?>%;" ></div>
  <div>On line members&nbsp;<img src="<?php echo plugins_url() ?>/bp-members-avatar-map/images/shadow_green_bottom.png">&nbsp;&nbsp;Off line members&nbsp;<img src="<?php echo plugins_url() ?>/bp-members-avatar-map/images/shadow_bottom.png"></div>

  <?php
  }
  
  if (  $where == '4')
  //  $map_tokens = $map_tokens . ' profile-loop';
    add_action('bp_before_directory_members_content', 'members_avatar_Map', 10);

?>