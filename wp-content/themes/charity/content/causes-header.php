<?php
/*
 * Charity - causes archive , taxonomy, list header section with breadcrumb
 *
 * @package     charity
 * @version     v.1.0
 * */
?>
<header class="page-header section-header top-spacer">
    <h2><?php echo vp_option('vpt_option.ch_causes_title'); ?></h2>
</header>
<?php
 
// Causes category menu
do_action("charity_causes_category_menu"); 