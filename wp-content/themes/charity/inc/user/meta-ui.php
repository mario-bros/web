<?php
/**
 * Charity - User meta ui 
 *
 * @package     charity.inc.metabox.user
 * @version     v.1.0
 */
$user_id = (isset($user->ID)) ? $user->ID : 0;
wp_nonce_field('cahrity_author_action', 'cahrity_author_nonce');
?><h3><?php esc_html_e("Profile Image", "charity"); ?></h3>
<table class="form-table">
    <tr>
        <th><label for="profile_image"><?php esc_html_e("Profile Image", "charity"); ?></label></th>
        <td>
            <div class="ad-iu-wrapper">
                <input type="text" class="ad-iu-url regular-text" name="charity_author_prifile[image]" value="<?php echo esc_attr(get_the_author_meta('image', $user_id)); ?>">
                <span class="ad-iu-btn">Upload</span>
                     <?php 
                        $imageURL = get_the_author_meta('image', $user_id);
                        $styleDispaly=(empty($imageURL)) ? 'none' : 'block';
                     ?>
                <div class="ad-iu-image-wrapper" style="display: <?php echo esc_attr($styleDispaly); ?>">
                    <img src="<?php echo esc_url($imageURL); ?>" class="ad-iu-image" height="200px"> <small class="ad-iu-remove"> X </small>
                </div>
            </div>
        </td>
    </tr>
</table>
<h3><?php esc_html_e("Social Profile", "charity"); ?></h3>
<table class="form-table">
    <tr>
        <th><label for="facebook_profile"><?php esc_html_e("Facebook", "charity"); ?></label></th>
        <td><input type="text" name="charity_author_prifile[facebook]" value="<?php echo esc_attr(get_the_author_meta('facebook', $user_id)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th><label for="twitter_profile"><?php esc_html_e("Twitter", "charity"); ?></label></th>
        <td><input type="text" name="charity_author_prifile[twitter]" value="<?php echo esc_attr(get_the_author_meta('twitter', $user_id)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th><label for="dribbble_profile"><?php esc_html_e("Dribbble", "charity"); ?></label></th>
        <td><input type="text" name="charity_author_prifile[dribbble]" value="<?php echo esc_attr(get_the_author_meta('dribbble', $user_id)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th><label for="pinterest_profile"><?php esc_html_e("Pinterest", "charity"); ?></label></th>
        <td><input type="text" name="charity_author_prifile[pinterest]" value="<?php echo esc_attr(get_the_author_meta('pinterest', $user_id)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th><label for="instagram_profile"><?php esc_html_e("Instagram", "charity"); ?></label></th>
        <td><input type="text" name="charity_author_prifile[instagram]" value="<?php echo esc_attr(get_the_author_meta('instagram', $user_id)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th><label for="google_plus_profile"><?php esc_html_e("Google plus", "charity"); ?></label></th>
        <td><input type="text" name="charity_author_prifile[google_plus]" value="<?php echo esc_attr(get_the_author_meta('google_plus', $user_id)); ?>" class="regular-text" /></td>
    </tr>    
</table>
<?php 
