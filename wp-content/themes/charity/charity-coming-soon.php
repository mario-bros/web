<?php
/**
 * Template Name: Charity Coming Soon
 * 
 * @package charity
 * @version     v.1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <!--[if lt IE 9]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>	
        <?php
        $coming_soon_bg =vp_option('vpt_option.coming_soon_page_bg');
        ?>
        <div id="wrapper" class="launch clearfix" style="background-image:url('<?php echo esc_url($coming_soon_bg); ?>')">
            <header id="header">
                <div class="container">

                    <div class="row">
                        <?php $site_logo = (vp_option('vpt_option.coming_soon_page_logo')) ? vp_option('vpt_option.coming_soon_page_logo') : get_stylesheet_directory_uri() . "/assets/img/launch-logo.png"; ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="col-xs-12" title="<?php bloginfo('name'); ?>"><img src="<?php echo esc_url($site_logo); ?>" alt="<?php bloginfo('name'); ?>"></a>

                    </div>

                </div>

            </header>

            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-7 col-lg-5 coming-soon-content">
                            <?php
                            if (have_posts()) : while (have_posts()) : the_post();
                                    the_content();
                                endwhile;
                            endif;
                            ?>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-7 col-lg-6">
                            <!-- CountDown Frame -->
                            <div id="charityCountdown" class="counter clearfix"></div>
                            <!-- CountDown Frame -->

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
                            <div class="input-group">
                                <?php
                                $newsLetter = vp_option('vpt_option.coming_soon_newsletter');
                                print do_shortcode($newsLetter);
                                ?>
                            </div>
                            <!--<form role="form">

                            <div class="input-group">
                                    <input class="form-control" type="email" placeholder="Enter email address">
                                    <div class="input-group-addon">
                                            <input type="submit" class="btn btn-theme" value="submit">
                                    </div>
                            </div>

                    </form>-->
                        </div>
                    </div>
                </div>

            </div>
            <footer id="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">

                            <span><?php print(vp_option('vpt_option.copy_right')); ?></span>

                        </div>

                    </div>

                </div>

            </footer>
        </div>
        <?php get_template_part("assets/switcher/switcher"); ?>
        <?php wp_footer(); ?>	
    </body>
</html>
