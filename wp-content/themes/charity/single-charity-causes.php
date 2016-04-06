<?php
/**
 * The Template for displaying causes detail page
 * 
 * @package     charity
 * @version     v.1.0
 */
get_header();
//breadcrumb
$titel = "Projects";
do_action("charity_breadcrumb", array("title" => $titel));
?>
<div class="cause-page" id="page-info">
    <!-- Our Causes Detail Section-->
    <div class="container anim-section">
        <div class="row">
            <?php do_action("charity_causes_single_layout"); ?>
        </div>
        <!-- our causes detail-->
    </div>
</div>
<!-- site content ends -->
<?php
get_footer();
