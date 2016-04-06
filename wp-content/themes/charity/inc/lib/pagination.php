<?php

/**
 * Pagination functionality
 * 
 * @package     cherity.inc.lib
 * @version     v.1.0
 */
function chy_pagenavi($wp_query = false) {

    if (empty($wp_query)) {
        global $wp_query;
    }
    /*if(ot_get_option('chy-paggingnation') == "loadmore" || ot_get_option('chy-paggingnation') == "infinitescroll"){
        chy_loadmore($wp_query);
    }
    else{*/
        chy_pagging($wp_query);
    //}
}
/*
function chy_loadmore($wp_query) {
    $behavior=(ot_get_option('chy-paggingnation') != "infinitescroll") ? 'twitter' : "none";
    $loadmore = array(
        "maxPages" => $wp_query->max_num_pages,
        "behavior" =>  $behavior
    );
    ?>
     <div class="load-more cols-xs-12 anim-section text-center animate" id="chy-infinitescroll-button" data-loadmore='<?php print(json_encode($loadmore)); ?>'>
        <span class="btn btn-default plain border-two">
        <?php
        echo next_posts_link(__('Load More', "cherity"), $wp_query->max_num_pages);
        ?></span>
    </div>
    <?php
}

add_filter("chylog_post_class", "chyInfiniteClass");

function chyInfiniteClass($class){
    $class[]="chy-infinite-loop";
    return $class;
}*/

function chy_pagging($wp_query) {
    
    $effect_class = "charity-paging-effect";//(ot_get_option('paging_effect') != "") ? ot_get_option('paging_effect'): 'cl-effect-2';
    
    $total = $wp_query->max_num_pages;

    // only bother with the rest if we have more than 1 page!
    if ($total > 1) {
        // get the current page
        if (!$current_page = get_query_var('paged'))
            $current_page = 1;
        // structure of "format" depends on whether we're using pretty permalinks
        if (get_option('permalink_structure')) {
            $format = '/page/%#%/';
        } else {
            $format = '/%#%/';
        }
        $big = 999999999;
        echo "<div class='cols-xs-12 anim-section text-center animate charity-paging'>";
        echo chy_paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))), //get_pagenum_link(1) . '%_%',
            'format' => $format,
            'current' => $current_page,
            'total' => $total,
            'mid_size' => 2,
            'end_size' => 1,
            'effect_class' => $effect_class
        ));
        echo '</div>';
    }
}

/**
 * Retrieve paginated link for archive post pages.
 *
 * @link wp-includes/general-template.php
 * @global type $wp_query
 * @global type $wp_rewrite
 * @param type $args
 * @return string
 */
function chy_paginate_links($args = '') {
    global $wp_query, $wp_rewrite;

    $total = ( isset($wp_query->max_num_pages) ) ? $wp_query->max_num_pages : 1;
    $current = ( get_query_var('paged') ) ? intval(get_query_var('paged')) : 1;
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $query_args = array();
    $url_parts = explode('?', $pagenum_link);

    if (isset($url_parts[1])) {
        wp_parse_str($url_parts[1], $query_args);
    }

    $pagenum_link = remove_query_arg(array_keys($query_args), esc_url($pagenum_link));
    $pagenum_link = trailingslashit($pagenum_link) . '%_%';

    $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

    $defaults = array(
        'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
        'format' => $format, // ?page=%#% : %#% is replaced by the page number
        'total' => $total,
        'current' => $current,
        'show_all' => false,
        'prev_next' => true,
        'prev_text' => __('&laquo; Previous', "cherity"),
        'next_text' => __('Next &raquo;', "cherity"),
        'end_size' => 1,
        'mid_size' => 2,
        'type' => 'plain',
        'add_args' => false, // array of query args to add
        'add_fragment' => '',
        'before_page_number' => '',
        'after_page_number' => '',
        'effect_class' => '',
    );

    $args = wp_parse_args($args, $defaults);

    // Who knows what else people pass in $args
    $total = (int) $args['total'];
    if ($total < 2) {
        return;
    }
    $current = (int) $args['current'];
    $end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
    if ($end_size < 1) {
        $end_size = 1;
    }
    $mid_size = (int) $args['mid_size'];
    if ($mid_size < 0) {
        $mid_size = 2;
    }
    $add_args = is_array($args['add_args']) ? $args['add_args'] : false;
    $r = '';
    $page_links = array();
    $dots = false;

    if ($args['prev_next'] && $current && 1 < $current) :
        $link = str_replace('%_%', 2 == $current ? '' : $args['format'], $args['base']);
        $link = str_replace('%#%', $current - 1, $link);
        if ($add_args)
            $link = add_query_arg($add_args, esc_url($link));
        $link .= $args['add_fragment'];
        
        $page_links[] = '<a class="prev page-numbers" href="' . esc_url(apply_filters('paginate_links', $link)) . '"><span data-hover="Prev">Prev</span></a>';
    else:
        $page_links[] = '';
    endif;
    for ($n = 1; $n <= $total; $n++) :
        if ($n == $current) :
            $page_links[] = "<a href='' class='page-numbers active'><span data-hover='" . number_format_i18n($n) . "'>" . number_format_i18n($n) . "</span></a>";
            $dots = true;
        else :
            if ($args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size )) :
                $link = str_replace('%_%', 1 == $n ? '' : $args['format'], $args['base']);
                $link = str_replace('%#%', $n, $link);
                if ($add_args)
                    $link = add_query_arg($add_args, esc_url($link));
                $link .= $args['add_fragment'];

                /** This filter is documented in wp-includes/general-template.php *///"  $args['before_page_number'] . number_format_i18n($n) . $args['after_page_number'] . "
                $page_links[] = "<a  href='" . esc_url(apply_filters('paginate_links', $link)) . "'><span data-hover='" . number_format_i18n($n) . "'>" . number_format_i18n($n) . "</span></a>";
                $dots = true;
            elseif ($dots && !$args['show_all']) :
                $page_links[] = '<a href="#"><span class="page-numbers dots">' . __('&hellip;', "cherity") . '</span></a>';
                $dots = false;
            endif;
        endif;
    endfor;
    if ($args['prev_next'] && $current && ( $current < $total || -1 == $total )) :
        $link = str_replace('%_%', $args['format'], $args['base']);
        $link = str_replace('%#%', $current + 1, $link);
        if ($add_args)
            $link = add_query_arg($add_args, esc_url($link));
        $link .= $args['add_fragment'];

        /** This filter is documented in wp-includes/general-template.php */
        $page_links[] = '<a class="next page-numbers" href="' . esc_url(apply_filters('paginate_links', $link)) . '" ><span data-hover="Next">Next</span></a>';
    else:
        $page_links[] = '';

    endif;

    $r .= "<ul class='pagination " . $args['effect_class'] . "'>\n\t<li>";
    $r .= join("</li>\n\t<li>", $page_links);
    $r .= "</li>\n</ul>\n";
    return $r;
}
