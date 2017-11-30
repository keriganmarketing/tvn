<?php
/**
 * Function used to clean out unnecessary file requests and harden WP Core
 */

namespace Includes\Modules\Helpers;

// Exit if accessed directly.
if ( ! defined('ABSPATH')) {
    exit;
}

class CleanWP
{

    public function __construct()
    {
        $this->removeUnneccesaryScripts();
        add_action('init', function(){
            $this->removeEmojis();
        });
    }

    public function removeUnneccesaryScripts()
    {

        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'start_post_rel_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link');

    }

    public function removeEmojis()
    {

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        add_filter('tiny_mce_plugins', function ($plugins) {
            if (is_array($plugins)) {
                return array_diff($plugins, array('wpemoji'));
            } else {
                return array();
            }
        });

        add_filter('wp_resource_hints', function ($urls, $relation_type) {
            if ('dns-prefetch' == $relation_type) {
                $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
                $urls = array_diff($urls, array($emoji_svg_url));
            }

            return $urls;
        }, 10, 2);

    }
}