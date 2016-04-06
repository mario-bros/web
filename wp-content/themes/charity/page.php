<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Charity
 */
get_header();
//breadcrumb
    
    $current = $post->ID;
    $parent = $post->post_parent;
    $grandparent_get = get_post($parent);
    $grandparent = $grandparent_get->post_parent;
    if ($root_parent = get_the_title($grandparent) !== $root_parent = get_the_title($current)) {$titel = get_the_title($grandparent); }else {$titel = get_the_title($parent); }


do_action("charity_breadcrumb", array("title" => $titel));
$flagLinkPages=false;
?>

<div class="content-wrapper container" id="page-info">
    <div class="row">
        <div class="col-xs-12">
            <?php
            while (have_posts()) : the_post();
                get_template_part('content/page');
				

				
                ?>
                <div class="row" style="display:none !important;">
                    <div class="col-xs-12">
                        <div class="comment-block">
                            <div class="row">
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">

                                    <section class="live-comment">
                                        <?php
                                        if (comments_open() || get_comments_number()) :
                                            comments_template();
                                        endif;
                                        ?>
                                    </section>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php
            
            if($flagLinkPages){
                wp_link_pages();
            }
            
            endwhile; // end of the loop.   ?>
        </div>
    </div>
</div>
<?php

				if ($current == "644" || $current == "646") {
					?>
					<div class="sponsorkidssubcontainer">
					<?php
				get_template_part("content/child", "support");	
	
				?>
					</div>
				<?php
							}
get_footer();
