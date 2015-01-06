<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list" role="main">

	<?php while ( bp_members() ) : bp_the_member(); ?>

	<?php
			$cur_avail = "";
			$cur_avail = bp_get_member_profile_data( 'field=Current Availability' );
			$not_avail = 'completely unavailable';
			$some_avail = 'somewhat available';
			$all_avail = 'completely available';

			if (strpos($cur_avail, $not_avail) !== FALSE)
			{
				$avail_color = "red";
				$avail_msg = "Not Available";
			} elseif (strpos($cur_avail, $some_avail) !== FALSE)
			{
				$avail_color = "yellow";
				$avail_msg = "Possibly Available";
			} else
			{
				$avail_color = "green";
				$avail_msg = "Available";
			}
			$avail_indicator = '<div class="pcss3t pcss3t-icons-left  pcss3t-height-auto pcss3t-theme-3-all-white"><label class="'. $avail_color . '">' . $avail_msg . '</label></div>';
			$mbr_roles = bp_get_member_profile_data( 'field=Role(s)' );
	?>

		<li>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>

			<div class="item">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>

					<span class="update"> <?php echo $mbr_roles . $avail_indicator; ?></span>
<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>
				</div>

				<?php do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /***
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
			</div>

			<div class="action">

				<?php do_action( 'bp_directory_members_actions' ); ?>

			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
