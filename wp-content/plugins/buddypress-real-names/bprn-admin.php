<?php
class BP_Real_Names_Admin {
    
        public $admin_page_slug='';

        /**
	 * @var Instance
	 */
	private static $instance;
        
        


	/**
	 * Main Instance
	 *
	 * Insures that only one instance of the plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
         * 
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new BP_Real_Names_Admin;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}
        
	/**
	 * A dummy constructor to prevent bbPress from being loaded more than once.
	 *
	 * @since bbPress (r2464)
	 * @see bbPress::instance()
	 * @see bbpress();
	 */
	private function __construct() { /* Do nothing here */ }
        
	function setup_globals() {

 
	}
        
	function includes(){

	}
	
	function setup_actions(){
            add_action ('admin_init', array( &$this, 'admin_init' ));
            add_action( 'admin_menu', array( &$this, 'register_settings_page' ));
            
	}
        

 
        function admin_init(){

            // If the theme options don't exist, create them.  
            if( false == get_option( 'bprn_options' ) ) {    
                add_option( 'bprn_options' );  
            } // end if 
            
            
            ///DISPLAY///
            
            add_settings_section( 
                    'display_name_section',
                    __('Display Name','bprn'),
                    array(&$this,'tab_display_name_section'),
                    'bprn_display_name_options'
            );
            
            add_settings_field(   
                    'display_name_active',                                   // ID used to identify the field throughout the theme  
                    __('Replace display name','bprn'),                        // The label to the left of the option interface element  
                    array(&$this,'tab_display_name_field_active'),   // The name of the function responsible for rendering the option interface  
                    'bprn_display_name_options',                                      // The page on which this option will be displayed  
                    'display_name_section',         // The name of the section to which this field belongs  
                    array(                              // The array of arguments to pass to the callback. In this case, just a description.  
                        'Activate this setting to display the header.'  
                    )  
            );
            
            add_settings_field(   
                    'display_name_rule',                                   // ID used to identify the field throughout the theme  
                    __('Rule','bprn'),                        // The label to the left of the option interface element  
                    array(&$this,'tab_display_name_field_rule'),   // The name of the function responsible for rendering the option interface  
                    'bprn_display_name_options',                                      // The page on which this option will be displayed  
                    'display_name_section',         // The name of the section to which this field belongs  
                    array(                              // The array of arguments to pass to the callback. In this case, just a description.  
                        'Activate this setting to display the header.'  
                    )  
            );
            
             
            
            ///MEMBER///
            
            add_settings_section( 
                    'members_section',
                    __('Members','bprn'),
                    array(&$this,'tab_members_section'),
                    'bprn_members_options'
            );
            
            add_settings_field(   
                    'members_active', 
                    __('Replace name in members lists','bprn'),
                    array(&$this,'tab_members_field_active'), 
                    'bprn_members_options',
                    'members_section',  
                    array(  
                        'Activate this setting to display the header.'  
                    )  
            );
            
            add_settings_field(   
                    'members_rule',
                    __('Rule','bprn'),  
                    array(&$this,'tab_members_field_rule'), 
                    'bprn_members_options', 
                    'members_section', 
                    array( 
                        'Activate this setting to display the header.'  
                    )  
            );
            

            
            ///SEARCH///
            
            add_settings_section( 
                    'search_section',
                    __('Alphabetical Members Sorting','bprn'),
                    array(&$this,'tab_search_section'),
                    'bprn_search_options'
            );
            
            add_settings_field(   
                    'search_active', 
                    __('Change the way members are sorted alphabetically','bprn'),
                    array(&$this,'tab_search_field_active'), 
                    'bprn_search_options',
                    'search_section',  
                    array(  
                        'Activate this setting to display the header.'  
                    )  
            );
            
            add_settings_field(   
                    'search_rule',
                    __('Rule','bprn'),  
                    array(&$this,'tab_search_field_rule'), 
                    'bprn_search_options', 
                    'search_section', 
                    array( 
                        'Activate this setting to display the header.'  
                    )  
            );
            
  
            
            ////

            register_setting(  
                'bprn_display_name_options',  
                'bprn_display_name_options',  
                array(&$this,'validate_display_name_options') 
            );  
            
            register_setting(  
                'bprn_members_options',  
                'bprn_members_options',  
                array(&$this,'validate_members_options') 
            );  
            
            register_setting(  
                'bprn_search_options',  
                'bprn_search_options',  
                array(&$this,'validate_search_options')
            );  
            


            
        }
        
        function scripts_styles(){
           
            
            wp_register_style( 'bprn-admin-style', bprn()->plugin_url . '_inc/css/bprn-admin.css' );
            wp_enqueue_style( 'bprn-admin-style' );
 
            //TO FIX
            wp_enqueue_script('bprn-admin-scripts', bprn()->plugin_url . '_inc/js/bprn-admin.js' , array('jquery'),bprn()->version);
        }
        
        function register_settings_page(){
                /* Register our plugin page */
                $this->admin_page_slug = add_users_page( __('BuddyPress Real Names', 'bprn'), __('BuddyPress Real Names', 'bprn'), 'manage_options', bprn()->prefix, array(&$this,'render_settings_page'));

                //add_action('admin_print_scripts-' . $page, 'oqp_admin_scripts');
                //add_action('admin_print_styles-' . $page, 'bprn_admin_styles');
                
                add_action( 'admin_print_scripts-'.$this->admin_page_slug , array( &$this, 'scripts_styles' ));
        }
        
        function render_settings_page(){
            ?>  
                <!-- Create a header in the default WordPress 'wrap' container -->  
                <div class="wrap">  

                    <?php screen_icon(); ?>  
                    <h2><?php _e('BuddyPress Real Names', 'bprn');?></h2>  
                    <?php settings_errors(); ?> 
                    
                    <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_name_options';?>  
                    
                    <h2 class="nav-tab-wrapper">
                        <a href="?page=bprn&tab=display_name_options" class="nav-tab <?php echo $active_tab == 'display_name_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Display Name','bprn');?></a>
                        <a href="?page=bprn&tab=members_options" class="nav-tab <?php echo $active_tab == 'members_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Members Lists','bprn');?></a>
                        <a href="?page=bprn&tab=search_options" class="nav-tab <?php echo $active_tab == 'search_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Members Sorting','bprn');?></a>  
                    </h2>  

                    <form method="post" action="options.php">  

                        <?php 
                        if( $active_tab == 'display_name_options' ) {  
                            settings_fields( 'bprn_display_name_options' );
                            do_settings_sections( 'bprn_display_name_options' );
                        }
                        ?>
                        <?php 
                        if( $active_tab == 'members_options' ) {  
                            settings_fields( 'bprn_members_options' );
                            do_settings_sections( 'bprn_members_options' );
                        }
                        ?>
                        <?php 
                        if( $active_tab == 'search_options' ) {  
                            settings_fields( 'bprn_search_options' );
                            do_settings_sections( 'bprn_search_options' );
                        }
                        ?>
                        
                        <?php do_action('bprn_render_settings_page');?>

                        <?php submit_button(); ?>  

                    </form>  

                </div><!-- /.wrap -->  
            <?php  
        }
        
        function render_component_field_active($component){

            $plugin_options = bprn()->options;
            $component_options = $plugin_options['components'];
            
            if(isset($component_options[$component])){
                $options=$component_options[$component];
                $option = $options['active'];
            }else{
                $option=1;
            }
            

            ?>
            <input type="checkbox" id="bprn_<?php echo $component;?>_active" name="bprn_<?php echo $component;?>_options[active]" value="1"<?php checked($option);?> />
            <?php
        }
        
        function xprofile_admin_link(){
            ?>
            <a class="xprofile_admin_link alignright" href="<?php echo $this->xprofile_get_admin_link();?>"><?php _e('Admin Profile Fields','bprn');?></a>
            <?php
        }
        
            function xprofile_get_admin_link(){
                return admin_url('users.php?page=bp-profile-setup');
            }
        
        function render_component_field_rule($component){
            $plugin_options = bprn()->options;
            
            $component_options = $plugin_options['components'];
            $options = $component_options[$component];
            $option = $options['rule'];
            
            if(!$option) $option=bprn()->default_options['base_rule'];
            
            ?>
            <div class="bprn_field_rule">
                <input type="text" id="bprn_<?php echo $component;?>_rule" name="bprn_<?php echo $component;?>_options[rule]" value="<?php echo esc_attr($option);?>" />
                <?php self::list_fields_helper($component,$option);?>
                <?php self::xprofile_admin_link();?>
            </div>
            <?php
        }
        
        function list_fields_helper($component,$rule){
            $fields = bprn()->get_profile_fields();
            
            $active_fields = bprn()->get_rule_fields_ids($rule);

            
            ?>
            <ul id="bprn_<?php echo $component;?>_list_fields" class="bprn_list_fields">
                <?php
                foreach ($fields as $the_field){
                    
                    
                    global $field;
                    $field = $the_field;

                    $classes=array();
                    if(in_array($field->id,$active_fields)) $classes[]='selected';
                    if($field->is_required) $classes[]='required';
                    ?>
                        <li <?php bprn()->classes_attr($classes);?>><label><?php bp_the_profile_field_name();?></label><code><?php echo 'field-'.$field->id;?></code></li>
                    <?php
                }
            ?>
            </ul>
            <?php
        }
        
        function tab_general_section() {
            ?>
            <div class="bprn_tab_description">
                <p>
                    <?php
                    _e('Select the fields that the plugin will use for Firstname and for Lastname.  Those must be textfields, required, and cannot be the base field (Name)');
                    ?>
                </p>
            </div>
            
            <?php
        }
        
        function tab_display_name_section() {
            ?>
            <div class="bprn_tab_description">
                <p>
                    <?php _e("Select the fields that the plugin will use to display the user's real name throughout the website.","bprn");?>
                </p>
                <p>
                    <?php printf(__("The basic usage would be to create two fields; %1s and %2s, then to set the rule","bprn"),'<strong>'.__('Firstname','bprn').'</strong>','<strong>'.__('Lastname','bprn').'</strong>');?> <input type="textfield" disabled="disabled" value="field-2 field-3">
                    <?php _e("to get something like :","bprn");?> <code>John Smith</code>.
                    <br/>
                    <em><?php _e("Of course, in this example, 2 should be the ID of your Firstname field and 3 should be the ID of your Lastname field.","bprn");?></em>
                </p>
                <p>
                    <?php _e("This allows to create more complex rules, to display names like this, for example :","bprn");?>
                    <ul>
                        <li><code>Smith, John</code></li>
                        <li><code>Mr John Smith</code></li>
                        <li><code>jsmith44 (John Smith)</code></li>
                    </ul>
                </p>
            </div>
            
            <?php
        }
        
        function tab_display_name_field_active() {
                self::render_component_field_active('display_name');
        }
        
        function tab_display_name_field_rule() {
            self::render_component_field_rule('display_name');
        }
        
       function tab_members_section() {
            ?>
            <div class="bprn_tab_description">
                <p>
                    <?php
                    _e('Write a rule that will be used to generate the name of the users in the Members Lists.');
                    echo"<br/>";
                    _e('For example, you could add a gender field, a firstname field and a lastname field to generate a name like this :');
                    echo"<code>Smith John, Mr.</code>";
                    ?>
                </p>
            </div>
            
            <?php
        }
        
        function tab_members_field_active() {
                self::render_component_field_active('members');
        }
        
        function tab_members_field_rule() {
            self::render_component_field_rule('members');
        }
        
        
        
        function tab_search_section() {
            ?>
            <div class="bprn_tab_description">
                <p>
                    <?php
                    printf(__("With BuddyPress Real Names, you can customize how your users's names are displayed in many ways, and even add a %1s field as prefix of the name, eg. %2s."),'<em>'.__('gender','bprn').'</em>','<code>Mr. John Smith</code>');
                    ?>
                </p>
                <p>
                    <?php
                    printf(__("But when BuddyPress sorts the members alphabetically, which field should it use first ?  %1s ? %2s ? %3s ?"),'<code>Mr.</code>','<code>Smith</code>','<code>John</code>');
                    ?>
                </p>
                <p>
                    <?php
                    _e('For this reason, you can customize a specific rule that will be used when sorting the members alphabetically.','bprn');
                    echo"<br/>";
                    printf(__("Setting a rule that will generate %1s in the Members List will not be a problem since this rule will be able to sort this member as %2s ! "),'<code>Mr. John Smith</code>','<code>Smith John</code>');
                    ?>
                </p>
                
                <p>
                    <strong>
                    <?php
                    printf(__("Be aware that all the fields of this specific rule MUST be set to %1s in your fields settings, because optional fields (=empty values) would cause sorting problems."),'<em>'.__('Required','bprn').'</em>');
                    ?>
                    </strong>
                </p>
                
            </div>
            
            <?php
        }
   
 

        
        function tab_search_field_active() {
                self::render_component_field_active('search');
        }
        
        function tab_search_field_rule() {
            self::render_component_field_rule('search');
        }

        
        function validate_rule($component,$rule){
            
            $rule = trim($rule);

            $rule_fields_ids = bprn()->get_rule_fields_ids($rule);//input fields
            
            if (!$rule_fields_ids){
                add_settings_error(
                        'bprn_'.$component.'_options',           // setting title
                        'empty_fields_ids',            // error ID
                        __('No valid fields have been set in the submitted rule!','bprn'),   // error message
                        'error'                        // type of message
                );
                return false;
            }
            
            $args_valid_fields_ids = array('remove_empty'=>false);
            
            if($component=='search') $args_valid_fields_ids['is_required']=true;

            $rule_valid_fields_ids = bprn()->get_rule_fields_ids($rule,$args_valid_fields_ids);//existing fields
            
            
            
            $rule_invalid_fields_ids = array_diff((array)$rule_fields_ids,(array)$rule_valid_fields_ids);
            /*
            echo"<br/><br/>sent:<br/>";
            print_r($component);
            echo"<br/><br/>sent:<br/>";
            print_r($rule_fields_ids);
            echo"<br/><br/>sent:<br/>";
            print_r($rule_valid_fields_ids);
            echo"<br/><br/>sent:<br/>";
            print_r($rule_invalid_fields_ids);
            exit;
             *
             */
            
            //warn about invalid fields

            if ($rule_invalid_fields_ids){

                foreach((array)$rule_invalid_fields_ids as $invalid_field_id){
                    $invalid_field_names[]='field-'.$invalid_field_id;
                }

                add_settings_error(
                        'bprn_'.$component.'_options',           // setting title
                        'invalid_fields_ids',            // error ID
                        sprintf(__('Those fields are not valid and were removed: %s','bprn'),implode(',',(array)$invalid_field_names)),   // error message
                        'error'                        // type of message
                );
            }
            
            //remove invalid fields from string
            
            foreach((array)$rule_invalid_fields_ids as $invalid_field_id){//remove invalid fields
                $replace_str='field-'.$invalid_field_id;
                $rule = preg_replace('/\b'.$replace_str.'\b/', '', $rule);
            }

            return trim($rule);
            
        }
        
        function validate_display_name_options($input){
            
            //active
            $output['active']=$input['active'];
            
            //rule
            $rule = self::validate_rule('display_name',$input['rule']);
            if($rule) $output['rule']=$rule;
            
            return $output;
        }
        
        function validate_members_options($input){
            
            //active
            $output['active']=$input['active'];
            
            //rule
            $rule = self::validate_rule('members',$input['rule']);
            if($rule) $output['rule']=$rule;
            
            return $output;
        }
        
        function validate_search_options($input){
            
            //active
            $output['active']=$input['active'];
            
            //rule
            $rule = self::validate_rule('search',$input['rule']);
            if($rule) $output['rule']=$rule;
            
            return $output;
        }

   
}
?>
