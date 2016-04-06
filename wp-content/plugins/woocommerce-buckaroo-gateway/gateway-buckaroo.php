<?php

class WC_Gateway_Buckaroo extends WC_Payment_Gateway {
    
        var $notify_url;
        var $transactiondescription;
        
    	function __construct() { 
		global $woocommerce;
        
                // Load the form fields
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
                
                // Get setting values
		//$this->enabled 		= $this->settings['enabled'];
		$this->title 		= $this->settings['title'];
		$this->description	= $this->settings['description'];
		$this->secretkey	= $this->settings['secretkey'];
		$this->mode		= $this->settings['mode'];
		$this->certificate	= $this->settings['certificate'];
                $this->thumbprint	= $this->settings['thumbprint'];
                $this->transactiondescription	= $this->settings['transactiondescription'];
                $this->culture          = $this->settings['culture'];
                $this->currency          = $this->settings['currency'];
		//$this->debug		= $this->settings['debug'];
                
                if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

                } else {
                        add_action( 'woocommerce_api_wc_gateway_buckaroo', array( $this, 'response_handler' ) );
                }
        }
        
        /**
     * Initialize Gateway Settings Form Fields
     */
        function init_form_fields() {
            $charset = strtolower(ini_get('default_charset'));
            $addDescription = '';
            if ($charset != 'utf-8') {
                $addDescription = '<fieldset style="border: 1px solid #ffac0e; padding: 10px;"><legend><b style="color: #ffac0e">Warning!</b></legend>default_charset is not set.<br>This might cause a problems on receiving push message.<br>Please set default_charset="UTF-8" in your php.ini and add AddDefaultCharset UTF-8 to .htaccess file.</fieldset>';
            }
            $this->form_fields = array(
			'enabled' => array(
							'title' => __( 'Enable/Disable', 'woocommerce' ), 
							'label' => __( 'Enable '.(isset($this->method_title) ? $this->method_title : '').' Payment Method', 'woocommerce' ), 
							'type' => 'checkbox', 
							'description' => $addDescription,
							'default' => 'no'
						), 
			'title' => array(
							'title' => __( 'Title' ), 
							'type' => 'text', 
							'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ), 
							'default' => __( $this->title, 'woocommerce' ),
							'css' => "width: 300px;"
						), 
			'description' => array(
							'title' => __( 'Description', 'woocommerce' ), 
							'type' => 'textarea', 
							'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ), 
							'default' => $this->description
						),  
			'merchantkey' => array(
							'title' => __( 'Merchant key', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __( 'This is your Buckaroo payment plaza Website key.', 'woocommerce' ), 
							'default' => ''
						), 
			'secretkey' => array(
							'title' => __( 'Secret key', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __( 'The Secret password to verify transactions', 'woocommerce' ), 
							'default' => ''
						),
			'mode' => array(
							'title' => __( 'Transaction Mode', 'woocommerce' ), 
							'type' => 'select', 
							'description' => __( 'Transaction mode used for processing orders', 'woocommerce' ), 
							'options' => array('live'=>'Live', 'test'=>'Test'),
							'default' => 'test'
						),
            		'certificate' => array(
							'title' => __( 'Certificate', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __( 'Please enter certificate name. Certificate should be uploaded to "'.dirname(__FILE__).'/library/certificate" directory.', 'woocommerce' ), 
							'default' => 'BuckarooPrivateKey.pem'
						),
                       	'thumbprint' => array(
							'title' => __( 'Certificate thumbprint', 'woocommerce' ), 
							'type' => 'text', 
							'description' => __( 'Certificate thumbprint', 'woocommerce' ), 
							'default' => ''
						),
                         'transactiondescription' => array(
							'title' => __( 'Transaction description', 'woocommerce' ), 
							'type' => 'textarea',
							'description' => __( 'Transaction description', 'woocommerce' ), 
							'default' => ''
						),        
                         'culture' => array(
							'title' => __( 'Language', 'woocommerce' ), 
							'type' => 'select', 
							'description' => __( 'Buckaroo Payment Engine culture', 'woocommerce' ), 
							'options' => array('en-US'=>'English', 'nl-NL'=>'Dutch', 'fr-FR'=> 'French', 'de-DE'=> 'German'),
							'default' => 'nl-NL'),
                         'currency' => array(
							'title' => __( 'Currency', 'woocommerce' ), 
							'type' => 'select', 
							'description' => __( 'Currency', 'woocommerce' ), 
							'options' => array('EUR'=>'Euro', 'USD'=>'USD'),
							'default' => 'EUR'),
			/*'debug' => array(
						'title' => __( 'Debug', 'woocommerce' ), 
						'type' => 'checkbox', 
						'label' => __( 'Enable logging (<code>woocommerce/logs/totalweb.txt</code>)', 'woocommerce' ), 
						'default' => 'no'
					)*/
			);
        }

        public function response_handler() {
		global $woocommerce;
                fn_buckaroo_process_response($this); 
                exit;
        }
        
        /**
        * Payment form on checkout page
        */
	function payment_fields() {
        ?>
                        <?php if ($this->mode=='test') : ?><p><?php _e('TEST MODE', 'woocommerce'); ?></p><?php endif; ?>
                        <?php if ($this->description) : ?><p><?php echo wpautop(wptexturize($this->description)); ?></p><?php endif; ?>
        <?php
	}
        
        function get_failed_url( ) {
            $thanks_page_id = woocommerce_get_page_id('checkout');
            if ( $thanks_page_id ) :
                    $return_url = get_permalink($thanks_page_id);
            else :
                    $return_url = home_url();
            endif;
            if ( is_ssl() || get_option('woocommerce_force_ssl_checkout') == 'yes' )
			$return_url = str_replace( 'http:', 'https:', $return_url );

            return apply_filters( 'woocommerce_get_return_url', $return_url );
        }
        
        function getInitials($str) {
            $ret = '';
            foreach (explode(' ', $str) as $word)
                $ret .= strtoupper($word[0]).'.';
            return $ret;
        }
        
         public static function cleanup_phone($phone) {
            $phone = preg_replace('/[^0-9]/', '', $phone);

            if (substr($phone, 0, 3) == '316' || substr($phone, 0, 5) == '00316' || substr($phone, 0, 6) == '003106' || substr($phone, 0, 2) == '06') {
                if (substr($phone, 0, 6) == '003106') {
                    $phone = substr_replace($phone, '00316', 0, 6);
                }
                $response = array('type' => 'mobile', 'phone' => $phone);
            } else {
                $response = array('type' => 'landline', 'phone' => $phone);
            }

            return $response;
        }
        
        function validateDate($date, $format = 'Y-m-d H:i:s')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
}
