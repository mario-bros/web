<?php 
function charity_vc_our_mission( $atts, $content = null ) {
	extract( shortcode_atts( array( 
		'our_mission'  => '',
		), $atts ) );
	  
      $page_id = get_page_by_title($our_mission);
      $missionQuery=new WP_Query(array("page_id" => $page_id->ID));
    if ($missionQuery->have_posts()):
        $missionQuery->the_post();
        $url = wp_get_attachment_image_src(get_post_thumbnail_id($page_id->ID), array(1143, 479));
        ?>
        <!-- Save Lives Section Start Here-->
        <section class="save-lives text-center parallax" style="background-image: url('<?php echo esc_url($url[0]); ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <header class="page-header">
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                        </header>
                    </div>
                </div>
            </div>
        </section>
        <!-- Save Lives Section Start Here-->
    <?php
    endif;
    wp_reset_postdata(); 
   
}


