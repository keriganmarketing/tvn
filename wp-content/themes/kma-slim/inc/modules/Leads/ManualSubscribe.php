<?php

namespace Includes\Modules\Leads;

class ManualSubscribe extends Leads
{
    public function __construct ()
    {
        parent::__construct ();
        parent::set('postType', 'Subscription');
    }

    protected function showForm()
    {
        $formSubmitted = (isset($_POST['sec']) ? ($_POST['sec'] == '' ? true : false) : false );
        if($formSubmitted){
            $this->handleLead($_POST);
        }
        ob_start();
        include(locate_template('template-parts/forms/subscribe-form.php'));
        return ob_get_clean();
    }

    public function setupShortcode()
    {
        add_shortcode( 'subscribe_form', function( $atts ){
            return $this->showForm();
        } );
    }
}