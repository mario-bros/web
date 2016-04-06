<?php
/*
 * Charity - breadcrumb
 *
 * @package     charity
 * @version     v.1.0
 * */
function charity_breadcrumb() {

    $delimiter = ''; // delimiter between crumbs
    $home = __('Home', 'charity'); // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<li class="active">'; // tag before the current crumb
	$projectslink = "/projects";
	$supportnowlabel = "Support our Children";
	$projectslabel = "Projects";
    $after = '</li>'; // tag after the current crumb

    global $post;
    $homeLink = home_url();

    echo '<ol class="breadcrumb"><li><a href="' . $homeLink . '">' . $home . '</a></li> ' . $delimiter . ' ';

    if (is_category()) {
        
        $thisCat = get_category(get_query_var('cat'), false);
        if ($thisCat->parent != 0)
        echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
        echo $before . single_cat_title('', false) . $after;
    } elseif (is_search()) {
        echo $before . __('Search results for "', "charity") . get_search_query() . '"' . $after;
    } elseif (is_day()) {
        echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
        echo '<li><a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a></li> ' . $delimiter . ' ';
        echo $before . get_the_time('d') . $after;
    } elseif (is_month()) {
        echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
        echo $before . get_the_time('F') . $after;
    } elseif (is_year()) {
        echo $before . get_the_time('Y') . $after;
    } elseif (is_single() && !is_attachment()) {
        if (get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            //print_r($post_type); //$slug['url']
            $slug = $post_type->rewrite;
            echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '">' . $post_type->labels->singular_name . '</a></li>';
            if ($showCurrent == 1)
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        } else {
            $cat = get_the_category();
            $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            if ($showCurrent == 0)
                $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
           // echo '<li>' . $cats . '</li>';
		   echo '<li><a href="/blog/">Blog</a></li>';
            if ($showCurrent == 1)
                echo $before . get_the_title() . $after;
        }
    } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_author()) {
        $post_type = get_post_type_object(get_post_type());
       	$postName = $post_type->labels->singular_name;
		//echo $postName;
		if ($postName == "Projects") {
		echo '<li><a href="' . $projectslink . '">' . $projectslabel . '</a></li> ' . $delimiter . ' ';
		echo $before . single_term_title("", false) . $after; //-----post type/taxonomy
		} elseif ($postName == "Page" && get_the_title($page->ID) == "Children in need of a sponsor") {
		echo $before . $supportnowlabel . $after; //-----post type/taxonomy
		} else {
		echo $before . $post_type->labels->singular_name . $after; //-----post type/taxonomy
		}
    } elseif (is_attachment()) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID);
        $cat = $cat[0];
        echo is_wp_error($cat_parents = get_category_parents($cat, TRUE, '' . $delimiter . '')) ? '' : $cat_parents;
        echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
        if ($showCurrent == 1)
            echo '' . $delimiter . ' ' . $before . get_the_title() . $after;
    } elseif (is_page() && !$post->post_parent) {
        if ($showCurrent == 1)
            echo $before . get_the_title() . $after;
    } elseif (is_page() && $post->post_parent) {
        $parent_id = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $page = get_page($parent_id);
			//echo get_the_title($page->ID);
			if (get_the_title($page->ID) == "Programmes" || get_the_title($page->ID) == "Get Involved" || get_the_title($page->ID) == "About Us" || get_the_title($page->ID) == "Support Us") {
			$breadcrumbs[] = '<li>' . get_the_title($page->ID) . '</li>';	
			} else { 
            $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
			}
            $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
            echo $breadcrumbs[$i];
            if ($i != count($breadcrumbs) - 1)
                echo ' ' . $delimiter . ' ';
        }
        if ($showCurrent == 1)
            echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
    } elseif (is_tag()) {
        echo $before . __('Posts tagged "', "charity") . single_tag_title('', false) . '"' . $after;
    } elseif (is_author()) {
        global $author;
        $userdata = get_userdata($author);
        echo $before . __('Articles posted by ', "charity") . $userdata->display_name . $after;
    } elseif (is_404()) {
        echo $before . __('Error 404', "charity") . $after;
    }

//    if ( get_query_var('paged') ) {
//      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
//      echo __('Page', 'bblog') . ' ' . get_query_var('paged');
//      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
//    }

    echo '</ol>';
}