<?php
/**
 * Function used to maintain a PHP session
 */

namespace Includes\Modules\Helpers;

// Exit if accessed directly.
if ( ! defined('ABSPATH')) {
    exit;
}

class Session
{
    public function __construct()
    {

        add_action('init', function (){
            $this->startSession();
        }, 1);

        add_action('wp_logout', function (){
            $this->destroySession();
        });

        add_action('wp_login', function (){
            $this->destroySession();
        });

    }

    private function startSession(){
        if (!session_id()) {
            session_start();
        }
    }

    private function destroySession(){
        session_destroy ();
    }
}