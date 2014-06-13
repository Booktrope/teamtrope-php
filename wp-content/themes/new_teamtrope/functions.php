<?php
ini_set( 'display_errors', false );
error_reporting( 0 );

// ********** myCRED Custom Hooks **********
add_filter( 'mycred_setup_hooks', 'register_my_custom_hook' );
function register_my_custom_hook( $installed )
{
	$installed['hook_id'] = array(
		'title'       => __( '%plural% for Teamtrope Activity', 'textdomain' ),
		'description' => __( 'Credit for completing Teamtrope Activity', 'textdomain' ),
		'callback'    => array( 'my_group_post_hook_class' )
	);
	return $installed;
}

if ( !class_exists( 'my_group_post_hook_class' ) ) {
	class my_group_post_hook_class extends myCRED_Hook {
		function __construct( $hook_prefs ) {
			parent::__construct( array(
				'id'       => 'post_in_group',
				'defaults' => array(
					'creds'   => 10,
					'log'     => '%plural% for project form submit'
				)
			), $hook_prefs );
		}
		/**
		 * Hook into WordPress
		 */
		public function run() {
			// Since we are running a single instance, we do not need to check
			// if points are set to zero (disable). myCRED will check if this
			// hook has been enabled before calling this method so no need to check
			// that either.
			add_action( 'gform_after_submission', array( $this, 'post_in_group' ) );
			add_action( 'groups_update_last_activity', array( $this, 'post_in_group' ) );
			
		}
		/**
		 * Check if the user qualifies for points
		 */
		public function post_in_group( $user_id ) {
			// Check if user is excluded (required)
			//if ( $this->core->exclude_user( $user_id ) ) return;

			// Check to see if user has filled in their first and last name
			//if ( empty( $_POST['first_name'] ) || empty( $_POST['last_name'] ) ) return;

			// Make sure this is a unique event
			//if ( $this->has_entry( 'post_in_group', '', $user_id ) ) return;

			// Execute
			$this->core->add_creds(
				'post_in_group',
				$user_id,
				$this->prefs['creds'],
				$this->prefs['log']
			);	
		}
		
		public function preferences() {
			$prefs = $this->prefs; ?>
			<!-- Creds for New Group Comment -->
			<label for="<?php echo $this->field_id( 'creds' ); ?>" class="subheader"><?php echo $this->core->template_tags_general( __( '%plural%', 'mycred' ) ); ?></label>
			<ol id="">
				<li>
					<div class="h2"><input type="text" name="<?php echo $this->field_name( 'creds' ); ?>" id="<?php echo $this->field_id( 'creds' ); ?>" value="<?php echo $this->core->format_number( $prefs['creds'] ); ?>" size="8" /></div>
				</li>
				<li class="empty">&nbsp;</li>
				<li>
					<label for="<?php echo $this->field_id( 'log' ); ?>"><?php _e( 'Log template', 'mycred' ); ?></label>
					<div class="h2"><input type="text" name="<?php echo $this->field_name('log' ); ?>" id="<?php echo $this->field_id( 'log' ); ?>" value="<?php echo $prefs['log']; ?>" class="long" /></div>
					<span class="description"><?php _e( 'Available template tags: General', 'mycred' ); ?></span>
				</li>
			</ol>
<?php		unset( $this );
		}
	}
}