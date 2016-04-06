<?php
/**
 * Post Format - quote
 *
 * @package     charity
 * @version     v.1.0
 * 
 */
global $charityHomeNews;

if ($charityHomeNews == "latestNewsSection"):
     do_action("charity_post_format_quote"); 
elseif ($charityHomeNews == "twoSection"):
    ?>
    <div class="media col-xs-12 col-sm-12">
        <?php do_action("charity_post_format_quote");  ?>
    </div>
    <?php
else:    
?>
<article <?php post_class("blog"); ?>>
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 caption">
            <?php do_action("charity_post_format_attribute"); ?>
        </div>
    </div>
    <?php do_action("charity_post_format_quote"); ?>
    <?php the_content("READ MORE"); ?>
</article>
<?php
endif;                      


