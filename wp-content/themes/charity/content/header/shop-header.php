<?php
/**
 * Charity header One
 * @package charity
 * @version     v.1.0
 */
?><header id="header" class="shop-header">
    <div class="container">
        <div class="row primary-header">
            <?php $site_logo = (vp_option('vpt_option.site_logo')) ? vp_option('vpt_option.site_logo') : get_stylesheet_directory_uri() . "/assets/img/logo.png"; ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="col-xs-12 col-sm-2 brand" title="<?php bloginfo('name'); ?>"><img src="<?php echo esc_url($site_logo); ?>" alt="<?php bloginfo('name'); ?>"></a>
            <div class="social-links col-xs-12 col-sm-10">
            	<?php 
            	$volunteer_btn_text=vp_option('vpt_option.become_volunteer_btn_text');
				$volunteer_btn_link=vp_option('vpt_option.become_volunteer_btn_link');
            	 ?>
                <a href="<?php echo esc_url($volunteer_btn_link); ?>" class="btn btn-default btn-volunteer"><?php echo esc_attr($volunteer_btn_text); ?></a>
                <?php do_action("charity-site-social-link", "hidden-xs"); ?> 
               <div class="charity-woocommerce-icons">
                <?php global $woocommerce; ?>
                <?php if ( is_user_logged_in() ) { ?>
 	               <span class="charity-shop-user" ><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','woothemes'); ?>"><i class="fa fa-user"></i></a></span>
                <?php } ?>
		  	  <span class="charity-products-counts"><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><i class="fa fa-shopping-cart"></i>
		  	   <span><?php echo $woocommerce->cart->cart_contents_count;?></span></a></span> 
		  	   </div>
			</div>
        </div>
    </div>
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only"><?php echo esc_html_e("Toggle navigation", "charity"); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php do_action("charity_nav_menu"); ?> 

                <form class="navbar-form navbar-right search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?php echo esc_attr_e("Search Here", "charity"); ?>" name="s">
                    </div>
                    <button type="submit">
                        <i class="icon-search"></i>
                    </button>
                </form>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>

</header>
