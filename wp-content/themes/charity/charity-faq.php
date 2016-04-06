<?php
/**
 * Template Name: Charity FAQ
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<div class="content-wrapper" id="page-info">
    <div class="container">

        <div class="row">
            <div class="col-xs-12">
                <header class="faq-header section-header">
                    <h2><?php echo vp_option('vpt_option.ch_faq_title'); ?></h2>
                </header>
                <!-- Collape Section Start Here -->

                <div class="row faq">

                    <div class="cols-xs-12 col-sm-6 anim-section">
                        <h3><?php echo vp_option('vpt_option.ch_faq_section_onetitle'); ?></h3>
                        <div class="panel-group" id="accordion">

                            <?php
                            $i = 1;
                            $args = array('post_type' => 'charity_faq', 'post_status' => 'publish', "posts_per_page"=> '-1');
                            $faqQuery1= new WP_Query($args);
                            if ($faqQuery1->have_posts()) :
                                while ($faqQuery1->have_posts()) : $faqQuery1->the_post();

                                    $faqPosition = vp_metabox('general_question.question-position');
                                    if ($faqPosition == 'left'):
                                        ?>					
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo esc_attr($i); ?>"> <?php the_title(); ?> <i class="fa fa-plus collape-plus"></i></a>
                                                </h4>
                                            </div>
                                            <div id="collapse-<?php echo esc_attr($i); ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <?php the_content(); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    endif;
                                    $i++;
                                endwhile;
                            endif;
                            wp_reset_postdata();
                            ?>																											
                        </div>
                    </div>



                    <div class="cols-xs-12 col-sm-6 anim-section">
                        <h3><?php echo vp_option('vpt_option.ch_faq_section_twotitle'); ?></h3>
                        <div class="panel-group" id="accordion-right">
                            <?php
                            $j = 1;
                            $args = array('post_type' => 'charity_faq', 'post_status' => 'publish');
                             $faqQuery2= new WP_Query($args);
                            if ( $faqQuery2->have_posts()) :
                                while ( $faqQuery2->have_posts()) :  $faqQuery2->the_post();

                                    $faqPositionRight = vp_metabox('general_question.question-position');
                                    if ($faqPositionRight == 'right'):
                                        ?>	
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"><a class="collapsed" data-toggle="collapse" data-parent="#accordion-right" href="#collapser-<?php echo esc_attr($j); ?>"> <?php the_title(); ?> <i class="fa fa-plus collape-plus"></i></a></h4>
                                            </div>
                                            <div id="collapser-<?php echo esc_attr($j); ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <?php the_content(); ?>
                                                </div>
                                            </div>
                                        </div>						
                                        <?php
                                    endif;
                                    $j++;
                                endwhile;
                            endif;
                            wp_reset_postdata();
                            ?>	
                        </div>
                    </div>

                </div>
                <!-- Collape Section End Here -->
            </div>

        </div>

    </div>
    <?php
    $askpageid = vp_metabox('charity-sub-page.charity_ask_us');
    if (!empty($askpageid)):
        $post = get_post($askpageid);
        $content = apply_filters('the_content', $post->post_content);
        ?>
        <!-- ask-us -->
        <section class="save-lives ask-us text-center">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <header class="page-header">
                            <h2><?php the_title(); ?></h2>
                            <?php echo $content; ?>
                        </header>
                    </div>
                </div>
            </div>
        </section>
        <!-- ask-us end  -->
    <?php endif; ?>
</div>
<?php
get_footer();