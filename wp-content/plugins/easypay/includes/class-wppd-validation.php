<?php

/**
 * The validations functionality of the plugin.
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

/**
 * The validations functionality of the plugin.
 *
 * @package    WPPD
 * @subpackage WPPD/public
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Validations {

    /**
     * If an email is Valid it returns the parameter
     * other wise it will return false
     * $email is the email address
     * */
    function is_email($email) {

        //email is not case sensitive make it lower case
        $email = strtolower($email);

        //check if email seems valid
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
            return $email;
        }

        return false;
    }

    /**
     * Checks if there are 7 or 10 numbers, if so returns cleaned parameter(no formating just numbers)
     * other wise it will return false
     * $phone is the phone number
     * $ext if set to true return an array with extension separated
     * */
    function isPhone($phone, $ext = false) {

        //remove everything but numbers
        $numbers = preg_replace("%[^0-9]%", "", $phone);

        //how many numbers are supplied
        $length = strlen($numbers);

        if ($length == 10 || $length == 7) { //Everything is find and dandy
            $cleanPhone = $numbers;

            if ($ext) {
                $clean['phone'] = $cleanPhone;
                return $clean;
            } else {
                return $cleanPhone;
            }
        } elseif ($length > 10) { //must be extension
            //checks if first number is 1 (this may be a bug for you)
            if (substr($numbers, 0, 1) == 1) {
                $clean['phone'] = substr($numbers, 0, 11);
                $clean['extension'] = substr($numbers, 11);
            } else {
                $clean['phone'] = substr($numbers, 0, 10);
                $clean['extension'] = substr($numbers, 10);
            }

            if (!$ext) { //return string
                if (!empty($clean['extension'])) {
                    $clean = implode("x", $clean);
                } else {
                    $clean = $clean['phone'];
                }

                return $clean;
            } else { //return array
                return $clean;
            }
        }

        return false;
    }

    /**
     * Canadian Postal code
     * thanks to: http://roshanbh.com.np/2008/03/canda-postal-code-validation-php.html
     * */
    function isPostalCode($postal) {
        $regex = "/^([a-ceghj-npr-tv-z]){1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}$/i";

        //remove spaces
        $postal = str_replace(' ', '', $postal);

        if (preg_match($regex, $postal)) {
            return $postal;
        } else {
            return false;
        }
    }

    /**
     * Checks for a 5 digit zip code
     * Clears extra characters
     * returns clean zip
     * */
    function isZipCode($zip) {
        //remove everything but numbers
        $numbers = preg_replace("[^0-9]", "", $zip);

        //how many numbers are supplied
        $length = strlen($numbers);

        if ($length != 5) {
            return false;
        } else {
            return $numbers;
        }
    }

    /**
     * adding credit card cvv length check from http://community.developer.authorize.net/t5/The-Authorize-Net-Developer-Blog/Validating-Credit-Card-Information-Part-3-of-3-CVV-Numbers/ba-p/7657
     */
    function validateCVV($cardNumber, $cvv) {
        // Get the first number of the credit card so we know how many digits to look for
        $firstnumber = (int) substr($cardNumber, 0, 1);
        if ($firstnumber === 3) {
            if (!preg_match("/^\d{4}$/", $cvv)) {
                // The credit card is an American Express card but does not have a four digit CVV code
                return false;
            }
        } else if (!preg_match("/^\d{3}$/", $cvv)) {
            // The credit card is a Visa, MasterCard, or Discover Card card but does not have a three digit CVV code
            return false;
        }
        return true;
    }

    /**
     * validating credit card based on http://en.wikipedia.org/wiki/Luhn_algorithm
     */
    function validateCCard($card_number) {

        $card_number = preg_replace('/[^\d]/', '', $card_number);

        $card_length = strlen($card_number);

        $is_even = false;
        $total = $digit = 0;
        
        if ($card_length < 13 || $card_length > 19) {
            return false;
        }

        for ($i = $card_length - 1; $i >= 0; $i--) {
            $char = substr($card_number, $i, 1);
            $digit = intval($char, 10);
            if ($is_even) {
                if (( $digit *= 2 ) > 9) {
                    $digit -= 9;
                }
            }
            $total += $digit;
            $is_even = !$is_even;
        }
        
        return ( $total % 10 ) === 0;
    }

}