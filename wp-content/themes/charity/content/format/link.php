<?php
/**
 * Post Format - link
 *
 * @package     charity
 * @version     v.1.0
 */
global $charityHomeNews;

if ($charityHomeNews == "latestNewsSection"):
    ?> <div class="blog-alert">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><?php
        do_action("charity_post_latest_format_attribute");
        
        ?><p><?php echo esc_html(charity_truncate_content(get_the_content(), 150)); ?><a href="<?php the_permalink(); ?>" class="embed-link"><i class="icon-embed"></i></a></p></div><?php
        
elseif ($charityHomeNews == "twoSection"):
    ?>
    <div class="media col-xs-12 col-sm-12 blog-alert">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php do_action("charity_post_latest_format_attribute"); ?>
        <p><?php echo esc_html(charity_truncate_content(get_the_content(), 100)); ?>
            <a class="more-link btn btn-default" href="<?php the_permalink(); ?>"><?php esc_html_e("READ MORE", "charity"); ?></a>
        </p>
    </div>
    <?php
        
else:    
    ?>
    <section <?php post_class("blog"); ?>>
        <div class="blog-alert">
            <h2 class="h1"><a href="<?php the_permalink(); ?> "><?php the_title() ?></a></h2>
            <?php the_content() ?>
            <a href="<?php the_permalink(); ?>" class="embed-link"><i class="icon-embed"></i></a>
        </div>
    </section>
    <?php
endif;                      

