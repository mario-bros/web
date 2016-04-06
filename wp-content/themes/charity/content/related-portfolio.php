<?php
/**
 * The Template part for related portfolio page
 * 
 * @package     charity
 * @version     v.1.0
 */
$term_list = wp_get_object_terms(get_the_ID(), 'portfolio-type');


if (!empty($term_list[0])):

    $args = array(
        "portfolio-type" => $term_list[0]->slug,
        'post__not_in' => array(get_the_ID()),
        'post_type' => 'charity-portfolio',
        'showposts' => 3,
    );

    query_posts($args);

    if (have_posts()):
        ?>
        <header class="page-header section-header text-left">
            <h2 class="text-left h3"><?php echo vp_option('vpt_option.ch_portfolio_related_section_title'); ?></h2>
        </header>
        <div class="row we-help anim-section">
            <?php
            while (have_posts()): the_post();
                $metaType = vp_metabox('cahrity-meta-type-settings.choose-meta-type');
                ?>

                <div class="cols-xs-12 zoom col-sm-4">
                    <div class="thumbnail <?php //echo esc_attr($thumbClass); ?>">
                        <?php
                        if ($metaType == "video"):
                            do_action("charity_portfolio_video");
                        else:
                            do_action("charity_portfolio_image");
                        endif;
                        ?>                                           
                        <h3 class="h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php the_excerpt(); ?>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
        <?php
    endif;
    wp_reset_query();
endif;

