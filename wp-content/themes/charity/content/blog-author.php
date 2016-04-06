<?php
/**
 * The template for displaying blog author.
 *
 * @package Charity 
 * @version 1.0
 */

$authorProfileImage=get_the_author_meta("image");

$authorImageClass="author-no-image";
if(!empty($authorProfileImage)): $authorImageClass="author-image";
?><a class="pull-left" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
    <figure>
        <img class="media-object" src="<?php echo  esc_url(charity_resize($authorProfileImage, 106, 120)) ; ?>" alt="<?php the_author(); ?>"  width="106px" height="120px">
    </figure> </a>
<?php endif; ?>
<div class="media-body <?php echo esc_attr($authorImageClass); ?>">
    <header>
        <h4 class="media-heading"><?php the_author(); ?></h4>
        <span class="date"><?php the_time('F jS, Y') ?></span>
        <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="btn btn-default pull-right"><?php esc_html_e("View Profile", "charity"); ?></a>
    </header>
    <p><?php the_author_meta("description"); ?></p>
    <?php do_action("charity_social_link", array("author_id" => get_the_author_meta("ID"), "section" => "single_page")); ?>
</div>
<?php 