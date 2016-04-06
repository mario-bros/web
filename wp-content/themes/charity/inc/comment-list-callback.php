<?php
/**
 * Charity - Comment list callback
 *
 * @package     charity/inc/lib
 * @version     v.1.0
 */
if (!function_exists('charity_comment')) :

    /**
     * Template for comments and pingbacks.
     * To override this walker in a child theme without modifying the comments template
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    function charity_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                    <p><?php _e('Pingback:', 'charity'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'charity'), '<span class="edit-link">', '</span>'); ?></p>
                    <?php
                    break;
                default :
                    // Proceed with normal comments.
                    global $post;
                    ?>
                <li <?php comment_class('reply-form'); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="media comment comment-list-content">
                        <header class="comment-meta comment-author vcard pull-left">
                           <?php
                            echo get_avatar($comment, 68);
                            ?>
							</header><!-- .comment-meta -->
                        <div class="comment-wrapper media-body">
                        	
                        	<header>
                        		<h4 class="media-heading">
								<?php
                                printf('<cite><b class="fn">%1$s</b> %2$s</cite>', get_comment_author_link(),
                                        // If current post author is also comment author, make it known visually.
                                        ( $comment->user_id === $post->post_author ) ? '<span>' . __('', 'charity') . '</span>' : '' );
                                ?>
								</h4><!---->
								<span class="date"><?php printf('<a href="%1$s" class="post-date-section"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),
                                        /* translators: 1: date, 2: time */ sprintf(__('%1$s - %2$s', 'charity'), get_comment_date(' M,d/m/Y') , get_comment_time('g:i A'))
                                );
                                ?></span>
                                <span class="btn btn-default btn-sm pull-right comment-reply-btn">
                                <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'charity'), 'after' => ' ', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                            </span><!-- .reply -->
                        		
                        	</header>
                        	<section class="comment-content">
                                <?php if ('0' == $comment->comment_approved) : ?>
                                    <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'charity'); ?></p>
                                <?php endif; ?>
                                <?php comment_text(); ?>
                                <?php edit_comment_link(__('Edit', 'charity'), '<p class="edit-link">', '</p>'); ?>
                            </section><!-- .comment-content -->
                            
                        </div>
                    </article><!-- #comment-## -->
                    <?php
                    break;
            endswitch; // end comment_type check
        }


endif;


