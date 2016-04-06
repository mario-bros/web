<?php

/**
 * The PayPal Standard settings tab for the plugin.
 *
 * 
 * @since      2.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

if (isset($_POST['stripe']) && $this->wppd_update_gateway_settings($_POST)):
    ?>
    <div id="message" class="updated below-h2"><p><?php _e('Settings updated successfully.', 'easypay') ?></p></div>      
<?php elseif (isset($_POST['stripe'])): ?>
    <div id="message" class="error below-h2"><p><?php _e("There's nothing to update.", 'easypay') ?></p></div>
<?php endif; ?>
<div class="postbox">
    <?php
    // Settings
    $easypay_stripe_setting = get_option('easypay_stripe_setting');
    $easypay_strip_enable = isset($easypay_stripe_setting['easypay_strip_enable']) ? $easypay_stripe_setting['easypay_strip_enable'] : array();
    $easypay_strip_description = isset($easypay_stripe_setting['easypay_strip_description']) ? stripslashes($easypay_stripe_setting['easypay_strip_description']) : '';
    $easypay_strip_title = isset($easypay_stripe_setting['easypay_strip_title']) ? stripslashes($easypay_stripe_setting['easypay_strip_title']) : '';
    $easypay_private_key = isset($easypay_stripe_setting['easypay_private_key']) ? stripslashes($easypay_stripe_setting['easypay_private_key']) : '';
    $easypay_secret_key = isset($easypay_stripe_setting['easypay_secret_key']) ? stripslashes($easypay_stripe_setting['easypay_secret_key']) : '';
    $easypay_strip_verifySSL = isset($easypay_stripe_setting['easypay_strip_verifySSL']) ? stripslashes($easypay_stripe_setting['easypay_strip_verifySSL']) : '';
    $easypay_strip_pay_mod = isset($easypay_stripe_setting['easypay_strip_pay_mod']) ? stripslashes($easypay_stripe_setting['easypay_strip_pay_mod']) : '';
    ?>
    <div class="inside">
        <h4><?php _e('Stripe Payments', 'easypay'); ?></h4>
        <form action="" method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <td><?php _e('Enable', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_strip_enable"
                                   value="true" <?php echo $easypay_strip_enable ? 'checked="checked"' : ''; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Description', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_strip_description" value="<?php echo $easypay_strip_description; ?>" ><br> <small><?php _e('The text that people see when they choose Stripe for payments.', 'easypay'); ?></small>
                        </td>
                    </tr>                   
                    <tr>
                        <td><?php _e('Title', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_strip_title"
                                   value="<?php echo $easypay_strip_title; ?>"><br> <small><?php _e('This controls the title which the user sees during checkout.', 'easypay'); ?></small>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Private Key:', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_private_key"
                                   value="<?php echo $easypay_private_key; ?>"><br> <small><?php _e('This controls the title which the user sees during checkout.', 'easypay'); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Secret Key:', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_secret_key"
                                   value="<?php echo $easypay_secret_key; ?>"><br> <small><?php _e('This controls the title which the user sees during checkout.', 'easypay'); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('stripe verifySSL', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_strip_verifySSL"
                                   value="true" <?php echo $easypay_strip_verifySSL ? 'checked="checked"' : ''; ?>><br> <small><?php _e('Set SSL verification turn on if you need.', 'easypay'); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Account Type:', 'easypay'); ?></td>
                        <td><select name="easypay_strip_pay_mod">
                                <option value="<?php echo esc_url('https://www.paypal.com/cgi-bin/webscr'); ?>"
                                <?php if ($easypay_strip_pay_mod === 'https://www.paypal.com/cgi-bin/webscr') { ?>
                                            selected="selected" <?php } ?>><?php _e('Live Account', 'easypay'); ?></option>
                                <option value="<?php echo esc_url('https://www.sandbox.paypal.com/cgi-bin/webscr'); ?>"
                                <?php if ($easypay_strip_pay_mod === 'https://www.sandbox.paypal.com/cgi-bin/webscr') { ?>
                                            selected="selected" <?php } ?>><?php _e('Sandbox Account', 'easypay'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Sandbox can be used to test payments.', 'easypay'); ?>  </span>
                        </td>
                    </tr>         
                    <tr class="update_gateway">
                        <td colspan="2">
                            <div class="submit">
                                <input type="submit" class="button button-primary button-hero" value="<?php _e('Update Settings', 'easypay'); ?>" name="stripe">
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </div>
</div>
