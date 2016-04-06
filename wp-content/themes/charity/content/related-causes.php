<?php
/**
 * Charity - Related causes 
 * 
 * @package     charity
 * @version     v.1.0
 */
$term_list=wp_get_object_terms(get_the_ID(), 'causes-category');


if(!empty($term_list[0])):

$args=array(
		"causes-category" => $term_list[0]->slug,
		'post__not_in' => array(get_the_ID()),
		'post_type' => 'charity-causes',
		'showposts'=> 3,
);

query_posts($args);

if(have_posts()):

?>
    <section class="our-causes our-causes-section our-causes3">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <header class="page-header section-header">
	
		<h2><?php //echo vp_option('vpt_option.ch_related_section_title'); ?>Related Projects</h2>
	</header>
                   <div class="row">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="col-xs-12 col-md-4 item-wrapper">
                                <div class="items zoom">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="img-thumb">
                                            <figure>
                                                <?php the_post_thumbnail("charity_causes_thumb"); ?>
                                            </figure>
                                        </a>
                                    <?php endif; ?>
                                    <div class="item-content">
                                        <h3><?php the_title(); ?></h3>
                                        <?php do_action("charity_cauese_donation_details_sidebar"); ?>
                                        <?php do_action("charity_cauese_donation_details_single"); ?>
                                        <?php the_content(""); ?>
                                        <div class="buttonmiddle"><?php do_action("charity_causes_donation_button"); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;
wp_reset_query();
endif;