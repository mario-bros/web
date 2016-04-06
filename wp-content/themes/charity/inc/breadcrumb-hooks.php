<?php

/**
 * Charity - breadcrumb hooks 
 * @package charity
 * @version     v.1.0
 * 
 */
class CharityBreadcrumbHooks {

    /**
     * class init
     */
    public function __construct() {
        $this->action();
    }

    /**
     * action init
     * 
     * array("title"=> "", "template_file" => "")
     * 
     */
    function action() {
        add_action("charity_breadcrumb", array(&$this, "layout"));
    }

    function layout($args) {
		
         if(!empty($args['title'])){
        ?>   
        <!--Breadcrumb Section Start Here-->
        <div class="breadcrumb-section" <?php $this->bgImage($args); ?>>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1><?php echo esc_html($args['title']); ?></h1>
                        <?php $subtitle = ""; $subtitle = $this->subTitle($args); echo $subtitle; ?>
						<?php if ($subtitle == "") { ?>
						<?php charity_breadcrumb(); ?>
						<?php } else { ?>
						
						<?php } ?>
						
                    </div>
                </div>
            </div>
        </div>
		<?php if ($subtitle != "") { ?>
		
		<div class="sponsormenucontainer">
		<?php if ( is_user_logged_in() ) {  ?> 
		<div class="sponsormenu"><ul>
		<li class="dashboardlink"><a href="/login">Dashboard</a></li>
		<li class="mysponsoringlink"><a href="/my-sponsoring">My Sponsoring</a></li>
		<li class="transactionslink"><a href="/transactions">Transactions</a></li>
		<li class="editaccountlink"><a href="/edit-account">Edit account</a></li>
		<li class="last"><a href="/logout">Logout</a></li>
		</ul>
		</div>
		<?php } ?>		
		</div>
		<?php } ?>
        <!--Breadcrumb Section End Here-->
        <?php
		
         }
    }
    
    function bgImage($args){
        
        if(!empty($args['template_file'])){
            $id=charity_get_page_template($args['template_file'], $by="ID");
        }
        else{
            global $post;
            $id=$post->ID;
        }
        
         $bgImage = vp_metabox('breadcrumb.image', '', $id);
		 $bgDefaultImage=vp_option('vpt_option.img_404');
         if(!empty($bgImage)){
            printf('style="background-image:url(%s)"',$bgImage);
        }
		 else{
		 	printf('style="background-image:url(%s)"',$bgDefaultImage); //default image
		 }
    }
	
    function subTitle($args){
        

            global $post;
            $id=$post->ID;
       		$subtitle = get_post_meta( $id, 'subtitle', true );
        
        
         if(!empty($subtitle)){
           $subtitle = '<h2 class="subtitle">'.$subtitle.'</h2>';
        }
		 else{
		 	 $subtitle = "";
		 }
		 return $subtitle;
    }	
	

}

new CharityBreadcrumbHooks();




