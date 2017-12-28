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
$sqlfilter = "";
$sqlorder = " ORDER BY anpt_dateCreated DESC ";
$anptTotal = 0;
$currentPage = 0;
if(!empty($_POST['cancelRecurring']) && count($_POST["cancelRecurring"])>0)
{
	require_once('terminal/authorize/configForAdmin.php');
	require_once('terminal/authorize/arb/authnetfunction.php');

	//query all information for the transaction id
	$query="SELECT * FROM ".$wpdb->prefix."anpt_transactions where anpt_id=".$_POST['id'];
	$row=$wpdb->get_row($query);
	if($wpdb->num_rows>0){
		$cuEmail=$row->anpt_payer_email;
		$profileID=urlencode($row->anpt_transaction_id);
		$content =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
        "<ARBCancelSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
        "<merchantAuthentication>".
        "<name>" . AUTHORIZENET_API_LOGIN_ID . "</name>".
        "<transactionKey>" . AUTHORIZENET_TRANSACTION_KEY . "</transactionKey>".
        "</merchantAuthentication>" .
        "<subscriptionId>" . $profileID . "</subscriptionId>".
        "</ARBCancelSubscriptionRequest>";
		
		$response = send_request_via_curl(AUTHORIZENET_HOST,AUTHORIZENET_PATH,$content);
		if ($response){
			/*
			a number of xml functions exist to parse xml results, but they may or may not be avilable on your system
			please explore using SimpleXML in php 5 or xml parsing functions using the expat library
			in php 4
			parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
			*/
			list ($resultCode, $code, $text, $subscriptionId) =parse_return($response);
			
			if($resultCode=='Ok'||$code=='Ok'){
				$message='Your '.get_bloginfo('name').' subscription was recently canceled.
Thanks for using '.get_bloginfo('name').'. We’re sorry to see you go. 

If you have any questions, please reply to '.get_bloginfo('admin_email').'

– '.get_bloginfo('name');
				$headers = "From: " . get_bloginfo('admin_email') . "\r\n";
				$headers .= "Reply-To: ". get_bloginfo('admin_email') . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				wp_mail($cuEmail['email'],'Your '.get_bloginfo('name').' Account has Been Canceled',nl2br($message),$headers);
				$query="update ".$wpdb->prefix."anpt_transactions set anpt_recurring_cancelled=1 where anpt_id=".$_POST['id'];
				$wpdb->query($query);
			}
		}
		?>
		<div class="deleted"><p><strong><?php _e('Subscription for customer '.$row->anpt_transaction_id.' was canceled!' ); ?></strong></p></div>
		<?php
	}
}
else
if(!empty($_POST['sync']) && count($_POST["sync"])>0)
{
	require_once ('terminal/authorize/configForAdmin.php');
	require_once ('terminal/authorize/authorize_net.php');	
	
	
	$includeStatistics = true;
	$lastSettlementDate = date('Y-m-d')."T00:00:00";
	$firstSettlementDate = date('Y-m-d',strtotime('now - 30 days'))."T00:00:00";

	// Get Settled Batch List
	$request = new AuthorizeNetTD;
	$response = $request->getSettledBatchList($includeStatistics,$firstSettlementDate,$lastSettlementDate);
	
	if($response->xml->messages->resultCode=='Error')
			echo '<div style="color:#fff; background:#cf0000; padding:10px; margin:0 20px 0 0;">'.$response->xml->messages->message->text[0].'</div>';
	else
	{
		$count=0;
		$countAlready=0;
		echo '<div style="color:#fff; background:#00cf00; padding:10px; margin:0 20px 0 0; ">';
		if(count($response->xml->batchList->batch)>0)
		{
			foreach ($response->xml->batchList->batch as $batch)
			{
				$xml=$request->GetTransactionList($batch->batchId);
				foreach($xml->xml->transactions->transaction as $transaction)
				{
					$transactionID    = $transaction->transId;
					$timeStamp=date('Y-m-d H:i:s',strtotime($transaction->submitTimeUTC));
					$payerName  = $transaction->firstName.' '.$transaction->lastName;
					$amount  = $transaction->settleAmount; 
					$status  =  $transaction->transactionStatus; 
					if($status=='settledSuccessfully')
					{
						$resultSql=$wpdb->get_row("select * from ".$wpdb->prefix."anpt_transactions where anpt_transaction_id='". $transactionID."'");
						if($wpdb->num_rows>0)
						{
							$countAlready++;
						}
						else
						{
							$stringSql="insert ignore into ".$wpdb->prefix."anpt_transactions set
								anpt_id='',
								anpt_transaction_id='". $transactionID."',
								anpt_payer_name='".$payerName."',
								anpt_amount='".$amount."',
								anpt_dateCreated='".$timeStamp."',
								anpt_status='2'";
							$wpdb->query($stringSql);
							
						}
						$count++;
					}
					
				}
			}
		}
		echo $count.' Transactions retrieved';
		if($countAlready>0)
			echo ', '.($count-$countAlready).' inserted to database';
		echo '</div>';
	}
}
if(!empty($_POST['toDelete']) && count($_POST["toDelete"])>0)
{
	$deleted=0;
	for($i=0; $i<count($_POST["toDelete"]); $i++)
	{
		$query="DELETE FROM ".$wpdb->prefix."anpt_transactions WHERE anpt_id='".$_POST["toDelete"][$i]."'";
		$wpdb->query($query);
		$deleted++;
	}

	if($deleted>0)
	{
		?>
		<div class="deleted"><p><strong><?php _e('Selected transaction(s) deleted!' ); ?></strong></p></div>
		<?php
	}
}

if(isset($_POST['anpt_filter_submit']) && $_POST['anpt_filter_submit'] == 'yes')
{
	if(!empty($_POST["anpt_date1"]) || !empty($_POST["anpt_date2"]))
	{
		$tmp1 = explode("/",$_POST["anpt_date1"]);
		$tmp2 = explode("/",$_POST["anpt_date2"]);
		$anptd1 = (!empty($_POST["anpt_date1"])?$tmp1[2]."-".$tmp1[0]."-".$tmp1[1]:date("Y-m-d"))." 00:00:00";
		$anptd2 = (!empty($_POST["anpt_date2"])?$tmp2[2]."-".$tmp2[0]."-".$tmp2[1]:date("Y-m-d"))." 23:59:59";
		$sqlfilter  .= " AND (anpt_dateCreated BETWEEN '".$anptd1."' AND '".$anptd2."') ";
	}

	if(!empty($_POST["anpt_sortby"]) && !empty($_POST["anpt_dir"]))
	{
		$sqlorder  = " ORDER BY ".$_POST["anpt_sortby"]." ".$_POST["anpt_dir"];
	}

	if(!empty($_POST["anpt_keyword"]))
	{
		$sqlfilter  .= " AND ( anpt_payer_name LIKE '%".$_POST["anpt_keyword"]."%' OR anpt_payer_email LIKE '%".$_POST["anpt_keyword"]."%' OR anpt_transaction_id LIKE '%".$_POST["anpt_keyword"]."%' )";
	}
}

$wp_url = get_site_url(); ?>
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/admin-style.css" />
<script type="text/javascript" src="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/js/functions.js"></script>
<?php
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
?>
<script type="text/javascript">
<!--
	jQuery(document).ready(function($) {
		$( "#anpt_date1" ).datepicker();
		$( "#anpt_date2" ).datepicker();
		$('#startDateStr').datepicker();
		$('#endDateStr').datepicker();

		
		
		$('.iconRecurring, .iconComment').mouseenter(function(){
			tmpOffset=$(this).offset();
			$('.transactionsPopupTera span').html($(this).attr('data-alt'));
			$('.transactionsPopupTera').css({left:tmpOffset.left-$('#adminmenuback').width()-15,top:tmpOffset.top-$('#wpadminbar').height()-50}).show();
		}).mouseleave(function(){
			$('.transactionsPopupTera').hide();
		});
	});
-->
</script>
<div class="transactionsPopupTera"><span></span><i></i></div>
<div class="wrap-anpt-bw">
	<div class="wrap-anpt-header" >
	<?php echo "<h2>" . __('Authorize.net Payment Terminal - Transactions','') . "</h2>"; ?>
	</div>
	<div class="anpt-subheader-anptr" >
		<p><?php _e("Please use filters and sorting functions of this page to view all or specific transactions. " ); ?></p>
	</div>                   
	<div class="wrap-anpt-content" style="padding-bottom: 20px;" >  
		<form name="anpt_form" id="anpt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="anpt_filter_submit" value="yes">   
			<div class="fieldset">
				<div class="wrap-anpt-breadcrumbs" >
					<h4><?php _e('Search settings'); ?></h4>
				</div>	  
				<div class="anpt-row-settings-search" >
					<div class="left title settings-span-text" >
						<label for="anpt_date1"><?php    echo   __('Filter by Date:',''); ?></label>
					</div>
					<div class="left paddedL5" >
						From:&nbsp;&nbsp;&nbsp;
						<input type="text" class="short anpt_text" id="anpt_date1" name="anpt_date1" value="<?php echo isset($_POST["anpt_date1"])?$_POST["anpt_date1"]:""?>">
					</div>
					<div class="right" >
						To: 
						<input type="text" class="short anpt_text" id="anpt_date2" name="anpt_date2" value="<?php echo isset($_POST["anpt_date2"])?$_POST["anpt_date2"]:""?>">
					</div>
				</div>
				<div class="anpt-row-settings-search" >
					<div class="left title settings-span-text" >
						<label for="anpt_sortby" ><?php    echo __('Sorting:',''); ?></label>
					</div>
					<div class="left" >
						<span class="left settings-span-text" >Sort By: </span>
						<span class="left paddedL5" >
							<select style="width: 150px !important" name="anpt_sortby" id="anpt_sortby"  class="short select">
									<option value="">Please Select</option>
									<option value="anpt_amount" <?php echo (isset($_POST["anpt_sortby"]) && $_POST["anpt_sortby"]=="anpt_amount")?"selected":""?>>Amount</option>
									<option value="anpt_payer_email" <?php echo (isset($_POST["anpt_sortby"]) && $_POST["anpt_sortby"]=="anpt_payer_email")?"selected":""?>>Email</option>
									<option value="anpt_payer_name" <?php echo (isset($_POST["anpt_sortby"]) && $_POST["anpt_sortby"]=="anpt_payer_name")?"selected":""?>>Name</option>
									<option value="anpt_serviceID" <?php echo (isset($_POST["anpt_sortby"]) && $_POST["anpt_sortby"]=="anpt_serviceID")?"selected":""?>>Service</option>
									<option value="anpt_dateCreated" <?php echo (isset($_POST["anpt_sortby"]) && $_POST["anpt_sortby"]=="anpt_dateCreated")?"selected":""?>>Transaction Date</option>
									<option value="anpt_transaction_id" <?php echo (isset($_POST["anpt_sortby"]) && $_POST["anpt_sortby"]=="anpt_transaction_id")?"selected":""?>>Transaction ID</option>
							</select> 
						</span>
					</div>
					<div class="right" >
						<span class="left settings-span-text" >In:&nbsp;&nbsp;</span>
						<span class="left settings-span-text" >
							<select style="width: 150px !important" name="anpt_dir" id="anpt_dir"  class="short select">
								<option value="ASC" <?php isset($_POST["anpt_dir"]) && $_POST["anpt_dir"]=="ASC"?"selected":""?>>Ascending</option>
								<option value="DESC"  <?php isset($_POST["anpt_dir"]) && $_POST["anpt_dir"]=="DESC"?"selected":""?>>Descending</option>
							</select>
						</span> 
					</div>
				</div>
				<div class="anpt-row-settings-search" >
					<div class="left willsubmit" >
						<input class="button" type="submit" name="Submit" value="<?php _e('Apply Settings', '' ) ?>" />
					</div>
				</div>		  
			</div>
			<div class="anpt-row-settings-search" >
				<div class="left title settings-span-text"  style="width: 140px;">
					<label for="whylableifnoused" >Search transaction</label>
				</div>
				<div class="right" >
					<span class="left settings-span-text" >
					<?php   echo __('By Keyword: &nbsp;','') ; ?>
					</span>
					<span class="left" >
						<input  type="text" class="anpt_text" id="anpt_keyword" name="anpt_keyword" value="<?php echo isset($_POST["anpt_keyword"])?$_POST["anpt_keyword"]:""?>">
					</span>
				</div>
			</div>
			<div class="anpt-row-settings-search"  >
				<div class="left willsubmit" >
					<input class="button-secondary" style="position: relative;bottom: 12px;" type="submit" name="Submit" value="<?php _e('Search', '' ) ?>" />
				</div>
			</div>
		</form>
	</div>  
	<div style="clear:both"></div>
	<div class="wrap-anpt-content" > 
		<div class="wrap-anpt-breadcrumbs" >
			<form name="syncTerminal" id="syncTerminal" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="sync" value="1"/>
             
   				<?php echo "<h4>" . __('All Transactions','');?>
                       
				<?php echo '<input type="button" class="button syncTerminalButton" value="Sync from Authorize.net"/>'; ?>
                 </h4>     
                          
			</form>
			<script type="text/javascript">
			<!--
				jQuery(document).ready(function($){
					$('.syncTerminalButton').click(function(e){
						e.preventDefault();
						$('form[name=syncTerminal]').submit();
					});
								
					$('.cancelRecurring').click(function(e){
						e.preventDefault();
						if(confirm('Are you sure you want to cancel this recurring payment?'))
						{
							$('form[name=cancelRecurring] input[name=id]').val($(this).attr('data-id'));
							$('form[name=cancelRecurring]').submit();
						}
					});
				});
			-->
			</script>
			<div class="clear"></div>			
		</div>
		<form name="cancelRecurring" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="id" value="">
			<input type="hidden" name="cancelRecurring" value="1">
		</form>
		<form name="anpt_form_del" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<div class="transactions_table">
				<div class="table_wrapper">
					<div class="table_header">
						<ul>
							<li class="deleter">&nbsp;</li>
							<li class="lastTransColumn"><?php _e('Transaction ID')?></li>
							<li class="bw-content-listtable-date"  ><?php _e('Date')?></li>
							<li class="bw-content-listtable-name" ><?php _e('Name & email')?></li>
							<li class="bw-content-listtable-amount" ><?php _e('Amount')?></li>
							<li class="bw-content-listtable-service_title" ><?php _e('Service')?></li>
							<li class="bw-content-listtable-options_title" ><?php _e('Options')?></li>
						</ul>
					</div>
					<?php
					if(isset($_GET['p']))
						$currentPage=$_GET['p']-1;	
					
					$itemsPerPage=20;				
					$query="SELECT count(anpt_id) as total FROM ".$wpdb->prefix."anpt_transactions where anpt_status=2";
					$row=$wpdb->get_row($query);
					$totalPages=ceil($row->total/$itemsPerPage);
					
					if(!is_numeric($currentPage)||$currentPage<0||$currentPage>$totalPages)
						$currentPage=0;
						
					//lets get all services from database
					$query="SELECT * FROM ".$wpdb->prefix."anpt_transactions  WHERE 1 AND anpt_status='2' $sqlfilter $sqlorder limit ".($currentPage*$itemsPerPage).",$itemsPerPage";
					$records = $wpdb->get_results($query);
					if($wpdb->num_rows>0){
						$del=true;
						$rClass = "row_b";
						foreach($records as $k=>$v){
							?>
							<div class="<?php echo $rClass=($rClass=="row_b"?"row_a":"row_b")?>">
								<ul>
									<li class="deleter">&nbsp;&nbsp;<input type="checkbox" value="<?php echo $v->anpt_id?>" name="toDelete[]" /></li>
									<li class="lastTransColumn"><?php echo stripslashes(strip_tags($v->anpt_transaction_id))?>&nbsp;</li>
									<li class="bw-content-listtable-date" ><?php echo date("d M Y, h:i a", strtotime($v->anpt_dateCreated))?></li>
									<li class="bw-content-listtable-name" >
										<?php echo stripslashes(strip_tags($v->anpt_payer_name))?> ( <?php echo stripslashes(strip_tags($v->anpt_payer_email))?> )</li>
									
									<li class="bw-content-listtable-amount" ><?php echo number_format(stripslashes(strip_tags($v->anpt_amount)),2); $anptTotal+=$v->anpt_amount;?></li>
									<?php
										$query2="SELECT anpt_services_title FROM ".$wpdb->prefix."anpt_services WHERE anpt_services_id='".$v->anpt_serviceID."'";
										$row2 = $wpdb->get_row($query2);
									?>
									<li class="bw-content-listtable-service_title" ><?php if($v->anpt_serviceID!=0){ echo stripslashes(strip_tags($row2->anpt_services_title)); } else { echo "N/A"; }?></li>
									<li class="bw-content-listtable-options_title" >
										<?php
										if($v->anpt_recurring=='1')
										{
											?>
											<span class="iconRecurring" data-alt="<?php echo $v->anpt_bill_cycle; if($v->anpt_recurring_cancelled==1) echo ' (CANCELED!)';?>"></span>
											<?php
										}
										if($v->anpt_comment!='')
										{
											?>
											<span class="iconComment" data-alt="<?php echo str_replace('"','',$v->anpt_comment);?>"></span>
											<?php
										}
										if($v->anpt_recurring=='1'&& $v->anpt_recurring_cancelled=='0')
										{
											?>
											<a href="#" data-id="<?php echo $v->anpt_id?>" class="cancelRecurring"></a>
											<?php
										}  
										?>
									</li>
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
								<li style="padding-left: 35px;" >&nbsp;&nbsp;<?php _e('0 transactions found');?></li>
							</ul>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="paginator">
				<?php
				for($i=1;$i<$totalPages+1;$i++)
				{
					?>
					<a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&p=<?php echo $i;?>" <?php if($i==($currentPage+1)){ echo ' class="active" ';} ?>><?php echo $i;?></a>
					<?php
				}
				?>
			</div>
			<div class="wrap-anpt-breadcrumbs bc-bottom" >
				<?php
				if($del)
				{
					?>
					<span class="left-btn" ><input class="button action" style="font-size: 13px;margin: 0;" type="submit" name="Submit" value="<?php _e('Delete Selected Transactions', '' ) ?>" /> </span>
					<?php
				}
				if($anptTotal>0)
				{
					?>
					<span style="padding: 5px 0px 0px;font-size: 13px;" class="right" ><strong>Total Amount:</strong> <?php echo number_format($anptTotal,2)?></span>
					<?php
				}
				?>
			</div>
		</form>
	</div> 
</div>