<?php


class BP_Real_Names {
	/** Version ***************************************************************/

	/**
	 * @public string plugin version
	 */

	public $version = '0.3.5';

	/**
	 * @public string plugin DB version
	 */
	public $db_version = '034';
	
	/** Paths *****************************************************************/

	public $file = '';
	
	/**
	 * @public string Basename of the plugin directory
	 */
	public $basename = '';

	/**
	 * @public string Absolute path to the plugin directory
	 */
	public $plugin_dir = '';
        
        
	/**
	 * @public string Prefix for the plugin
	 */
        public $prefix = '';
        
	/**
	 * @public string DB options
	 */
        public $options;

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
			self::$instance = new BP_Real_Names;
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

		/** Paths *************************************************************/
		$this->file       = __FILE__; 
		$this->basename   = plugin_basename( $this->file );
		$this->plugin_dir = plugin_dir_path( $this->file );
		$this->plugin_url = plugin_dir_url ( $this->file );
                $this->prefix = 'bprn';
                
                
                /*
                 * syntax for the new fields.
                 * eg. '%d1' will display the field #1 (base name)
                 * eg. '%d3, %d2' will display 'Smith, John' if field#3 is the name and field #2 is the firstname
                 */
                

                $this->default_options = array(
                    'base_field_name'=>__('Nickname','bprn'), //Name
                    'base_rule'=>'field-1'
                );
                
                $display_name = get_option('bprn_display_name_options');
                if($display_name) $options['components']['display_name']=$display_name;
                
                $members = get_option('bprn_members_options');
                if($members) $options['components']['members']=$members;
                
                $search = get_option('bprn_search_options');
                if($search) $options['components']['search']=$search;
                
                

                
                $this->options = wp_parse_args($options,$this->default_options);


	}
        
	function includes(){
            require( $this->plugin_dir . 'bprn-template.php'   );  
            
            if (is_admin()){
                require( $this->plugin_dir . 'bprn-admin.php'   );
                BP_Real_Names_Admin::instance();
            }
	}

	
	function setup_actions(){
            //add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );//scripts + styles

            //
            //add_filter( 'bp_xprofile_fullname_field_name', array(&$this,'bp_xprofile_fullname_field_name') );
            
            add_filter( 'bp_get_the_profile_field_name', array(&$this,'base_profile_field_name') );
            
            //display name
            add_filter( 'bp_core_get_user_displayname', array(&$this,'filter_user_displayname'), 10, 2 );
            
            //members lists
            add_filter( 'bp_get_member_name', array(&$this,'filter_member_name'));
            add_action( 'bp_pre_user_query', array(&$this,'filter_query_members_alphabetical'));
            
            //localization
            add_action('init', array($this, 'load_plugin_textdomain'));
            
	}
        
        public function load_plugin_textdomain(){
            load_plugin_textdomain($this->prefix, FALSE, $this->plugin_dir.'/languages/');
        }
        
        /**
         * Replace BASE field name if it's the default one.
         * @global type $field
         * @param type $name
         * @return type 
         */
        
        function base_profile_field_name($name){
            global $field;

            if(!bprn_is_base_field()) return $name;

            return $this->options['base_field_name'];
        }

        
        function filter_user_displayname($fallback,$user_id){
            $component_key = 'display_name';

            if (!$this->options['components'][$component_key]['active']) return $fallback;
            return bprn_get_fullname($component_key,$user_id,$fallback);
        }

        function filter_member_name($fallback){
            global $members_template;
            
            $component_key = 'members';
            
            if (!$this->options['components'][$component_key]['active']) return $fallback;

            return bprn_get_fullname($component_key,$members_template->member->ID,$fallback);
        }
        
        /**
         * Filters query when alphabetical.
         * Checks that field is required (we don't want to deal with empty values)
         * Different process when using one or several xprofile fields.
         * 
         * Several field query made with the help of Michael Berkowski, thanks to him !
         * http://stackoverflow.com/questions/14658137/order-mysql-query-using
         * 
  
            SELECT
            u.user_id as id,
            MAX(CASE WHEN field_id = 1 THEN value ELSE null END) AS NICKNAME,
            MAX(CASE WHEN field_id = 2 THEN value ELSE null END) AS FIRSTNAME,
            MAX(CASE WHEN field_id = 3 THEN value ELSE null END) AS LASTNAME
            FROM wp_bp_xprofile_data
            GROUP BY user_id
            // Include the HAVING if you only want those who have both first & last names specified
            HAVING 
            FIRSTNAME IS NOT NULL 
            AND LASTNAME IS NOT NULL
            // Pivoted columns can then be treated in the ORDER BY 
            ORDER BY 
            FIRSTNAME,
            LASTNAME

         * 
         * 
         * @global type $bp
         * @param type $query
         * @return type 
         */
        
        function filter_query_members_alphabetical($query){
            global $bp;
            
            if($query->query_vars['type']!='alphabetical') return $query;
            
            if (!$this->options['components']['search']['active']) return $query;
            
            $rule = $this->options['components']['search']['rule'];

            $bprn_fields_ids=$this->get_rule_fields_ids($rule,array('required_only'=>true));
            
            if(!$bprn_fields_ids) return $query;

            
            if(count($bprn_fields_ids)==1){//ORDER USING UNIQUE FIELD

                
                if ($bprn_fields_ids[0]==1) return $query;//acts like core, escape filter
                
                $clauses['select']  = "SELECT DISTINCT u.user_id as id FROM {$bp->profile->table_name_data} u";
                $clauses['where'][] = "u.field_id = {$bprn_fields_ids[0]}";
                $clauses['where'][] = "u.value IS NOT NULL";
                $clauses['orderby'] = "ORDER BY u.value";
            
                
            }else{ //ORDER USING SEVERAL VALUES
                
                $select[]="SELECT u.user_id as id";

                //emulate fields
                foreach((array)$bprn_fields_ids as $field_id){
                    $dummy_name = 'bprn_'.$field_id;
                    $select_max[]="MAX(CASE WHEN field_id = {$field_id} THEN value ELSE null END) AS {$dummy_name}";
                    $having[]="{$dummy_name} IS NOT NULL";
                    $orderby[]=$dummy_name;
                }

                if($select_max)$select[]=', '.implode(', ',$select_max);
                $select[]="FROM {$bp->profile->table_name_data} u";
                $select[]="GROUP BY u.user_id";

                if($having)$select[]="HAVING ".implode(" AND ",$having);


                $clauses['select']=implode(" ",$select);


                if($orderby)$clauses['orderby'] ="ORDER BY ".implode(',',$orderby);
                
            }
            
            $clauses['where']   = ! empty( $clauses['where'] ) ? 'WHERE ' . implode( ' AND ', $clauses['where'] ) : '';
  
            $query->uid_clauses = wp_parse_args($clauses,$query->uid_clauses);

            return $query;
        }
        


        
        function scripts_styles() {
            //wp_register_style( $this->prefix.'-style', $this->plugin_url . 'style.css' );
            //wp_enqueue_style( $this->prefix.'-style' );
        }
        
        function classes_attr($classes=false){
            if (!$classes) return false;
            echo ' class="'.implode(" ",(array)$classes).'"';
            
        }
        
        function get_profile_fields(){
            
            if ( !$fields = wp_cache_get( 'xprofile_fields', $this->prefix ) ) {

                if ( !$groups = wp_cache_get( 'xprofile_groups_inc_empty', $this->prefix ) ) {
                        $groups = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
                        wp_cache_set( 'xprofile_groups_inc_empty', $groups, $this->prefix );
                }

                foreach ((array)$groups as $group) {

                    foreach((array)$group->fields as $key=>$field) {

                        $fields[]=$field;

                        }
                }
                
                
                wp_cache_set( 'xprofile_fields', $fields, $this->prefix );
                
            }

            return $fields;
            
        }
        
        function get_rule_fields_ids($rule,$args=false){
            
            $default_args = array(
                'remove_empty'=>true,
                'is_required'=>false
            );
                    
            $args = wp_parse_args($args,$default_args);
            
            //get words starting by 'field-'
            preg_match_all('(field-\d+)', $rule, $fields);
            $fields = $fields[0];
            
            
            foreach($fields as $field){
                //get int
                $field_arr = explode('-',$field);
                $field_int = $field_arr[1];       
                $field = new BP_XProfile_Field( $field_int );
                
                if($args['remove_empty']){//check field is valid
                    if(!$field->id) continue;
                }

                
                if($args['is_required']){//check field is required
                    if(!$field->is_required) continue;
                }
                
                $field_ids[]=$field_int;
            }
            
            return $field_ids;
            
        }

}
        
  
/**
 * The main function responsible for returning the one instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $bprn = bprn(); ?>
 *
 * @return The one Instance
 */

function bprn() {
	return BP_Real_Names::instance();
}

bprn();


?>