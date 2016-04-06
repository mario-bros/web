<?php
/**
  * Post Format - aside
 *
 * @package     charity
 * @version     v.1.0
 */
global $charityHomeNews;

if ($charityHomeNews == "latestNewsSection"):
    ?><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php  do_action("charity_post_latest_format_attribute"); ?>
        <p><?php echo esc_html(charity_truncate_content(get_the_content(), 100)); ?>
            <a class="more-link btn btn-default" href="<?php the_permalink(); ?>"><?php esc_html_e("READ MORE", "charity"); ?></a>
        </p>
        <?php
        
elseif ($charityHomeNews == "twoSection"):
    ?>
    <div class="media col-xs-12 col-sm-12">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php do_action("charity_post_latest_format_attribute"); ?>
        <p><?php echo esc_html(charity_truncate_content(get_the_content(), 100)); ?>
            <a class="more-link btn btn-default" href="<?php the_permalink(); ?>"><?php esc_html_e("READ MORE", "charity"); ?></a>
        </p>
    </div>
    <?php
else:    
?><article <?php post_class("blog"); ?>>
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
