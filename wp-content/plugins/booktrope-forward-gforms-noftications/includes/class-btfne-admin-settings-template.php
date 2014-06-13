<?php

if(!defined('WP_BTFNE_PATH')) die('.______.');

if(!current_user_can('manage_options'))
{
	wp_die(__('You do not have sufficient permissions to access this page.'));
}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Booktrope Forward Gforms Notification Emails</h2>
	
	<form action="options.php" method="post">
		<?php settings_fields('btfne-settings-group'); ?>
		
		<h3Settings</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Active</th>
				<td><input type="checkbox" name="is_active" <?php if (get_option('is_active') === "on") { ?> checked <?php } ?>></td>
			</tr>
			<tr valign="top">
				<th scope="row">Forward All Notifications To (email)</th>
				<td><input type="text" name="forwarding_email" value="<?php echo get_option('forwarding_email'); ?>"></td>
			</tr>
			<tr valign="top">
				<th scope="row">From Email</th>
				<td><input type="text" name="from_email" value="<?php echo get_option('from_email'); ?>"></td>
			</tr>			
			</table>
			<?php submit_button(); ?>
	</form>
	
</div>