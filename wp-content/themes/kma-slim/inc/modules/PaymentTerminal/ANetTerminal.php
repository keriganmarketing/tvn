<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/21/2017
 * Time: 12:38 PM
 */

namespace Includes\Modules\PaymentTerminal;


class ANetTerminal
{
    protected $moduleDir;
    protected $scriptDir;
    protected $templateDir;

    public function __construct()
    {
        $this->moduleDir = dirname(__FILE__);
        $this->scriptDir = $this->moduleDir . '/js/';
        $this->templateDir = $this->moduleDir . '/templates/';

        $this->showForm();
    }

    protected function showForm()
    {
        add_action('wp_enqueue_scripts', function(){
            wp_enqueue_script('ccvalidation', $this->scriptDir . 'ccvalidations.js');
        });

        $imageDir = get_template_directory_uri() . '/inc/modules/PaymentTerminal/templates/images/';
        $moduleDir =  $this->moduleDir;

        include( $this->templateDir . 'anet-form.php' );
    }
}