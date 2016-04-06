<?php

/**
 * The general settings tab for the plugin.
 *
 * 
 * @since      2.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

if (isset($_POST['general']) && $this->wppd_update_gateway_settings($_POST)):
    ?>
    <div id="message" class="updated below-h2"><p><?php _e('Settings updated successfully.', 'easypay') ?></p></div>      
<?php elseif (isset($_POST['general'])): ?>
    <div id="message" class="error below-h2"><p><?php _e("There's nothing to update.", 'easypay') ?></p></div>
<?php endif; ?>
<div class="postbox">
    <?php
    // Settings
    $wppd_options = get_option('easypay_options');
    $easypay_user_defined_name = isset($wppd_options['easypay_user_defined_name']) ? $wppd_options['easypay_user_defined_name'] : '';
    $easypay_recurring_enable = isset($wppd_options['easypay_recurring_enable']) ? $wppd_options['easypay_recurring_enable'] : '';
    $easypay_success_url = isset($wppd_options['easypay_success_url']) ? $wppd_options['easypay_success_url'] : '';
    $easypay_fail_url = isset($wppd_options['easypay_fail_url']) ? $wppd_options['easypay_fail_url'] : '';
    $easypay_retry_url = isset($wppd_options['easypay_retry_url']) ? $wppd_options['easypay_retry_url'] : '';
    $easypay_amt_size = isset($wppd_options['easypay_amt_field_size']) ? $wppd_options['easypay_amt_field_size'] : '';
    $easypay_email_size = isset($wppd_options['easypay_email_field_size']) ? $wppd_options['easypay_email_field_size'] : '';
    $easypay_allow_currency = isset($wppd_options['easypay_allow_currency']) ? $wppd_options['easypay_allow_currency'] : '';
    $easypay_pay_currency = isset($wppd_options['easypay_pay_currency']) ? $wppd_options['easypay_pay_currency'] : '';
    $easypay_disable_custom_amount = isset($wppd_options['easypay_disable_custom_amount']) ? $wppd_options['easypay_disable_custom_amount'] : '';
    $easypay_st_amount_enable = isset($wppd_options['easypay_st_amount_enable']) ? $wppd_options['easypay_st_amount_enable'] : '';
    $easypay_st_amounts = isset($wppd_options['easypay_st_amounts']) ? array_filter((array) $wppd_options['easypay_st_amounts']) : '';
    ?>
    <div class="inside">
        <h4><?php _e('General Settings', 'easypay'); ?></h4>
        <form action="" method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <td><?php _e('Item Display Name', 'easypay'); ?></td>
                        <td><input type="text" name="easypay_user_defined_name"
                                   value="<?php echo $easypay_user_defined_name; ?>"><br> <small><?php _e('The text that people see when making a purchase as a item name.', 'easypay'); ?></small>
                        </td>
                    </tr>
                     <tr>
                        <td><?php _e('Enable Recurring Payment', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_recurring_enable"
                                   value="true" <?php echo $easypay_recurring_enable?'checked="checked"':''; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Payment Success Page:', 'easypay'); ?></td>
                        <td>
                            <select name="easypay_success">
                                <?php echo $this->wppd_get_pages_option($easypay_success_url); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select page to redirect users on successful payments.', 'easypay'); ?>  </span>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Payment Error Page:', 'easypay'); ?></td>
                        <td>

                            <select name="easypay_failed">
                                <?php echo $this->wppd_get_pages_option($easypay_fail_url); ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select page to redirect users for failed payments.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Payment Retry Page:', 'easypay'); ?></td>
                        <td>

                            <select name="easypay_retry">
                                <?php echo $this->wppd_get_pages_option($easypay_retry_url); ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select page to redirect users for retrying payments.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <?php
                    $input_sizes = array(
                        "col-md-1" => "Mini",
                        "col-md-2" => "Small",
                        "col-md-4" => "Medium",
                        "col-md-5" => "Large",
                        "col-md-6" => "Xlarge",
                        "col-md-8" => "Xxlarge"
                    );
                    ?>
                    <tr>
                        <td><?php _e('Amount field size:', 'easypay'); ?></td>
                        <td>
                            <select name="easypay_amt_size">
                                <?php foreach ($input_sizes as $key => $val) : ?>
                                    <?php if ($easypay_amt_size == $key): ?>
                                        <option value="<?php echo $key; ?>" selected="selected" ><?php echo $val; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $key; ?>" ><?php echo $val; ?></option>
                                    <?php endif; ?>    
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select a size for amount field of the form.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Email field size:', 'easypay'); ?></td>
                        <td>
                            <select name="easypay_email_size">
                                <?php foreach ($input_sizes as $key => $val) : ?>
                                    <?php if ($easypay_email_size == $key): ?>
                                        <option value="<?php echo $key; ?>" selected="selected" ><?php echo $val; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $key; ?>" ><?php echo $val; ?></option>
                                    <?php endif; ?>    
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select a size for email field of the form.', 'easypay'); ?>  </span>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Allow Users to Choose Currency', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_allow_currency"
                                   value="true" <?php echo $easypay_allow_currency ? 'checked="checked"' : ''; ?>>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Payment Currency:', 'easypay'); ?></td>
                        <td><select name="easypay_pay_currency">
                                <?php foreach ($this->wppd_currency as $key => $val) : ?>
                                    <?php if ($easypay_pay_currency == $key): ?>
                                        <option value="<?php echo $key; ?>" selected="selected" ><?php echo $val; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $key; ?>" ><?php echo $val; ?></option>
                                    <?php endif; ?>    
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Disable Custom Amounts', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_disable_custom_amount"
                                   value="true" <?php echo $easypay_disable_custom_amount ? 'checked="checked"' : ''; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Enable Amount Choices', 'easypay'); ?></td>
                        <td><input type="checkbox" name="easypay_st_amount_enable"
                                   value="true" <?php echo $easypay_st_amount_enable ? 'checked="checked"' : ''; ?>>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Amount Choices', 'easypay'); ?></td>
                        <td>
                            <div id="wppd_amt_holder">
                                <?php
                                foreach ((array) $easypay_st_amounts as $amount) {
                                    ?>
                                    <div class="elements"><input type="text" value="<?php echo $amount; ?>" name="easypay_st_amounts[]"><input type="button" onclick="removeAmountField(this);" value="Remove"></div>
                                <?php } ?>

                            </div>
                            <a href="javascript:;" onclick="new crerateAmountField();" class="button">Add Amount</a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Enable amount choices for the users, please enter numeric values only.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr class="update_gateway">
                        <td colspan="2">
                            <div class="submit">
                                <input type="submit" class="button button-primary button-hero" value="<?php _e('Update Settings', 'easypay'); ?>" name="general">
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </div>
</div>
