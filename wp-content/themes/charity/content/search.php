<?php
/**
 * Search page 
 *
 * @package     charity
 * @version     v.1.0
 */
?><article <?php post_class("blog search-blog"); ?>>
    <div class="row">
        <div class="col-xs-12 col-sm-10  caption">
            <h2 class="h1"><a href="<?php the_permalink(); ?> "><?php the_title() ?></a></h2>
            <?php if ( 'post' == get_post_type() ) : ?>
            <ul class="post-in">
                <li>
                    <?php  
                        esc_html_e("Posted In  :", "charity");
                        the_category(", ");
                    ?>
                </li>
                <li>
                    <a href="<?php comments_link(); ?>"><?php comments_number( '0 : comment', '1 : comment', '% : comments' ); ?></a>
                </li>
            </ul>
            <?php endif; ?>
           <?php the_excerpt(); ?>
        </div>
    </div>
</article>
<?php 