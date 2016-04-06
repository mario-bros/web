<?php
/**
 * Email Templates for the plugin
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/includes
 */

/**
 * Email Templates for the plugin
 *
 * This class defines all code necessary to handle emails to customer and administrator of the system.
 *
 * @since      1.0.0
 * @package    WPPD
 * @subpackage WPPD/includes
 * @author     theem'on <support@theemon.com>
 */
//include_once(plugin_dir_path( __FILE__ )."gateways/lib/Stripe/Charge.php");
//include_once(plugin_dir_path( __FILE__ )."gateways/lib/Stripe/Charge.php");

class WPPD_Email {

    public function wppd_customer_invoice() {
        $this->wppd_get_email_settings_form('wppd_customer_invoice');
    }

    public function wppd_customer_initiated() {
        $this->wppd_get_email_settings_form('wppd_customer_initiated');
    }

    public function wppd_admin_new_order() {
        $this->wppd_get_email_settings_form('wppd_admin_new_order');
    }

    public function wppd_get_email_settings_form($type) {
        ?>
        <form role="form" action="" method="post">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <input type="text" placeholder="<?php _e('Email Subject', 'easypay'); ?>" name="subject" size="30" value="<?php echo $this->wppd_get_email_subject($type); ?>" id="subject" autocomplete="off">
                            </div>
                        </div><!-- /titlediv -->
                        <div id="postdivrich" class="postarea wp-editor-expand">
                            <?php
                            $content = $this->wppd_get_email_template($type);
                            $editor_id = 'template';
                            $settings = array(
                                'media_buttons' => false,
                                'textarea_name' => 'template',
                                'textarea_rows' => 25,
                                'teeny' => true,
                                    //'quicktags' => false
                            );
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="submitdiv" class="postbox ">
                            <h3 class="hndle"><span><?php _e('Save Template', 'easypay') ?></span></h3>
                            <div class="inside">
                                <div class="submitbox" id="submitpost">



                                    <div id="major-publishing-actions">


                                        <div id="publishing-action">
                                            <input type="hidden" value="<?php echo $type; ?>" name="email-type">
                                            <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php _e('Save', 'easypay'); ?>">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="formatdiv" class="postbox ">
                            <h3 class="hndle"><span><?php _e('Available Placeholder', 'easypay'); ?></span></h3>
                            <div class="inside">
                                <p><strong>{{sitemane}} || </strong><span class="description"> <?php _e('General Settings > Site Title', 'easypay'); ?></span></p>
                                <p><strong>{{transaction-id}} || </strong><span class="description"> <?php _e('Gateway Transaction ID', 'easypay'); ?></span></p>
                                <p><strong>{{payment-amount}} || </strong><span class="description"> <?php _e('Amount paid by customer.', 'easypay'); ?></span></p>
                                <p><strong>{{payment-actual-amount}} || </strong><span class="description"> <?php _e('Payment including gateway fee.', 'easypay'); ?></span></p>
                                <p><strong>{{payment-fees-rate}} || </strong><span class="description"> <?php _e('Gateway fee rate.', 'easypay'); ?></span></p>
                                <p><strong>{{payment-currency}} || </strong><span class="description"> <?php _e('Currency used for payment.', 'easypay'); ?></span></p>
                                <p><strong>{{payment-gateway}} || </strong><span class="description"> <?php _e('Payment gateway used by customer.', 'easypay'); ?></span></p>
                                <p><strong>{{payment-status}} || </strong><span class="description"> <?php _e('Current status of the payment.', 'easypay'); ?></span></p>
                                <p><strong>{{createdtime}} || </strong><span class="description"> <?php _e('Time at which payment was initiated.', 'easypay'); ?></span></p>
                                <p><strong>{{customer-email}} || </strong><span class="description"> <?php _e('Email ID of the customer.', 'easypay'); ?></span></p>
                                <p><strong>{{optional-info}} || </strong><span class="description"> <?php _e('Data sent using the option form fields.', 'easypay'); ?></span></p>
                                <p><strong>{{transaction-info}} || </strong><span class="description"> <?php _e('Transaction data returned by payment gateway.', 'easypay'); ?></span></p>
                                <p><strong>{{retry-link}} || </strong><span class="description"> <?php _e('Link to retry the payment.', 'easypay'); ?></span></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <?php
    }

    public function wppd_update_email_settings($type, $template, $subject) {

        $email_updated = false;
        $subject_upadated = false;
        if (update_option($type, $template)) {
            $email_updated = true;
        }

        if (update_option($type . '_subject', stripslashes($subject))) {
            $email_updated = true;
        }

        if ($email_updated || $email_updated) {
            return true;
        }
    }

    public function wppd_get_email_template($type) {
        return stripslashes(get_option($type));
    }

    public function wppd_get_email_subject($type) {
        return get_option($type . '_subject');
    }

    public function wppd_send_email($to, $type, $transaction) {
		
        global $wppd_db;
        
        $payment_info = $wppd_db->wppd_get_payment_info($transaction);

        if ($to == 'admin') {
            $to_email = get_bloginfo('admin_email');
        } elseif ($to == 'customer') {
            $to_email = $payment_info['email'];
        }

        $subject = $this->wppd_get_email_subject($type);

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;

        // main header
        $headers = 'From: ' . get_bloginfo('name') . ' <no-reply@' . $_SERVER['HTTP_HOST'] . '>' . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";

        // no more headers after this, we start the body! //
        // prepare message content
        $message = $this->wppd_prepare_message($type, $transaction) . $eol;
        
        //$body = "--" . $separator . $eol;
        $body = "Content-Transfer-Encoding: 7bit" . $eol . $eol;

        // message
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $message . $eol;

        if ($type == 'wppd_customer_invoice') {
            // attachment solution form http://stackoverflow.com/questions/4353271/email-pdf-attachment-with-php-using-fpdf
            $filename = $payment_info['txnid'];
            $path = $this->wppd_prepare_pdf($message, $filename);
            $attachment = chunk_split(base64_encode(file_get_contents($path)));
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . ".pdf\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;
            $body .= $attachment . $eol;
            $body .= "--" . $separator . "--";
        }
		//wp_mail('ashwaney@sparxitsolutions.com', $subject, $body, $headers);
        wp_mail($to_email, $subject, $body, $headers);
    }
    
    //public $flag=true;

    public function wppd_prepare_message($type, $transaction) {
        global $wppd_db;
        global $wppd_gateway;


        $payment_info = $wppd_db->wppd_get_payment_info($transaction);

        $template = stripslashes(get_option($type));

        $custom = unserialize(stripslashes($payment_info['custom']));
        $custom_data = "<table>";

        $ccard_keys = array(
            'card_holder_name' => __('Card Holder Name', 'easypay'),
            'card_number' => __('Card Number', 'easypay'),
            'expiry_month' => __('Expiry Month', 'easypay'),
            'expiry_year' => __('Expiry Year', 'easypay'),
            'cvv' => __('CVV', 'easypay'),
            'gateway' => __('Gateway', 'easypay'),
        );

        foreach ($custom as $key => $value) {
            $value = is_array($value) ? implode(', ', $value) : $value;
            $label = ($key != 'currency_code') ? $this->wppd_field_label($key) : 'Currency';
            if (!$label && array_key_exists($key, $ccard_keys)) {
                $label = $ccard_keys[$key];
            }
            $custom_data .= '<tr><td><strong>' . $label . ':</strong></td> <td>' . $value . "</td></tr>";
        }
        $custom_data .= '</table>';
        //print_r($custom); exit;
        $transaction_data = '';
        $transaction_info = unserialize(stripslashes($payment_info['transaction_info']));
        if($payment_info['payment_gateway'] == 'Stripe'){
        	
        	$easypay_stripe_option = get_option('easypay_stripe_setting');
        	$stripe_sk_key = isset($easypay_stripe_option['easypay_secret_key']) ? $easypay_stripe_option['easypay_secret_key'] : 'sk_test_bajvh4G8hxAwfjOsJZG36uSt';
        	if(is_array($transaction_info) ):
        	//$this->flag=false;
            $transaction_data = "<table>";
        	foreach($transaction_info as $skey => $sval):
        		//if(!is_array($sval))	
        		$transaction_data .= '<tr><td><strong>' . ucwords(str_replace('_', ' ', $skey)) . ':</strong></td> <td>' . $sval . "</td></tr>";      	
              endforeach;
            $transaction_data .= "</table>";
              endif;
        }else{
        if ($transaction_info) {
            $transaction_data = "<table>";
            foreach ($transaction_info as $key => $value) {
                if ($key != 'address_status' && $key != 'charset' && $key != 'custom' && $key != 'verify_sign' && $key != 'txn_id' && $key != 'receiver_id' && $key != 'payer_id' && $key != 'quantity' && $key != 'item_number' && $key != 'test_ipn' && $key != 'transaction_subject' && $key != 'mc_gross' && $key != 'ipn_track_id') {
                    $transaction_data .= '<tr><td><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong></td> <td>' . $value . "</td></tr>";
                }
            }
            $transaction_data .= "</table>";
        }
    } 
        
        $easypay_options = get_option('easypay_options');
        $wppd_cancel_return = $easypay_options['easypay_retry_url'];

        $retry_link = add_query_arg(array('payment_id' => base64_encode($transaction), 'action_type' => 'retry'), $wppd_cancel_return);

        // Run str replace on template with actual values

        $template = str_replace('{{transaction-id}}', $payment_info['txnid'], $template);
        $template = str_replace('{{payment-amount}}', $payment_info['payment_amount'], $template);
        $template = str_replace('{{payment-actual-amount}}', $payment_info['payment_actual_amount'], $template);
        $template = str_replace('{{payment-fees-rate}}', $payment_info['payment_fees_rate'], $template);
        $template = str_replace('{{payment-currency}}', $payment_info['payment_currency'], $template);
        $template = str_replace('{{payment-gateway}}', $payment_info['payment_gateway'], $template);
        $template = str_replace('{{payment-status}}', $payment_info['payment_status'], $template);
        $template = str_replace('{{item}}', $payment_info['itemid'], $template);
        $template = str_replace('{{createdtime}}', $payment_info['createdtime'], $template);
        $template = str_replace('{{customer-email}}', $payment_info['email'], $template);
        $template = str_replace('{{optional-info}}', $custom_data, $template);
        $template = str_replace('{{transaction-info}}', $transaction_data, $template);
        $template = str_replace('{{sitename}}', get_bloginfo('name'), $template);
        if ($wppd_gateway->gateway_name == 'PayPal Payments Standard') {
            $template = str_replace('{{retry-link}}', $retry_link, $template);
        } elseif ($wppd_gateway->gateway_name == 'PayPal Pro') {
            $template = str_replace('{{retry-link}}', '', $template);
        } elseif ($wppd_gateway->gateway_name == 'Stripe') {
            $template = str_replace('{{retry-link}}', '', $template);
        }

        $message = '<body style="width:600px; background: #eaeaea; border: #ff4200 solid 2px; margin: 0 auto;"><div style="padding:10px; background: #fff;">' . nl2br($template) . '</div></body>';
        return $message;
    }

    /**
     * function to generate PDF to be attached with the email
     */
    public function wppd_prepare_pdf($message, $filename) {


        require_once(dirname(__FILE__) . '/html2pdf/html2pdf.class.php');


        $upload_dir = wp_upload_dir();

        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->WriteHTML(nl2br($message));
        $path = $upload_dir['basedir'] . '/easypay/' . $filename . '.pdf';
        $html2pdf->Output($path, 'F');

        return $path;
    }

    public function wppd_field_label($id) {
        $saved_form = get_option('easypay_form_builder');

        //echo $saved_form = str_replace('[', '', str_replace(']', '', $saved_form));
        $saved_form = json_decode($saved_form, true, 512);
        //print_r($saved_form);
        $field_id = '';
        if ($saved_form != null) {
            foreach ($saved_form as $field) {

                //apeend [] symbol for array type fields

                if ($field['title'] == 'Select Multiple' || $field['title'] == 'Multiple Checkboxes' || $field['title'] == 'Multiple Checkboxes Inline') {
                    $field_id = $id . '[]';
                } else {
                    $field_id = $id;
                }
                if (isset($field['fields']['id']) && $field['fields']['id']['value'] == $field_id) {
                    return $field['fields']['label']['value'];
                } elseif (isset($field['fields']['name']) && $field['fields']['name']['value'] == $field_id) {
                    return $field['fields']['label']['value'];
                }
            }
        }

        return false;
    }

}
