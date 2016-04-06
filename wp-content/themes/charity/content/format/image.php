<?php
/**
 * Post Format - image
 *
 * @package charity
 */
global $charityHomeNews;

if ($charityHomeNews == "latestNewsSection"):
    if (has_post_thumbnail()):
        ?>
        <a href="<?php the_permalink(); ?>" class="img-thumb">
            <figure>
                <?php the_post_thumbnail("charity_causes_thumb"); ?>
            </figure>
        </a>
    <?php endif; ?>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php do_action("charity_post_latest_format_attribute"); ?>
    <p><?php echo esc_html(charity_truncate_content(get_the_content(), 100)); ?>
        <a class="more-link btn btn-default" href="<?php the_permalink(); ?>"><?php esc_html_e("READ MORE", "charity"); ?></a>
    </p>
    <?php
elseif ($charityHomeNews == "twoSection"):
    ?><div class="col-xs-12 col-sm-5">
        <a href="<?php the_permalink(); ?>" class="img-thumb"><figure><?php the_post_thumbnail('charity_causes_thumb'); ?></figure> </a>
    </div>   
    <div class="media col-xs-12 col-sm-7">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php do_action("charity_post_latest_format_attribute"); ?>
        <p><?php echo esc_html(charity_truncate_content(get_the_content(), 100)); ?>
            <a class="more-link btn btn-default" href="<?php the_permalink(); ?>"><?php esc_html_e("READ MORE", "charity"); ?></a>
        </p>
    </div>
    <?php
else:
    ?>
    <article <?php post_class("blog"); ?>>
        <?php if (has_post_thumbnail()): ?>   <figure>
                <?php the_post_thumbnail(); ?>
            </figure>
        <?php endif; ?>
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 caption">
                <h2 class="h1"><a href="<?php the_permalink(); ?> "><?php the_title() ?></a></h2>

                <?php do_action("charity_post_format_attribute"); ?>

                <?php the_content("READ MORE"); ?>
            </div>
        </div>
    </article>
                            <?php

endif;                      
