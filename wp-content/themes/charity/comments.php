<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to charity_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Charity
 * @since Charity 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required())
    return;
?>

<div id="comments" class="comments-area">

    <?php // You can start editing here -- including this comment!  ?>

    <?php if (have_comments()) : ?>
        <h3 class="comments-title">
            <?php
            printf(_n('Comments', 'Comments', get_comments_number(), 'charity'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
            ?>
            </h2>

            <ol class="commentlist media-list">
                <?php wp_list_comments(array('callback' => 'charity_comment', 'style' => 'ol')); ?> 
            </ol><!-- .commentlist -->

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
                <nav id="comment-nav-below" class="navigation" role="navigation">
                    <h3 class="comment-navigation-title"><?php _e('Comment navigation', 'charity'); ?></h3>
                    <div class="nav-previous btn btn-default btn-sm"><?php previous_comments_link(__('&larr; Older Comments', 'charity')); ?></div>
                    <div class="nav-next btn btn-default btn-sm pull-right"><?php next_comments_link(__('Newer Comments &rarr;', 'charity')); ?></div>
                </nav>
            <?php endif; // check for comment navigation  ?>

            <?php
            /* If there are no comments and comments are closed, let's leave a note.
             * But we only want the note on posts and pages that had comments in the first place.
             */
            if (!comments_open() && get_comments_number()) :
                ?>
                <p class="nocomments"><?php _e('Comments are closed.', 'charity'); ?></p>
            <?php endif; ?>

        <?php endif; // have_comments()  ?>


        <!-- Comment Form  -->
        <div class="reply-form comment-form-wrapper">

            <?php
            global $aria_req;
            $args = array(
                'id_form' => 'commentform',
                'id_submit' => 'submit-comment',
                'title_reply' => __('Leave a Reply', 'charity'),
                'title_reply_to' => __('Leave a Reply to %s', 'charity'),
                'cancel_reply_link' => __('Cancel Reply', 'charity'),
                'label_submit' => __('SUBMIT', 'charity'),
                'comment_field' => __('', 'charity') .
                '<div class="charity-comment-fields"><div class="form-group"><label for="comment">Comment*</label><textarea id="comment" name="comment" rows="3" cols="1" aria-required="true" class="form-control"></textarea></div></div>',
                'must_log_in' => '<p class="must-log-in">' .
                sprintf(
                        __('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink()))
                ) . '</p>',
                'logged_in_as' => '<p class="logged-in-as">' .
                sprintf(
                        __('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))
                ) . '</p>',
                'comment_notes_before' => '',
                'comment_notes_after' => '',
                'fields' => apply_filters('comment_form_default_fields', array(
                    'author' =>
                    '<div class="charity-comment-fields"><div class="form-group"><label for="author">Name<span>*</span></label><input id="author" class="form-control"  name="author" type="text"  value="' . (esc_attr($commenter['comment_author'] ? $commenter['comment_author'] : '' )) . '" size="30"' . $aria_req . ' /></div>',
                    'email' =>
                    '<div class="form-group"><label for="email">Email<span>*</span></label><input id="email" class="form-control" name="email" type="text" value="' . esc_attr($commenter['comment_author_email'] ? $commenter['comment_author_email'] : '' ) . '" size="30"' . $aria_req . ' /></div>',
                    'url' =>
                    '<div class="form-group"><label for="url">Website</label>' .
                    '<input id="url" name="url" type="text" class="form-control" value="' . esc_attr($commenter['comment_author_url']) .
                    '" size="30" /></div></div>',
                        )
                ),
            );
            comment_form($args);
            ?>

        </div>

</div><!-- #comments .comments-area -->


