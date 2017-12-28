<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/27/2017
 * Time: 1:21 PM
 */

class PaymentTerminalAdminPages
{
    public function __construct()
    {
        global $wp_url;
        $wp_url = get_site_url();

        $this->addMenus();
    }

    public function addMenus()
    {
        add_menu_page( "Payment Terminal", "Payment Terminal", "administrator", 'anpt-terminal-slug', function(){
            include(dirname(dirname(__FILE__)) . '/inc/anpt.cfg.php');
            include(dirname(dirname(__FILE__)) . '/admin-pages/payment_terminal_overview.php');
        }, "dashicons-cart");

        add_submenu_page( 'anpt-terminal-slug', 'Payment Terminal Settings', 'Settings', 'administrator', 'anpt_admin_settings', function() {
            include(dirname(dirname(__FILE__)) . '/inc/anpt.cfg.php');
            include(dirname(dirname(__FILE__)) . '/admin-pages/payment_terminal_settings.php');
        });

        add_submenu_page( 'anpt-terminal-slug', 'Payment Terminal Services', 'Services', 'administrator', 'anpt_admin_services', function() {
            include(dirname(dirname(__FILE__)) . '/inc/anpt.cfg.php');
            include(dirname(dirname(__FILE__)) . '/admin-pages/payment_terminal_services.php');
        });

        add_submenu_page('anpt-terminal-slug', 'Payment Terminal Transactions', 'Search Transactions', 'administrator', 'anpt_admin_transactions', function() {
            include(dirname(dirname(__FILE__)) . '/inc/anpt.cfg.php');
            include(dirname(dirname(__FILE__)) . '/admin-pages/payment_terminal_transactions.php');
        });

        add_submenu_page('anpt-terminal-slug', 'Payment Terminal Service Edit', '', 'administrator', 'anpt_admin_services_edit', function() {
            include(dirname(dirname(__FILE__)) . '/inc/anpt.cfg.php');
            include(dirname(dirname(__FILE__)) . '/admin-pages/payment_terminal_services_edit.php');
        });
    }
}