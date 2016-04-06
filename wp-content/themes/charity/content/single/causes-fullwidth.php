<?php
/**
 * Charity - causes single fullwidth
 * 
 * @package     charity
 * @version     v.1.0
 */
             if (have_posts()) :
                while (have_posts()) : the_post();
             $shortCodeMeta = vp_metabox('causes-short-code.causesshortcode');
                    ?>
                    <div class="col-xs-12">
                        <div class="row article-list-large progressbar">
                            <div class="col-xs-12 anim-section">
                                <div class="text-center section-header">
                                    <h2 class="h4"><?php the_title(); ?></h2>
                                    <?php do_action("charity_causes_details_attribute"); ?>
                                </div>
                                <?php if (has_post_thumbnail()): ?>
                                    <figure class="article-pic"><?php the_post_thumbnail("charity_causes_details"); ?></figure>
                                <?php endif; ?>
                                <div class="detail-description">
                                    <div class="donation-details">

                                        <?php do_action("charity_cauese_donation_details"); ?>
                                        <?php do_action("charity_causes_donation_button", "pull-right"); ?>
                                    </div>
                                    <?php
                                    the_content();
                                    //do_action("charity_causes_donation_button");
                                    ?>
                                   <!--step donation-->
											<!--<div class="step-donation">
												<header class="donate-easy-steps section-header">
													<h2><?php echo vp_option('vpt_option.ch_donation_steps_section_title'); ?></h2>
												</header>
											<?php if(!empty($shortCodeMeta)): ?>
												<div class="text-center">
											      <?php print($shortCodeMeta); ?>
												</div>
                                            <?php endif;?>-->
												<!--step donation--> 
                                    
                                </div>
                            </div>
                        </div>
						<?php get_template_part("content/related", "causes"); ?>
                    </div>
                    <?php
                endwhile;
            endif;
