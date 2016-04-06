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

if (isset($_POST['paypal']) && $this->wppd_update_gateway_settings($_POST)):
    ?>
    <div id="message" class="updated below-h2"><p><?php _e('Settings updated successfully.', 'easypay') ?></p></div>      
<?php elseif (isset($_POST['paypal'])): ?>
    <div id="message" class="error below-h2"><p><?php _e("There's nothing to update.", 'easypay') ?></p></div>
<?php endif; ?>
<div class="postbox">
    <?php
    // Settings
    $wppd_options = get_option('easypay_options');

    $easypay_paypal_enable = isset($wppd_options['easypay_paypal_enable']) ? $wppd_options['easypay_paypal_enable'] : '';
    $easypay_paypal_description = isset($wppd_options['easypay_paypal_description']) ? $wppd_options['easypay_paypal_description'] : '';
    $easypay_business = isset($wppd_options['easypay_business']) ? $wppd_options['easypay_business'] : '';
    $easypay_pay_mod = isset($wppd_options['easypay_pay_mod']) ? $wppd_options['easypay_pay_mod'] : '';
    $easypay_paypal_fee = isset($wppd_options['easypay_paypal_fee']) ? $wppd_options['easypay_paypal_fee'] : '';
    $easypay_amt_size = isset($wppd_options['easypay_amt_field_size']) ? $wppd_options['easypay_amt_field_size'] : '';
    $easypay_email_size = isset($wppd_options['easypay_email_field_size']) ? $wppd_options['easypay_email_field_size'] : '';
    ?>
    <div class="inside">
        <h4><?php _e('Paypal Payments Standard', 'easypay'); ?></h4>
        <form action="" method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <td><?php _e('Enable', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_paypal_enable"
                                   value="true" <?php echo $easypay_paypal_enable ? 'checked="checked"' : ''; ?>>
                        </td>
                    </tr>
                                       
                    <tr>
                        <td><?php _e('Description', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_paypal_description"
                                   value="<?php echo $easypay_paypal_description; ?>"><br> <small><?php _e('The text that people see when they choose PayPal for payments.', 'easypay'); ?></small>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Username:', 'easypay'); ?></td>
                        <td><input type="text" size="40" name="easypay_business"
                                   value="<?php echo $easypay_business; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1"><span class="wpscsmall description"><?php _e('This is your PayPal email address.', 'easypay'); ?>  </span>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Account Type:', 'easypay'); ?></td>
                        <td><select name="easypay_pay_mod">
                                <option value="<?php echo esc_url('https://www.paypal.com/cgi-bin/webscr'); ?>"
                                <?php if ($easypay_pay_mod === 'https://www.paypal.com/cgi-bin/webscr') { ?>
                                            selected="selected" <?php } ?>><?php _e('Live Account', 'easypay'); ?></option>
                                <option value="<?php echo esc_url('https://www.sandbox.paypal.com/cgi-bin/webscr'); ?>"
                                <?php if ($easypay_pay_mod === 'https://www.sandbox.paypal.com/cgi-bin/webscr') { ?>
                                            selected="selected" <?php } ?>><?php _e('Sandbox Account', 'easypay'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Use Sandbox mode for testing purposes, if you just have a standard PayPal account then you will want to use Live mode.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Paypal Fee:', 'easypay'); ?></td>
                        <td><input type="text" size="4" value="<?php echo $easypay_paypal_fee; ?>" maxlength="6" name="easypay_paypal_fee" /> <b>%</b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1"><span class="wpscsmall description"><?php _e('This is your PayPal tax at total amount.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr class="update_gateway">
                        <td colspan="2">
                            <div class="submit">
                                <input type="submit" class="button button-primary button-hero" value="<?php _e('Update Settings', 'easypay'); ?>" name="paypal">
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </div>
</div>
