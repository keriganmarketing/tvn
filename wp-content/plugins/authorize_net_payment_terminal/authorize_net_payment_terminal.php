<?php
/*
Plugin Name: Authorize.net Payment Terminal for Wordpress
Plugin URI: http://www.convergine.com
Description: Plugin allows easily accept Authorize.net payments or donations by credit cards on your blog, on any page or post, with button generator and widget
Author: Convergine.com
Version: 1.3
Release Date: 16 December 2014
Latest Update: 13 November 2015
Initial Release Date: June 24 2014
Author URI: http://www.convergine.com
*/

//installation function
global $wp_url;

$wp_url = get_site_url();

add_action( 'wp_enqueue_scripts', 'anpt11_enqueue_styles' );

function anpt_install()
{
    include('anpt.cfg.php');
    global $wpdb;
	
    //let's create transaction table.
    $table = $wpdb->prefix."anpt_transactions";

    $structure = "CREATE TABLE IF NOT EXISTS $table (
					anpt_id int(20) NOT NULL auto_increment,
					anpt_dateCreated datetime default '0000-00-00 00:00:00',
					anpt_amount double NOT NULL,
					anpt_payer_email varchar(255) default NULL,
					anpt_comment longtext,
					anpt_transaction_id varchar(255) default NULL,
					anpt_status tinyint(5) default '1',
					anpt_payer_name varchar(255) NOT NULL,
					anpt_serviceID int(20) NOT NULL default '0',
					anpt_service_name  varchar(255) NOT NULL,
					anpt_bill_cycle  varchar(255) NOT NULL,
					anpt_recurring tinyint(5) default '0',
					anpt_recurring_cancelled tinyint(5) default '0',
					UNIQUE KEY anpt_id (anpt_id),
					UNIQUE KEY anpt_transaction_id (anpt_transaction_id))
					ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

    $wpdb->query($structure);

    //now create services table
    $table = $wpdb->prefix."anpt_services";
    $structure = "CREATE TABLE IF NOT EXISTS $table (
					anpt_services_id INT(20) NOT NULL AUTO_INCREMENT,
					anpt_services_title VARCHAR(255) NOT NULL,
					anpt_services_price DOUBLE NOT NULL,
					anpt_services_recurring BOOLEAN default 0,
					anpt_services_recurring_period_type varchar(10),
					anpt_services_recurring_period_number INT(20) not null default 0,
					anpt_services_recurring_trial BOOLEAN default 0,
					anpt_services_recurring_trial_days INT(20) not null default 0,
					anpt_services_descr MEDIUMTEXT NULL,
					UNIQUE KEY anpt_services_id (anpt_services_id));";
    $wpdb->query($structure);
    $add_default_terminals = anptCRUD($anpt_cfg_arr,'install');

    update_option('anpt_processor',"1");
    update_option('anpt_currency',"USD");
    update_option('anpt_ty_title',"Thank You!");
    update_option('anpt_ty_text',"<p>Thank you for your payment! We really appreciate it.</p>");
    update_option('anpt_admin_email',"changeme@email.com");
    update_option('anpt_admin_send',"1");
    update_option('anpt_show_comment_field',"1");
    update_option('anpt_license',"");
    update_option('anpt_show_dd_text',"2"); //show drop down with services 1 or show text box for input 2
    update_option('anpt_test',"1");
}

function anpt_uninstall()
{
    //in case somebody want's to remove the script.
    //we are leaving intact transactions and services table - for history and in case client will want to re-instantiate the script.
    include('anpt.cfg.php');
    $remove_terminals = anptCRUD($anpt_cfg_arr,'uninstall');
    delete_option('anpt_processor');
    delete_option('anpt_currency');
    delete_option('anpt_ty_title');
    delete_option('anpt_ty_text');
    delete_option('anpt_admin_email');
    delete_option('anpt_admin_send');
    delete_option('anpt_show_comment_field');
    delete_option('anpt_show_dd_text');
    delete_option('anpt_license');
    delete_option('anpt_test');
}

//Creating our menu in WP admin.
function anpt_admin_actions()
{
    global $wp_url;
	
    add_menu_page( "Authorize.net Payment Terminal", "Authorize.net Payment Terminal", "administrator", 'anpt-terminal-slug', "anpt_admin_overview", $wp_url."/wp-content/plugins/authorize_net_payment_terminal/images/payment_icon.png");
	
	 add_submenu_page( 'anpt-terminal-slug', 'Authorize.net Payment Terminal Settings', 'Settings', 'administrator', 'anpt_admin_settings','anpt_admin_settings');
    add_submenu_page( 'anpt-terminal-slug', 'Authorize.net Payment Terminal Services', 'Services', 'administrator', 'anpt_admin_services','anpt_admin_services');
	add_submenu_page( 'anpt-terminal-slug', 'Authorize.net Payment Terminal Buttons', 'Button Generator', 'administrator', 'anpt_admin_buttons','anpt_admin_buttons');
    add_submenu_page('anpt-terminal-slug', 'Authorize.net Payment Terminal Transactions', 'Transactions', 'administrator', 'anpt_admin_transactions','anpt_admin_transactions');
    add_submenu_page('anpt-terminal-slug', 'Authorize.net Payment Terminal Service Edit', '', 'administrator', 'anpt_admin_services_edit','anpt_admin_services_edit');
   
}

//function for including needed page into wp admin upon request from menu

function anpt_admin_overview() { /* plugin overview page */
	include('payment_terminal_overview.php');
}

function anpt_admin_settings() { /* settings page */
    include('payment_terminal_settings.php');
}

function anpt_admin_services() { /* services page */
    include('payment_terminal_services.php');
}

function anpt_admin_buttons() { /* buttons page */
    include('payment_terminal_buttons.php');
}

function anpt_admin_services_edit() { /* services editing page */
    include('payment_terminal_services_edit.php');
}

function anpt_admin_transactions() { /* transactions page */
    include('payment_terminal_transactions.php');
}

function anpt_tinymce()
{
    global $wp_version;
    $cos_search_provider_wp_version = "3.3";
    if ( version_compare($wp_version, $cos_search_provider_wp_version, "<"))
	{
        wp_enqueue_script('common');
        wp_enqueue_script('jquery-color');
        wp_admin_css('thickbox');
        wp_print_scripts('post');
        wp_print_scripts('media-upload');
        wp_print_scripts('jquery');
        wp_print_scripts('jquery-ui-core');
        wp_print_scripts('jquery-ui-tabs');
        wp_print_scripts('tiny_mce');
        wp_print_scripts('editor');
        wp_print_scripts('editor-functions');
        add_thickbox();
        wp_tiny_mce();
        wp_admin_css();
        wp_enqueue_script('utils');
        do_action("admin_print_styles-post-php");
        do_action('admin_print_styles');
        remove_all_filters('mce_external_plugins');
    }
}

function anpt_add_widget(){
    register_widget( 'anpt_widget' );
}

function anpt_init_jquery() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('anpt_prettyPhoto',plugins_url('/js/prettyPhoto/js/jquery.prettyPhoto.js', __FILE__),'1.0' );
}

//add the hooks for install/uninstall and menu.
register_activation_hook( __FILE__, 'anpt_install' );
register_deactivation_hook(__FILE__, 'anpt_uninstall');
add_action('admin_menu', 'anpt_admin_actions');
add_filter('admin_head','anpt_tinymce');
add_action('widgets_init', 'anpt_add_widget');
add_action('init', 'anpt_init_jquery');

//short code function
add_shortcode('anpt_paybutton', 'anpt_payform_display'); //NEW IN v2

function anpt_payform_display($atts)
{
    $copyright_img = ""; //powered by convergine logo.
    global $wp_url;
    global $wpdb;

    extract( shortcode_atts( array(
		'text'=>'',
		'comment'=>'',
		'service'=>'',
		'design'=>'',
		'lightbox'=>'',
		'amount'=>'',
		'bgcolor'=>'',
		'textcolor'=>'',
		'icon'=>'',
		'iconcolor'=>'',
		'corner'=>''
    ), $atts ) );

    $anpt_form='';
    $anpt_processors=array(
        1=>'authorize'
    );

    $anpt_current_processor_dir=$anpt_processors[get_option('anpt_processor')];
    $serviceID='';

	if(!empty($service) && is_numeric($service)){
		$serviceID.="serviceID=".$service;
		$query="SELECT * FROM ".$wpdb->prefix."anpt_services WHERE anpt_services_id={$service} ORDER BY anpt_services_title";
		$result = $wpdb->get_results($query);
		if($wpdb->num_rows==0){ return ""; }
	} else if($service=='true'){
		  $serviceID.="&serv=".$service;
	} else if($service=='false'){
		  $serviceID.="&serv=".$service.'&amount='.$amount;
	}
	
	wp_enqueue_style( 'anpt_style' );
	wp_enqueue_style( 'anpt_prettyPhoto_style' );
	wp_enqueue_style( 'anpt_fontawesome_style' );
	
	if($lightbox=='true'){
		if($design==0)
			$anpt_form.= '<a style="background-color:'.$bgcolor.'; color:'.$textcolor.';" data-rel="prettyPhoto" class="corner'.$corner.' anpt_newpay_button buttondesign'.$design.'" href="'.$wp_url.'/wp-content/plugins/authorize_net_payment_terminal/terminal/index.php?'.$serviceID.'&comment='.$comment.'&iframe=true">'.$text.'<span class="fa fa-2x '.$icon.'"  style="color:'.$iconcolor.'"></span></a>';
		else
    		$anpt_form.= '<a data-rel="prettyPhoto" class="anpt_newpay_button buttondesign'.$design.'" href="'.$wp_url.'/wp-content/plugins/authorize_net_payment_terminal/terminal/index.php?'.$serviceID.'&comment='.$comment.'&iframe=true">'.$text.'<span></span></a>';
	} else {
		if($design==0)
			$anpt_form.= '<a style="background-color:'.$bgcolor.'; color:'.$textcolor.';" href="'.$wp_url.'/wp-content/plugins/authorize_net_payment_terminal/terminal/index.php?'.$serviceID.'&comment='.$comment.'&lbox" class="corner'.$corner.' anpt_newpay_button buttondesign'.$design.'">'.$text.'<span style="color:'.$iconcolor.'" class="fa fa-2x '.$icon.'"></span></a>';
		else
			$anpt_form.= '<a href="'.$wp_url.'/wp-content/plugins/authorize_net_payment_terminal/terminal/index.php?'.$serviceID.'&comment='.$comment.'&lbox" class="anpt_newpay_button buttondesign'.$design.'">'.$text.'<span></span></a>';
	}
	return $anpt_form;
}

//widget function
class anpt_widget extends WP_Widget
{
	/*function anpt_widget()
	{
		// Widget settings.
		$widget_ops = array( 'classname' => 'anpt_widget', 'description' => __('Authorize.net Payment Terminal Widget allows you to place a generated payment button in a sidebar of your site.', 'anpt_widget') );
		
		// Widget control settings. 
		$control_ops = array( 'width' => 250, 'height' => 200, 'id_base' => 'anpt_widget' );
		
		// Create the widget. 
		$this->WP_Widget( 'anpt_widget', __('Authorize.net Payment Terminal', 'anpt_widget'), $widget_ops, $control_ops );
    }*/
    function __construct() {
        parent::__construct(

            'anpt_widget', // Base ID

            __('Authorize.net Payment Terminal Widget allows you to place a generated payment button in a sidebar of your site.', 'anpt_widget'), // Name

            array( 'description' => __('Authorize.net Payment Terminal Widget allows you to place a generated payment button in a sidebar of your site.', 'anpt_widget'), ) // Args

        );
    }
	
	function widget( $args, $instance )
	{
		$copyright_img = ""; //powered by convergine logo.
		global $wp_url;
		global $wpdb;
		extract( $args );
		
	
	
		// User-selected settings		
		
		wp_enqueue_style( 'anpt_style' );
		wp_enqueue_style( 'anpt_prettyPhoto_style' );
		wp_enqueue_style( 'anpt_fontawesome_style' );
		
		echo $before_widget;
		echo do_shortcode($instance['button_code']);
		echo $after_widget;		
	}
	
	function update( $new_instance, $old_instance)
	{
		$instance = $old_instance;
		// Strip tags (if needed) and update the widget settings.
		$instance['button_code'] = strip_tags( $new_instance['button_code'] );
		return $instance;
	}
	
	function form( $instance )
	{
		// Set up some default widget settings.
		$defaults = array( 'title' => 'Authorize.net Payment Terminal Widget');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Generated Button Code:</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'button_code' ); ?>" style="width:100%;" name="<?php echo $this->get_field_name( 'button_code' ); ?>"><?php echo $instance['button_code']; ?></textarea>
		</p>
		<?php
	}

}

function anpt11_enqueue_styles(){
	wp_register_style( 'anpt_style', plugins_url('css/style.css', __FILE__) );
	wp_register_style( 'anpt_prettyPhoto_style', plugins_url('js/prettyPhoto/css/prettyPhoto.css', __FILE__) );
	wp_register_style( 'anpt_fontawesome_style', plugins_url('css/font-awesome.css', __FILE__) );
}

?>