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

$configProcessor=is_string(get_option('anpt_details_AUTHORIZE'))?unserialize(get_option('anpt_details_AUTHORIZE')):get_option('anpt_details_AUTHORIZE');

// IF YOU'RE GOING LIVE FOLLOWING VARIABLE SHOULD BE SWITCH TO true
$liveMode = get_option('anpt_test')==2 ? true : false;

if(!$liveMode)
{
	//TEST MODE   
	define('AccountCurrency', get_option('anpt_currency'));
	define("AUTHORIZENET_API_LOGIN_ID", $configProcessor['test']['API_LOGIN_ID']);
	define("AUTHORIZENET_TRANSACTION_KEY", $configProcessor['test']['API_TRANSACTION_KEY']);
	define("AUTHORIZENET_SANDBOX", true);
	define("AUTHORIZENET_HOST","apitest.authorize.net");
	define("AUTHORIZENET_PATH","/xml/v1/request.api");
}

else
{
	//LIVE MODE
	define('AccountCurrency', get_option('anpt_currency'));
	define("AUTHORIZENET_API_LOGIN_ID", $configProcessor['live']['API_LOGIN_ID']);
	define("AUTHORIZENET_TRANSACTION_KEY", $configProcessor['live']['API_TRANSACTION_KEY']);
	define("AUTHORIZENET_SANDBOX", false);
	define("AUTHORIZENET_HOST","api.authorize.net");
	define("AUTHORIZENET_PATH","/xml/v1/request.api");
}