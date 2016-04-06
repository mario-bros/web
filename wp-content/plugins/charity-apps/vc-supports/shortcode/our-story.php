<?php 
function charity_vc_our_story( $atts, $content = null ) {
	extract( shortcode_atts( array( 
		'our_story'  => '',
		), $atts ) );
	  
      $page_id = get_page_by_title($our_story);
    $storyQuery=new WP_Query(array("page_id" => $page_id->ID));
    if ($storyQuery->have_posts()): $storyQuery->the_post();
       $url = wp_get_attachment_image_src(get_post_thumbnail_id($page_id->ID), array(1143, 479));
        ?>
        <!-- Helping Section Start here-->
        <section class="helping-child parallax" style="background-image: url('<?php echo esc_url($url[0]); ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7 col-md-5">
                        <h2 class="h1"> <?php the_title(); ?> </h2>
                        <?php the_content(); ?>        
                    </div>
                </div>
            </div>
        </section>
        <!-- Helping Section Start here-->
    <?php  endif;  wp_reset_postdata();
}
