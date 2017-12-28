<?php

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

define("AUTHORIZENET_LOG_FILE","phplog");

class PaymentTerminalProcess
{
    protected $pluginDir;

    public $anpt_captcha_site;
    public $anpt_enable_captcha;
    protected $anpt_captcha_key;
    public $serviceID;
    public $anpt_display_comment;
    public $anpt_show_amount_text;
    public $anpt_ty_title;
    public $anpt_ty_text;
    public $anpt_show_dd_text;
    public $show_services;
    protected $errors;

    public function __construct()
    {
        $this->pluginDir = dirname(dirname(__FILE__));
        require_once($this->pluginDir . '/authorize/vendor/autoload.php');
        $this->errors = [];

        //grab settings from WP
        $this->anpt_enable_captcha = get_option('anpt_enable_captcha');
        $this->anpt_captcha_key    = get_option('anpt_captcha_key');
        $this->anpt_captcha_site   = get_option('anpt_captcha_site');
        $this->anpt_ty_title       = get_option('anpt_ty_title');
        $this->anpt_ty_text        = get_option('anpt_ty_text');
        $this->anpt_show_dd_text   = get_option('anpt_show_dd_text');

        //configurations controlled by url params
        $this->anpt_display_comment  = isset($_GET['comment']) && $_GET['comment'] == 'true' ? 1 : 2;
        $this->anpt_show_amount_text = isset($_GET['serv']) ? $_GET['serv'] : 1;
        $this->serviceID             = isset($_GET['serviceID']) ? $_GET["serviceID"] : '';
        $this->show_services         = isset($_GET['serv']) && $_GET['serv'] == 'true' ? 1 : 0;

        //$this->formSubmit(); //listen for form submit
    }

    public function getServices()
    {
        global $wpdb;
        $where  = empty($this->serviceID) ? "" : "WHERE anpt_services_id='" . $this->serviceID . "'";
        $query  = "SELECT * FROM " . $wpdb->prefix . "anpt_services " . $where . " ORDER BY anpt_services_title";
        $result = $wpdb->get_results($query);

        return $result;
    }

    public function formSubmit()
    {
        if (isset($_POST["process"]) && $_POST["process"] == 'yes') {
            if ($this->validateSubmission()) {
                $this->processCC();
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    protected function validateSubmission()
    {
        return $this->validateContent() && $this->validateCaptcha();
    }

    protected function validateContent()
    {
        $valid = false;

        $item_description = ( ! empty($_REQUEST["item_description"])) ? strip_tags(str_replace("'", "`", $_REQUEST["item_description"])) : '';
        $amount           = ( ! empty($_REQUEST["amount"])) ? strip_tags(str_replace("'", "`", $_REQUEST["amount"])) : '';
        $invoicenum       = ( ! empty($_REQUEST["invoicenum"])) ? strip_tags(str_replace("'", "`", $_REQUEST["invoicenum"])) : '';
        $fname            = ( ! empty($_REQUEST["fname"])) ? strip_tags(str_replace("'", "`", $_REQUEST["fname"])) : '';
        $lname            = ( ! empty($_REQUEST["lname"])) ? strip_tags(str_replace("'", "`", $_REQUEST["lname"])) : '';
        $email            = ( ! empty($_REQUEST["email"])) ? strip_tags(str_replace("'", "`", $_REQUEST["email"])) : '';
        $address          = ( ! empty($_REQUEST["address"])) ? strip_tags(str_replace("'", "`", $_REQUEST["address"])) : '';
        $city             = ( ! empty($_REQUEST["city"])) ? strip_tags(str_replace("'", "`", $_REQUEST["city"])) : '';
        $country          = ( ! empty($_REQUEST["country"])) ? strip_tags(str_replace("'", "`", $_REQUEST["country"])) : 'US';
        $state            = ( ! empty($_REQUEST["state"])) ? strip_tags(str_replace("'", "`", $_REQUEST["state"])) : '';
        $zip              = ( ! empty($_REQUEST["zip"])) ? strip_tags(str_replace("'", "`", $_REQUEST["zip"])) : '';
        $serviceID        = ( ! empty($_REQUEST['serviceID'])) ? strip_tags(str_replace("'", "`", strip_tags($_REQUEST['serviceID']))) : '';
        $service          = ( ! empty($_REQUEST['service'])) ? strip_tags(str_replace("'", "`", strip_tags($_REQUEST['service']))) : $serviceID;

        if ( ! empty($amount) && is_numeric($amount)) {
            $cctype = ( ! empty($_POST['cctype'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['cctype']))) : '';
            $ccname = ( ! empty($_POST['ccname'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['ccname']))) : '';
            $ccn    = ( ! empty($_POST['ccn'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['ccn']))) : '';
            $exp1   = ( ! empty($_POST['exp1'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['exp1']))) : '';
            $exp2   = ( ! empty($_POST['exp2'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['exp2']))) : '';
            $cvv    = ( ! empty($_POST['cvv'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['cvv']))) : '';

            if ($cctype != "PP") {

                //CREDIT CARD PHP VALIDATION
                if (empty($ccn) || empty($cctype) || empty($exp1) || empty($exp2) || empty($ccname) || empty($cvv) || empty($address) || empty($state) || empty($city)) {
                    $valid          = false;
                    $this->errors[] = 'Not all required fields were filled out';
                } else {
                    $valid = true;
                }

                if ( ! is_numeric($cvv)) {
                    $valid          = false;
                    $this->errors[] = 'CVV number can contain numbers only.';
                } else {
                    $valid = true;
                }

                if ( ! is_numeric($ccn)) {
                    $valid          = false;
                    $this->errors[] = 'Credit Card number can contain numbers only.';
                } else {
                    $valid = true;
                }

                if (date("Y-m-d", strtotime($exp2 . "-" . $exp1 . "-01")) < date("Y-m-d")) {
                    $valid          = false;
                    $this->errors[] = 'Your credit card is expired.';
                } else {
                    $valid = true;
                }

                if ($valid) {
                    if ($this->validateCC($ccn, $cctype)) {
                        $valid = true;
                    } else {
                        $valid          = false;
                        $this->errors[] = 'The number you\'ve entered does not match the card type selected.';
                    }
                }

                if ($valid) {
                    if ($this->validateLuhn($ccn)) {
                        $valid = true;
                    } else {
                        $valid          = false;
                        $this->errors[] = 'Invalid credit card number.';
                    }
                }

            } else {
                $valid = true;
            }

        }

        return $valid;
    }

    public function validateLuhn($number)
    {
        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number = preg_replace('/\D/', '', $number);

        // Set the string length and parity
        $number_length = strlen($number);
        $parity        = $number_length % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            // Total up the digits
            $total += $digit;
        }

        // If the total mod 10 equals 0, the number is valid
        return ($total % 10 == 0) ? true : false;
    }

    public function validateCC($cc_num, $type)
    {
        $verified = false;
        if ($type == "A") {
            $denum = "American Express";
        } elseif ($type == "DI") {
            $denum = "Diner's Club";
        } elseif ($type == "D") {
            $denum = "Discover";
        } elseif ($type == "M") {
            $denum = "Master Card";
        } elseif ($type == "V") {
            $denum = "Visa";
        }

        if ($type == "A") {
            $pattern = "/^([34|37]{2})([0-9]{13})$/";//American Express
            if (preg_match($pattern, $cc_num)) {
                $verified = true;
            } else {
                $verified = false;
            }

        } elseif ($type == "DI") {
            $pattern = "/^([30|36|38]{2})([0-9]{12})$/";//Diner's Club
            if (preg_match($pattern, $cc_num)) {
                $verified = true;
            } else {
                $verified = false;
            }

        } elseif ($type == "D") {
            $pattern = "/^([6011]{4})([0-9]{12})$/";//Discover Card
            if (preg_match($pattern, $cc_num)) {
                $verified = true;
            } else {
                $verified = false;
            }

        } elseif ($type == "M") {
            $pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";//Mastercard
            if (preg_match($pattern, $cc_num)) {
                $verified = true;
            } else {
                $verified = false;
            }

        } elseif ($type == "V") {
            $pattern = "/^([4]{1})([0-9]{12,15})$/";//Visa
            if (preg_match($pattern, $cc_num)) {
                $verified = true;
            } else {
                $verified = false;
            }

        }

        return $verified;
    }

    /**
     * Replaces all but the last for digits with x's in the given credit card number
     * @param int|string $cc The credit card number to mask
     * @return string The masked credit card number
     */
    function MaskCreditCard($cc)
    {
        // Get the cc Length
        $cc_length = strlen($cc);
        // Replace all characters of credit card except the last four and dashes
        for ($i = 0; $i < $cc_length - 4; $i++) {
            if ($cc[$i] == '-') {
                continue;
            }
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
        $cc = str_replace(array('-', ' '), '', $cc);
        // Get the CC Length
        $cc_length = strlen($cc);
        // Initialize the new credit card to contian the last four digits
        $newCreditCard = substr($cc, -4);
        // Walk backwards through the credit card number and add a dash after every fourth digit
        for ($i = $cc_length - 5; $i >= 0; $i--) {
            // If on the fourth character add a dash
            if ((($i + 1) - $cc_length) % 4 == 0) {
                $newCreditCard = '-' . $newCreditCard;
            }
            // Add the current character to the new credit card
            $newCreditCard = $cc[$i] . $newCreditCard;
        }

        // Return the formatted credit card number
        return $newCreditCard;
    }

    protected function validateCaptcha()
    {
        $captcha = false;
        if ($this->anpt_enable_captcha) {
            $g_recaptcha_response = isset($_POST['g-recaptcha-response']) ? str_replace("'", "`",
                $_POST['g-recaptcha-response']) : "0";
            $response             = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->anpt_captcha_key . "&response=" . $g_recaptcha_response);
            $response             = json_decode($response, true);
            if ($response["success"] === true) {
                $captcha = true;
            } else {
                $this->errors[] = 'Incorrect captcha entered';
            }
        } else {
            $captcha = true;
        }

        return $captcha;
    }

    protected function displayErrorMessage(
        $message = 'There was an error in your submission. Please review your entry and try again.'
    ) {
        echo '<div class="error danger">
                <p>' . $message . '</p><ul>';

        foreach ($this->errors as $error) {
            echo '<li>' . $error . '</li>';
        }

        echo '</ul></div>';
    }

    protected function processCC()
    {
        $state    = ( ! empty($_REQUEST["state"])) ? strip_tags(str_replace("'", "`", $_REQUEST["state"])) : '';
        $amount   = ( ! empty($_REQUEST["amount"])) ? strip_tags(str_replace("'", "`", $_REQUEST["amount"])) : '';
        $province = str_replace("-AU-", "", $state);

        $anpt_service_name = '';
        $anpt_bill_cycle   = '';
        $anpt_recurring    = 0;
        $currencyDisplay   = "";

        global $wpdb;
        //get thank you text to display in emails and in thank you message, if needed.
        $anpt_ty_text_var = $wpdb->get_var("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'anpt_ty_text'");
        $anpt_ty_text     = $anpt_ty_text_var ? nl2br($anpt_ty_text_var) : "";

        if ($this->show_services == 1) {
            $query             = "SELECT * FROM " . $wpdb->prefix . "anpt_services WHERE anpt_services_id =" . $this->serviceID;
            $res               = $wpdb->get_row($query, ARRAY_A);
            $amount            = number_format($res['anpt_services_price'], 2, ".", "");
            $anpt_service_name = $res['anpt_services_title'];
            if ($res['anpt_services_recurring'] == '0') {
                $payment_mode    = 'ONETIME';
                $anpt_bill_cycle = '';
            } else {
                $anpt_recurring  = 1;
                $payment_mode    = 'RECUR';
                $anpt_bill_cycle = '';
                if ($res['anpt_services_recurring_trial'] == '1') {
                    $anpt_bill_cycle .= '(' . $res['anpt_services_recurring_trial_days'] . ' trial days)';
                }
                $anpt_bill_cycle = 'Every ' . $res['anpt_services_recurring_period_type'] . ' ' . $res['anpt_services_recurring_period_number'] . ' ' . $anpt_bill_cycle;
            }
        }

        $cctype = ( ! empty($_POST['cctype'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['cctype']))) : '';

        switch ($cctype) {
            case "V":
                $cctype = "VISA";
                break;
            case "M":
                $cctype = "MASTERCARD";
                break;
            case "DI":
                $cctype = "DINERS CLUB";
                break;
            case "D":
                $cctype = "DISCOVER";
                break;
            case "A":
                $cctype = "AMEX";
                break;
            case "PP":
                $cctype = "PAYPAL";
                break;
        }

        switch (AccountCurrency) {
            case "USD":
                $currencyDisplay = "$" . number_format($amount, 2);
                break;
            case "CAD":
                $currencyDisplay = "$" . number_format($amount, 2);
                break;
            case "GBP":
                $currencyDisplay = number_format($amount, 2) . " GBP";
                break;
            case "AUD":
                $currencyDisplay = "$" . number_format($amount, 2) . " AUD";
                break;
            case "EUR":
                $currencyDisplay = number_format($amount, 2) . " EUR";
                break;
            default:
                $currencyDisplay = "$" . number_format($amount, 2);
                break;
        }

        if ($this->validateSubmission()) {

            //TODO: change this

            $cctype = ( ! empty($_POST['cctype'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['cctype']))) : '';
            $ccname = ( ! empty($_POST['ccname'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['ccname']))) : '';
            $ccn    = ( ! empty($_POST['ccn'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['ccn']))) : '';
            $exp1   = ( ! empty($_POST['exp1'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['exp1']))) : '';
            $exp2   = ( ! empty($_POST['exp2'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['exp2']))) : '';
            $cvv    = ( ! empty($_POST['cvv'])) ? strip_tags(str_replace("'", "`", strip_tags($_POST['cvv']))) : '';

            // Common setup for API credentials
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName(AUTHORIZENET_API_LOGIN_ID);
            $merchantAuthentication->setTransactionKey(AUTHORIZENET_TRANSACTION_KEY);
            $refId = 'ref' . time();

            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($ccn);
            $creditCard->setExpirationDate( $exp2 . "-" . $exp1);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            // Create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($amount);
            $transactionRequestType->setPayment($paymentOne);
            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId( $refId);
            $request->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

            if ($response != null) {
                $tresponse = $response->getTransactionResponse();
                if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                    echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                    return true;
                } else {
                    $errors = $tresponse->getErrors();
                    echo "Charge Credit Card ERROR :  Code " . $tresponse->getResponseCode();
                    echo '<pre>' . print_r($errors) . '</pre>';
                    return false;
                }
            } else {
                echo "Charge Credit Card Null response returned";
                return false;
            }

        }
    }

    public function displaySuccessMessage()
    {
        global $wpdb;
        //get thank you text to display in emails and in thank you message, if needed.
        $anpt_ty_text_var = $wpdb->get_var("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'anpt_ty_text'");
        $anpt_ty_text = $anpt_ty_text_var ? nl2br($anpt_ty_text_var) : "";

        echo '<p>' . $anpt_ty_text . '</p>';
    }

}