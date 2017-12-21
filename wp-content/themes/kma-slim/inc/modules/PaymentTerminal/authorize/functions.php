<?php
/*
#******************************************************************************
#                      Authorize.net Payment Terminal Wordpress
#
#	Author: Convergine.com
#	http://www.convergine.com
#	Version: 1.3
#	Released: December 16, 2014
#
#******************************************************************************
*/

/* Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
 * This code has been released into the public domain, however please      *
 * give credit to the original author where possible.                      */
function luhn_check($number) {

  // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
  $number=preg_replace('/\D/', '', $number);

  // Set the string length and parity
  $number_length=strlen($number);
  $parity=$number_length % 2;

  // Loop through each digit and do the maths
  $total=0;
  for ($i=0; $i<$number_length; $i++) {
    $digit=$number[$i];
    // Multiply alternate digits by two
    if ($i % 2 == $parity) {
      $digit*=2;
      // If the sum is two digits, add them together (in effect)
      if ($digit > 9) {
        $digit-=9;
      }
    }
    // Total up the digits
    $total+=$digit;
  }

  // If the total mod 10 equals 0, the number is valid
  return ($total % 10 == 0) ? TRUE : FALSE;

}
function validateCC($cc_num, $type) {
$verified = false;
        if($type == "A") {
        $denum = "American Express";
        } elseif($type == "DI") {
        $denum = "Diner's Club";
        } elseif($type == "D") {
        $denum = "Discover";
        } elseif($type == "M") {
        $denum = "Master Card";
        } elseif($type == "V") {
        $denum = "Visa";
        }

        if($type == "A") {
        $pattern = "/^([34|37]{2})([0-9]{13})$/";//American Express
        if (preg_match($pattern,$cc_num)) {
        $verified = true;
        } else {
        $verified = false;
        }


        } elseif($type == "DI") {
        $pattern = "/^([30|36|38]{2})([0-9]{12})$/";//Diner's Club
        if (preg_match($pattern,$cc_num)) {
        $verified = true;
        } else {
        $verified = false;
        }


        } elseif($type == "D") {
        $pattern = "/^([6011]{4})([0-9]{12})$/";//Discover Card
        if (preg_match($pattern,$cc_num)) {
        $verified = true;
        } else {
        $verified = false;
        }


        } elseif($type == "M") {
        $pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";//Mastercard
        if (preg_match($pattern,$cc_num)) {
        $verified = true;
        } else {
        $verified = false;
        }


        } elseif($type == "V") {
        $pattern = "/^([4]{1})([0-9]{12,15})$/";//Visa
        if (preg_match($pattern,$cc_num)) {
        $verified = true;
        } else {
        $verified = false;
        }

        }

        return $verified;
}

function getActualYears(){
    $html = "";
    for($i=date("Y");$i<date("Y", strtotime(date("Y")." +10 years"));$i++){
        $html .= '<option value="'.$i.'">'.$i.'</option>';
    }
    return $html;
}
/**
 * Replaces all but the last for digits with x's in the given credit card number
 * @param int|string $cc The credit card number to mask
 * @return string The masked credit card number
 */
function MaskCreditCard($cc){
	// Get the cc Length
	$cc_length = strlen($cc);
	// Replace all characters of credit card except the last four and dashes
	for($i=0; $i<$cc_length-4; $i++){
		if($cc[$i] == '-'){continue;}
		$cc[$i] = 'X';
	}
	// Return the masked Credit Card #
	return $cc;
}
/**
 * Add dashes to a credit card number.
 * @param int|string $cc The credit card number to format with dashes.
 * @return string The credit card with dashes.
 */
function FormatCreditCard($cc)
{
	// Clean out extra data that might be in the cc
	$cc = str_replace(array('-',' '),'',$cc);
	// Get the CC Length
	$cc_length = strlen($cc);
	// Initialize the new credit card to contian the last four digits
	$newCreditCard = substr($cc,-4);
	// Walk backwards through the credit card number and add a dash after every fourth digit
	for($i=$cc_length-5;$i>=0;$i--){
		// If on the fourth character add a dash
		if((($i+1)-$cc_length)%4 == 0){
			$newCreditCard = '-'.$newCreditCard;
		}
		// Add the current character to the new credit card
		$newCreditCard = $cc[$i].$newCreditCard;
	}
	// Return the formatted credit card number
	return $newCreditCard;
}

function getDurationPaypal($firstDataRVar){
    switch($firstDataRVar){
        case "Day":
            return "D";
        break;
        case "Week":
            return "W";
        break;
        case "Month":
            return "M";
        break;
        case "Year":
            return "Y";
        break;

    }
}

function get_arb_interval($billing,$interval){
    //"Day", "Week", "SemiMonth", "Month", "Year"
    $returnArr = array();
    switch($billing){
        case "Day":
            $returnArr[0] = "month";
            $returnArr[1] = $interval*0.03;
        break;
        case "Week":
            $returnArr[0] = "moth";
            $returnArr[1] = $interval*7*0.03;
        break;
        case "Month":
            $returnArr[0] = "month";
            $returnArr[1] = $interval;
        break;
        case "Year":
            $returnArr[0] = "year";
            $returnArr[1] = $interval;
        break;
    }
    return $returnArr;
}