<?php

if(!defined('WP_PARSE_API_PATH')) die('.______.');

if(!current_user_can('manage_options'))
{
	wp_die(__('You do not have sufficient permissions to access this page.'));
}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Parse API</h2>
	
	<form action="options.php" method="post">
		<?php settings_fields('wp-parse-api-settings-group'); ?>
		
		<h3>Live Settings</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">APP ID</th>
				<td><input type="text" name="app_id" value="<?php echo get_option('app_id'); ?>"></td>
			</tr>
			<tr valign="top">
				<th scope="row">App Masterkey</th>
				<td><input type="text" name="app_masterkey" value="<?php echo get_option('app_masterkey'); ?>"></td>
			</tr>
			<tr valign="top">
				<th scope="row">App Rest Key</th>
				<td><input type="text" name="app_restkey" value="<?php echo get_option('app_restkey');?>"></td>
			</tr>
			</table>
			
		<h3>Dev Settings</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">APP ID</th>
				<td><input type="text" name="dev_app_id" value="<?php echo get_option('dev_app_id'); ?>"></td>
			</tr>
			<tr valign="top">
				<th scope="row">App Masterkey</th>
				<td><input type="text" name="dev_app_masterkey" value="<?php echo get_option('dev_app_masterkey'); ?>"></td>
			</tr>
			<tr valign="top">
				<th scope="row">App Rest Key</th>
				<td><input type="text" name="dev_app_restkey" value="<?php echo get_option('dev_app_restkey');?>"></td>
			</tr>
			</table>		
			
			<h3>Which Parse</h3>
			<input type="radio" name="active_account" value="live"     <?php if (get_option('active_account') === "live") { ?>checked<?php } ?>>&nbsp;Live</input><br/>
			<input type="radio" name="active_account" value="dev" <?php if (get_option('active_account') === "dev") { ?>checked<?php } ?>>&nbsp;Dev</input>
			
			<?php submit_button(); ?>
	</form>
	
</div>