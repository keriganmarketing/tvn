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
$wp_url = get_site_url();
?>
<script type="text/javascript" src="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/js/spectrum/spectrum.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/js/spectrum/spectrum.css" />
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/admin-style.css" />
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/style.css" />
<link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/authorize_net_payment_terminal/css/font-awesome.css" />
<div class="wrap-anpt-bw">
	<div class="wrap-anpt-header" >
		<?php    echo "<h2>" . __('Authorize.net Payment Terminal - Button Generator','') . "</h2>"; ?>
	</div>
	<div class="anpt-subheader-anptr" >
		<p><?php _e("Here you can create shortcodes for payment buttons." ); ?></p>
	</div>
	<div class="wrap-anpt-content" >
		<div class="wrap-anpt-breadcrumbs" >
			<?php echo "<h4>" . __( 'Button Generator', '' ) . "</h4>"; ?>
		</div>
		<form name="anpt_form" method="post" action="#">
			<div class="anpt-services-rowwsto143">
				<label for="anpt_buttons_title" ><?php _e("Button Text: " ); ?></label>
				<input class="anpt_text" type="text" name="anpt_buttons_title" id="anpt_buttons_title" value="<?php _e("Pay Now" ); ?>" size="40"><br />
				<em><?php _e("" ); ?></em>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_services_price" ><?php _e("Default Amount: " ); ?></label>
				<input class="anpt_text" type="text" name="anpt_services_price" id="anpt_services_price" onkeyup="noAlpha(this)"  value="10.99" size="40"><br />
				<em><?php _e("Tip: Numbers only. ex. 10.99. Applicable only if services dropdown is hidden" ); ?></em>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_buttons_comment" ><?php _e("Show comments field?: " ); ?></label>
				<input type="radio" name="anpt_buttons_comment" value="true"> <?php _e("Yes"); ?>&nbsp;&nbsp;
				<input type="radio" name="anpt_buttons_comment" value="false" checked> <?php _e("No"); ?><br />
				<em><?php _e("" ); ?></em>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_buttons_service" ><?php _e("Services Dropdown: " ); ?></label>
				<input type="radio" name="anpt_buttons_service" value="false" checked> <?php _e("Hide"); ?> &nbsp;&nbsp;
				<input type="radio" name="anpt_buttons_service" value="true"> <?php _e("Show all services"); ?> &nbsp;&nbsp;
				
				<input type="radio" name="anpt_buttons_service" value="defined"> <?php _e("Pre Select a service"); ?><br />
				<em><?php _e("" ); ?></em>
			</div>
			<div class="anpt-services-rowwsto143 servicesListDiv" style="display:none;">
				<label for="anpt_buttons_service" ><?php _e("Select a service: " ); ?></label>
				<?php
				$query="SELECT * FROM ".$wpdb->prefix."anpt_services ORDER BY anpt_services_title";
				$records = $wpdb->get_results($query);
				if($wpdb->num_rows>0){
					?>
					<select name="serviceId">
						<?php
						foreach($records as $k=>$v){
							?>
							<option value="<?php echo $v->anpt_services_id;?>"><?php echo $v->anpt_services_title;?></option>
							<?php } ?>
					</select>
					<?php
				} else {
					_e('Please add some services first.');
				}
				?>
				<em><?php _e(""); ?></em>
			</div>
			<script type="text/javascript">
			<!--
				currentActiveTab=0;
				jQuery(document).ready(function($){
					$('.tabbedArea .tabs li').click(function(){
						tmpIndex=$('.tabbedArea .tabs li').index($(this));
						currentActiveTab=tmpIndex;
						$('.tabbedArea .tabs li').removeClass('active');
						$(this).addClass('active');
						$('.tabbedArea .tabDiv').hide();
						$('.tabbedArea .tabDiv').eq(tmpIndex).show();
					});
					$('.tabbedArea .tabs li:eq(0)').trigger('click');
					$('input[name=button_corner]').change(function(){
						$('.buttondesign0').removeClass('corner0 corner1 corner2');
						$('.buttondesign0').addClass('corner'+$(this).val());
					});
					$('.iconSelectionGroup span').click(function(){
						$('.iconSelectionGroup span').removeClass('active');
						$(this).addClass('active');
						$('input[name=button_icon]').val($(this).attr('data-value'));
						$('.buttondesign0 span').attr('class','fa fa-2x '+$(this).attr('data-value'));
					});
					$('input[name=iconColor]').change(function(){
						$('.buttondesign0 span').css({'color':$(this).val()});
					});					
					$('input[name=bgColor]').change(function(){
						$('.buttondesign0').css({'background-color':$(this).val()});
					});
					$('input[name=textColor]').change(function(){
						$('.buttondesign0').css({'color':$(this).val()});
					});
				});
			-->
			</script>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_buttons_design" ><?php _e("Button Design: " ); ?></label><br/>
				<div class="tabbedArea">
					<ul class="tabs">
						<li><?php _e('Select Button Style');?></li>
						<li><?php _e('Generate Button');?></li>
					</ul>
					<div class="clear"></div>
					<div class="tabDiv">
						<?php
						for($i=1;$i<43;$i++)
						{
							?>
							<div class="designDiv">
							<a href="#" class="anpt_newpay_button buttondesign<?php echo $i;?>"><?php _e("Pay Now"); ?><span></span></a><br/>
							<input type="radio" name="anpt_buttons_design" value="<?php echo $i;?>" <?php if($i==1) echo 'checked';?>>
							</div>
							<?php
							if($i%7==0)
								echo '<div style="clear:both; height:1px; "></div>';
						}
						?>
					</div>
					<div class="tabDiv">
						<div class="anpt-services-rowwsto143">
							<label>1. <?php _e('Select corner style');?>: </label>
							<input type="radio" name="button_corner" value="0" checked>
							<span style=" vertical-align:middle; display:inline-block; background:#2A96FF; width:20px; height:20px; border-radius:0 0 0 0;"></span> &nbsp;&nbsp;
							<input type="radio" name="button_corner" value="1">
							<span style=" vertical-align:middle; display:inline-block; background:#2A96FF; width:20px; height:20px; border-radius:10px 0 0 0;"></span> &nbsp;&nbsp;
							<input type="radio" name="button_corner" value="2">
							<span style=" vertical-align:middle; display:inline-block; background:#2A96FF; width:20px; height:20px; border-radius:20px 0 0 0;"></span> &nbsp;&nbsp;
						</div>
						<div class="anpt-services-rowwsto143">
							<label class="iconSelectionLabel">2. <?php _e('Select icon');?>: </label>
							<input type="hidden" name="button_icon" value="noIcon">
							<script type="text/javascript">
							<!--
								jQuery(document).ready(function($){
									$('.iconSelectionRight .internalTabs li').click(function(){
										tmpIndex=$('.iconSelectionRight .internalTabs li').index($(this));
										currentActiveTab=tmpIndex;
										$('.iconSelectionRight .internalTabs li').removeClass('active');
										$(this).addClass('active');
										$('.iconSelectionRight .iconSelectionGroup').hide();
										$('.iconSelectionRight .iconSelectionGroup').eq(tmpIndex).show();
									});
									$('.iconSelectionRight .internalTabs li:eq(0)').trigger('click');
								});
							-->
							</script>
							<div class="iconSelectionRight">
								<ul class="internalTabs">
									<li>Currency</li>
									<li>Web App</li>
									<li>Form Control</li>
									<li>Text Editor</li>
									<li>Directional</li>
									<li>Video Player</li>
									<li>Medical</li>
									<li>Brand</li>
								</ul>
								<div class="clear"></div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon" class="active">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-bitcoin,fa-btc,fa-cny,fa-dollar,fa-eur,fa-euro,fa-gbp,fa-inr,fa-jpy,fa-krw,fa-money,fa-rmb,fa-rouble,fa-rub,fa-ruble,fa-rupee,fa-try,fa-turkish-lira,fa-usd,fa-won,fa-yen');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-adjust,fa-anchor,fa-archive,fa-arrows,fa-arrows-h,fa-arrows-v,fa-asterisk,fa-ban,fa-bar-chart-o,fa-barcode,fa-bars,fa-beer,fa-bell,fa-bell-o,fa-bolt,fa-book,fa-bookmark,fa-bookmark-o,fa-briefcase,fa-bug,fa-building-o,fa-bullhorn,fa-bullseye,fa-calendar,fa-calendar-o,fa-camera,fa-camera-retro,fa-caret-square-o-down,fa-caret-square-o-left,fa-caret-square-o-right,fa-caret-square-o-up,fa-certificate,fa-check,fa-check-circle,fa-check-circle-o,fa-check-square,fa-check-square-o,fa-circle,fa-circle-o,fa-clock-o,fa-cloud,fa-cloud-download,fa-cloud-upload,fa-code,fa-code-fork,fa-coffee,fa-cog,fa-cogs,fa-comment,fa-comment-o,fa-comments,fa-comments-o,fa-compass,fa-credit-card,fa-crop,fa-crosshairs,fa-cutlery,fa-dashboard,fa-desktop,fa-dot-circle-o,fa-download,fa-edit,fa-ellipsis-h,fa-ellipsis-v,fa-envelope,fa-envelope-o,fa-eraser,fa-exchange,fa-exclamation,fa-exclamation-circle,fa-exclamation-triangle,fa-external-link,fa-external-link-square,fa-eye,fa-eye-slash,fa-female,fa-fighter-jet,fa-film,fa-filter,fa-fire,fa-fire-extinguisher,fa-flag,fa-flag-checkered,fa-flag-o,fa-flash,fa-flask,fa-folder,fa-folder-o,fa-folder-open,fa-folder-open-o,fa-frown-o,fa-gamepad,fa-gavel,fa-gear,fa-gears,fa-gift,fa-glass,fa-globe,fa-group,fa-hdd-o,fa-headphones,fa-heart,fa-heart-o,fa-home,fa-inbox,fa-info,fa-info-circle,fa-key,fa-keyboard-o,fa-laptop,fa-leaf,fa-legal,fa-lemon-o,fa-level-down,fa-level-up,fa-lightbulb-o,fa-location-arrow,fa-lock,fa-magic,fa-magnet,fa-mail-forward,fa-mail-reply,fa-mail-reply-all,fa-male,fa-map-marker,fa-meh-o,fa-microphone,fa-microphone-slash,fa-minus,fa-minus-circle,fa-minus-square,fa-minus-square-o,fa-mobile,fa-mobile-phone,fa-money,fa-moon-o,fa-music,fa-pencil,fa-pencil-square,fa-pencil-square-o,fa-phone,fa-phone-square,fa-picture-o,fa-plane,fa-plus,fa-plus-circle,fa-plus-square,fa-plus-square-o,fa-power-off,fa-print,fa-puzzle-piece,fa-qrcode,fa-question,fa-question-circle,fa-quote-left,fa-quote-right,fa-random,fa-refresh,fa-reply,fa-reply-all,fa-retweet,fa-road,fa-rocket,fa-rss,fa-rss-square,fa-search,fa-search-minus,fa-search-plus,fa-share,fa-share-square,fa-share-square-o,fa-shield,fa-shopping-cart,fa-sign-in,fa-sign-out,fa-signal,fa-sitemap,fa-smile-o,fa-sort,fa-sort-alpha-asc,fa-sort-alpha-desc,fa-sort-amount-asc,fa-sort-amount-desc,fa-sort-asc,fa-sort-desc,fa-sort-down,fa-sort-numeric-asc,fa-sort-numeric-desc,fa-sort-up,fa-spinner,fa-square,fa-square-o,fa-star,fa-star-half,fa-star-half-empty,fa-star-half-full,fa-star-half-o,fa-star-o,fa-subscript,fa-suitcase,fa-sun-o,fa-superscript,fa-tablet,fa-tachometer,fa-tag,fa-tags,fa-tasks,fa-terminal,fa-thumb-tack,fa-thumbs-down,fa-thumbs-o-down,fa-thumbs-o-up,fa-thumbs-up,fa-ticket,fa-times,fa-times-circle,fa-times-circle-o,fa-tint,fa-toggle-down,fa-toggle-left,fa-toggle-right,fa-toggle-up,fa-trash-o,fa-trophy,fa-truck,fa-umbrella,fa-unlock,fa-unlock-alt,fa-unsorted,fa-upload,fa-user,fa-users,fa-video-camera,fa-volume-down,fa-volume-off,fa-volume-up,fa-warning,fa-wheelchair,fa-wrench');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-check-square,fa-check-square-o,fa-circle,fa-circle-o,fa-dot-circle-o,fa-minus-square,fa-minus-square-o,fa-plus-square,fa-plus-square-o,fa-square,fa-square-o');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-align-center,fa-align-justify,fa-align-left,fa-align-right,fa-bold,fa-chain,fa-chain-broken,fa-clipboard,fa-columns,fa-copy,fa-cut,fa-dedent,fa-eraser,fa-file,fa-file-o,fa-file-text,fa-file-text-o,fa-files-o,fa-floppy-o,fa-font,fa-indent,fa-italic,fa-link,fa-list,fa-list-alt,fa-list-ol,fa-list-ul,fa-outdent,fa-paperclip,fa-paste,fa-repeat,fa-rotate-left,fa-rotate-right,fa-save,fa-scissors,fa-strikethrough,fa-table,fa-text-height,fa-text-width,fa-th,fa-th-large,fa-th-list,fa-underline,fa-undo,fa-unlink');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-angle-double-down,fa-angle-double-left,fa-angle-double-right,fa-angle-double-up,fa-angle-down,fa-angle-left,fa-angle-right,fa-angle-up,fa-arrow-circle-down,fa-arrow-circle-left,fa-arrow-circle-o-down,fa-arrow-circle-o-left,fa-arrow-circle-o-right,fa-arrow-circle-o-up,fa-arrow-circle-right,fa-arrow-circle-up,fa-arrow-down,fa-arrow-left,fa-arrow-right,fa-arrow-up,fa-arrows,fa-arrows-alt,fa-arrows-h,fa-arrows-v,fa-caret-down,fa-caret-left,fa-caret-right,fa-caret-square-o-down,fa-caret-square-o-left,fa-caret-square-o-right,fa-caret-square-o-up,fa-caret-up,fa-chevron-circle-down,fa-chevron-circle-left,fa-chevron-circle-right,fa-chevron-circle-up,fa-chevron-down,fa-chevron-left,fa-chevron-right,fa-chevron-up,fa-hand-o-down,fa-hand-o-left,fa-hand-o-right,fa-hand-o-up,fa-long-arrow-down,fa-long-arrow-left,fa-long-arrow-right,fa-long-arrow-up,fa-toggle-down,fa-toggle-left,fa-toggle-right,fa-toggle-up');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-arrows-alt,fa-backward,fa-compress,fa-eject,fa-expand,fa-fast-backward,fa-fast-forward,fa-forward,fa-pause,fa-play,fa-play-circle,fa-play-circle-o,fa-step-backward,fa-step-forward,fa-stop,fa-youtube-play');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-ambulance,fa-h-square,fa-hospital-o,fa-medkit,fa-plus-square,fa-stethoscope,fa-user-md,fa-wheelchair');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
								<div class="iconSelectionGroup">
									<span data-value="noIcon">&nbsp;</span>
									<?php
									$iconArray=explode(',','fa-adn,fa-android,fa-apple,fa-bitbucket,fa-bitbucket-square,fa-bitcoin (alias),fa-btc,fa-css3,fa-dribbble,fa-dropbox,fa-facebook,fa-facebook-square,fa-flickr,fa-foursquare,fa-github,fa-github-alt,fa-github-square,fa-gittip,fa-google-plus,fa-google-plus-square,fa-html5,fa-instagram,fa-linkedin,fa-linkedin-square,fa-linux,fa-maxcdn,fa-pagelines,fa-pinterest,fa-pinterest-square,fa-renren,fa-skype,fa-stack-exchange,fa-stack-overflow,fa-trello,fa-tumblr,fa-tumblr-square,fa-twitter,fa-twitter-square,fa-vimeo-square,fa-vk,fa-weibo,fa-windows,fa-xing,fa-xing-square,fa-youtube,fa-youtube-play,fa-youtube-square');
									foreach($iconArray as $icon)
									{
										?>
										<span class="fa fa-2x <?php echo $icon;?>" data-value="<?php echo $icon;?>"></span>
										<?php
									}
									?>
									<div class="clear"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="anpt-services-rowwsto143">
							<label>3. <?php _e('Select icon color');?>: </label>
							<input type='color' name='iconColor' value='#ffffff' />							
						</div>
						<div class="anpt-services-rowwsto143">
							<label>4. <?php _e('Select background color');?>: </label>
							<input type='color' name='bgColor' value='#000000' />							
						</div>
						<div class="anpt-services-rowwsto143">
							<label>5. <?php _e('Select text color');?>: </label>
							<input type='color' name='textColor' value='#ffffff' />							
						</div>
						<div class="anpt-services-rowwsto143">
							<label>6. <?php _e('Preview');?>: </label><br/>
							<a href="#" class="anpt_newpay_button buttondesign0"><?php _e("Pay Now"); ?><span></span></a>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="anpt-services-rowwsto143">
				<label for="anpt_buttons_lightbox" ><?php _e("Open payment form in lightbox: " ); ?></label>
				<input type="radio" name="anpt_buttons_lightbox" value="true" checked> <?php _e("Yes"); ?> &nbsp;&nbsp;
				<input type="radio" name="anpt_buttons_lightbox" value="false" > <?php _e("No"); ?><br />
				<em><?php _e("" ); ?></em>
			</div>
			<p class="submit">
				<input style="margin: 0 10px;" class="button button-primary" type="button" name="generateCodeButton" value="<?php _e('Generate Shortcode', '' ) ?>" />
			</p>
			<div class="anpt-services-rowwsto143 shortcodeResultDiv" style="display:none;">
				<label for="shortcodeResult" ><?php _e("Shortcode: " ); ?></label><br/>
				<textarea name="shortcodeResult" style="margin:10px; padding:10px; width:90%; height:100px;"></textarea>
			</div>
		</form>
	</div>                
</div>

<script type="text/javascript">
<!--
	jQuery(document).ready(function($){
		$('input[name=anpt_buttons_service]').click(function(){
			if($('input[name=anpt_buttons_service]:checked').val()=='defined')
			{
				$('.servicesListDiv').show();
			}
			else
			{
				$('.servicesListDiv').hide();
			}
		});
		$('input[name=generateCodeButton]').click(function(e){
			e.preventDefault();
			if(currentActiveTab==0)
			{				
				if($('input[name=anpt_buttons_service]:checked').val()=='defined')
				{
					$('textarea[name=shortcodeResult]').html('[anpt_paybutton text="'+$('input[name=anpt_buttons_title]').val().replace(/["']/g, "")+'" comment="'+$('input[name=anpt_buttons_comment]:checked').val()+'" service="'+$('select[name=serviceId] option:selected').val()+'" design="'+$('input[name=anpt_buttons_design]:checked').val()+'" lightbox="'+$('input[name=anpt_buttons_lightbox]:checked').val()+'"]');
				}
				else
				if($('input[name=anpt_buttons_service]:checked').val()=='false')
				{
					$('textarea[name=shortcodeResult]').html('[anpt_paybutton text="'+$('input[name=anpt_buttons_title]').val().replace(/["']/g, "")+'" comment="'+$('input[name=anpt_buttons_comment]:checked').val()+'" service="'+$('input[name=anpt_buttons_service]:checked').val()+'" amount="'+$('input[name=anpt_services_price]').val()+'" design="'+$('input[name=anpt_buttons_design]:checked').val()+'" lightbox="'+$('input[name=anpt_buttons_lightbox]:checked').val()+'"]');
				}
				else
				{
					$('textarea[name=shortcodeResult]').html('[anpt_paybutton text="'+$('input[name=anpt_buttons_title]').val().replace(/["']/g, "")+'" comment="'+$('input[name=anpt_buttons_comment]:checked').val()+'" service="'+$('input[name=anpt_buttons_service]:checked').val()+'" design="'+$('input[name=anpt_buttons_design]:checked').val()+'" lightbox="'+$('input[name=anpt_buttons_lightbox]:checked').val()+'"]');
				}
			}
			else
			{
				if($('input[name=anpt_buttons_service]:checked').val()=='defined')
				{
					$('textarea[name=shortcodeResult]').html('[anpt_paybutton text="'+$('input[name=anpt_buttons_title]').val().replace(/["']/g, "")+'" comment="'+$('input[name=anpt_buttons_comment]:checked').val()+'" service="'+$('select[name=serviceId] option:selected').val()+'" design="0" iconcolor="'+$('input[name=iconColor]').val()+'" bgcolor="'+$('input[name=bgColor]').val()+'" textcolor="'+$('input[name=textColor]').val()+'" icon="'+$('input[name=button_icon]').val()+'" corner="'+$('input[name=button_corner]:checked').val()+'" lightbox="'+$('input[name=anpt_buttons_lightbox]:checked').val()+'"]');
				}
				else
				if($('input[name=anpt_buttons_service]:checked').val()=='false')
				{
					$('textarea[name=shortcodeResult]').html('[anpt_paybutton text="'+$('input[name=anpt_buttons_title]').val().replace(/["']/g, "")+'" comment="'+$('input[name=anpt_buttons_comment]:checked').val()+'" service="'+$('input[name=anpt_buttons_service]:checked').val()+'" amount="'+$('input[name=anpt_services_price]').val()+'" design="0" iconcolor="'+$('input[name=iconColor]').val()+'" bgcolor="'+$('input[name=bgColor]').val()+'" textcolor="'+$('input[name=textColor]').val()+'" icon="'+$('input[name=button_icon]').val()+'" corner="'+$('input[name=button_corner]:checked').val()+'"  lightbox="'+$('input[name=anpt_buttons_lightbox]:checked').val()+'"]');
				}
				else
				{
					$('textarea[name=shortcodeResult]').html('[anpt_paybutton text="'+$('input[name=anpt_buttons_title]').val().replace(/["']/g, "")+'" comment="'+$('input[name=anpt_buttons_comment]:checked').val()+'" service="'+$('input[name=anpt_buttons_service]:checked').val()+'" design="0" iconcolor="'+$('input[name=iconColor]').val()+'" bgcolor="'+$('input[name=bgColor]').val()+'" textcolor="'+$('input[name=textColor]').val()+'" icon="'+$('input[name=button_icon]').val()+'" corner="'+$('input[name=button_corner]:checked').val()+'"  lightbox="'+$('input[name=anpt_buttons_lightbox]:checked').val()+'"]');
				}				
			}
			$('.shortcodeResultDiv').show();
		});
	});
-->
</script>