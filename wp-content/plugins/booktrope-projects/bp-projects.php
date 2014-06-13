<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require_once '/Users/andy/Documents/Websites/teamtrope.dev/wp-content/plugins/buddypress/bp-groups/bp-groups-classes.php';

if( ! class_exists( 'BP_Group_Extension') ) {
	// Groups component is not enabled; don't initialize the extension
    return;
}

    function bp_new_intro_text() {
	echo bp_get_new_intro_text();
    }
    function bp_get_new_intro_text() {
	global $bp;

	if ( isset( $bp->groups->current_group->intro ) )
            $intro_text = $bp->groups->current_group->intro;
	else
            $intro_text = '';

	return apply_filters( 'bp_get_new_intro_text', $intro_text );
    }

class My_Group_Extension extends BP_Group_Extension {   
     
    function my_group_extension() {
        $this->name = 'Start Here!';
        $this->slug = 'group-intro';
     
        $this->create_step_position = 21;
        $this->nav_item_position = 31;
    }
     
    function create_screen() {
        if ( !bp_is_group_creation_step( $this->slug ) )
            return false;
        ?>
     
        <p> This is the Introduction Text for each Teamroom</p>
        <?php
            $post_id = 2067;
            $queried_post = get_post($post_id); 
        ?>
        <h2><?php echo $queried_post->post_title; ?></h2>
        <?php   echo $queried_post->post_content;  
                wp_nonce_field( 'groups_create_save_' . $this->slug );
    }

    function create_screen_save() {
        global $bp;
        check_admin_referer( 'groups_create_save_' . $this->slug );
 
        /* Save any details submitted here */
        groups_update_groupmeta( $bp->groups->new_group_id, 'my_meta_name', 'value' );        
    }
     
    function edit_screen() {
        if ( !bp_is_group_admin_screen( $this->slug ) )
            return false; ?>
             
        <h2><?php echo esc_attr( $this->name ) ?></h2>
     
        <p>Edit steps here</p>
        <input type='submit' name='save' value='Save' />
     
        <?php
        wp_nonce_field( 'groups_edit_save_' . $this->slug );     
    }
     
    function edit_screen_save() {
        global $bp;
     
        if ( !isset( $_POST['save'] ) )
            return false;
         
        check_admin_referer( 'groups_edit_save_' . $this->slug );
         
        /* Insert your edit screen save code here */
        $success = true;
        /* To post an error/success message to the screen, use the following */
        if ( !$success )
            bp_core_add_message( __( 'There was an error saving, please try again', 'buddypress' ), 'error' );
        else
            bp_core_add_message( __( 'Settings saved successfully', 'buddypress' ) );
 
        bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . '/admin/' . $this->slug );
    }
     
    function display() {
        /* Use this function to display the actual content of your group extension when the nav item is selected */
        $post_id = 2067;
        $queried_post = get_post($post_id); 
        ?>
        <br/>
        <div class="page" id="blog-page" role="main">
        <h2><?php echo $queried_post->post_title; ?></h2>
            <div id="post-2067" class="post-2067 page type-page status-publish hentry">
                <div class="entry">
                    <?php echo $queried_post->post_content;  ?>
                </div>
            </div>
        </div>
<?php }
     
    function widget_display() { ?>
        <div class='info-group''>
            <h4><?php echo esc_attr( $this->name ) ?></h4>
            <p>
                You could display a small snippet of information from your group extension here. It will show on the group 
                home screen.
            </p>
        </div>
        <?php
    }
}
bp_register_group_extension( 'My_Group_Extension' );
