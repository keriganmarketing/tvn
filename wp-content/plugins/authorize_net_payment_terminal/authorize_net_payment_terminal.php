<?php
/*
Plugin Name: Authorize.net Payment Terminal for Wordpress
Plugin URI: https://www.keriganmarketing.com
Description: Plugin allows easily accept Authorize.net payments or donations by credit cards on your blog, on any page or post
Author: Kerian Marketing Associates
Version: 1.0
Release Date: 12/27/17
Latest Update: 12/27/17
Initial Release Date: 12/27/17
Author URI: https://www.keriganmarketing.com
*/

//add_action( 'wp_enqueue_scripts', 'anpt11_enqueue_styles' );
require_once ('config.php');
require_once ('inc/PaymentTerminalTemplate.php');
require_once ('inc/PaymentTerminalSetup.php');
require_once ('inc/PaymentTerminalAdminPages.php');

function anpt_install()
{
    $pluginSetup = new PaymentTerminalSetup();
    $pluginSetup->installPlugin();
}

function anpt_uninstall()
{
    $pluginSetup = new PaymentTerminalSetup();
    $pluginSetup->uninstallPlugin();
}

//Creating our menu in WP admin.
function anpt_admin_actions()
{
    $adminPages = new PaymentTerminalAdminPages();
}

function anpt_construct()
{
    $terminal = new PaymentTerminalTemplate();
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

//add the hooks for install/uninstall and menu.
register_activation_hook( __FILE__, 'anpt_install' );
register_deactivation_hook(__FILE__, 'anpt_uninstall');
add_action('admin_menu', 'anpt_admin_actions');
add_action('init', 'anpt_construct');
add_filter('admin_head','anpt_tinymce');
?>