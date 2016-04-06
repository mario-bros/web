<?php

/**
 * Charity donation payment function
 *
 * @package  charity
 * @version  v.1.0
 */
class CharityDonationPayment {

    public function __construct() {
        $this->action();
    }

    public function action() {
        add_action("charity_easy_pay_ipn", array(&$this, 'donationPaymentUpdate'));
    }

    public function donationPaymentUpdate($post) {
        if (!empty($_REQUEST['item_id'])) {

			$donationValue = get_post_meta($_REQUEST['item_id'], 'doantion-settings');
			$ttt = $donationValue[0]['donation-achivement'] = $donationValue[0]['donation-achivement'] + $_REQUEST['ramount'];
			update_post_meta($_REQUEST['item_id'], 'doantion-settings', $donationValue[0]);
			

        } else {
           
            //wp_mail('ashwaney@sparxitsolutions.com', "Donation Payment Error","This file full path and file name is". __FILE__ .".\n This is line number".__LINE__ );
        }
    }

}

new CharityDonationPayment();
