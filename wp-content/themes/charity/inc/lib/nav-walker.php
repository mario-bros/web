<?php

/**
 * Charity - Nav Menu Walker
 *
 * @package  charity.inc
 * @version  v.1.0
 */
class Charity_Nav_Walker extends Walker_Nav_Menu {

// add classes to ul sub-menus
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // depth dependent classes
        $indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'sub-menu',
            ( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >= 2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode(' ', $classes);


        $charitSubDiv = ($display_depth == 1) ? '<div class="dropdown-menu">' : ''; //charity div for sub menu
        // build html
        $output .= "\n" . $indent . $charitSubDiv . '<ul class="' . $class_names . '">' . "\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {

        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $charitSubDiv = ($display_depth == 1) ? '</div>' : ''; //charity div for sub menu

        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>" . $charitSubDiv . "\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        if (!isset($args->before)) {
            return;
        }
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        //-------has submenu
        if (in_array("menu-item-has-children", $classes)) {
            $classes[] = "submenu-icon";
        }
        //------------------


        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '#';
		$atts['onclick'] = !empty($item->url) ? '' : 'return false;';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ( 'href' === $attr ) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        //-------has submenu
        $charitSubClass="";
        if (in_array("menu-item-has-children", $classes)) {
            $charitSubClass ='data-toggle="dropdown"'; 
        }
        //--------------

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' '.$charitSubClass.'>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        //-------has submenu
        if (in_array("menu-item-has-children", $classes)) {
            $item_output .=  '<span class="glyphicon glyphicon-chevron-down"></span> <span class="glyphicon glyphicon-chevron-up"></span>'; 
        }
        //---------
        
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}
