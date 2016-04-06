<?php
/**
 * Template Name: Charity payment Success
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
?><div class="container thank-you-wrap">
    <div class="row">
        <div class="col-xs-12">	
            <?php
            $easypay_options = get_option('easypay_options');
            $charity_pay_currency = (isset($easypay_options['easypay_pay_currency']) ) ? stripslashes($easypay_options['easypay_pay_currency']) : 'USD';
            $charityCurrency = get_charity_currency_symbol($charity_pay_currency);


            if (!empty($_REQUEST['item_id'])):
                $successQuery=new WP_Query(array("p" => $_REQUEST['item_id'], "post_type" => "charity-causes"));
                if ($successQuery->have_posts()) : while ($successQuery->have_posts()) : $successQuery->the_post();
                        the_title('<h1>', '</h1>');
                        if (has_post_thumbnail()):
                            the_post_thumbnail();
                        endif;
                        ?>
                        <h3><?php echo vp_option('vpt_option.ch_donation_message_title'); ?> <strong><?php echo (!empty($_REQUEST['rsamount'])) ? esc_html($charityCurrency . $_REQUEST['rsamount']) : 0; ?> </strong><?php esc_html_e("for", "charity"); ?>  <?php echo get_the_title($_REQUEST['item_id']); ?></h3>
                        <?php

                    endwhile;
                endif;
                wp_reset_postdata();
            endif;
			
			echo '<p>'.get_the_content().'</p>';
            ?>
        </div>
    </div>
</div>
<?php
// causes listing
get_template_part("content/success", "payment-causes");
get_footer();