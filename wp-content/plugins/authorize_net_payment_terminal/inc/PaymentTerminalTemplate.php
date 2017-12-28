<?php

class PaymentTerminalTemplate
{
    protected $scriptDir;
    protected $templateDir;
    protected $pluginDir;

    public function __construct()
    {
        $this->pluginDir = dirname(dirname(__FILE__));
        $this->templateDir = $this->pluginDir . '/terminal/templates';

        $this->addShortcode();
    }

    protected function showForm()
    {
        include( dirname(__FILE__) . '/PaymentTerminalProcess.php');
        $imageDir = plugins_url() . '/authorize_net_payment_terminal/terminal/templates/images';

        $form = new PaymentTerminalProcess();
        if(!$form->formSubmit()) {
            include($this->templateDir . '/anet-form.php');
        } else {
            $form->displaySuccessMessage();
        }
    }

    protected function addShortcode()
    {
        add_shortcode( 'payment_form', function( $atts ){
            $this->showForm();
        } );
    }
}