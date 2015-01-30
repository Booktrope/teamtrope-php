<?php
/*
Plugin Name: List Users Emails
Plugin URI: http://www.u3b.com.br/plugins/list-users-emails
Description: List emails from users with chosen roles
Version: 1.1
Author: Augusto Bennemann
Author URI: http://www.u3b.com.br
License: GPL2
*/

/*  Copyright 2012 Augusto Bennemann (email: gutobenn at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function list_emails_register_shortcodes(){
   add_shortcode('list-emails', 'list_emails_function');
}

add_action( 'init', 'list_emails_register_shortcodes');

function list_emails_function($atts){
  extract(shortcode_atts(array('roles' => "administrator", 'antibot' => 0), $atts));
  $roles = explode(',', $atts['roles'].',', -1);

  $return_string = ''; $antibot = '';
  if($atts['antibot'] == 'hiddenspan'){$antibot .= '<span style="display:none;">antibot</span>';}
  for($i = 0; $i < sizeof($roles); $i++){
    $group_users = get_users('orderby=nicename&role='.$roles[$i]);
    foreach ($group_users as $user) {
      $emails = explode("@", $user->user_email);
      $return_string .= ', ' . $emails[0] . $antibot . '@' . $emails[1] ;
    };
  }

  
  return substr($return_string, 2);
}?>