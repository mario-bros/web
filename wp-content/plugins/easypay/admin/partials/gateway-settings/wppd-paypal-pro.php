<?php

/**
 * The PayPal Pro settings tab for the plugin.
 *
 * 
 * @since      2.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

if (isset($_POST['ccard']) && $this->wppd_update_gateway_settings($_POST)):
    ?>
    <div id="message" class="updated below-h2"><p><?php _e('Settings updated successfully.', 'easypay') ?></p></div>      
<?php elseif (isset($_POST['ccard'])): ?>
    <div id="message" class="error below-h2"><p><?php _e("There's nothing to update.", 'easypay') ?></p></div>
<?php endif; ?>
<div class="postbox">
    <?php
    // Settings
    $wppd_options = get_option('easypay_options');
    $pro_options = isset($wppd_options['pro_settings']) ? $wppd_options['pro_settings'] : array();
    $easypay_pro_enable = isset($pro_options['easypay_pro_enable']) ? stripslashes($pro_options['easypay_pro_enable']) : '';
    $easypay_pro_email = isset($pro_options['easypay_pro_email']) ? stripslashes($pro_options['easypay_pro_email']) : '';
    $easypay_pro_description = isset($pro_options['easypay_pro_description']) ? stripslashes($pro_options['easypay_pro_description']) : '';
    $easypay_pro_user = isset($pro_options['easypay_pro_user']) ? stripslashes($pro_options['easypay_pro_user']) : '';
    $easypay_pro_password = isset($pro_options['easypay_pro_password']) ? stripslashes($pro_options['easypay_pro_password']) : '';
    $easypay_pro_signature = isset($pro_options['easypay_pro_signature']) ? stripslashes($pro_options['easypay_pro_signature']) : '';
    $easypay_pro_mode = isset($pro_options['easypay_pro_mode']) ? $pro_options['easypay_pro_mode'] : '';
    $easypay_pro_fee = isset($pro_options['easypay_pro_fee']) ? stripslashes($pro_options['easypay_pro_fee']) : '';
    ?>
    <div class="inside">
        <h4><?php _e('Paypal Payments Pro', 'easypay'); ?></h4>
        <form action="" method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <td><?php _e('Enable', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_pro_enable"
                                   value="true" <?php echo $easypay_pro_enable?'checked="checked"':''; ?>>
                        </td>
                    </tr>              
                    <tr>
                        <td><?php _e('Email', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_pro_email"
                                   value="<?php echo $easypay_pro_email; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Description', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_pro_description"
                                   value="<?php echo $easypay_pro_description; ?>"><br> <small><?php _e('The text that people see when they choose PayPal Pro for payments.', 'easypay'); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('This is your PayPal Pro email address.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('User', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_pro_user"
                                   value="<?php echo $easypay_pro_user; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1"><span class="wpscsmall description"><?php _e('The username for your Pro account.', 'easypay'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Password', 'easypay'); ?></td>
                        <td><input type="text" size="40" name="easypay_pro_password"
                                   value="<?php echo $easypay_pro_password; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1"><span class="wpscsmall description"><?php _e('This is your pro account password.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Signature', 'easypay'); ?></td>
                        <td><input type="text" size="40" name="easypay_pro_signature"
                                   value="<?php echo $easypay_pro_signature; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1"><span class="wpscsmall description"><?php _e('This is your pro account signature.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Mode:', 'easypay'); ?></td>
                        <td><select name="easypay_pro_mode">
                                <option value="<?php echo esc_url('https://api-3t.paypal.com/nvp'); ?>"
                                <?php if ($easypay_pro_mode === 'https://api-3t.paypal.com/nvp') { ?>
                                            selected="selected" <?php } ?>><?php _e('Live', 'easypay'); ?></option>
                                <option value="<?php echo esc_url('https://api-3t.sandbox.paypal.com/nvp'); ?>"
                                <?php if ($easypay_pro_mode === 'https://api-3t.sandbox.paypal.com/nvp') { ?>
                                            selected="selected" <?php } ?>><?php _e('Sandbox', 'easypay'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Use Sandbox mode for testing purposes, if you just have a PayPal Pro account then you will want to use Live mode.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Paypal Fee:', 'easypay'); ?></td>
                        <td><input type="text" size="4" value="<?php echo $easypay_pro_fee; ?>" maxlength="6" name="easypay_pro_fee" /> <b>%</b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1"><span class="wpscsmall description"><?php _e('This is your PayPal tax at total amount.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr class="update_gateway">
                        <td colspan="2">
                            <div class="submit">
                                <input type="submit" class="button button-primary button-hero" value="<?php _e('Update Settings', 'easypay'); ?>" name="ccard">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
