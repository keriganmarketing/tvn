<?php

namespace Includes\Modules\Leads;

class SimpleContact extends Leads
{
    public function __construct ()
    {
        parent::__construct ();
        parent::assembleLeadData(
            [
                'message' => 'Message'
            ]
        );
        parent::set('postType', 'Contact Submission');
    }

    protected function showForm()
    {
        $form = file_get_contents(locate_template('template-parts/forms/contact-form.php'));
        $formSubmitted = (isset($_POST['sec']) ? ($_POST['sec'] == '' ? true : false) : false );
        ob_start();
        if($formSubmitted){
            if($this->handleLead($_POST)){
                return '<message title="Success" class="is-success">Thank you for contacting us. Your message has been received.</message>';
            }else{
                return '<message title="Error" class="is-danger">There was an error with your submission. Please try again.</message>';
                echo $form;
                return ob_get_clean();
            }
        }else{
            echo $form;
            return ob_get_clean();
        }
    }

    public function setupShortcode()
    {
        add_shortcode( 'contact_form', function( $atts ){
            return $this->showForm();
        } );
    }

}