<?php

/**
 * Charity helper function
 *
 * @package  charity
 * @version  v.1.0
 */
class CharitySocialMediaLink {

    public function __construct() {
        $this->action();
    }

    public function action() {
        add_action('charity_social_link', array(&$this, 'author_social_link_get'));
    }

    function author_social_link_get($arr) {
        $author_id = $arr['author_id'];
        switch ($arr['section']) {
            case "single_page": $this->singlePage($author_id); break;
        }
    }

    function singlePage($author_id) {
        $chy_options = array();
        $social_icons = vp_option('vpt_option.auther_social_linlk');
        
        if(empty($social_icons)) {
            return ;
        }
        
        foreach ($social_icons as $social_icon) {
            $chy_options[$social_icon] = array("title" => __($social_icon, "charity"), "font-class" => "fa fa-" . $social_icon);
        }

        if (count($chy_options)>0):
            ?>
            <ul class="social-icons blog-page-author">
                <?php
                foreach ($chy_options as $key => $val) {
                    $this->setLink($key, $val['title'], $author_id, $val['font-class']);
                }
                ?>
            </ul>
            <?php
        endif;
    }

    function setLink($meta_key, $title, $author_id, $font_class) {
        $link = get_the_author_meta($meta_key, $author_id);

        if (!empty($link)):
            ?><li class="charity-<?php echo esc_attr($meta_key); ?> charity-social-link">
                <a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($title); ?>"><i class="<?php echo esc_attr($font_class); ?>"></i></a>
            </li><?php
        endif;
    }

}

new CharitySocialMediaLink();
