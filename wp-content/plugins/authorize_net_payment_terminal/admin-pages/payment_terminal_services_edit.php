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
global $wpdb;
$ssdurl = $_SERVER['REQUEST_URI'];
if(stristr($ssdurl, "&anpt_serviceID="))
{
	$myurltemp = explode("&anpt_serviceID=",$ssdurl);
	$anpt_serviceID = $myurltemp[1];
}

if(isset($_POST['anpt_submit_service']) && $_POST['anpt_submit_service'] == 'yes' && !empty($anpt_serviceID) && is_numeric($anpt_serviceID))
{
	//Form data sent
	$pt_title = $_POST['anpt_services_title'];
	$pt_descr = $_POST['anpt_services_descr'];
	$pt_price = $_POST['anpt_services_price'];
	
	if(is_numeric($pt_price) && !empty($pt_title))
	{
		$query="update ".$wpdb->prefix."anpt_services set 
		anpt_services_title='".addslashes(strip_tags($pt_title))."',
		anpt_services_descr='".addslashes(strip_tags($pt_descr))."',
		anpt_services_recurring='".addslashes(strip_tags($_POST['anpt_services_recurring']))."',
		anpt_services_recurring_period_type='".addslashes(strip_tags($_POST['anpt_services_recurring_period_type']))."',
		anpt_services_recurring_period_number='".addslashes(strip_tags($_POST['anpt_services_recurring_period_number']))."',
		anpt_services_recurring_trial='".addslashes(strip_tags($_POST['anpt_services_recurring_trial']))."',
		anpt_services_recurring_trial_days='".addslashes(strip_tags($_POST['anpt_services_recurring_trial_days']))."',
		anpt_services_price='".addslashes(strip_tags($pt_price))."'
		WHERE anpt_services_id='".$anpt_serviceID."'";
		$wpdb->query($query);
		?>
		<div class="updated">
			<p><strong><?php _e('Service updated! <a href="admin.php?page=anpt_admin_services">Click here</a> to go back to all services' ); ?></strong></p>
		</div>
		<?php
	}
	else
	{ 
		?>
		<div class="updated">
			<p><strong><?php _e('Service not updated! Please check your input. Price must contain numbers only and name cannot be blank.' ); ?></strong></p>
		</div>
		<?php
	}
}

$query2="SELECT * FROM ".$wpdb->prefix."anpt_services WHERE anpt_services_id='".$anpt_serviceID."'";
$record = $wpdb->get_row($query2);
$anpt_services_title = $record->anpt_services_title;
$anpt_services_price = $record->anpt_services_price;
$anpt_services_descr = $record->anpt_services_descr;

$wp_url = get_site_url();  ?>
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/admin-style.css" />

<script type="text/javascript" src="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/js/functions.js"></script>
<script type="text/javascript">
<!--
	jQuery(document).ready(function($){
		$('input[name=anpt_services_recurring]').click(function(){
			if($('input[name=anpt_services_recurring]:checked').val()=='1')
				$('.recurringDiv').show();
			else
				$('.recurringDiv').hide();
			// check the trial fields
			if($('input[name=anpt_services_recurring_trial]:checked').val()=='1')
				$('.recurringDivTrial').show();
			else
				$('.recurringDivTrial').hide();
		});
		
		$('input[name=anpt_services_recurring_trial]').click(function(){
			if($('input[name=anpt_services_recurring_trial]:checked').val()=='1')
				$('.recurringDivTrial').show();
			else
				$('.recurringDivTrial').hide();
		});
	});
-->
</script>
<div class="wrap-anpt-bw">
	<div class="wrap-anpt-header" >
	<?php echo "<h2>" . __('Authorize.net Payment Terminal - Services','') . "</h2>"; ?>
	</div>
	<div class="wrap-anpt-content" >
		<div class="wrap-anpt-breadcrumbs" >
			<?php echo "<h4>" . __( 'Edit Service', '' ) . "</h4>"; ?>
		</div>
		<form name="anpt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="anpt_submit_service" value="yes">
			<input type="hidden" name="anpt_serviceID" value="<?php echo $anpt_serviceID?>">

			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_title" ><?php _e("Service Name: " ); ?></label>
				<input class="anpt_text" type="text" name="anpt_services_title" id="anpt_services_title" value="<?php echo $anpt_services_title?>" size="40"><br />
				<em><?php _e("This is what customers will see in the services dropdown to select from when they decide to pay" ); ?></em>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_recurring" ><?php _e("Is recurring service? ");?></label>
				<input type="radio" name="anpt_services_recurring" value="1" <?php if($record->anpt_services_recurring=='1') echo 'checked';?>> <?php _e("Yes");?> &nbsp;&nbsp;
				<input type="radio" name="anpt_services_recurring" value="0" <?php if($record->anpt_services_recurring=='0') echo 'checked';?>> <?php _e("No");?> &nbsp;&nbsp;
			</div>
			<div class="anpt-services-rowwsto143 recurringDiv" <?php if($record->anpt_services_recurring=='0') echo 'style="display:none;"';?>>
				<label><?php _e("Billing period");?>: </label>
				<select name="anpt_services_recurring_period_number">
				<?php
				for($i=1;$i<31;$i++)
				{
					?>
					<option <?php if($record->anpt_services_recurring_period_number==$i) echo 'selected';?>><?php echo $i;?></option>
					<?php
				}
				?>
				</select>
				&nbsp;&nbsp;
				<select name="anpt_services_recurring_period_type">
				<option value="days" <?php if($record->anpt_services_recurring_period_type=='days') echo 'selected';?>>Days</option>
				<option value="months" <?php if($record->anpt_services_recurring_period_type=='months') echo 'selected';?>>Months</option>
				</select>
			</div>
			<div class="anpt-services-rowwsto143 recurringDiv" <?php if($record->anpt_services_recurring=='0') echo 'style="display:none;"';?>>
				<label><?php _e("Trial period");?>: </label>
				<input type="radio" name="anpt_services_recurring_trial" value="1" <?php if($record->anpt_services_recurring_trial=='1') echo 'checked';?>> <?php _e("Yes");?> &nbsp;&nbsp;
				<input type="radio" name="anpt_services_recurring_trial" value="0" <?php if($record->anpt_services_recurring_trial=='0') echo 'checked';?>> <?php _e("No");?> &nbsp;&nbsp;
			</div>
			<div class="anpt-services-rowwsto143 recurringDivTrial" <?php if($record->anpt_services_recurring_trial=='0') echo 'style="display:none;"';?>>
				<label><?php _e("Trial period days");?>: </label>
				<select name="anpt_services_recurring_trial_days">
				<?php
				for($i=1;$i<31;$i++)
				{
					?>
					<option <?php if($record->anpt_services_recurring_trial_days==$i) echo 'selected';?>><?php echo $i;?></option>
					<?php
				}
				?>
				</select>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_price" ><?php _e("Price to charge: " ); ?></label>
				<input class="anpt_text" type="text" name="anpt_services_price" id="anpt_services_price" onkeyup="noAlpha(this)"  value="<?php echo $anpt_services_price?>" size="40"><br />
				<em><?php _e("Numbers only. ex. 10.99" ); ?></em>
			</div>
			<div id="poststuff">
				<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea postare-test-me2">
					<label for="anpt_services_descr" style="font-weight: bold;color: #666;" ><?php _e("Service Description: " ); ?></label>
					<?php wp_editor($anpt_services_descr, $editor_id = 'anpt_services_descr', $settings = array($textarea_name = 'anpt_services_descr', $media_buttons = false, $tabindex = 3));?><?php _e(" (Optional small text describing the service, won't be displayed to customer, internal use only at the moment)" ); ?>
				</div>
			</div> 
			<p class="submit">
				<input style="margin: 0 10px;" class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Service', '' ) ?>" />
			</p>
		</form>
	</div>
</div>