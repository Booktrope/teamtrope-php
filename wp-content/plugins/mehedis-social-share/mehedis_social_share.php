<?php
/*
Plugin Name: Mehedi's Social Share
Version: 1.0
Description: This Plugin helps you to tweet (twitter) and share (Facebook) your posts from the end of your each article. Just a simple plugin of 1KB.
Author: Mh Mehedi
Author URI: http://mhmehedi.com
Plugin URI: http://mhmehedi.com

*/

function display_post_end_notice($content){
if (is_single()){
$content.= <<<EOT

<div>
<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>
<br>
</div>

<div style="float:left; margin-right:10px">
<a  name="fb_share"></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>
</div>

EOT;
}
return $content;
}
add_filter('the_content', 'display_post_end_notice');