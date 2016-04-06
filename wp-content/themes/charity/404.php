<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Charity
 */
get_header();
//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<section class="container" id="page-info">
    <div class="row">
        <!-- Table Section Start Here -->
        <div class="col-xs-12 col-md-8 col-md-offset-2 four-zero-four">
            <strong><?php esc_html_e('404', 'charity'); ?></strong>

            <header class="page-header">
                <h2><?php echo vp_option('vpt_option.ch_404_title'); ?></h2>
                <p>
                    <?php echo vp_option('vpt_option.ch_404_desc'); ?>
                </p>
            </header>
            <a class="btn btn-default" href="<?php echo esc_url(home_url()); ?>"><?php esc_html_e('BACK TO HOMEPAGE', 'charity'); ?></a>

        </div>		

    </div>

</section>

<?php
get_footer();

