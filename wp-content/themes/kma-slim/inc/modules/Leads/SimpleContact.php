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
        parent::set('adminEmail', 'thevirtualnephrologist@gmail.com');
    }

    protected function showForm()
    {
        $form = file_get_contents(locate_template('template-parts/forms/contact-form.php'));
        $form = str_replace('{{user-agent}}', $_SERVER['HTTP_USER_AGENT'], $form);
		$form = str_replace('{{ip-address}}', parent::getIP(), $form);
        $form = str_replace('{{referrer}}', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null), $form);
        
        $formSubmitted = (isset($_POST['sec-validation-feild']) ? ($_POST['sec-validation-feild'] == '' ? true : false) : false );
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