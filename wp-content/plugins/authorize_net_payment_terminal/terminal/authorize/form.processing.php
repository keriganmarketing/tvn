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

$province = str_replace("-AU-", "", $state);

$anpt_service_name='';
$anpt_bill_cycle='';
$anpt_recurring=0;
$currencyDisplay = "";
# PLEASE DO NOT EDIT FOLLOWING LINES IF YOU'RE NOT SURE ------->

//global $wpdb;
//get thank you text to display in emails and in thank you message, if needed.
$anpt_ty_text_var = $wpdb->get_var("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'anpt_ty_text'");
$anpt_ty_text = $anpt_ty_text_var ? nl2br($anpt_ty_text_var) : "";

if($show_services==1)
{
    $query="SELECT * FROM ".$wpdb->prefix."anpt_services WHERE anpt_services_id =".$service;
	$res=$wpdb->get_row($query,ARRAY_A);
	$amount = number_format($res['anpt_services_price'],2, ".", "");
	$anpt_service_name=$res['anpt_services_title'];
	if($res['anpt_services_recurring']=='0'){
		$payment_mode='ONETIME';
		$anpt_bill_cycle='';
	} else {
		$anpt_recurring=1;
		$payment_mode='RECUR';
		$anpt_bill_cycle='';
		if($res['anpt_services_recurring_trial']=='1'){
			$anpt_bill_cycle.= '('.$res['anpt_services_recurring_trial_days'].' trial days)';
		}
		$anpt_bill_cycle='Every '.$res['anpt_services_recurring_period_type'].' '.$res['anpt_services_recurring_period_number'].' '.$anpt_bill_cycle;
	}
}

$continue = false;
if(!empty($amount) && is_numeric($amount))
{ 	
	$cctype = (!empty($_POST['cctype']))?strip_tags(str_replace("'","`",strip_tags($_POST['cctype']))):'';
	$ccname = (!empty($_POST['ccname']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccname']))):'';
	$ccn = (!empty($_POST['ccn']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccn']))):'';
	$exp1 = (!empty($_POST['exp1']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp1']))):'';
	$exp2 = (!empty($_POST['exp2']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp2']))):'';
	$cvv = (!empty($_POST['cvv']))?strip_tags(str_replace("'","`",strip_tags($_POST['cvv']))):'';
	
	if($cctype!="PP")
	{
		//CREDIT CARD PHP VALIDATION
		if(empty($ccn) || empty($cctype) || empty($exp1) || empty($exp2) || empty($ccname) || empty($cvv) || empty($address) || empty($state) || empty($city))
		{
			$continue = false;
			$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> Not all required fields were filled out.</p></div></div><br />';
		}
		else
			$continue = true;

		if(!is_numeric($cvv))
		{
			$continue = false;
			$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> CVV number can contain numbers only.</p></div></div><br />';
		}
		else
			$continue = true;

		if(!is_numeric($ccn)){
			$continue = false;
			$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> Credit Card number can contain numbers only.</p></div></div><br />';
		}
		else
			$continue = true;

		if(date("Y-m-d", strtotime($exp2."-".$exp1."-01")) < date("Y-m-d"))
		{
			$continue = false;
			$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> Your credit card is expired.</p></div></div><br />';
		}
		else
			$continue = true;

		if($continue)
		{
			//echo "1";
			if(validateCC($ccn,$cctype))
				$continue = true;
			else
			{
				$continue = false;
				$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> The number you\'ve entered does not match the card type selected.</p></div></div><br />';
			}
		}

		if($continue)
		{
			if(luhn_check($ccn))
				$continue = true;
			else
			{
				$continue = false;
				$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> Invalid credit card number.</p></div></div><br />';
			}
		}
	}
	else
		$continue = true;
	
	switch($cctype)
	{
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

	switch(AccountCurrency){
		case "USD":
			$currencyDisplay = "$".number_format($amount,2);
		break;
		case "CAD":
			$currencyDisplay = "$".number_format($amount,2);
		break;
		case "GBP":
			$currencyDisplay = number_format($amount,2)." GBP";
		break;
		case "AUD":
			$currencyDisplay = "$".number_format($amount,2)." AUD";
		break;
		case "EUR":
			$currencyDisplay = number_format($amount,2)." EUR";
		break;
		default:
			$currencyDisplay = "$".number_format($amount,2);
		break;
	}

	$transactID = time()."-".rand(1,999);
	if($continue && $cctype!="PAYPAL")
	{
		$query=$wpdb->prepare("INSERT INTO ".$wpdb->prefix."anpt_transactions (anpt_dateCreated, anpt_amount, anpt_comment,anpt_serviceID,anpt_service_name,anpt_bill_cycle,anpt_recurring) VALUES ( %s, %f, %s, %d, %s, %s, %d)", date('Y-m-d H:i:s',time()) , $amount , $item_description, $service , $anpt_service_name , $anpt_bill_cycle,$anpt_recurring );
		$wpdb->query($query);
		$orderID=$wpdb->insert_id;

		switch($payment_mode)
		{
			case "ONETIME":
				require('authorize_net.php');
				if(!empty($_POST["process"]) && $_POST["process"]=="yes")
				{
					/**
					 * Get required parameters from the web form for the request
					 */
					 
					 // Month must be padded with leading zero
					 $padDateMonth = str_pad($exp1, 2, '0', STR_PAD_LEFT);
					
					 $sale = new AuthorizeNetAIM;
					 $exp_date =  $padDateMonth.substr($exp2,2,2);

                    $sale->setFields(
                        array(
                            'amount' => $amount,
                            'card_num' => $ccn,
                            'exp_date' => $exp_date,
                            'invoice_num' => $transactID,

                            'description'=>$show_services?$anpt_service_name:$item_description,
                            'email'=>$email,
                            'first_name'=>$fname,
                            'last_name'=>$lname,

                            'city'=>$city,
                            'address'=>$address,
                            'country'=>$country,
                            'zip'=>$zip,
                            'state'=>$state,
                            'customer_ip'=>$_SERVER["REMOTE_ADDR"]
                        )
                    );
					 $response = $sale->authorizeAndCapture();
					 if ($response->approved)
					 {
						 $transactID = $response->transaction_id;
						 $terminal_success = 1;
						 $return_msg = 'Your payment was successful.';
					 }
					 else
					 {
						 $terminal_success = 2;
						 $return_msg = $response->response_reason_text;
						 $return_msg.= $response->error_message;
					 }

					// Process the payment and output the results
					switch ($terminal_success)
					{
						case 1:  // Successs
							$sMessageResponse= "<br /><div>Your payment was <b>APPROVED</b>!";
							$sMessageResponse .= "<div>";
							$sMessageResponse .= "Gateway Response:".$return_msg;
							$sMessageResponse .= "</div>";
							//if(!isset($_GET['lbox']))
								//$sMessageResponse .= "<br/><a href='index.php'>Return to payment page</a>";
                            if(isset($anpt_ty_text) && !empty($anpt_ty_text) ){
                                $sMessageResponse .=nl2br($anpt_ty_text)."<br />";
                            }
							$sMessageResponse .="<br /><br/></div>";
							$mess = '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">'.$sMessageResponse.'</div></div><br />';
							#**********************************************************************************************#
							#		THIS IS THE PLACE WHERE YOU WOULD INSERT ORDER TO DATABASE OR UPDATE ORDER STATUS.
							#**********************************************************************************************#
							$q="UPDATE ".$wpdb->prefix."anpt_transactions SET anpt_status='2', anpt_payer_email='".$email."', anpt_transaction_id='".$transactID."', anpt_payer_name='".$ccname."' WHERE anpt_id='".$orderID."'";
							$wpdb->query($q);
							#**********************************************************************************************#
							/******************************************************************
							ADMIN EMAIL NOTIFICATION
							******************************************************************/
							$headers  = "MIME-Version: 1.0\n";
							$headers .= "Content-type: text/html; charset=utf-8\n";
							$headers .= "From: 'Authorize.net Payment Terminal' <noreply@".$_SERVER['HTTP_HOST']."> \n";
							$subject = "New Payment Received";
							$message =  "New payment was successfully received through Authorize.net <br />";
							$message .= "from ".$fname." ".$lname."  on ".date('m/d/Y')." at ".date('g:i A').".<br /> Payment total is: ".$currencyDisplay;
							if($show_services){
								$message .= "<br />Payment was made for \"".$anpt_service_name."\"";
							} else {
								$message .= "<br />Payment description: \"".$item_description."\"";
							}
							$message .= "<br /><br />Billing Information:<br />";
							$message .= "Full Name: ".$fname." ".$lname."<br />";
							$message .= "Email: ".$email."<br />";
							$message .= "Address: ".$address."<br />";
							$message .= "City: ".$city."<br />";
							$message .= "Country: ".$country."<br />";
							$message .= "State/Province: ".$state."<br />";
							$message .= "ZIP/Postal Code: ".$zip."<br />";
							wp_mail($admin_email,$subject,$message,$headers);
		
							/******************************************************************
							CUSTOMER EMAIL NOTIFICATION
							******************************************************************/
							$subject = "Payment Received!";
							$message =  "Dear ".$fname.",<br />";
							$message .= nl2br($anpt_ty_text);
							$message .= "<br />";
							if ($show_services){
								$message .= "<br />Payment was made for \"" . $anpt_service_name. "\"";
							} else {
								$message .= "<br />Payment was made for: \"" . $item_description . "\"";
							}
							$message .= "<br />Payment amount: ".$currencyDisplay;
							$message .= "<br /><br />Billing Information:<br />";
							$message .= "Full Name: " . $fname . " " . $lname . "<br />";
							$message .= "Email: " . $email . "<br />";
							$message .= "Address: " . $address . "<br />";
							$message .= "City: " . $city . "<br />";
							$message .= "Country: " . $country . "<br />";
							$message .= "State/Province: " . $state . "<br />";
							$message .= "ZIP/Postal Code: " . $zip . "<br />";
							$message .= "<br /><br />Kind Regards,<br />" . $_SERVER['HTTP_HOST'];
							wp_mail($email,$subject,$message,$headers);
							//-----> send notification end
							$show_form=0;		
							break;
						case 2:  // Declined
							$sMessageResponse= "<br /><div>Your payment was <b>DECLINED</b>!";
							$sMessageResponse .= "<div>";
							$sMessageResponse .= $return_msg;
							$sMessageResponse .= "</div>";
							$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'.$sMessageResponse.'</div></div><br />';
							break;
						case 3:  // Error
							$sMessageResponse= "<br /><div>Payment processing returned <b>ERROR</b>!";
							$sMessageResponse .= "<div>";
							$sMessageResponse .= $return_msg;
							$sMessageResponse .= "</div>";
							$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'.$sMessageResponse.'</div></div><br />';
						break;
					}
				} 
				break;
		  
			case "RECUR":
				require('arb/authnetfunction.php');

				$ccnamet = explode(" ",$ccname);
				$firstName = isset($ccnamet[0])?$ccnamet[0]:$ccname;
				$lastName = str_replace($firstName,"",implode(" ",$ccnamet));
				$firstName = trim($firstName);
				$lastName = trim($lastName);
				if(!empty($_POST["process"]) && $_POST["process"]=="yes")
				{
					$trial_end=time();
					if($res['anpt_services_recurring_trial']=='1'){
						$trial_end = time() + ($res['anpt_services_recurring_trial_days'] * 24 * 60 * 60);
					} else {
						$quantity = 1;
						$trial_end=time();
					}
					
					$padDateMonth = str_pad($exp1, 2, '0', STR_PAD_LEFT);
					
					$amount = number_format($amount,2,".","");
					$refId = $transactID;
					$name = $res['anpt_services_title'].' - '.$transactID;
					$length = $res['anpt_services_recurring_period_number'];
					$unit = $res['anpt_services_recurring_period_type'];
					$startDate = date('Y-m-d',$trial_end);
					$totalOccurrences = 9999;
					$trialOccurrences = 0;
					$trialAmount = 0;
					$cardNumber = $ccn;
					$expirationDate = $padDateMonth.'/'.substr($exp2,2,2);;
					$firstName = $fname;
					$lastName = $lname ;
					
					//build xml to post
					$content =
							"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
							"<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
							"<merchantAuthentication>".
							"<name>" . AUTHORIZENET_API_LOGIN_ID . "</name>".
							"<transactionKey>" . AUTHORIZENET_TRANSACTION_KEY . "</transactionKey>".
							"</merchantAuthentication>".
							"<refId>" . $refId . "</refId>".
							"<subscription>".
							"<name>" . $name . "</name>".
							"<paymentSchedule>".
							"<interval>".
							"<length>". $length ."</length>".
							"<unit>". $unit ."</unit>".
							"</interval>".
							"<startDate>" . $startDate . "</startDate>".
							"<totalOccurrences>". $totalOccurrences . "</totalOccurrences>".
							"<trialOccurrences>". $trialOccurrences . "</trialOccurrences>".
							"</paymentSchedule>".
							"<amount>". $amount ."</amount>".
							"<trialAmount>" . $trialAmount . "</trialAmount>".
							"<payment>".
							"<creditCard>".
							"<cardNumber>" . $cardNumber . "</cardNumber>".
							"<expirationDate>" . $expirationDate . "</expirationDate>".
							"</creditCard>".
							"</payment>".
							"<billTo>".
							"<firstName>". $firstName . "</firstName>".
							"<lastName>" . $lastName . "</lastName>".
							"<address>" . $address . "</address>".
							"<city>" . $city . "</city>".
							"<state>" . $state . "</state>".
							"<zip>" . $zip . "</zip>".
							"<country>" . $country . "</country>".
							"</billTo>".
							"</subscription>".
							"</ARBCreateSubscriptionRequest>";

					$response = send_request_via_curl(AUTHORIZENET_HOST,AUTHORIZENET_PATH,$content);
					
					
					if ($response)
					{
						/*
						a number of xml functions exist to parse xml results, but they may or may not be avilable on your system
						please explore using SimpleXML in php 5 or xml parsing functions using the expat library
						in php 4
						parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
						*/
						list ($refId, $resultCode, $code, $text, $subscriptionId) =parse_return($response);
						if($resultCode=='Ok')
						{
							$resArray["TRANSACTIONID"] = $subscriptionId;
							$terminal_success = 1;
							/* You may adjust this success message. to your needs */
							$return_msg = 'Your payment was successful.';
						} else {
							$terminal_success = 2;
							$return_msg=$text;
						}
					} else {
						$terminal_success = 2;
						$return_msg="Transaction Failed.";
					}
					
					if ($terminal_success){
						if($terminal_success == 2){
							$my_status="<div>Subscription Un-successful!<br/>";
							$my_status .=$subscriptionId."<br />";
							#$my_status .="Response Code: ".$resultCode."<br />";
							#$my_status .="Response Reason Code: ".$code."<br />";
							#$my_status .="Response Text: ".$text."<br /><br />";
							$error=0;
							$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'.$return_msg.'</div></div><br />';
						} else if($terminal_success == 1){
							$my_status="<br/><div>Subscription Created Successfully!<br/>";
							$my_status .= "Subscription ID: " . $subscriptionId . "<br />";
                            if(isset($anpt_ty_text) && !empty($anpt_ty_text) ){
							    $my_status .=nl2br($anpt_ty_text)."<br />";
                            } else {
                                $my_status .="Thank you for your payment<br /><br />";
                            }
							$my_status .="Gateway Response:<br />";
							$my_status .=$return_msg."<br />";
							#$my_status .="Response Reason Code: ".$code."<br />";
							#$my_status .="Response Text: ".$text."<br /><br />";
							$my_status .= "You will receive confirmation email within 5 minutes.<br/><br/>";
							//if(!isset($_GET['lbox']))
								//$my_status.="<a href='index.php'>Return to payment page</a>";
							$my_status.="</div><br/>";
							$error=0;
							$mess = '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">'.$my_status.'</div></div><br />';
							/******************************************************************
							ADMIN EMAIL NOTIFICATION
							******************************************************************/
							$headers  = "MIME-Version: 1.0\n";
							$headers .= "Content-type: text/html; charset=utf-8\n";
							$headers .= "From: 'Authorize.net Payment Terminal' <noreply@".$_SERVER['HTTP_HOST']."> \n";
							$subject = "New Recurring Payment Received";
							$message = "New recurring payment was successfully received through Authorize.net <br />";
							$message .= "from ".$fname." ".$lname."  on ".date('m/d/Y')." at ".date('g:i A').".<br /> Payment total is: ".$currencyDisplay;
							if($show_services)
							{
								$message .= "<br />Payment was made for \"".$anpt_service_name."\"";
							}
							else
							{
								$message .= "<br />Payment description: \"".$item_description."\"";
							}
							$message .= "<br/>Start Date: ".date("Y-m-d")."<br />";
							$message .= "Billing Frequency: ".$res['anpt_services_recurring_period_number']." ".$res['anpt_services_recurring_period_type']."<br />";
							$message .= "Subscription ID: ".$subscriptionId."<br />";
							$message .= "<br /><br />Billing Information:<br />";
							$message .= "Full Name: ".$fname." ".$lname."<br />";
							$message .= "Email: ".$email."<br />";
							$message .= "Address: ".$address."<br />";
							$message .= "City: ".$city."<br />";
							$message .= "Country: ".$country."<br />";
							$message .= "State/Province: ".$state."<br />";
							$message .= "ZIP/Postal Code: ".$zip."<br /><br />";
		
							//$message .= "If for any reason you need to cancel this subscription you can follow <a href='http://".$_SERVER["SERVER_NAME"].str_replace("/index.php","",$_SERVER["REQUEST_URI"])."/cancel.php?subid=".$subscriptionId."'>this link</a><br />";
							wp_mail($admin_email,$subject,$message,$headers);

							/******************************************************************
							CUSTOMER EMAIL NOTIFICATION
							******************************************************************/
							$subject = "Payment Received!";
							$message =  "Dear ".$fname.",<br />";
							$message .= nl2br($anpt_ty_text);
							$message .= "<br />";
							if($show_services)
							{
								$message .= "<br />Payment was made for \"".$anpt_service_name."\"";
							}
							else
							{
								$message .= "<br />Payment description: \"".$item_description."\"";
							}
							$message .= "<br/>Start Date: ".date("Y-m-d")."<br />";
							$message .= "Billing Frequency: ".$res['anpt_services_recurring_period_number']." ".$res['anpt_services_recurring_period_type']."<br />";
							$message .= "Subscription ID: ".$subscriptionId."<br />";
							$message .= "<br />Payment amount: ".$currencyDisplay;
							$message .= "<br /><br />Billing Information:<br />";
							$message .= "Full Name: " . $fname . " " . $lname . "<br />";
							$message .= "Email: " . $email . "<br />";
							$message .= "Address: " . $address . "<br />";
							$message .= "City: " . $city . "<br />";
							$message .= "Country: " . $country . "<br />";
							$message .= "State/Province: " . $state . "<br />";
							$message .= "ZIP/Postal Code: " . $zip . "<br /><br />";
							//$message .= "If for any reason you need to cancel this subscription you can follow <a href='http://".$_SERVER["SERVER_NAME"].str_replace("/index.php","",$_SERVER["REQUEST_URI"])."/cancel.php?subid=".$subscriptionId."'>this link</a>";
							$message .= "<br /><br />Kind Regards,<br />" . $_SERVER['HTTP_HOST'];
							
							$show_form=0;
							$q="UPDATE ignore ".$wpdb->prefix."anpt_transactions SET anpt_status='2', anpt_payer_email='".$email."', anpt_transaction_id='".$resArray["TRANSACTIONID"]."', anpt_payer_name='".$ccname."' WHERE anpt_id='".$orderID."'";
							$wpdb->query($q);
							
							wp_mail($email,$subject,$message,$headers);
						}
					}
					else
					{
						$count=0;
						$my_status="<div>Transaction Un-successful!<br/>";
						$my_status .="There was an error with your credit card processing.<br/>";
						$error=1;
						$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'.$return_msg.'</div></div><br />';
					}
				}
				break;
		}
	}	
}
else
if(!is_numeric($amount) || empty($amount))
{ 
	if($show_services)
	{
		$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> Please select service you\'re paying for.</p></div></div><br />';
	}
	else
	{ 
		$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error!</strong> Please type amount to pay for services!</p></div></div><br />';
	}
	$show_form=1; 
} 
# END OF PLEASE DO NOT EDIT IF YOU'RE NOT SURE