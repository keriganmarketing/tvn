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
//$wp_url = get_bloginfo('siteurl'); 
$wp_url = get_site_url();
global $wpdb;
$sqlfilter = "";
$sqlorder = " order by anpt_dateCreated desc";
?>
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/admin-style.css" />
<div class="wrap-anpt-bw">
	<div class="wrap-anpt-header" >
		<?php echo "<h2>" . __('Authorize.net Payment Terminal - Overview','') . "</h2>"; ?>
	</div>
	<div class="anpt-subheader-anptr" >
		<p><?php _e("This simple plugin enables you to accept credit card payments on your WP website and manage transactions in your website's WP control panel. " ); ?></p>
	</div>
	<div class="wrap-anpt-content" >
		<div class="wrap-anpt-breadcrumbs" >
			<?php echo "<h4>" . __('Last 15 Transactions','') . "</h4>"; ?>
		</div>
		<div class="transactions_overview_table ">
			<div class="table_wrapper">
				<div class="table_header">
					<ul>
						<li class="deleter">&nbsp;</li>
						<li class="lastTransColumn"><?php _e('Transaction ID')?></li>
						<li class="bw-content-listtable-date"  ><?php _e('Date')?></li>
						<li class="bw-content-listtable-name" ><?php _e('Name')?></li>
						<li class="bw-content-listtable-email" ><?php _e('Email')?></li>
						<li class="bw-content-listtable-amount" ><?php _e('Amount')?></li>
						<li class="bw-content-listtable-service_title" ><?php _e('Service')?></li>
					</ul>
				</div>
				<?php
				//lets get all services from database
				$query="SELECT * FROM ".$wpdb->prefix."anpt_transactions  WHERE 1 AND anpt_status='2' $sqlfilter $sqlorder limit 0,15";
				$records = $wpdb->get_results($query);
				if($wpdb->num_rows>0){
					$del=true;
					$rClass = "row_b";
					foreach($records as $k=>$v){
						?>
						<div class="<?php echo $rClass=($rClass=="row_b"?"row_a":"row_b")?>">
							<ul>
								<li class="deleter">&nbsp;&nbsp;</li>
								<li class="lastTransColumn"><?php echo stripslashes(strip_tags($v->anpt_transaction_id))?>&nbsp;</li>
								<li class="bw-content-listtable-date" ><?php echo date("d M Y, h:i a", strtotime($v->anpt_dateCreated))?>&nbsp;</li>
								<li class="bw-content-listtable-name" ><?php echo stripslashes(strip_tags($v->anpt_payer_name))?>&nbsp;</li>
								<li class="bw-content-listtable-email" ><?php echo stripslashes(strip_tags($v->anpt_payer_email))?>&nbsp;</li>
								<li class="bw-content-listtable-amount" ><?php echo number_format(stripslashes(strip_tags($v->anpt_amount)),2);?>&nbsp;</li>
								<?php
								$query2="SELECT anpt_services_title FROM ".$wpdb->prefix."anpt_services WHERE anpt_services_id='".$v->anpt_serviceID."'";
								$row2 = $wpdb->get_row($query2);
								?>
								<li class="bw-content-listtable-service_title" ><?php if($v->anpt_serviceID!=0){ echo stripslashes(strip_tags($row2->anpt_services_title)); } else { echo "N/A"; }?>&nbsp;</li>
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
							<li  style="padding-left: 35px;" >0 transactions found</li>
						</ul>
					</div>
					<br clear="all" />
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="wrap-anpt-content-noborder">
		<input class="button button-primary" type="button" onclick="window.location='admin.php?page=anpt_admin_transactions'" name="Submit" value="<?php _e('View All Transactions', '' ) ?>" />
	</div>
</div>