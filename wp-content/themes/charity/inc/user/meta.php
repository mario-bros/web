<?php

/**
 * Charity - User meta ui 
 *
 * @package     charity.inc.user
 * @version     v.1.0
 */
class CharityUserMeta {

    public function __construct() {
     $this->action();   
    }

    public function action() { 
		//user_new_form
        add_action('show_user_profile', array(&$this, 'userProfile'));
        add_action('edit_user_profile', array(&$this, 'userProfile'));
        add_action( 'personal_options_update', array(&$this, 'userSaveProfile'));
        add_action( 'edit_user_profile_update', array(&$this, 'userSaveProfile'));
    }
    
    function userProfile($user){
        require_once 'meta-ui.php';
    }
    
    function userSaveProfile($user_id){
        if ( !isset( $_POST['cahrity_author_nonce'] ) ||  !wp_verify_nonce( $_POST['cahrity_author_nonce'], 'cahrity_author_action' ) ) {
           // wp_die("Wrong Profile");
           // return "";
        }
        
        
        if(isset($_POST['charity_author_prifile'])):
            foreach($_POST['charity_author_prifile'] as $key=>$val):
                if($key=="image"){
                    $this->setThumb($user_id, $val);
                }
                update_user_meta( $user_id,$key, $val );
            endforeach;    
        endif;
    }
    
    function setThumb($user_id, $url){
       update_user_meta( $user_id,"image_thumb", $url );
       update_user_meta( $user_id,"image_medium", $url );
    }

}

new CharityUserMeta();
