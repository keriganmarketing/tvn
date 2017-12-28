<?php

$configProcessor=is_string(get_option('anpt_details_AUTHORIZE'))?unserialize(get_option('anpt_details_AUTHORIZE')):get_option('anpt_details_AUTHORIZE');

//THIS IS ADMIN EMAIL FOR NEW PAYMENT NOTIFICATIONS.
$admin_email = get_option('anpt_admin_email'); //this email is for notifications about new payments
define("anpt_CURRENCY_CODE",get_option('anpt_currency'));
$payment_mode = "ONETIME";
//NOW, IF YOU WANT TO ACTIVATE THE DROPDOWN WITH SERVICES ON THE TERMINAL
//ITSELF, CHANGE BELOW VARIABLE TO TRUE;			
$show_services = get_option('anpt_show_dd_text');

$terminal = 'authorize';

// IF YOU'RE GOING LIVE FOLLOWING VARIABLE SHOULD BE SWITCH TO true
$liveMode = get_option('anpt_test')==2 ? true : false;

if(!$liveMode)
{	//TEST MODE
	define('AccountCurrency', get_option('anpt_currency'));
	define("AUTHORIZENET_API_LOGIN_ID", $configProcessor['test']['API_LOGIN_ID']);
	define("AUTHORIZENET_TRANSACTION_KEY", $configProcessor['test']['API_TRANSACTION_KEY']);
	define("AUTHORIZENET_SANDBOX", true);
	define("AUTHORIZENET_HOST","apitest.authorize.net");
	define("AUTHORIZENET_PATH","/xml/v1/request.api");
    $redirect_non_https = false;
}
else
{	//LIVE MODE
	define('AccountCurrency', get_option('anpt_currency'));
	define("AUTHORIZENET_API_LOGIN_ID", $configProcessor['live']['API_LOGIN_ID']);
	define("AUTHORIZENET_TRANSACTION_KEY", $configProcessor['live']['API_TRANSACTION_KEY']);
	define("AUTHORIZENET_SANDBOX", false);
	define("AUTHORIZENET_HOST","api.authorize.net");
	define("AUTHORIZENET_PATH","/xml/v1/request.api");
    $redirect_non_https = true;
}

###### This Custom JS must be defined here, after declaring the publishable key
$customJS[$terminal] = '<script type="text/javascript">
<!--
	$(document).ready(function(){
		$("#ff1").submit(function(event){
			var validation = checkForm();
			if(validation)
			{
				$("#ff1").get(0).submit();
			}
		});
	});				
-->
</script>';

//DO NOT CHANGE ANYTHING BELOW THIS LINE, UNLESS SURE OF COURSE
define("PAYMENT_MODE",$payment_mode);

//DO NOT CHANGE ANYTHING BELOW THIS LINE, UNLESS SURE OF COURSE
if($redirect_non_https){
	if ($_SERVER['SERVER_PORT']!=443) {
		$sslport=443; //whatever your ssl port is
		$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		header("Location: $url");
		exit();
	}
}
?>