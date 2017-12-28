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

if(isset($_POST['anpt_submit_service']) && $_POST['anpt_submit_service'] == 'yes')
{
	//Form data sent
	$pt_title = $_POST['anpt_services_title'];
	$pt_descr = $_POST['anpt_services_descr'];
	$pt_price = $_POST['anpt_services_price'];

	if(is_numeric($pt_price) && !empty($pt_title))
	{
		$query="INSERT INTO ".$wpdb->prefix."anpt_services set 
		anpt_services_title='".addslashes(strip_tags($pt_title))."',
		anpt_services_descr='".addslashes(strip_tags($pt_descr))."',
		anpt_services_recurring='".addslashes(strip_tags($_POST['anpt_services_recurring']))."',
		anpt_services_recurring_period_type='".addslashes(strip_tags($_POST['anpt_services_recurring_period_type']))."',
		anpt_services_recurring_period_number='".addslashes(strip_tags($_POST['anpt_services_recurring_period_number']))."',
		anpt_services_recurring_trial='".addslashes(strip_tags($_POST['anpt_services_recurring_trial']))."',
		anpt_services_recurring_trial_days='".addslashes(strip_tags($_POST['anpt_services_recurring_trial_days']))."',
		anpt_services_price='".addslashes(strip_tags($pt_price))."'";
		$wpdb->query($query);
		?>
		<div class="updated"><p><strong><?php _e('Service added!' ); ?></strong></p></div>
		<?php
	}
	else
	{
		?>
		<div class="updated">
			<p><strong><?php _e('Service not added! Please check your input. Price must contain numbers only and name cannot be blank.' ); ?></strong></p>
		</div><?php
	}
}

$anpt_services_descr = "";
if(!empty($_POST['toDelete']) && count($_POST["toDelete"])>0)
{
	$deleted=0;
	for($i=0; $i<count($_POST["toDelete"]); $i++)
	{
		$query="DELETE FROM ".$wpdb->prefix."anpt_services WHERE anpt_services_id='".$_POST["toDelete"][$i]."'";
		$wpdb->query($query);
		$deleted++;
	}
	if($deleted>0)
	{
		?> <div class="updated"><p><strong><?php _e('Selected service(s) deleted!' ); ?></strong></p></div><?php
	}
}

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
	<div class="anpt-subheader-anptr" >
		<p><?php _e("Here you create and manage basic list of products, services or events you'd like to accept payments for." ); ?></p>
	</div>
	<div class="wrap-anpt-content" >
		<div class="wrap-anpt-breadcrumbs" >
			<?php echo "<h4>" . __( 'Add New Service', '' ) . "</h4>"; ?>
		</div>
		<form name="anpt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="anpt_submit_service" value="yes">
			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_title" ><?php _e("Service Name: " ); ?></label>
				<input class="anpt_text" type="text" name="anpt_services_title" id="anpt_services_title" value="" size="40"><br />
				<em><?php _e("This is what customers will see in the services dropdown to select from when they decide to pay" ); ?></em>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_recurring" ><?php _e("Is recurring service? ");?></label>
				<input type="radio" name="anpt_services_recurring" value="1"> <?php _e("Yes");?> &nbsp;&nbsp;
				<input type="radio" name="anpt_services_recurring" value="0" checked> <?php _e("No");?> &nbsp;&nbsp;
			</div>
			<div class="anpt-services-rowwsto143 recurringDiv" style="display:none;">
				<label><?php _e("Billing period");?>: </label>
				<select name="anpt_services_recurring_period_number">
					<?php
					for($i=1;$i<31;$i++)
					{
						?>
						<option><?php echo $i;?></option>
						<?php
					}
					?>
				</select>
				&nbsp;&nbsp;
				<select name="anpt_services_recurring_period_type">
					<option value="days">Days</option>
					<option value="months">Months</option>
				</select>
			</div>
			<div class="anpt-services-rowwsto143 recurringDiv" style="display:none;">
				<label><?php _e("Trial period");?>: </label>
				<input type="radio" name="anpt_services_recurring_trial" value="1"> <?php _e("Yes");?> &nbsp;&nbsp;
				<input type="radio" name="anpt_services_recurring_trial" value="0" checked> <?php _e("No");?> &nbsp;&nbsp;
			</div>
			<div class="anpt-services-rowwsto143 recurringDivTrial" style="display:none;">
				<label><?php _e("Trial period days");?>: </label>
				<select name="anpt_services_recurring_trial_days">
				<?php
				for($i=1;$i<31;$i++)
				{
					?>
					<option><?php echo $i;?></option>
					<?php
				}
				?>
				</select>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_price" ><?php _e("Price to charge: " ); ?></label>
				<input class="anpt_text" type="text" name="anpt_services_price" id="anpt_services_price" onkeyup="noAlpha(this)"  value="" size="40"><br />
				<em><?php _e("Numbers only. ex. 10.99" ); ?></em>
			</div>
			<div id="poststuff">
				<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea postare-test-me2">
					<label for="anpt_services_descr" ><?php _e("Service Description: " ); ?></label>
					<?php wp_editor($anpt_services_descr, $editor_id = 'anpt_services_descr', $settings = array( $textarea_name = 'anpt_services_descr', $media_buttons = false, $tabindex = 2));?>
					<em><?php _e(" (Optional small text describing the service, won't be displayed to customer, internal use only at the moment)" ); ?></em>
				</div>
			</div>
			<p class="submit">
				<input style="margin: 0 10px;" class="button button-primary" type="submit" name="Submit" value="<?php _e('Add Service', '' ) ?>" />
			</p>
		</form>
	</div>
	<div class="wrap-anpt-content" >
		<div class="wrap-anpt-breadcrumbs" >    
		<?php echo "<h4>" . __('List of Services','') . "</h4>"; ?>
		</div>
		<form name="anpt_form_del" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<div class="services_table">
				<div class="table_wrapper">
					<div class="table_header">
						<ul>
							<li class="deleter">&nbsp;</li>
							<li class="anpt-services-list-nor-id" ><?php _e('ID')?></li>
							<li class="anpt-services-list-nor-sname" ><?php _e('Service Name')?></li>
							<li class="anpt-services-list-nor-sprice" ><?php _e('Price')?></li>
							<li class="lastColumn anpt-services-list-nor-descr" style="width:35%"><?php _e('Description')?></li>
						</ul>
					</div>
					<?php
					//lets get all services from database
					$query="SELECT * FROM ".$wpdb->prefix."anpt_services ORDER BY anpt_services_title";
					$records = $wpdb->get_results($query);
					if($wpdb->num_rows>0){
						$del=true;
						$rClass = "row_b";
						foreach($records as $k=>$v){ ?>
							<div class="<?php echo $rClass=($rClass=="row_b"?"row_a":"row_b")?>">
								<ul>
									<li class="deleter">&nbsp;&nbsp;<input type="checkbox" value="<?php echo $v->anpt_services_id?>" name="toDelete[]" /></li>
									<li class="anpt-services-list-nor-id" ><?php echo stripslashes(strip_tags($v->anpt_services_id))?></li>
									<li class="anpt-services-list-nor-sname" ><?php echo stripslashes(strip_tags($v->anpt_services_title))?> <a href="admin.php?page=anpt_admin_services_edit&amp;anpt_serviceID=<?php echo $v->anpt_services_id?>">edit</a></li>
									<li class="anpt-services-list-nor-sprice"  ><?php echo number_format(stripslashes(strip_tags($v->anpt_services_price)),2)?></li>
									<li class="lastColumn anpt-services-list-nor-descr" style="width:35%"><?php echo stripslashes(strip_tags($v->anpt_services_descr))?></li>
								</ul>
							</div> 
							<?php
						}
					}
					else
					{
						$del=false;
						?>
						<div class="row_msg">
							<ul>
								<li>&nbsp;&nbsp;<?php _e('0 service records found in the database');?></li>
							</ul>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			if($del)
			{
				?>
				<p style="padding: 0;" class="submit">
					<input class="button action" style="margin: 10px;" type="submit" name="Submit" value="<?php _e('Delete Selected Services', '' ) ?>" />
				</p>
				<?php
			}
			?>
		</form>
	</div>
</div>