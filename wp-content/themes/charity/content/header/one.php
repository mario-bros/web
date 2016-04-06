<?php
/**
 * Charity header One
 * @package charity
 * @version     v.1.0
 */
?><header id="header">
    <div class="container">
        <div class="row primary-header">
            <?php $site_logo = (vp_option('vpt_option.site_logo')) ? vp_option('vpt_option.site_logo') : get_stylesheet_directory_uri() . "/assets/img/logo.png"; $site_logo = str_replace("http","https",$site_logo); ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="col-xs-12 col-sm-2 brand" title="<?php bloginfo('name'); ?>"><img src="<?php echo esc_url($site_logo); ?>" alt="<?php bloginfo('name'); ?>" width="246" height="80"></a>
            <div class="social-links col-xs-12 col-sm-10">
            	<?php 
            	$volunteer_btn_text=vp_option('vpt_option.become_volunteer_btn_text');
				$volunteer_btn_link=vp_option('vpt_option.become_volunteer_btn_link');
            	 ?>
                <!--<a href="<?php echo esc_url($volunteer_btn_link); ?>" class="btn btn-default btn-volunteer"><?php echo esc_attr($volunteer_btn_text); ?></a>-->
				<a class="btn btn-default btn-volunteer charity-donation-button"  href="/login">Sponsor Login</a>
				<a href="/general-donation/" data-id="301" data-target=".donate-form" class="btn btn-default btn-volunteer charity-global-causes-301 charity-donation-button">DONATE NOW</a> 
				
                <?php do_action("charity-site-social-link", "hidden-xs"); ?>
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

               

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>

</header>
