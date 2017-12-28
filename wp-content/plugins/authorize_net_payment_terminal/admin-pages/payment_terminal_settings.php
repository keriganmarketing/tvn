<?php

$payment_terminals = anptCRUD($anpt_cfg_arr,'default');
$anpt_submit_settings = "";
$anpt_submit_license = "";
$anpt_ty_title = "";
$wp_url = get_site_url();
?>
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/admin-style.css" />
<script type="text/javascript">
<!--
	function anpt_selectProcessor(el,element)
	{
		jQuery("#BW-change-anpt_processor").val(el);
		jQuery(".BW-anpt-terminal-holder").hide();
		jQuery("#terminal_"+el).show();
		jQuery(".BW-anpt-terminal-holder ,.BW-anpt-terminal-liselector").removeClass("selected");
		jQuery(element).addClass("selected")
		jQuery("#terminal_"+el).addClass("selected");
		return false;
	}
	jQuery(function(){
	    jQuery('#anpt_enable_captcha').click(function(){
	        if(jQuery(this).is(":checked")){
	            jQuery("#anpt-captcha-cont").show();
	        }else{
                jQuery("#anpt-captcha-cont").hide();
	        }
	    })
	})
-->
</script>
<?php
if(isset($_POST['anpt_submit_settings']) && $_POST['anpt_submit_settings'] == 'yes')
{				
	update_option('anpt_currency', isset($_POST['anpt_currency'])?$_POST['anpt_currency']:'');
	update_option('anpt_ty_title', isset($_POST['anpt_ty_title'])?$_POST['anpt_ty_title']:'');
	update_option('anpt_ty_text', isset($_POST['anpt_ty_text'])?$_POST['anpt_ty_text']:'');
	update_option('anpt_admin_email', isset($_POST['anpt_admin_email'])?$_POST['anpt_admin_email']:'');
	update_option('anpt_show_comment_field', isset($_POST['anpt_show_comment_field'])?$_POST['anpt_show_comment_field']:'');
	update_option('anpt_show_dd_text', isset($_POST['anpt_show_dd_text'])?$_POST['anpt_show_dd_text']:'');
	update_option('anpt_processor', isset($_POST['anpt_processor'])?$_POST['anpt_processor']:'');
	update_option('anpt_test', isset($_POST['anpt_test'][$_POST['anpt_processor']])?$_POST['anpt_test'][$_POST['anpt_processor']]:$_POST['AHAM']);


    update_option('anpt_enable_captcha', isset($_POST['anpt_enable_captcha'])?$_POST['anpt_enable_captcha']:'0');
    update_option('anpt_captcha_key', isset($_POST['anpt_captcha_key'])?$_POST['anpt_captcha_key']:'');
    update_option('anpt_captcha_site', isset($_POST['anpt_captcha_site'])?$_POST['anpt_captcha_site']:'');

} 

$anpt_currency = get_option('anpt_currency');
$anpt_ty_title = get_option('anpt_ty_title');
$anpt_ty_text = get_option('anpt_ty_text');
$anpt_admin_email = get_option('anpt_admin_email');
$anpt_show_comment_field = get_option('anpt_show_comment_field');
$anpt_show_dd_text = get_option('anpt_show_dd_text');
$anpt_license = get_option('anpt_license');
$anpt_processor = get_option('anpt_processor');
$anpt_test = get_option('anpt_test');

$anpt_enable_captcha = get_option('anpt_enable_captcha');
$anpt_captcha_key = get_option('anpt_captcha_key');
$anpt_captcha_site = get_option('anpt_captcha_site');

?>
	<div class="wrap-anpt-bw">
		<div class="wrap-anpt-header" >
			<?php echo "<h2>" . __('Authorize.net Payment Terminal - Settings','') . "</h2>"; ?>
		</div>
		<div class="anpt-subheader-anptr" >
			<p><?php _e("Here you define your Payment Terminal settings such as your e-mail address associated with your merchant account, currency, thank you message and other settings." ); ?></p>
		</div>
		<div class="wrap-anpt-content-noborder" >
			<form enctype="multipart/form-data" name="anpt_form" id="anpt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="anpt_submit_settings" value="yes">
				<div class="BW-anpt-terminal_settings_wrap" >
					<div id="BW-anpt-select_terminal_nav_list" style="display:none;" >
						<ul>
							<?php
							$i=1;
							foreach($payment_terminals as $terminals => $details)
							{
								if($terminals != "CUSTOM_ERROR")
								{
									?>
									<li class="BW-anpt-terminal-liselector<?php echo displaySelectedCond($i,$anpt_processor); ?>" onclick="return anpt_selectProcessor('<?php echo $i; ?>',this);" ><a title="Select <?php echo strtoupper($terminals); ?> as your payment Gateway" id="terminal_nav_bg_<?php echo strtolower(str_replace(array(' ','"','\'','.'), '_', $terminals)); ?>" href="#<?php if(isset($_GET['page']) && $_GET['page'] == 'anpt_admin_settings'){echo $_SERVER['REQUEST_URI']."&active_terminal=".$i;} ?>#You should not see this in your browser url with javascript enabled!" ><?php echo $terminals; ?></a></li>
									<?php
									$i++;
								}
							}
							?>
						</ul>
					</div>
					<div id="BW-anpt-terminal_settings_wrap_left" >
						<div id="BW-anpt-terminal-fields" >
							<p class="BW-anpt-settings-tip-text-left" >
								<b><?php _e("Authorize.net API Credentials: " ); ?></b><br/>
								<?php /* tera -- key s file url */ ?>
								<span class='anpt_tip'><i><?php _e("Tip:" ); ?></i><?php _e("if you don´t know where to get them, <a href=\"https://www.authorize.net/support/CP/helpfiles/Account/Settings/Security_Settings/General_Settings/API_Login_ID_and_Transaction_Key.htm\" target=\"_blank\">click here</a>" ); ?></span>
							</p>
							<input type="hidden" name="anpt_processor" id="BW-change-anpt_processor" value="<?php echo (isset($_GET['active_terminal']) ? $_GET['active_terminal'] : $anpt_processor); ?>" />
							<INPUT TYPE="HIDDEN" NAME="AHAM" VALUE="<?php echo $anpt_test; ?>" />
							<?php
							$i=1;
							foreach($payment_terminals as $terminal => $details)
							{
								if($terminal != "CUSTOM_ERROR")
								{
									?>
									<div id="terminal_<?php echo $i; ?>" class="BW-anpt-terminal-holder<?php echo displaySelectedCond($i,$anpt_processor); ?>" >
										<?php
										foreach($details as $case => $fields)
										{
											if ($case == "LIVE" || $case == "TEST")
											{
												?>
												<div class="BW-anpt-terminal-<?php echo strtolower($case); ?> " >
													<div class="BW-left-terminal-case<?php if($anpt_test == "2" && $case == "LIVE") : echo " active"; elseif($anpt_test == "1" && $case == "TEST") : echo " active"; endif; ?>" ><span><?php echo $case." MODE"; ?></span></div>
													<div class="BW-anpt-terminal-field-wrap<?php if($anpt_test == "2" && $case == "LIVE") : echo " active"; elseif($anpt_test == "1" && $case == "TEST") : echo " active"; endif; ?>">
														<?php
														foreach($fields as $field_name => $field_value)
														{
															$input_id = strtolower(str_replace(array(' ','"','\'','.'), '_', $terminal."_".$case."_".$field_name));
															$filter = isanptfilter($field_name);
															if($filter != null)
															{
																$field_name = $filter;
																$input_id = isanptfilter($input_id);
																?>
																<p>
																	<label for="<?php echo $input_id; ?>"><?php echo __(strtoupper($field_name)); ?>:</label>
																	<input onchange="jQuery('#trigger_<?php echo $input_id; ?>').addClass('button-disabled').text('File Selected !');" class="icancustomizethistooiguess" style="display:none;width: 185px !important;height: 30px !important;" type="file" autocomplete="off" id="<?php echo $input_id; ?>" name="<?php echo $input_id; ?>"  />
																	<span id="trigger_<?php echo $input_id; ?>" onclick="jQuery('#<?php echo $input_id; ?>').trigger('click')" class="anpt-upload-btn button" >Click to select File</span>
																</p>
																<?php
															}
															else
															{
																?>
																<p><label for="<?php echo $input_id; ?>"><?php echo __(strtoupper($field_name)); ?>:</label><input type="text" autocomplete="off" id="<?php echo $input_id; ?>" name="<?php echo $input_id; ?>" value="<?php echo $field_value; ?>" /></p>
																<?php
															}
														}
														?>
												</div>
												<div class="BW-settings-change-case<?php if(($anpt_test == "2" && $case == "LIVE") || ($anpt_test == "1" && $case == "TEST")){echo " active";} ?>" >
													<button type="submit" name="anpt_test[<?php echo $i; ?>]" value="<?php echo ($case == "TEST" ? 1 : 2); ?>" title="<?php if(($anpt_test == "2" && $case == "LIVE") || ($anpt_test == "1" && $case == "TEST")){echo $case." Settings are ON !";} ?>" >
														<?php if($anpt_test != "2" && $case == "LIVE"){ _e(" Turn "); } elseif($anpt_test != "1" && $case == "TEST"){ _e(" Turn  "); } ?><?php echo __($case); ?><?php _e(' Settings ');?><?php if($anpt_test == "2" && $case == "LIVE"){ _e(" Are "); } elseif($anpt_test == "1" && $case == "TEST") {_e("Are"); } ?><?php _e(' On '); ?>
													</button>
												</div>
											</div>
												<?php
											}
										}
										?>
									</div>
                                <?php
									$i++;
								}
							}
							?>
						</div>
						
						<p class="BW-anpt-settings-tip-text-left" >
							<b><?php _e("Default Currency: " ); ?></b><br>
							<span class='anpt_tip'><i><?php _e("Tip:" ); ?></i><?php _e("Authorize.net supported currencies only." ); ?></span></p>
						<p style="clear: both;" >
							<select name="anpt_currency" style="height: 32px;width: 247px;" class="select">
								<?php /*
																
								<option value="CZK" <?php echo $anpt_currency=="CZK"?"selected":""?>>Czech Koruna        (CZK)</option>
								<option value="DKK" <?php echo $anpt_currency=="DKK"?"selected":""?>>Danish Krone        (DKK)</option>
								
								<option value="HKD" <?php echo $anpt_currency=="HKD"?"selected":""?>>Hong Kong Dollar        (HKD)</option>
								<option value="HUF" <?php echo $anpt_currency=="HUF"?"selected":""?>>Hungarian Forint       (HUF)</option>
								<option value="JPY" <?php echo $anpt_currency=="JPY"?"selected":""?>>Japanese Yen  (JPY)</option>
								<option value="NOK" <?php echo $anpt_currency=="NOK"?"selected":""?>>Norwegian Krone    (NOK)</option>
								
								<option value="PLN" <?php echo $anpt_currency=="PLN"?"selected":""?>>Polish Zloty      (PLN)</option>
								
								<option value="SGD" <?php echo $anpt_currency=="SGD"?"selected":""?>>Singapore Dollar       (SGD)</option>
								<option value="SEK" <?php echo $anpt_currency=="SEK"?"selected":""?>>Swedish Krona      (SEK)</option>
								<option value="CHF" <?php echo $anpt_currency=="CHF"?"selected":""?>>Swiss Franc      (CHF)</option>*/?>
								<option value="USD" <?php echo $anpt_currency=="USD"?"selected":""?>>United States Dollar (USD)</option>
								<option value="CAD" <?php echo $anpt_currency=="CAD"?"selected":""?>>Canadian Dollar (CAD)</option>
								<option value="GBP" <?php echo $anpt_currency=="GBP"?"selected":""?>>British Pound (GBP)</option>
								<option value="EUR" <?php echo $anpt_currency=="EUR"?"selected":""?>>Euro (EUR)</option>
								<option value="AUD" <?php echo $anpt_currency=="AUD"?"selected":""?>>Australian Dollar (AUD)</option>
								<option value="NZD" <?php echo $anpt_currency=="NZD"?"selected":""?>>New Zealand Dollar (NZD)</option>
							</select>
						</p>
						
						<p class="BW-anpt-settings-tip-text-left" style="clear: both;padding-top: 20px;" ><b><?php _e("Admin Notification Email:" ); ?></b></p>
						<p><input type="text"  class="anpt_text" name="anpt_admin_email" value="<?php echo $anpt_admin_email; ?>" size="40"></p>

                        <p class="BW-anpt-settings-tip-text-left" style="clear: both;padding-top: 20px;" >
                        <b><?php _e("Enable reCaptcha:" ); ?></b>&nbsp; <input type="checkbox" id="anpt_enable_captcha"  name="anpt_enable_captcha" value="1" <?php echo $anpt_enable_captcha==1?"checked":""; ?>></p>


                        <div style="display: <?php echo $anpt_enable_captcha==1?"block":"none"; ?>" id="anpt-captcha-cont">
                            <p class="BW-anpt-settings-tip-text-left" style="clear: both;padding-top: 20px;" ><b><?php _e("reCaptcha Site Key:" ); ?></b></p>
                            <p><input type="text"  class="anpt_text long" name="anpt_captcha_site" value="<?php echo $anpt_captcha_site; ?>" size="40"></p>
                            <p class="BW-anpt-settings-tip-text-left" style="clear: both;padding-top: 20px;" ><b><?php _e("reCaptcha Secret Key:" ); ?></b></p>
                            <p><input type="text"  class="anpt_text long" name="anpt_captcha_key" value="<?php echo $anpt_captcha_key; ?>" size="40"></p>
                        </div>
						
						<p style="height: 0px;border-bottom: 1px solid #e6e6e6;" >&nbsp;</p>
						<?php echo "<h4 class='BW-anpt-settings-tip-text-left' >" . __( '"Thank-You" Message' ) . "</h4>"; ?>
						<div id="poststuff">
							<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
								<?php wp_editor($anpt_ty_text, $editor_id = 'anpt_ty_text', $settings= array($prev_id = 'anpt_ty_text', $media_buttons = false, $tabindex = 4));?><?php _e(" (small text describing next step, appears in widget)" ); ?>
							</div>
						</div>
						<p class="submit"><input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Settings') ?>" /></p>
					</div>
				</div>
			</form>
		</div>
	</div>