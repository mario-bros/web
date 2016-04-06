<?php
/*
 * Template Name: Charity Children Page
 *
 * @package     charity
 * @version     v.1.0
 */

get_header();

remove_all_filters('posts_orderby');
$args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 1000, "orderby" => "rand");
query_posts ( $args );

//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<div class="content-wrapper" id="page-info">

    <!-- Gallery sections Start Here -->
    <section class="we-help gallery-wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <!--Sub Breadcrumb Section Start Here-->
                    
                    <!--Sub Breadcrumb Section Start Here-->
                    <!--Gallery Section Start Here-->
                    <div class="row gallery">
                        <?php
                        

                       $args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 1000);
                        query_posts($args);
                        if (have_posts()) :
                            while (have_posts()) : the_post();
							
							$liv_sponsor = "";
							$edu_sponsor = "";							
							$liv_sponsor = get_post_meta( get_the_ID(), '_give_liv_sponsor', true );
							$edu_sponsor = get_post_meta( get_the_ID(), '_give_edu_sponsor', true );	
							$livstatus = "inactive";
							if ($liv_sponsor != "") {
								$livstatus = "active";
							}
							$edustatus = "inactive";
							if ($edu_sponsor != "") {
								$edustatus = "active";
							}														
							if ($livstatus == "active" && $edustatus == "active") { continue; }
                                $metaType = vp_metabox('cahrity-meta-type-settings.choose-meta-type');
                                ?>
                                <div <?php post_class('cols-xs-12 col-sm-4'); ?> style="text-align:center;">
                                    <?php $thumbClass = ( $metaType == "video") ? "embed-vedio-block" : "embed-image"; ?>
                                    <div class="thumbnail <?php echo esc_attr($thumbClass); ?>">
                                        <?php
                                        if ($metaType == "video"):
                                            do_action("charity_portfolio_video");
                                        else:
                                            echo get_the_post_thumbnail( get_the_ID(), 'medium');
                                        endif;
                                        ?>                                           
							<?php $intro = get_post_meta( get_the_ID(), '_give_intro', true ); ?>
							<?php $name = get_post_meta( get_the_ID(), '_give_childname', true ); ?>	
							<?php $dob = get_post_meta( get_the_ID(), '_give_date_of_birth', true ); ?>
							<?php $gender = get_post_meta( get_the_ID(), '_give_gender', true ); ?>																
							<?php $intro = (strlen($intro) > 150) ? substr($intro, 0, 150) . '...' : $intro; ?> 							
                                        <h3 class="h3"><a href="<?php the_permalink(); ?>"><?php echo $name; ?></a></h3>
										<p>Date of Birth: <?php echo $dob; ?></p>
										<p>Gender: <?php echo $gender; ?></p>
										<p><?php echo $intro; ?></p>
										<p class="sponsoredicon"><span class="dashicons sponsor-<?php echo $edustatus; ?>-education"></span><span class="sponsor-item">Education Sponsorship</span></p>
										<p class="sponsoredicon"><span class="dashicons sponsor-<?php echo $livstatus; ?>-hearth"></span><span class="sponsor-item">Livelihood Sponsorship</span></p>
										
						 <div class="buttonmiddle">
						 <a class="btn btn-default charity-donation-button"  href="<?php the_permalink();?>">Sponsor now</a>						
						</div>										
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                                <?php
								
                            endwhile;
                            //chy_pagenavi();
                        else :
                            get_template_part('content/none');
                        endif;
                        wp_reset_query();
                        ?>
                    </div>
                    <!--Gallery Section End Here-->
                </div>
            </div>
        </div>
    </section>
    <!-- Gallery sections Start Here -->
</div>

<?php
get_footer();
