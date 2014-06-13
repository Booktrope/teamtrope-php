<!-- start buddypress navigation -->
	<?php
		if(has_nav_menu('bp-custom-menu')){
			$args = array(
					'theme_location' => 'bp-custom-menu'
				);
			wp_nav_menu($args);
		}else{
	?>
	<ul class="panelnav">
		<?php if ( 'activity' != bp_dtheme_page_on_front() && bp_is_active( 'activity' ) ) : ?>
			<li<?php if ( bp_is_page( BP_ACTIVITY_SLUG ) ) : ?> class="selected"<?php endif; ?>>
				<a href="<?php echo site_url() ?>/<?php echo bp_get_activity_root_slug(); ?>/" title="<?php _e( 'Activity', 'studio' ) ?>"><?php _e( 'Activity', 'studio' ) ?></a>
			</li>
		<?php endif; ?>
		<li<?php if ( bp_is_page( BP_MEMBERS_SLUG ) || bp_is_member() ) : ?> class="selected"<?php endif; ?>>
			<a href="<?php echo site_url() ?>/<?php echo bp_get_members_root_slug(); ?>/" title="<?php _e( 'Members', 'studio' ) ?>"><?php _e( 'Members', 'studio' ) ?></a>
		</li>
		<?php if ( bp_is_active( 'groups' ) ) : ?>
			<li<?php if ( bp_is_page( BP_GROUPS_SLUG ) || bp_is_group() ) : ?> class="selected"<?php endif; ?>>
				<a href="<?php echo site_url() ?>/<?php echo bp_get_groups_root_slug(); ?>/" title="<?php _e( 'Groups', 'studio' ) ?>"><?php _e( 'Groups', 'studio' ) ?></a>
			</li>
			<?php if ( bp_is_active( 'forums' ) && bp_is_active( 'groups' ) && ( function_exists( 'bp_forums_is_installed_correctly' ) && !(int) bp_get_option( 'bp-disable-forum-directory' ) ) && bp_forums_is_installed_correctly() ) : ?>
				<li<?php if ( bp_is_page( BP_FORUMS_SLUG ) ) : ?> class="selected"<?php endif; ?>>
					<a href="<?php echo site_url() ?>/<?php echo bp_get_forums_root_slug(); ?>/" title="<?php _e( 'Forums', 'studio' ) ?>"><?php _e( 'Forums', 'studio' ) ?></a>
				</li>
			<?php elseif ( function_exists('bbpress') ): ?>
				<li<?php if ( bbp_is_forum($post->ID) || bbp_is_topic($post->ID) ) : ?> class="selected"<?php endif; ?>>
					<a href="<?php bbp_forums_url(); ?>" title="<?php _e( 'Forums', 'studio' ) ?>"><?php _e( 'Forums', 'studio' ) ?></a>
				</li>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( bp_is_active( 'blogs' ) && is_multisite() ) : ?>
			<li<?php if ( bp_is_page( BP_BLOGS_SLUG ) ) : ?> class="selected"<?php endif; ?>>
				<a href="<?php echo site_url() ?>/<?php echo bp_get_blogs_root_slug(); ?>/" title="<?php _e( 'Blogs', 'studio' ) ?>"><?php _e( 'Blogs', 'studio' ) ?></a>
			</li>
		<?php endif; ?>
		<?php do_action( 'bp_nav_items' ); ?>
</ul>
	<?php } ?>
<!-- end buddypress navigation -->