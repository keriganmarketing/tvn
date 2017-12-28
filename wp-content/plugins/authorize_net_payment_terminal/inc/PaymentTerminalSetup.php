<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/27/2017
 * Time: 1:10 PM
 */

class PaymentTerminalSetup
{
    public function __construct()
    {
        include('anpt.cfg.php');
    }

    public function installPlugin()
    {
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

    public function uninstallPlugin()
    {
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
}